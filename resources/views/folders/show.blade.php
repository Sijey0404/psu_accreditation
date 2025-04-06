<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">{{ $folder->name }}</h2>
        <p class="mb-6 text-gray-600">Under subtopic: {{ $folder->subtopic->name }}</p>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <!-- Upload Form -->
<form action="{{ route('folders.uploadToArea', $folder->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label class="block mb-2 text-sm font-medium text-gray-700">Document Title</label>
    <input type="text" name="title" required class="block w-full mb-4 text-sm border-gray-300 rounded" />

    <label class="block mb-2 text-sm font-medium text-gray-700">Upload File</label>
    <input type="file" name="file" required class="block w-full mb-4 text-sm border-gray-300 rounded" />

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Upload
    </button>
</form>


<!-- Uploaded Documents -->
@if($folder->areaDocuments->count())
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Uploaded Files</h3>
        <ul class="space-y-2">
            @foreach($folder->areaDocuments as $doc)
                <li class="bg-gray-100 p-3 rounded shadow-sm flex justify-between items-center">
                    <span>{{ $doc->title }}</span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                        📂 View
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

    </div>
</x-app-layout>
