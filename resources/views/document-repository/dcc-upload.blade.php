<x-app-layout>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4">📤 Upload Document</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <form action="{{ route('upload.document') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Document Title:</label>
                <input type="text" name="title" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Category:</label>
                <select name="category" class="w-full p-2 border rounded">
                    <option value="Area 1">Area 1</option>
                    <option value="Area 2">Area 2</option>
                    <option value="Area 3">Area 3</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Upload File:</label>
                <input type="file" name="file" class="w-full p-2 border rounded" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
        </form>
    </div>
</x-app-layout>
