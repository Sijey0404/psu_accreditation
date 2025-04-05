<x-app-layout>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4">📂 Pending Approvals</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-2 mb-4 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-2 mb-4 rounded">{{ session('error') }}</div>
        @endif

        @if($pendingDocuments->isEmpty())
            <p class="text-gray-500">No pending documents for approval.</p>
        @else
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Title</th>
                        <th class="border p-2">Category</th>
                        <th class="border p-2">Uploaded By</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingDocuments as $document)
                        <tr>
                            <td class="border p-2">{{ $document->title }}</td>
                            <td class="border p-2">{{ $document->category }}</td>
                            <td class="border p-2">{{ $document->user->name ?? 'Unknown' }}</td>
                            <td class="border p-2">
                                <!-- Approve Button -->
                                <form action="{{ route('document.approve', $document->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">✅ Approve</button>
                                </form>

                                <!-- Reject Button (Using Tailwind Modal) -->
                                <button type="button" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onclick="openRejectModal('{{ $document->id }}')">
                                    ❌ Reject
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tailwind-based Modals -->
            @foreach($pendingDocuments as $document)
                <div id="rejectModal{{ $document->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" id="modal">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Reject Document</h3>
                            <div class="mt-2 px-7 py-3">
                                <form action="{{ route('document.reject', $document->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_reason" class="block text-gray-700 text-sm font-bold mb-2 text-left">Rejection Reason:</label>
                                        <textarea 
                                            name="rejection_reason" 
                                            id="rejection_reason" 
                                            rows="3" 
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            required
                                        ></textarea>
                                    </div>
                                    <div class="flex justify-between mt-4">
                                        <button 
                                            type="button" 
                                            onclick="closeRejectModal('{{ $document->id }}')"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                            Cancel
                                        </button>
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                            Confirm Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        function openRejectModal(documentId) {
            document.getElementById('rejectModal' + documentId).classList.remove('hidden');
        }
        
        function closeRejectModal(documentId) {
            document.getElementById('rejectModal' + documentId).classList.add('hidden');
        }
    </script>
</x-app-layout>