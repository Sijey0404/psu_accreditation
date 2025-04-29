<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-blue-900 leading-tight">
            Pending Document Approvals
        </h2>
    </x-slot>

    <div class="py-10 px-6 bg-blue-50 min-h-screen">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($documents->isEmpty())
            <div class="text-center mt-20">
                <p class="text-blue-700 text-lg">No pending documents to review.</p>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($documents as $doc)
                    <div class="bg-white border border-blue-200 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex justify-between items-start flex-col md:flex-row gap-4">
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-blue-800">{{ $doc->title }}</h3>
                                <p class="text-sm text-blue-600">
                                    Uploaded by: <span class="font-medium">{{ $doc->uploader->name ?? 'Unknown Uploader' }}</span> |
                                    Subtopic: <span class="font-medium">{{ $doc->subtopic->name ?? 'Unknown Subtopic' }}</span>
                                </p>
                                <p class="text-sm text-blue-500">
                                    Type: <span class="font-medium">{{ $doc->file_type }}</span> |
                                    Status: <span class="text-yellow-500 font-semibold">{{ ucfirst($doc->status) }}</span>
                                </p>
                            </div>

                            <div class="flex gap-3 mt-4 md:mt-0">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                   class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-200 transition">
                                    View
                                </a>

                                {{-- Approve Form --}}
                                <form action="{{ route('documents.approve', $doc->id) }}" method="POST" onsubmit="return confirm('Approve this document?')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                        Approve
                                    </button>
                                </form>

                                {{-- Reject Form --}}
                                <form action="{{ route('documents.reject', $doc->id) }}" method="POST" onsubmit="return confirm('Reject this document?')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
