<div id="approvalModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded shadow-md w-1/3">
        <h2 class="text-xl font-bold mb-4">Pending Document Approvals</h2>

        <div class="max-h-64 overflow-y-auto">
            @foreach($pendingDocuments as $document)
            <div class="p-4 border rounded shadow-md bg-white mb-2">
                <p class="text-lg font-bold">{{ $document->title }}</p>
                <p class="text-sm text-gray-600">{{ $document->category }}</p>
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-500 underline">View File</a>

                <div class="flex mt-2 space-x-2">
                    <form action="{{ route('document.approve', $document->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-green-500 text-white p-2 rounded">Approve</button>
                    </form>
                    <form action="{{ route('document.reject', $document->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-red-500 text-white p-2 rounded">Reject</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <button class="mt-4 text-gray-600 block w-full text-center" onclick="document.getElementById('approvalModal').style.display='none'">Close</button>
    </div>
</div>
