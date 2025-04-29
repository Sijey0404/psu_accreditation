<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subtopic: ') . $subtopic->name }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        {{-- FOLDER GENERATION FORM --}}
        @if(in_array(Auth::user()->role, ['QA', 'Accreditor']))
            <form action="{{ route('subtopics.generateFolders', $subtopic->id) }}" method="POST" class="mb-6" onsubmit="showLoadingModal()">
                @csrf
                <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Select Area</label>
                <select name="area" id="area" class="form-select block w-full mb-2">
                    @foreach($areaFolders as $area => $folders)
                        <option value="{{ $area }}">{{ $area }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Generate Folders</button>
            </form>
        @endif

        {{-- DISPLAY FOLDERS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach($subtopic->folders as $folder)
                <div>
                    <div onclick="openFolderModal({{ $folder->id }}, '{{ $folder->name }}')" class="cursor-pointer inline-block group transition-all duration-300">
                        <svg class="w-24 h-24 mx-auto transition-transform duration-200 transform group-hover:scale-110 
                            {{ 
                                str_contains(strtolower($folder->name), 'area') ? 'text-blue-500' : 
                                (str_contains(strtolower($folder->name), 'status') ? 'text-green-500' : 'text-yellow-500') 
                            }}" 
                            fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 012-2h4l2 2h10a2 2 0 012 2v2M3 7h18a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                        </svg>
                        <p class="mt-2 font-medium text-gray-700 group-hover:underline">{{ $folder->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FOLDER MODAL --}}
        <dialog id="folderModal" class="rounded-lg shadow-xl w-full max-w-2xl p-6">
            <form method="dialog" class="text-right mb-2">
                <button class="text-red-600 font-bold text-xl hover:text-red-800">&times;</button>
            </form>

            <h3 id="folderModalTitle" class="text-xl font-semibold mb-4 text-center"></h3>

            {{-- UPLOAD FORM --}}
            <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <input type="hidden" name="subtopic_id" value="{{ $subtopic->id }}">
                <input type="hidden" name="folder_id" id="modalFolderId">

                <div class="grid gap-3">
                    <input type="text" name="title" placeholder="Document Title" class="border px-3 py-2 rounded" required>
                    <input type="text" name="category" placeholder="Category" class="border px-3 py-2 rounded">
                    <input type="url" name="original_link" placeholder="Original Link (optional)" class="border px-3 py-2 rounded">
                    <input type="file" name="file" class="border px-3 py-2 rounded" required>

                    <button type="submit" class="bg-green-600 text-white py-2 rounded hover:bg-green-700">
                        Upload
                    </button>
                </div>
            </form>

            {{-- DOCUMENT LIST --}}
            <h4 class="text-lg font-semibold mb-2">Uploaded Documents</h4>
            <div id="documentList" class="max-h-64 overflow-y-auto space-y-3 text-sm text-left">
                <p class="text-gray-500">Loading...</p>
            </div>
        </dialog>
    </div>

    {{-- LOADING MODAL --}}
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg text-center shadow-lg animate-pulse max-w-sm w-full">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 animate-spin-slow">
            <h2 class="text-xl font-bold text-blue-700 mb-2">Generating Area Repository</h2>
            <p class="text-gray-700">This might take a while, Please wait...</p>
        </div>
    </div>

    {{-- JS --}}
    <script>
        function openFolderModal(folderId, folderName) {
            const modal = document.getElementById('folderModal');
            document.getElementById('modalFolderId').value = folderId;
            document.getElementById('folderModalTitle').innerText = folderName + ' Contents';

            const docList = document.getElementById('documentList');
            docList.innerHTML = '<p class="text-gray-500">Loading...</p>';

            fetch(`/documents/folder/${folderId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        docList.innerHTML = '<p class="text-gray-500">No documents uploaded yet.</p>';
                        return;
                    }

                    docList.innerHTML = '';
                    data.forEach(doc => {
                        const statusColor = doc.status === 'approved' ? 'text-green-600'
                                            : doc.status === 'rejected' ? 'text-red-600'
                                            : 'text-yellow-600';
                        const rejectionNote = doc.rejection_reason ? `<br><span class="text-xs text-red-600">Reason: ${doc.rejection_reason}</span>` : '';
                        docList.innerHTML += `
                            <div class="bg-gray-100 p-3 rounded shadow-sm border">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">${doc.title}</p>
                                        <p class="text-sm text-gray-500">
                                            Type: ${doc.file_type || 'N/A'} |
                                            Status: <span class="${statusColor}">${doc.status}</span>
                                            ${rejectionNote}
                                        </p>
                                    </div>
                                    <a href="${doc.file_url}" target="_blank" class="text-blue-500 hover:underline text-sm">View</a>
                                </div>
                            </div>`;
                    });
                });

            modal.showModal();
        }

        function showLoadingModal() {
            document.getElementById('loadingModal').classList.remove('hidden');
        }
    </script>

    {{-- Tailwind custom animation --}}
    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 2s linear infinite;
        }
    </style>
</x-app-layout>
