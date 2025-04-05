<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Fixed Navigation -->
        <div class="fixed top-0 w-full z-50">
            @include('layouts.navigation')
        </div>
        
        <div class="flex pt-16"> <!-- Add padding top to account for fixed navbar -->
            <!-- Fixed Sidebar -->
            <div class="fixed w-64 h-screen top-0"> <!-- Ensure sidebar starts from top -->
                @include('components.sidebar')
            </div>
            
            </div>
            
            <!-- Main Content Area -->
            <div class="flex-1 ml-64"> <!-- Add margin-left to account for sidebar width -->
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>