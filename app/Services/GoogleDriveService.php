<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        
        // Path to your service account key
        $credentialsPath = storage_path('app/google-drive/API.json');
        
        // Check if credentials file exists
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Google Drive credentials file not found. Please upload your service account JSON.");
        }

        // Configure the client
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(Google_Service_Drive::DRIVE);
        
        // Create Drive service
        $this->service = new Google_Service_Drive($this->client);
    }

    /**
     * Uploads a file to Google Drive
     */
    public function uploadFile($filePath, $fileName, $folderId = null)
    {
        try {
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($fileName);

            if ($folderId) {
                $file->setParents([$folderId]);
            }

            $result = $this->service->files->create(
                $file,
                [
                    'data' => file_get_contents($filePath),
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                ]
            );

            $fileId = $result->getId();

            // Set permission to "anyone with the link"
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('reader');
            $this->service->permissions->create($fileId, $permission);

            return "https://drive.google.com/file/d/{$fileId}/view";
        } catch (\Exception $e) {
            \Log::error('Google Drive Upload Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Creates a folder in Google Drive, optionally under a parent folder
     */
    public function createFolder($folderName, $parentId = null)
    {
        try {
            $folderMetadata = new Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

            if ($parentId) {
                $folderMetadata->setParents([$parentId]);
            }

            $folder = $this->service->files->create($folderMetadata, [
                'fields' => 'id',
            ]);

            // Set permission to "anyone with the link"
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('reader');
            $this->service->permissions->create($folder->id, $permission);

            return $folder->id;
        } catch (\Exception $e) {
            \Log::error('Google Drive Folder Creation Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
