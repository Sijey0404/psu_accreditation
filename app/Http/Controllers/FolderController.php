<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Document;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AreaDocument;

class FolderController extends Controller
{
    public function show($id)
    {
        $folder = Folder::with('subtopic')->findOrFail($id);
        return view('folders.show', compact('folder'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'
        ]);

        $folder = Folder::findOrFail($id);

        // Store locally first
        $path = $request->file('file')->store('documents', 'public');

        // Upload to Google Drive
        $drive = new GoogleDriveService();
        $driveFolderId = env('GOOGLE_DRIVE_FOLDER_ID'); // You can later make this per-folder
        $driveFileId = $drive->uploadFile($request->file('file'), $driveFolderId);

        // Save to DB
        Document::create([
            'title' => $request->title,
            'file_path' => $path,
            'status' => 'pending',
            'uploaded_by' => Auth::id(),
            'subtopic_id' => $folder->subtopic_id,
            'folder_id' => $folder->id,
            'drive_file_id' => $driveFileId,
        ]);

        return back()->with('success', 'File uploaded successfully and sent to Google Drive!');
    }


//area document 

    

public function uploadToArea(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'
    ]);

    $folder = Folder::findOrFail($id);
    $path = $request->file('file')->store('area_documents', 'public');

    AreaDocument::create([
        'folder_id' => $folder->id,
        'title' => $request->title,
        'file_path' => $path,
    ]);

    return back()->with('success', 'File uploaded to Area folder successfully!');
}

}
