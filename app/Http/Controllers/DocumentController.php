<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Subtopic;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;
use App\Models\User; // ✅ Added to fetch user names
use App\Models\Notification;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        try {
        $request->validate([
            'subtopic_id' => 'required|exists:subtopics,id',
            'folder_id' => 'required|exists:folders,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'original_link' => 'nullable|url',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'public');

        $folder = Folder::findOrFail($request->folder_id);
        $googleDriveId = $folder->drive_id ?? null;

        $status = in_array($user->role, ['QA', 'Accreditor']) ? 'approved' : 'pending';

            $document = Document::create([
            'user_id' => $user->id,
            'uploaded_by' => $user->id,
            'subtopic_id' => $request->subtopic_id,
            'folder_id' => $request->folder_id,
            'title' => $request->title,
            'category' => $request->category,
            'original_link' => $request->original_link,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'status' => $status,
            'drive_id' => $googleDriveId,
        ]);

            // Create notification for QA and Accreditor
            if (in_array($user->role, ['Area Member', 'Area Chair'])) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'file_upload',
                        'message' => $user->name . ' uploaded a document to "' . $folder->name . '". Check it out for accreditation.',
                        'link' => '/documents/' . $document->id,
                        'notified_roles' => ['QA', 'Accreditor'],
                        'is_read' => false,
                        'data' => [
                            'document_id' => $document->id,
                            'folder_id' => $folder->id,
                            'folder_name' => $folder->name,
                            'uploader_role' => $user->role
                        ]
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create notification: ' . $e->getMessage());
                    // Don't throw the error, just log it and continue
                }
            }

        return back()->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error('Document upload failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload document. Please try again.');
        }
    }

    public function pending()
    {
        $user = auth()->user();

        if ($user->role !== 'QA') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $documents = Document::where('status', 'pending')
            ->with(['uploader', 'subtopic'])
            ->latest()
            ->get();

        return view('documents.pending', compact('documents'));
    }

    public function approve($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['QA', 'Accreditor'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        try {
            $document = Document::with(['uploader', 'folder'])->findOrFail($id);
            $feedback = request('feedback');
            
            // Upload to Google Drive
            $drive = new GoogleDriveService();
            $filePath = storage_path('app/public/' . $document->file_path);
            $fileName = basename($document->file_path);
            $folderId = $document->folder->drive_id;

            try {
                $driveLink = $drive->uploadFile($filePath, $fileName, $folderId);
                
                // Update document with approval details and Google Drive link
        $document->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
                    'approval_feedback' => $feedback,
                    'drive_link' => $driveLink
                ]);

                // Create notification for document uploader
                if (in_array($document->uploader->role, ['Area Member', 'Area Chair'])) {
                    Notification::create([
                        'user_id' => $document->uploader->id,
                        'type' => 'document_approved',
                        'message' => $user->name . ' has approved your "' . $document->folder->name . '" file. It has been uploaded to Google Drive.',
                        'link' => $driveLink,
                        'is_read' => false,
                        'notified_roles' => ['Area Member', 'Area Chair'],
                        'data' => [
                            'document_id' => $document->id,
                            'document_title' => $document->title,
                            'folder_name' => $document->folder->name,
                            'status' => 'approved',
                            'feedback' => $feedback,
                            'approver_name' => $user->name,
                            'drive_link' => $driveLink
                        ]
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Document approved and uploaded to Google Drive successfully',
                    'feedback' => $feedback,
                    'drive_link' => $driveLink
                ]);

            } catch (\Exception $e) {
                \Log::error('Google Drive upload failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload to Google Drive: ' . $e->getMessage()
                ], 500);
                }

        } catch (\Exception $e) {
            \Log::error('Document approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to approve document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['QA', 'Accreditor'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $reason = request('reason');
        if (empty($reason)) {
            return response()->json(['success' => false, 'message' => 'Rejection reason is required.'], 422);
        }

        try {
            $document = Document::with(['uploader', 'folder'])->findOrFail($id);
            
            // Update document with rejection details
        $document->update([
            'status' => 'rejected',
                'rejection_reason' => $reason,
            'approved_by' => $user->id,
            'approved_at' => now(),
                'drive_link' => null // Clear any existing drive link
            ]);

            // Create notification for document uploader
            if (in_array($document->uploader->role, ['Area Member', 'Area Chair'])) {
                Notification::create([
                    'user_id' => $document->uploader->id,
                    'type' => 'document_rejected',
                    'message' => $user->name . ' has rejected your "' . $document->folder->name . '" file. Please check the rejection reason and resubmit.',
                    'link' => '/my-documents/rejected',
                    'is_read' => false,
                    'notified_roles' => ['Area Member', 'Area Chair'],
                    'data' => [
                        'document_id' => $document->id,
                        'document_title' => $document->title,
                        'folder_name' => $document->folder->name,
                        'status' => 'rejected',
                        'rejection_reason' => $reason,
                        'rejector_name' => $user->name
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Document rejected successfully',
                'reason' => $reason
            ]);

        } catch (\Exception $e) {
            \Log::error('Document rejection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to reject document: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Updated this function to use filtering like approved documents
    public function rejected(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'QA') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $query = Document::with('uploader', 'subtopic')
            ->where('status', 'rejected');

        if ($request->has('search') && $search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('uploader', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('file_type') && $file_type = $request->get('file_type')) {
            $query->where('file_type', $file_type);
        }

        if ($request->has('approved_at') && $approved_at = $request->get('approved_at')) {
            $query->whereDate('approved_at', $approved_at);
        }

        $documents = $query->latest()->get();

        return view('documents.rejected', compact('documents'));
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        return Storage::disk('public')->download($document->file_path);
    }

    public function rejection()
    {
        return $this->hasOne(Rejection::class);
    }

    public function getDocumentsByFolder($folderId)
    {
        $documents = Document::where('folder_id', $folderId)
            ->with(['rejection', 'uploader'])
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'file_type' => $doc->file_type,
                    'status' => $doc->status,
                    'file_url' => Storage::disk('public')->url($doc->file_path),
                    'uploaded_by' => optional($doc->uploader)->name ?? 'Unknown',
                    'rejection_reason' => $doc->rejection->reason ?? null,
                ];
            });

        return response()->json($documents);
    }

    public function getByFolder($folderId)
    {
        $documents = Document::where('folder_id', $folderId)
            ->with('uploader')
            ->get()
            ->map(function ($doc) {
                return [
                    'title' => $doc->title,
                    'file_type' => $doc->file_type,
                    'status' => $doc->status,
                    'rejection_reason' => $doc->rejection_reason,
                    'file_url' => asset('storage/' . $doc->file_path),
                    'uploaded_by' => optional($doc->uploader)->name ?? 'Unknown',
                ];
            });

        return response()->json($documents);
    }

    public function showApprovedDocuments(Request $request)
    {
        $query = Document::with('uploader')
            ->where('status', 'approved');

        if ($request->has('search') && $search = $request->get('search')) {
            $query->whereHas('uploader', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhere('category', 'like', '%' . $search . '%');
        }

        if ($request->has('file_type') && $file_type = $request->get('file_type')) {
            $query->where('file_type', $file_type);
        }

        if ($request->has('approved_at') && $approved_at = $request->get('approved_at')) {
            $query->whereDate('approved_at', $approved_at);
        }

        $documents = $query->get();

        return view('documents.approved', compact('documents'));
    }
    public function showRejectedDocuments(Request $request)
{
    $query = Document::where('status', 'rejected');

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhereHas('uploader', function($q2) use ($search) {
                  $q2->where('name', 'like', "%$search%");
              })
              ->orWhere('category', 'like', "%$search%");
        });
    }

    // Apply file type filter
    if ($request->filled('file_type')) {
        $query->where('file_type', $request->input('file_type'));
    }

    // Apply created_at filter
    if ($request->filled('created_at')) {
        $query->whereDate('created_at', $request->input('created_at'));
    }

    $documents = $query->with('uploader')->latest()->get();

    return view('documents.rejected', compact('documents'));
}

    public function myApprovedDocuments(Request $request)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['Area Chair', 'Area Member'])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $query = Document::with(['uploader', 'folder', 'approver'])
            ->where('uploaded_by', $user->id)
            ->where('status', 'approved');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhereHas('folder', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('approved_at', $request->input('date'));
        }

        // Apply file type filter
        if ($request->filled('file_type')) {
            $query->where('file_type', $request->input('file_type'));
        }

        $documents = $query->latest('approved_at')->paginate(10);
        
        return view('documents.my-approved', compact('documents'));
    }

    public function myRejectedDocuments(Request $request)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['Area Chair', 'Area Member'])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $query = Document::with(['uploader', 'folder', 'approver'])
            ->where('uploaded_by', $user->id)
            ->where('status', 'rejected');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhereHas('folder', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('approved_at', $request->input('date'));
        }

        // Apply file type filter
        if ($request->filled('file_type')) {
            $query->where('file_type', $request->input('file_type'));
        }

        $documents = $query->latest('approved_at')->paginate(10);
        
        return view('documents.my-rejected', compact('documents'));
}
}
