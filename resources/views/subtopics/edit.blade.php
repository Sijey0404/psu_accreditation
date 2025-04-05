<x-app-layout>
<div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Edit Subtopic</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-md">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Subtopic Edit Form -->
    <form action="{{ route('subtopics.update', $subtopic->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-lg font-medium text-gray-700">Subtopic Name:</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name', $subtopic->name) }}" required>
        </div>

        <div class="text-center">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">
                ✏️ Update Subtopic
            </button>
        </div>
    </form>
</div>
</x-app-layout>
