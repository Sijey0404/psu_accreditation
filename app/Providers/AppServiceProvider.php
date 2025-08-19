<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\View;
use App\Models\Department;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Google Drive Storage Integration
        Storage::extend('google', function ($app, $config) {
            $client = new Client();
            $client->setAuthConfig($config['service_account_json']);
            $client->setScopes([Drive::DRIVE_FILE]);

            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder_id'] ?? null);

            return new Filesystem($adapter);
        });

        // Make departments globally available in all views
        View::composer('*', function ($view) {
            $departments = \App\Models\Department::with([
                'accreditationFolders.subtopics'
            ])->orderBy('name')->get();
            $view->with('departments', $departments);
        });
    }
}
