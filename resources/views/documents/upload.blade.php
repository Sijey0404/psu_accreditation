<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📤 Upload Documents
        </h2>
    </x-slot>

    <div class="flex h-screen">
        <!-- Sidebar -->

        <!-- Main Content -->
        <div class="flex-1 p-6 ml-64">
            <h3 class="text-lg font-bold">Upload Accreditation Documents</h3>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('upload.document') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Category</label>
                        <select name="category" class="w-full p-2 border rounded">
                            <option value="Area 1">Area 1</option>
                            <option value="Area 2">Area 2</option>
                            <option value="Area 3">Area 3</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Upload File</label>
                        <input type="file" name="file" class="w-full p-2 border rounded" required>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white p-2 rounded">Upload</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
