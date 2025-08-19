<x-app-layout>
    <div class="max-w-2xl mx-auto p-8">
        <h1 class="text-2xl font-bold mb-6">Edit Folder Structure: {{ $structure->area_key }}</h1>
        <form method="POST" action="{{ route('folder-structures.update', $structure->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Area Name</label>
                <input type="text" name="area_name" value="{{ old('area_name', $structure->area_name) }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Folders</label>
                <div id="folders-list">
                    @foreach(old('folders', $structure->folders) as $i => $folder)
                        <div class="flex mb-2">
                            <input type="text" name="folders[]" value="{{ $folder }}" class="w-full border rounded p-2" required>
                            <button type="button" onclick="removeFolder(this)" class="ml-2 px-2 py-1 bg-red-500 text-white rounded">Remove</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addFolder()" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">Add Folder</button>
            </div>
            <div class="flex gap-4 mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Changes</button>
                <a href="{{ route('folder-structures.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">Cancel</a>
            </div>
        </form>
    </div>
    <script>
        function addFolder() {
            const list = document.getElementById('folders-list');
            const div = document.createElement('div');
            div.className = 'flex mb-2';
            div.innerHTML = `<input type="text" name="folders[]" class="w-full border rounded p-2" required> <button type="button" onclick="removeFolder(this)" class="ml-2 px-2 py-1 bg-red-500 text-white rounded">Remove</button>`;
            list.appendChild(div);
        }
        function removeFolder(btn) {
            btn.parentElement.remove();
        }
    </script>
</x-app-layout> 