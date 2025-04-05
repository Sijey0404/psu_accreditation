<x-app-layout>
    @php
        $departments = \App\Models\Department::orderBy('name')->get();
    @endphp


        <!-- Main Content -->
        <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Department Details</h1>
        
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ $department->name }}</h2>
                <p class="text-sm text-gray-500">Slug: {{ $department->slug }}</p>
            </div>
        
            <div class="mb-4">
                <a href="{{ route('departments.edit', $department->slug) }}" class="text-blue-600 hover:text-blue-800">✏️ Edit Department</a>
            </div>
        
            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg">Back to Dashboard</a>
            </div>
        </div>
</x-app-layout>
