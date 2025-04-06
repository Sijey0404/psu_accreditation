<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $subtopic->name }}</h2>

        <p class="text-gray-600 mb-4">
            Belongs to Department: 
            <a href="{{ route('departments.show', $subtopic->department->slug) }}" class="text-blue-600 underline hover:text-blue-800">
                {{ $subtopic->department->name }}
            </a>
        </p>

        <div class="mb-6">
            <a href="{{ route('subtopics.edit', $subtopic->id) }}" class="text-blue-600 hover:text-blue-800">✏️ Edit Subtopic</a>
        </div>

        <form action="{{ route('subtopics.generateFolders', $subtopic->id) }}" method="POST" class="mb-8 max-w-md" onsubmit="showLoadingModal()">
            @csrf
            <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Select Area:</label>
            <select name="area" id="area" class="border border-gray-300 rounded p-2 w-full mb-4" required>
                <option value="">-- Choose Area --</option>
                @foreach(array_keys($areaFolders) as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">
                📁 Generate Folders
            </button>
        </form>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Folders</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse ($subtopic->folders as $folder)
                <div 
                    class="flex flex-col items-center text-center cursor-pointer bg-yellow-50 hover:bg-yellow-100 rounded-lg p-4 transition-transform transform hover:scale-105 shadow-sm"
                    onclick="openFolderModal('{{ $folder->id }}')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#FACC15" class="w-16 h-16 mb-2">
                        <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v1H2V6z" />
                        <path d="M2 9h20v9a2 2 0 01-2 2H4a2 2 0 01-2-2V9z" />
                    </svg>
                    <span class="text-sm text-gray-700 break-words px-1">{{ $folder->name }}</span>
                </div>

                <!-- Modal -->
                <div id="modal-{{ $folder->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
                    <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
                        <h2 class="text-xl font-bold mb-4">{{ $folder->name }}</h2>

                        <!-- Upload Form -->
                        <form action="{{ route('folders.upload', $folder->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Upload File</label>
                            <input type="file" name="file" required class="block w-full mb-4 text-sm border-gray-300 rounded" />
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Upload
                            </button>
                        </form>

                        <!-- Uploaded Files -->
                        <h4 class="mt-6 mb-2 text-sm font-semibold text-gray-800">Uploaded Files:</h4>
                        <ul class="text-sm text-gray-700 space-y-1 max-h-40 overflow-y-auto">
                            @foreach ($folder->documents ?? [] as $document)
                                <li class="flex justify-between items-center border-b pb-1">
                                    <span>{{ $document->title }}</span>
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                                </li>
                            @endforeach
                        </ul>

                        <button onclick="closeFolderModal('{{ $folder->id }}')" class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                            ✖
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-400">No folders created yet.</p>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition">
                ⬅️ Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Generating "{{ $subtopic->name }}" Repository</h3>
            <p class="text-sm text-gray-600">Please wait...</p>
        </div>
    </div>

    <script>
        function showLoadingModal() {
            document.getElementById('loadingModal').classList.remove('hidden');
        }

        function openFolderModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
        }

        function closeFolderModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
        }
    </script>
</x-app-layout>
