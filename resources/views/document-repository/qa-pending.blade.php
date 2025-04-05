<x-app-layout>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4">📂 Pending Approvals</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-2 mb-4 rounded">{{ session('success') }}</div>
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
                                    @method('POST')
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">✅ Approve</button>
                                </form>

                                <!-- Reject Button (Opens Modal) -->
                                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $document->id }}">
                                    ❌ Reject
                                </button>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1" aria-labelledby="rejectLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectLabel">Reject Document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('document.reject', $document->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <label for="reason" class="font-bold">Rejection Reason:</label>
                                            <textarea name="reason" class="form-control border-gray-300 rounded mt-1" required></textarea>
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 mt-3">Confirm Reject</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
