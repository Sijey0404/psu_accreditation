<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Rejected Documents</h1>

        <!-- Search Form -->
        <form method="GET" action="{{ route('documents.rejected') }}" class="mb-6 flex">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by User Name or Category" class="border border-gray-300 p-2 rounded-l-lg w-full focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-r-lg focus:outline-none hover:bg-red-600">
                Search
            </button>
        </form>

        <!-- Filter Button and Dropdown -->
        <div class="relative inline-block">
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 focus:outline-none" id="filterBtn">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>

            <!-- Dropdown -->
            <div id="filterDropdown" class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-48 hidden">
                <form id="filterForm" method="GET" action="{{ route('documents.rejected') }}" class="p-4">
                    <div class="mb-4">
                        <label for="file_type" class="block text-sm font-medium text-gray-700">File Type</label>
                        <select name="file_type" id="file_type" class="mt-1 block w-full text-gray-700 border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <option value="">All</option>
                            <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="docx" {{ request('file_type') == 'docx' ? 'selected' : '' }}>DOCX</option>
                            <option value="xlsx" {{ request('file_type') == 'xlsx' ? 'selected' : '' }}>XLSX</option>
                            <option value="txt" {{ request('file_type') == 'txt' ? 'selected' : '' }}>TXT</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="created_at" class="block text-sm font-medium text-gray-700">Rejected At</label>
                        <input type="date" name="created_at" id="created_at" value="{{ request('created_at') }}" class="mt-1 block w-full text-gray-700 border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg focus:outline-none hover:bg-red-600">
                            Apply
                        </button>
                        <button type="button" onclick="clearFilters()" class="text-red-500 hover:text-red-600">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto mt-6">
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Title</th>
                        <th class="py-2 px-4 border-b text-left">Category</th>
                        <th class="py-2 px-4 border-b text-left">Uploaded By</th>
                        <th class="py-2 px-4 border-b text-left">File Type</th>
                        <th class="py-2 px-4 border-b text-left">Rejected At</th>
                        <th class="py-2 px-4 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $document->title }}</td>
                            <td class="py-2 px-4 border-b">{{ $document->category ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                {{ $document->uploader->name ?? 'Unknown User' }}
                            </td>
                            <td class="py-2 px-4 border-b">{{ strtoupper($document->file_type) }}</td>
                            <td class="py-2 px-4 border-b">
                                {{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-red-500 hover:underline">
                                    View File
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                No rejected documents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle the dropdown visibility when clicking the filter button
        document.getElementById('filterBtn').addEventListener('click', function() {
            document.getElementById('filterDropdown').classList.toggle('hidden');
        });

        // Clear all filter inputs
        function clearFilters() {
            document.getElementById('file_type').value = '';
            document.getElementById('created_at').value = '';
            document.getElementById('filterForm').submit();
        }
    </script>
</x-app-layout>
