<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            📂 View Documents
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-700 mb-4">
                    Browse and access submitted documents for review and evaluation. Click folders to open and view files.
                </p>

                <!-- Navigation Breadcrumb -->
                <div id="breadcrumb" class="mb-4 text-sm text-gray-600"></div>

                <!-- Drive Content -->
                <div id="drive-content">
                    <p>Loading documents...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentFolderId = "{{ env('GOOGLE_DRIVE_FOLDER_ID') }}";
        let folderStack = [];

        document.addEventListener("DOMContentLoaded", function () {
            loadFolder(currentFolderId);
        });

        function loadFolder(folderId) {
            fetch(`/google-drive/folder/${folderId}`)
                .then(response => response.json())
                .then(data => {
                    renderContent(data.files);
                    updateBreadcrumb(folderId, data.folderName);
                });
        }

        function renderContent(files) {
    const contentDiv = document.getElementById('drive-content');
    contentDiv.innerHTML = '';

    if (files.length === 0) {
        contentDiv.innerHTML = '<p class="text-gray-500">This folder is empty.</p>';
        return;
    }

    files.forEach(file => {
        const div = document.createElement('div');
        div.className = "relative p-3 border rounded mb-2 hover:bg-gray-50 cursor-pointer";

        if (file.mimeType === 'application/vnd.google-apps.folder') {
            const fileCount = file.fileCount !== undefined ? file.fileCount : 0;
            const fileCountLabel = `<div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full px-2 py-1">${fileCount} file${fileCount !== 1 ? 's' : ''}</div>`;
            div.innerHTML = `
                ${fileCountLabel}
                📁 <strong>${file.name}</strong>
            `;
            div.onclick = () => {
                folderStack.push({ id: currentFolderId, name: file.name });
                currentFolderId = file.id;
                loadFolder(file.id);
            };
        } else {
            div.innerHTML = `
                📄 ${file.name} 
                <a href="https://drive.google.com/file/d/${file.id}/preview" 
                   target="_blank" 
                   class="text-blue-600 ml-2 underline">View</a>
            `;
        }

        contentDiv.appendChild(div);
    });
}


        function updateBreadcrumb(currentId, currentName) {
            const breadcrumbDiv = document.getElementById('breadcrumb');
            let html = '<a href="#" onclick="goToRoot()" class="text-blue-600 underline">Root</a>';

            folderStack.forEach((folder, index) => {
                html += ` / <a href="#" onclick="goToFolder(${index})" class="text-blue-600 underline">${folder.name}</a>`;
            });

            breadcrumbDiv.innerHTML = html;
        }

        function goToRoot() {
            folderStack = [];
            currentFolderId = "{{ env('GOOGLE_DRIVE_FOLDER_ID') }}";
            loadFolder(currentFolderId);
        }

        function goToFolder(index) {
            const target = folderStack[index];
            folderStack = folderStack.slice(0, index);
            currentFolderId = target.id;
            loadFolder(currentFolderId);
        }
    </script>
</x-app-layout>
