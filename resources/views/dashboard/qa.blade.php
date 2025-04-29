<x-app-layout>
    <div class="bg-gradient-to-br from-blue-50 to-white min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">🎓 PSU-SCC Accreditation Portal</h1>
                <p class="text-lg text-gray-700">
                    Welcome, <span class="font-semibold text-blue-700">QA Officer</span>! Oversee document approvals, validate submissions, and manage Google Drive content here.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Accreditation Info -->
                <div class="lg:col-span-2 bg-white shadow-md rounded-2xl p-8 border border-blue-100">
                    <h2 class="text-2xl font-semibold text-blue-800 mb-4">🏛 About PSU-SCC Accreditation</h2>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        QA Officers ensure the integrity and quality of submitted accreditation documents. Pangasinan State University – San Carlos Campus (PSU-SCC) complies with AACCUP standards to maintain academic excellence and institutional credibility.
                    </p>
                </div>

                <!-- Role Overview -->
                <div class="bg-blue-50 shadow-md rounded-2xl p-6 border border-blue-100">
                    <h3 class="text-xl font-semibold text-blue-700 mb-3">👤 QA Officer Duties</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Review uploaded documents from Area Chairs and Members.</li>
                        <li>Approve, reject, or comment on pending files.</li>
                        <li>Upload approved files to Google Drive.</li>
                        <li>Ensure accuracy and alignment with AACCUP indicators.</li>
                        <li>Maintain version control and status tracking.</li>
                    </ul>
                </div>

                <!-- Google Drive Integration -->
                <div class="lg:col-span-3 bg-white shadow-md rounded-2xl p-6 border border-blue-100">
                    <h2 class="text-2xl font-semibold text-blue-800 mb-4">📂 PSU SAN CARLOS ACCREDITATION REPOSITORY</h2>

                    <!-- Search Bar -->
                    <div class="flex mb-6">
                        <input 
                            type="text" 
                            id="searchQuery" 
                            class="border border-blue-300 rounded-l px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-200"
                            placeholder="Search Google Drive files...">
                        <button 
                            onclick="redirectToGoogleDrive()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-r transition duration-200">
                            Search
                        </button>
                    </div>

                    <!-- Embedded Drive Folder -->
                    <div class="border rounded-xl overflow-hidden shadow">
                        <iframe 
                            src="https://drive.google.com/embeddedfolderview?id=1c_wtPut9V6LXv1745hrlqv8RWYzsUh8s#grid"
                            class="w-full h-[600px] border-none">
                        </iframe>
                    </div>
                </div>

                <!-- Guidelines -->
                <div class="lg:col-span-3 bg-white shadow-md rounded-2xl p-8 border border-blue-100 mt-6">
                    <h2 class="text-2xl font-semibold text-blue-800 mb-4">📌 QA Guidelines</h2>
                    <ul class="space-y-3 text-gray-700 list-disc list-inside">
                        <li>Carefully evaluate documents before approval.</li>
                        <li>Use only the designated Google Drive folder for uploading finalized files.</li>
                        <li>Ensure document titles and contents match accreditation standards.</li>
                        <li>Tag or label documents appropriately by area/indicator.</li>
                        <li>Work closely with Area Chairs and Accreditors for continuous improvement.</li>
                    </ul>
                </div>
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

            let searchUrl = `https://drive.google.com/drive/search?q=${encodeURIComponent(query)}`;
            window.open(searchUrl, '_blank');
        }
    </script>
</x-app-layout>
