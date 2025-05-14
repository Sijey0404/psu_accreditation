<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[{{ $royalBlue }}] leading-tight">
            {{ __('Subtopic: ') . $subtopic->name }}
        </h2>
            <div class="flex items-center space-x-4">
                <!-- Sort Dropdown -->
                <div class="relative inline-block">
                    <button id="sortBtn" class="bg-[{{ $royalBlue }}] text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <span>Sort By</span>
                    </button>
                    <div id="sortDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="p-2">
                            <button onclick="sortContent('name-asc')" class="w-full text-left px-4 py-2 hover:bg-[{{ $royalBlue }}] hover:bg-opacity-10 rounded-md">
                                Name (A-Z)
                            </button>
                            <button onclick="sortContent('name-desc')" class="w-full text-left px-4 py-2 hover:bg-[{{ $royalBlue }}] hover:bg-opacity-10 rounded-md">
                                Name (Z-A)
                            </button>
                            <button onclick="sortContent('files-asc')" class="w-full text-left px-4 py-2 hover:bg-[{{ $royalBlue }}] hover:bg-opacity-10 rounded-md">
                                Files (Low to High)
                            </button>
                            <button onclick="sortContent('files-desc')" class="w-full text-left px-4 py-2 hover:bg-[{{ $royalBlue }}] hover:bg-opacity-10 rounded-md">
                                Files (High to Low)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- FOLDER GENERATION SECTION --}}
            @if(in_array(Auth::user()->role, ['QA', 'Accreditor']) && !$subtopic->has_generated_folders)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenBrown }}] p-4">
                        <h3 class="text-xl font-semibold text-white">Generate Repositories</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-[{{ $royalBlue }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-center mb-6">
                            Click below to generate all necessary folders and structure for {{ $subtopic->name }}.
                        </p>
                        <form action="{{ route('subtopics.generateFolders', $subtopic->id) }}" method="POST" onsubmit="showLoadingModal()" class="text-center">
                @csrf
                            <input type="hidden" name="area" value="{{ $subtopic->name }}">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-[{{ $royalBlue }}] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ $royalBlue }}] transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Generate Repositories
                            </button>
            </form>
                    </div>
                </div>
        @endif

        {{-- DISPLAY FOLDERS --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenBrown }}] p-4">
                    <h3 class="text-xl font-semibold text-white">Folders</h3>
                </div>
                <div class="p-6">
                    <div id="folders-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($subtopic->folders as $folder)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer"
                                 onclick="openFolderModal({{ $folder->id }}, '{{ $folder->name }}')">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">📁</span>
                <div>
                                            <h3 class="font-medium text-[{{ $royalBlue }}]">{{ $folder->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $folder->documents_count }} files</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Pagination -->
                    <div id="pagination" class="mt-6 flex justify-center items-center space-x-2">
                        <!-- Pagination will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- FOLDER MODAL --}}
    <dialog id="folderModal" class="rounded-xl shadow-2xl w-full max-w-xl p-0 bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-[{{ $royalBlue }}] text-white px-6 py-4 flex justify-between items-center">
            <h3 id="folderModalTitle" class="text-lg font-semibold"></h3>
            <form method="dialog" class="ml-4">
                <button class="text-white hover:text-blue-200 transition-colors duration-150">&times;</button>
            </form>
        </div>

        <div class="p-6">
            {{-- UPLOAD FORM --}}
            <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <input type="hidden" name="subtopic_id" value="{{ $subtopic->id }}">
                <input type="hidden" name="folder_id" id="modalFolderId">

                <div class="space-y-4">
                    <div>
                        <input type="text" name="title" placeholder="Document Title" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] transition-colors duration-150" 
                            required>
                    </div>
                    <div>
                        <input type="text" name="category" placeholder="Category" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] transition-colors duration-150">
                    </div>
                    <div class="relative">
                        <input type="file" name="file" 
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] transition-colors duration-150 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[{{ $royalBlue }}] file:bg-opacity-10 file:text-[{{ $royalBlue }}] hover:file:bg-opacity-20" 
                            required>
                    </div>
                    <button type="submit" 
                        class="w-full bg-[{{ $royalBlue }}] text-white py-2 rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:ring-offset-2 transition-colors duration-150 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Upload Document
                    </button>
                </div>
            </form>

            {{-- DOCUMENT LIST --}}
            <div class="border-t border-gray-200 pt-4">
                <h4 class="text-lg font-semibold mb-3 text-[{{ $royalBlue }}]">Uploaded Documents</h4>
                <div id="documentList" class="max-h-64 overflow-y-auto space-y-2 text-sm">
                <p class="text-gray-500">Loading...</p>
                </div>
            </div>
            </div>
        </dialog>

    {{-- LOADING MODAL --}}
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col items-center max-w-sm w-full mx-4">
            <div class="relative w-20 h-20 mb-4">
                <!-- Circular loading animation -->
                <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-[{{ $royalBlue }}] border-r-[{{ $goldenBrown }}] rounded-full animate-spin"></div>
                
                <!-- Folder icon in center -->
                <div class="absolute inset-0 flex items-center justify-center text-2xl">
                    📂
                </div>
            </div>
            <h3 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-2">Generating Area Repository</h3>
            <p class="text-gray-500 text-center">This might take a while, Please wait...</p>
        </div>
    </div>

    <script>
        let currentSort = 'name-asc';
        let currentPage = 1;
        const itemsPerPage = 6;
        let allFolders = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize folders array
            allFolders = Array.from(document.querySelectorAll('#folders-grid > div')).map(div => ({
                element: div,
                name: div.querySelector('h3').textContent,
                fileCount: parseInt(div.querySelector('p').textContent) || 0
            }));

            // Initial sort and render
            sortAndRenderContent();

            // Sort dropdown toggle
            document.getElementById('sortBtn').addEventListener('click', function() {
                document.getElementById('sortDropdown').classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#sortBtn') && !e.target.closest('#sortDropdown')) {
                    document.getElementById('sortDropdown').classList.add('hidden');
                }
            });
        });

        function sortContent(sortType) {
            currentSort = sortType;
            currentPage = 1;
            sortAndRenderContent();
            document.getElementById('sortDropdown').classList.add('hidden');
        }

        function sortAndRenderContent() {
            let sortedFolders = [...allFolders];
            
            switch(currentSort) {
                case 'name-asc':
                    sortedFolders.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    sortedFolders.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'files-asc':
                    sortedFolders.sort((a, b) => a.fileCount - b.fileCount);
                    break;
                case 'files-desc':
                    sortedFolders.sort((a, b) => b.fileCount - a.fileCount);
                    break;
            }

            renderContent(sortedFolders);
        }

        function renderContent(folders) {
            const grid = document.getElementById('folders-grid');
            grid.innerHTML = '';

            if (folders.length === 0) {
                grid.innerHTML = '<p class="text-gray-500 col-span-full text-center">No folders available.</p>';
                return;
            }

            // Calculate pagination
            const totalPages = Math.ceil(folders.length / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedFolders = folders.slice(start, end);

            paginatedFolders.forEach(folder => {
                grid.appendChild(folder.element.cloneNode(true));
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            if (totalPages <= 1) return;

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Previous button
            paginationDiv.appendChild(createPaginationButton('←', currentPage > 1, () => {
                currentPage--;
                sortAndRenderContent();
            }));

            // First page and ellipsis
            if (startPage > 1) {
                paginationDiv.appendChild(createPaginationButton('1', true, () => {
                    currentPage = 1;
                    sortAndRenderContent();
                }));
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-500';
                    ellipsis.textContent = '...';
                    paginationDiv.appendChild(ellipsis);
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                paginationDiv.appendChild(createPaginationButton(i.toString(), true, () => {
                    currentPage = i;
                    sortAndRenderContent();
                }, i === currentPage));
            }

            // Last page and ellipsis
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-500';
                    ellipsis.textContent = '...';
                    paginationDiv.appendChild(ellipsis);
                }
                paginationDiv.appendChild(createPaginationButton(totalPages.toString(), true, () => {
                    currentPage = totalPages;
                    sortAndRenderContent();
                }));
            }

            // Next button
            paginationDiv.appendChild(createPaginationButton('→', currentPage < totalPages, () => {
                currentPage++;
                sortAndRenderContent();
            }));

            // Page info
            const pageInfo = document.createElement('div');
            pageInfo.className = 'text-sm text-gray-500 ml-4';
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            paginationDiv.appendChild(pageInfo);
        }

        function createPaginationButton(text, enabled, onClick, isActive = false) {
            const button = document.createElement('button');
            button.className = `px-3 py-1 rounded-md mx-1 ${
                isActive 
                    ? `bg-[{{ $royalBlue }}] text-white` 
                    : enabled 
                        ? `text-[{{ $royalBlue }}] hover:bg-[{{ $royalBlue }}] hover:bg-opacity-10 border border-[{{ $royalBlue }}] border-opacity-20` 
                        : 'text-gray-400 cursor-not-allowed'
            }`;
            button.textContent = text;
            if (enabled) button.onclick = onClick;
            return button;
        }

        function openFolderModal(folderId, folderName) {
            const modal = document.getElementById('folderModal');
            document.getElementById('modalFolderId').value = folderId;
            document.getElementById('folderModalTitle').innerText = folderName;

            const docList = document.getElementById('documentList');
            docList.innerHTML = '<p class="text-gray-500">Loading...</p>';

            fetch(`/documents/folder/${folderId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        docList.innerHTML = '<p class="text-gray-500 text-center py-4">No documents uploaded yet.</p>';
                        return;
                    }

                    docList.innerHTML = '';
                    data.forEach(doc => {
                        const statusColor = doc.status === 'approved' ? 'text-green-600 bg-green-50'
                                        : doc.status === 'rejected' ? 'text-red-600 bg-red-50'
                                        : 'text-yellow-600 bg-yellow-50';
                        const rejectionNote = doc.rejection_reason ? `<p class="text-xs text-red-600 mt-1">Reason: ${doc.rejection_reason}</p>` : '';
                        docList.innerHTML += `
                            <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow duration-150">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">${doc.title}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500">Type: ${doc.file_type || 'N/A'}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full ${statusColor}">${doc.status}</span>
                                        </div>
                                            ${rejectionNote}
                                    </div>
                                    <a href="${doc.file_url}" target="_blank" 
                                       class="flex items-center text-[{{ $royalBlue }}] hover:text-[{{ $goldenBrown }}] text-sm font-medium">
                                       <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                       </svg>
                                       View
                                    </a>
                                </div>
                            </div>`;
                    });
                });

            modal.showModal();
        }

        function showLoadingModal() {
            document.getElementById('loadingModal').classList.remove('hidden');
            document.getElementById('loadingModal').classList.add('flex');
        }
    </script>

    <style>
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</x-app-layout>
