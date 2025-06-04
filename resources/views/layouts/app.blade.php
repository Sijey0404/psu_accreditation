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
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Global CSRF token setup for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function initializeFilters() {
            // Get all filter elements
            const filterForm = document.querySelector('form[method="GET"]');
            const dateFilter = document.querySelector('input[type="date"]');
            const fileTypeFilter = document.querySelector('select[name="file_type"]');
            const searchInput = document.querySelector('input[name="search"]');

            if (!filterForm) return;

            // Function to submit form
            const submitForm = () => {
                const currentUrl = new URL(window.location.href);
                const formData = new FormData(filterForm);
                
                // Clear existing params
                currentUrl.searchParams.forEach((value, key) => {
                    if (key !== 'page') { // Preserve page parameter
                        currentUrl.searchParams.delete(key);
                    }
                });

                // Add new params
                formData.forEach((value, key) => {
                    if (value) {
                        currentUrl.searchParams.set(key, value);
                    }
                });

                window.location.href = currentUrl.toString();
            };

            // Add event listeners
            if (dateFilter) {
                dateFilter.addEventListener('change', submitForm);
            }
            
            if (fileTypeFilter) {
                fileTypeFilter.addEventListener('change', submitForm);
            }

            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(submitForm, 500); // Debounce search input
                });
            }
        }

        // Initialize filters when DOM is loaded
        document.addEventListener('DOMContentLoaded', initializeFilters);
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-[#1a237e]/5 to-[#FFD700]/5">
        <!-- Fixed Navigation Bar -->
        <div class="fixed top-0 right-0 z-50 bg-gradient-to-r from-[#1a237e] to-[#FFD700] text-white h-14 flex items-center border-b border-white/20" style="left: 256px;">
            <div class="flex justify-end w-full px-2 items-center space-x-4">
                <!-- Notification Component -->
                <x-notification-dropdown align="right" width="96" />
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1.5 py-0.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-white/80 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
        
            <!-- Fixed Sidebar -->
        <div class="fixed left-0 top-0 h-screen z-40 shadow-lg">
                @include('components.sidebar')
            </div>
            
            <!-- Main Content Area -->
        <div class="ml-64 pt-[58px]"> <!-- Reduced top padding -->
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-1 px-0.5">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
            <main class="py-1 px-0.5"> <!-- Minimal padding -->
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
                </main>
        </div>
    </div>
</body>
</html>