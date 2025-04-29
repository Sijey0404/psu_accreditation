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

class DocumentController extends Controller
{
    public function store(Request $request)
    {
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

        Document::create([
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

        return back()->with('success', 'Document uploaded successfully.');
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
            return back()->with('error', 'Unauthorized access.');
        }

        $document = Document::findOrFail($id);

        $document->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        try {
            if ($document->folder_id) {
                $folder = Folder::findOrFail($document->folder_id);

                if ($folder && $folder->drive_id) {
                    $driveService = new GoogleDriveService();
                    $filePath = storage_path('app/public/' . $document->file_path);
                    $fileName = $document->title . '.' . $document->file_type;

                    $driveLink = $driveService->uploadFile($filePath, $fileName, $folder->drive_id);

                    $document->update([
                        'drive_link' => $driveLink,
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Google Drive Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Document approved but failed to upload to Google Drive.');
        }

        return back()->with('success', 'Document approved and uploaded to Google Drive.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([]);

        $user = Auth::user();
        if (!in_array($user->role, ['QA', 'Accreditor'])) {
            return back()->with('error', 'Unauthorized access.');
        }

        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Document rejected.');
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
}
