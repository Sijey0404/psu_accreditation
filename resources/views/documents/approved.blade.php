<x-app-layout>
    <div class="container mx-auto px-4 py-8 bg-gray-50 rounded-lg shadow-lg">
        

        <!-- Search Form -->
        <form method="GET" action="{{ route('documents.approved') }}" class="mb-6 flex items-center space-x-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by User Name or Category"
                    class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 placeholder-gray-400">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg focus:outline-none hover:bg-blue-600 transition duration-200 ease-in-out">
                Search
            </button>
        </form>

        <!-- Filter Button and Dropdown -->
        <div class="relative inline-block mb-6">
            <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none flex items-center space-x-2 transition duration-200 ease-in-out" id="filterBtn">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>

            <!-- Dropdown -->
            <div id="filterDropdown" class="absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-60 hidden p-4 border border-gray-200">
                <form method="GET" action="{{ route('documents.approved') }}">
                    <div class="mb-4">
                        <label for="file_type" class="block text-sm font-medium text-gray-700">File Type</label>
                        <select name="file_type" id="file_type" class="mt-1 block w-full text-gray-700 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="docx" {{ request('file_type') == 'docx' ? 'selected' : '' }}>DOCX</option>
                            <option value="xlsx" {{ request('file_type') == 'xlsx' ? 'selected' : '' }}>XLSX</option>
                            <option value="txt" {{ request('file_type') == 'txt' ? 'selected' : '' }}>TXT</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="approved_at" class="block text-sm font-medium text-gray-700">Approved At</label>
                        <input type="date" name="approved_at" id="approved_at" value="{{ request('approved_at') }}" class="mt-1 block w-full text-gray-700 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg focus:outline-none hover:bg-blue-600 transition duration-200 ease-in-out">
                            Apply
                        </button>
                        <button type="button" onclick="clearFilters()" class="text-red-500 hover:text-red-600">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto text-sm text-gray-700">
                <thead class="bg-blue-100 text-blue-700">
                    <tr>
                        <th class="py-3 px-6 text-left">Title</th>
                        <th class="py-3 px-6 text-left">Category</th>
                        <th class="py-3 px-6 text-left">Uploaded By</th>
                        <th class="py-3 px-6 text-left">File Type</th>
                        <th class="py-3 px-6 text-left">Approved At</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr class="hover:bg-blue-50">
                            <td class="py-3 px-6 border-b">{{ $document->title }}</td>
                            <td class="py-3 px-6 border-b">{{ $document->category ?? 'N/A' }}</td>
                            <td class="py-3 px-6 border-b">{{ $document->uploader->name ?? 'Unknown User' }}</td>
                            <td class="py-3 px-6 border-b">{{ strtoupper($document->file_type) }}</td>
                            <td class="py-3 px-6 border-b">
                                {{ $document->approved_at ? \Carbon\Carbon::parse($document->approved_at)->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 border-b">
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-500 hover:underline">
                                    View File
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                No approved documents found.
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
            document.getElementById('approved_at').value = '';
            document.querySelector('form').submit();
        }
    </script>
</x-app-layout>
