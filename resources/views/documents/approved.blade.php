<x-app-layout>


    <div class="flex">
        <!-- Include Sidebar Component -->
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($documents->where('status', 'approved') as $document)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $document->title }}</h3>
                        <p class="text-gray-600 mb-4">
                            Category: {{ $document->category }}
                            @if($document->file_type)
                                <span class="ml-2 text-sm text-gray-500">({{ strtoupper($document->file_type) }})</span>
                            @endif
                        </p>
                        
                        <div class="flex space-x-2">
                            @if (strpos($document->file_path, 'https://drive.google.com') !== false)
                                <a 
                                    href="{{ $document->file_path }}" 
                                    target="_blank"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    View on Google Drive
                                </a>
                                <a 
                                    href="{{ $document->file_path }}" 
                                    target="_blank"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Download
                                </a>
                            @else
                                <button 
                                    onclick="openDocumentModal({{ $document->id }})"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    View Document
                                </button>
                                
                                <a 
                                    href="{{ route('documents.download', $document->id) }}"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Download
                                </a>
                            @endif
                        </div>

                        <div class="mt-2 text-sm text-gray-500">
                            @if($document->approved_at)
                                Approved: {{ $document->approved_at->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Document Modal -->
    <div 
        id="documentModal" 
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center"
    >
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 id="modalDocumentTitle" class="text-xl font-bold">Document Viewer</h2>
                <button 
                    onclick="closeDocumentModal()"
                    class="text-gray-600 hover:text-gray-900"
                >
                    &times;
                </button>
            </div>
            
            <div id="modalDocumentContent" class="p-4 overflow-auto">
                <!-- Document content will be loaded here -->
                <iframe 
                    id="documentFrame" 
                    class="w-full h-[600px]" 
                    src=""
                ></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDocumentModal(documentId) {
            const modal = document.getElementById('documentModal');
            const iframe = document.getElementById('documentFrame');
            const titleElement = document.getElementById('modalDocumentTitle');

            // Set the iframe source to the document view route
            iframe.src = `/documents/${documentId}/view`;
            
            // You might want to fetch the document title via AJAX and set it here
            // titleElement.textContent = documentTitle;

            modal.classList.remove('hidden');
        }

        function closeDocumentModal() {
            const modal = document.getElementById('documentModal');
            const iframe = document.getElementById('documentFrame');
            
            iframe.src = ''; // Clear the iframe source
            modal.classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>