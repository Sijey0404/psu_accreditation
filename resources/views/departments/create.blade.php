<x-app-layout>
    @php
        $departments = \App\Models\Department::orderBy('name')->get();
    @endphp

    <!-- Main Content -->
    <div class="flex-1 p-6 bg-gray-50 rounded-lg shadow-md">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">📂 Add New Course</h1>

        <form action="{{ route('departments.store') }}" method="POST" class="mt-4 space-y-6">
            @csrf
            <div class="space-y-2">
                <label for="name" class="block text-lg font-medium text-gray-700">Course:</label>
                <input type="text" name="name" id="name" class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Enter department name" value="{{ old('name') }}">

                @error('name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="slug" class="block text-lg font-medium text-gray-700">Department:</label>
                <input type="text" name="slug" id="slug" class="border border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Enter name" value="{{ old('slug') }}">

                @error('slug')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">
                    ➕ Add Programs
                </button>
            </div>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-600 rounded-lg text-center">
                <p>{{ session('success') }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
