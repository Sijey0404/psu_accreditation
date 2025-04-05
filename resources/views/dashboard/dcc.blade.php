<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📂 DCC Dashboard
        </h2>
    </x-slot>

    <div class="flex h-screen">
        <!-- Sidebar Component -->
    

        <!-- Main Content -->
        <div class="flex-1 p-6 ml-64">
            <h3 class="text-lg font-bold">Welcome, Document Custodian Coordinator!</h3>
            <p class="text-gray-600 mb-4">Upload and manage documents for accreditation.</p>
            
            <!-- Upload Form -->
            <div class="bg-white p-4 rounded shadow-md mb-6">
                <h4 class="font-semibold mb-2">Upload a New Document</h4>
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Select File:</label>
                        <input type="file" name="document" required class="w-full border p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Category:</label>
                        <select name="category" required class="w-full border p-2">
                            <option value="">Select a category</option>
                            <option value="Area 1">Area 1</option>
                            <option value="Area 2">Area 2</option>
                            <option value="Area 3">Area 3</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
                </form>
            </div>
            
            <!-- Documents List -->
            <div class="bg-white p-4 rounded shadow-md">
                <h4 class="font-semibold mb-2">Pending Documents</h4>

                @if(isset($documents) && $documents->isNotEmpty())
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">Document Name</th>
                                <th class="border p-2">Category</th>
                                <th class="border p-2">Status</th>
                                <th class="border p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td class="border p-2">{{ $document->name }}</td>
                                <td class="border p-2">{{ $document->category }}</td>
                                <td class="border p-2 text-yellow-500">Pending</td>
                                <td class="border p-2">
                                    <a href="#" class="text-blue-500">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No pending documents.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
