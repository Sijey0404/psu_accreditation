<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    // 📂 Upload Documents (Handled by DCC & Area Chairs)
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|mimes:pdf,docx,xlsx|max:10240'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'pending_documents/' . $fileName;

        // Store file locally (Pending state)
        Storage::disk('local')->put($filePath, file_get_contents($file));

        // Save document details in MySQL
        $document = Document::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'category' => $request->category,
            'status' => 'pending', // Mark as pending
            'uploaded_by' => Auth::id(),
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully, pending approval.');
    }

    // ✅ Approve Document & Upload to Google Drive
    public function approve($id)
    {
        $document = Document::findOrFail($id);

        // Ensure file exists in local storage
        $localFilePath = storage_path('app/' . $document->file_path);
        if (!file_exists($localFilePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get Google Drive folder ID based on category
        $folderId = $this->getFolderIdByCategory($document->category);

        // Upload file to Google Drive
        $driveLink = $this->googleDriveService->uploadFile($localFilePath, $document->title, $folderId);

        // Update document status & store Google Drive link
        $document->update([
            'status' => 'approved',
            'original_link' => $driveLink,
            'file_path' => $driveLink, // Store drive link as file path
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Optional: Delete local file after Google Drive upload
        if (Storage::disk('local')->fileExists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        return redirect()->back()->with('success', 'Document approved and uploaded to Google Drive.');
    }

    // ❌ Reject Document
    public function reject(Request $request, $id)
    {
        $document = Document::findOrFail($id);
    
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason') // Updated to match the form field name
        ]);
    
        // Optional: Delete local file for rejected documents
        if (Storage::disk('local')->fileExists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }
    
        return redirect()->back()->with('success', 'Document rejected successfully.');
    }

    // 📌 Show Pending Documents (QA Review)
    public function showPending()
    {
        $pendingDocuments = Document::where('status', 'pending')->get();
        return view('documents.pending', compact('pendingDocuments'));
    }

    // 📌 Show Approved Documents
    public function showApprovedDocuments()
    {
        $documents = Document::where('status', 'approved')->get();
        return view('documents.approved', compact('documents'));
    }

    // 🔗 Generate Google Drive Upload Link (Manual Upload)
    public function generateGoogleDriveLink($id)
    {
        $document = Document::findOrFail($id);
        $googleDriveFolder = "https://drive.google.com/drive/folders/1c_wtPut9V6LXv1745hrlqv8RWYzsUh8s";
        return redirect($googleDriveFolder)->with('success', 'Upload the document manually to Google Drive.');
    }

    public function dccUpload()
    {
        return view('documents.upload');
    }

    // 🔍 Get Google Drive Folder ID Based on Category
    private function getFolderIdByCategory($category)
    {
        $folders = [
            'Area 1' => '1wfCmfOFFEbnLZqybQg6Q4NXMRyiK4CgM',
            'Area 2' => '15d2i4kRajhshkQAV7ethWH8BUiFROenQ',
            'Area 3' => '1JDhsiwB1TEyyOkEkJMc6nWvogy85aXgg'
        ];

        return $folders[$category] ?? '1c_wtPut9V6LXv1745hrlqv8RWYzsUh8s';
    }

    // 📄 Index of Approved Documents
    public function index()
    {
        $documents = Document::where('status', 'approved')->get();
        return view('documents.index', compact('documents'));
    }

    // 👁️ View Document
    public function view($id)
    {
        $document = Document::findOrFail($id);
        
        // Ensure the document is approved
        if ($document->status !== 'approved') {
            abort(403, 'Unauthorized access');
        }

        // If it's a Google Drive link, redirect
        if (strpos($document->file_path, 'https://drive.google.com') !== false) {
            return redirect($document->file_path);
        }

        // Check if local file exists
        $fullPath = storage_path('app/' . $document->file_path);
        
        if (!File::exists($fullPath)) {
            abort(404, 'File not found');
        }

        // Return the document file
        return response()->file($fullPath);
    }

    // 📥 Download Document
    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        // Ensure the document is approved
        if ($document->status !== 'approved') {
            abort(403, 'Unauthorized access');
        }

        // If it's a Google Drive link, redirect to download
        if (strpos($document->file_path, 'https://drive.google.com') !== false) {
            return redirect($document->file_path);
        }

        // Check if local file exists
        $fullPath = storage_path('app/' . $document->file_path);
        
        if (!File::exists($fullPath)) {
            abort(404, 'File not found');
        }

        // Return the document for download
        return response()->download($fullPath, $document->title);
    }

    public function showRejectedDocuments()
    {
        $rejectedDocuments = Document::where('status', 'rejected')->get();
        return view('document-repository.rejected', compact('rejectedDocuments'));
    }

    public function rejectedDocuments()
    {
        $rejectedDocuments = Document::where('status', 'rejected')->get();
        return view('documents.rejected', compact('rejectedDocuments'));
    }

    // New method to handle the pending approvals view shown in your blade template
    public function pendingApprovals()
    {
        $pendingDocuments = Document::where('status', 'pending')
            ->with('user')
            ->get();
            
        return view('documents.pending-approvals', compact('pendingDocuments'));
    }

    public function pending()
{
    $documents = Document::where('status', 'pending')->get(); // Fetch pending documents
    return view('your-view-name', compact('documents')); // Ensure correct view path
}

public function dccDashboard()
{
    $documents = Document::where('status', 'pending')->get(); // Fetch pending documents
    return view('dashboard.dcc', compact('documents'));
}


}