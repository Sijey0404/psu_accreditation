<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;

class GoogleDriveController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function index()
    {
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');
        $files = $this->googleDriveService->listFiles($folderId);
        return view('qa.google-drive', compact('files'));
    }

    public function search(Request $request)
    {
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');
        $query = $request->input('query');
        $files = $this->googleDriveService->searchFiles($folderId, $query);
        return view('qa.google-drive', compact('files'));
    }
    public function getFolderContents($id)
{
    $files = $this->googleDriveService->listFiles($id);
    return response()->json([
        'files' => $files,
        'folderName' => 'Folder', // You can enhance this to get actual name
    ]);
}

}
