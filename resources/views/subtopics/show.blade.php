<x-app-layout>
    @php
        $departments = \App\Models\Department::orderBy('name')->get();
    @endphp

<div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <!-- Display Subtopic Details -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800"> {{ $subtopic->name }}</h2>
    </div>

    <!-- Display Department Info -->
    <div class="mb-4">
        <h3 class="text-lg text-gray-600">Belongs to Department: <a href="{{ route('departments.show', $subtopic->department->slug) }}" class="text-blue-600">{{ $subtopic->department->name }}</a></h3>
        <p class="text-sm text-gray-500">Course: {{ $subtopic->department->slug }}</p>
    </div>

    <div class="mb-4">
        <a href="{{ route('subtopics.edit', $subtopic->id) }}" class="text-blue-600 hover:text-blue-800">✏️ Edit Subtopic</a>
    </div>

    <div class="text-center">
        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg">Back to Dashboard</a>
    </div>
</div>


</x-app-layout>
