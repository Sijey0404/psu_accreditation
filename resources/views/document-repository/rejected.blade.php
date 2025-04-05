<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold text-red-600 mb-6 flex items-center">
            ❌ Rejected Documents
        </h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gradient-to-r from-red-500 to-red-700 text-white">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Title</th>
                        <th class="p-3">File</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Rejection Reason</th>
                        <th class="p-3">Date Rejected</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rejectedDocuments as $document)
                    @php
                        $filePath = 'pending_documents/' . $document->file_path;
                        $fileExists = Storage::disk('local')->fileExists($filePath);
                        $fileUrl = $fileExists ? asset('storage/' . $filePath) : '#';
                    @endphp
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="p-3">{{ $document->id }}</td>
                        <td class="p-3 font-semibold">{{ $document->title }}</td>
                        <td class="p-3">
                            @if ($fileExists)
                                <a href="{{ $fileUrl }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition"
                                   target="_blank">
                                    View File
                                </a>
                            @else
                                <span class="text-gray-500">File not found</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $document->category }}</td>
                        <td class="p-3 text-red-500">{{ $document->rejection_reason }}</td>
                        <td class="p-3">{{ $document->updated_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>