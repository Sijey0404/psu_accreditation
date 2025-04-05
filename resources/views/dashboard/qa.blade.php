<x-app-layout>
  
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold">📊 QA Dashboard</h1>
            <p class="text-gray-600">Welcome, QA Officer! Manage document approvals and reports here.</p>

            <!-- Search Bar -->
            <div class="mt-4 flex">
                <input type="text" id="searchQuery" class="border rounded-l p-2 w-full" placeholder="Search Google Drive files...">
                <button onclick="redirectToGoogleDrive()" class="bg-blue-600 text-white px-4 rounded-r">Search</button>
            </div>

            <!-- Google Drive Integration -->
            <div class="mt-6 border rounded-lg shadow-md">
                <h2 class="text-lg font-semibold bg-gray-200 p-3">📂 Google Drive Interface</h2>
                <iframe 
                    src="https://drive.google.com/embeddedfolderview?id=1c_wtPut9V6LXv1745hrlqv8RWYzsUh8s#grid"
                    class="w-full h-[600px] border-none">
                </iframe>
            </div>
        </div>
    </div>

    <script>
        function redirectToGoogleDrive() {
            let query = document.getElementById('searchQuery').value.trim();
            if (!query) {
                alert("Please enter a search term.");
                return;
            }

            // Redirect to Google Drive search in a new tab
            let searchUrl = `https://drive.google.com/drive/search?q=${encodeURIComponent(query)}`;
            window.open(searchUrl, '_blank');
        }
    </script>
</x-app-layout>
