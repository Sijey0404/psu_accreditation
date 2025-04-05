<x-dashboard-layout>
    <h2 class="text-xl font-semibold mb-4">📤 Upload Document to Google Drive</h2>

    <p><strong>Title:</strong> {{ $document->title }}</p>
    <p><strong>Category:</strong> {{ $document->category }}</p>
    <p><strong>Status:</strong> {{ $document->status }}</p>

    <a href="{{ $googleDriveLink }}" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded inline-block">Upload to Google Drive</a>

    <form action="{{ route('save.google.drive.link', $document->id) }}" method="POST" class="mt-4">
        @csrf
        <label class="block text-gray-700">Paste the Google Drive link after uploading:</label>
        <input type="url" name="google_drive_link" class="border p-2 w-full mb-2" required>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Link</button>
    </form>
</x-dashboard-layout>

