<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[{{ $royalBlue }}] leading-tight">
            📂 View Documents
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
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Navigation Breadcrumb -->
                <div class="bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenBrown }}] p-4">
                    <div id="breadcrumb" class="text-white flex items-center space-x-2 text-sm"></div>
                </div>

                <div class="p-6">
                    <p class="text-gray-600 mb-6">
                    Browse and access submitted documents for review and evaluation. Click folders to open and view files.
                </p>

                <!-- Drive Content -->
                    <div id="drive-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <p class="text-gray-500">Loading documents...</p>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="mt-6 flex justify-center items-center space-x-2">
                        <!-- Pagination will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
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
            <h3 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-2" id="loadingText">Loading Folder</h3>
            <p class="text-gray-500 text-center text-sm">Please wait while we fetch the contents...</p>
        </div>
    </div>

    <script>
        let currentFolderId = "{{ env('GOOGLE_DRIVE_FOLDER_ID') }}";
        let folderStack = [];
        let currentSort = 'name-asc';
        let currentPage = 1;
        const itemsPerPage = 6;
        let allFiles = [];

        // Loading modal functions
        function showLoading(type = 'folder') {
            const loadingModal = document.getElementById('loadingModal');
            const loadingText = document.getElementById('loadingText');
            loadingText.textContent = `Loading ${type}`;
            loadingModal.classList.remove('hidden');
            loadingModal.classList.add('flex');
        }

        function hideLoading() {
            const loadingModal = document.getElementById('loadingModal');
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
        }

        document.addEventListener("DOMContentLoaded", function () {
            showLoading();
            loadFolder(currentFolderId);
            
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

            // Close loading modal when clicking outside
            document.getElementById('loadingModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideLoading();
                }
            });
        });

        function loadFolder(folderId) {
            showLoading('folder');
            fetch(`/google-drive/folder/${folderId}`)
                .then(response => response.json())
                .then(data => {
                    allFiles = data.files;
                    updateBreadcrumb(folderId, data.folderName);
                    sortAndRenderContent();
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function sortContent(sortType) {
            currentSort = sortType;
            currentPage = 1;
            sortAndRenderContent();
            document.getElementById('sortDropdown').classList.add('hidden');
        }

        function sortAndRenderContent() {
            let sortedFiles = [...allFiles];
            
            switch(currentSort) {
                case 'name-asc':
                    sortedFiles.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    sortedFiles.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'files-asc':
                    sortedFiles.sort((a, b) => (a.fileCount || 0) - (b.fileCount || 0));
                    break;
                case 'files-desc':
                    sortedFiles.sort((a, b) => (b.fileCount || 0) - (a.fileCount || 0));
                    break;
            }

            renderContent(sortedFiles);
        }

        function renderContent(files) {
    const contentDiv = document.getElementById('drive-content');
    contentDiv.innerHTML = '';

    if (files.length === 0) {
                contentDiv.innerHTML = '<p class="text-gray-500 col-span-full text-center">This folder is empty.</p>';
        return;
    }

            // Calculate pagination
            const totalPages = Math.ceil(files.length / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedFiles = files.slice(start, end);

            paginatedFiles.forEach(file => {
        const div = document.createElement('div');
                div.className = "bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer";
                
                const isFolder = file.mimeType === 'application/vnd.google-apps.folder';
                const fileCount = file.fileCount || 0;

            div.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">${isFolder ? '📁' : '📄'}</span>
                            <div>
                                <h3 class="font-medium text-[{{ $royalBlue }}]">${file.name}</h3>
                                ${isFolder ? `<p class="text-sm text-gray-500">${fileCount} files</p>` : ''}
                            </div>
                        </div>
                        ${!isFolder ? `
                            <a href="${file.webViewLink}" 
                   target="_blank" 
                               class="text-[{{ $goldenBrown }}] hover:underline"
                               onclick="showLoading('file')">
                                View
                            </a>
                        ` : ''}
                    </div>
                `;

                if (isFolder) {
                    div.onclick = () => loadFolder(file.id);
        }

        contentDiv.appendChild(div);
    });

            // Render pagination
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            if (totalPages <= 1) return;

            const maxVisiblePages = 5; // Show max 5 page numbers at a time
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if we're near the end
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Previous button
            const prevButton = createPaginationButton('←', currentPage > 1, () => {
                currentPage--;
                sortAndRenderContent();
            });
            paginationDiv.appendChild(prevButton);

            // First page and ellipsis
            if (startPage > 1) {
                const firstButton = createPaginationButton('1', true, () => {
                    currentPage = 1;
                    sortAndRenderContent();
                });
                paginationDiv.appendChild(firstButton);
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-500';
                    ellipsis.textContent = '...';
                    paginationDiv.appendChild(ellipsis);
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = createPaginationButton(i.toString(), true, () => {
                    currentPage = i;
                    sortAndRenderContent();
                }, i === currentPage);
                paginationDiv.appendChild(pageButton);
            }

            // Last page and ellipsis
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-500';
                    ellipsis.textContent = '...';
                    paginationDiv.appendChild(ellipsis);
                }
                const lastButton = createPaginationButton(totalPages.toString(), true, () => {
                    currentPage = totalPages;
                    sortAndRenderContent();
            });
                paginationDiv.appendChild(lastButton);
        }

            // Next button
            const nextButton = createPaginationButton('→', currentPage < totalPages, () => {
                currentPage++;
                sortAndRenderContent();
            });
            paginationDiv.appendChild(nextButton);

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

        function updateBreadcrumb(folderId, folderName) {
            const breadcrumb = document.getElementById('breadcrumb');
            const index = folderStack.findIndex(f => f.id === folderId);

            if (index === -1) {
                folderStack.push({ id: folderId, name: folderName });
            } else {
                folderStack = folderStack.slice(0, index + 1);
            }

            breadcrumb.innerHTML = folderStack.map((folder, i) => `
                <button onclick="loadFolder('${folder.id}')" 
                    class="hover:underline ${i === folderStack.length - 1 ? 'font-semibold' : ''}">
                    ${folder.name}
                </button>
                ${i < folderStack.length - 1 ? '<span class="mx-2">/</span>' : ''}
            `).join('');
        }
    </script>
</x-app-layout>
