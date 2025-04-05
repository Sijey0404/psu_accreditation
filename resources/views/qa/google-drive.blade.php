<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 min-h-screen p-4">
            <h2 class="text-lg font-bold mb-4">📂 Smart Repository</h2>

            <ul>
                <li><a href="{{ route('dashboard') }}" class="block py-2">📊 Dashboard</a></li>
                
                @if(auth()->user()->role == 'QA')
                    <li><a href="{{ route('qa.pending') }}" class="block py-2">📂 Pending Approvals</a></li>
                    <li><a href="{{ route('qa.approved') }}" class="block py-2">✅ Approved Documents</a></li>
                    <li><a href="{{ route('qa.rejected') }}" class="block py-2">❌ Rejected Documents</a></li>
                    <li><a href="{{ route('qa.reports') }}" class="block py-2">📜 Reports</a></li>
                @endif
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold">📊 QA Dashboard</h1>
            <p class="text-gray-600">Welcome, QA Officer! Manage document approvals and reports here.</p>

            <!-- Google Drive Search -->
            <form method="GET" action="{{ route('qa.google-drive.search') }}" class="mt-4">
                <input type="text" name="query" placeholder="Search files..." class="p-2 border rounded w-1/2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
            </form>

            <!-- Google Drive File List -->
            <div class="mt-6 border rounded-lg shadow-md">
                <h2 class="text-lg font-semibold bg-gray-200 p-3">📂 Google Drive Documents</h2>

                @if(isset($files) && count($files) > 0)
                    <ul class="p-4">
                        @foreach($files as $file)
                            <li class="py-2 border-b">
                                <a href="{{ $file->webViewLink }}" target="_blank" class="text-blue-600">
                                    {{ $file->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="p-4 text-gray-500">No files found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
