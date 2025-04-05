<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
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

    public function uploadFile($filePath, $fileName, $folderId = null)
    {
        try {
            $file = new \Google_Service_Drive_DriveFile();
            $file->setName($fileName);
            
            // If folder ID is provided, set the parent
            if ($folderId) {
                $file->setParents([$folderId]);
            }

            // Perform the file upload
            $result = $this->service->files->create(
                $file,
                [
                    'data' => file_get_contents($filePath),
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                ]
            );

            // Generate a shareable link
            $fileId = $result->getId();
            $permission = new \Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('reader');
            $this->service->permissions->create($fileId, $permission);

            return "https://drive.google.com/file/d/{$fileId}/view";
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Google Drive Upload Error: ' . $e->getMessage());
            throw $e;
        }
    }
}