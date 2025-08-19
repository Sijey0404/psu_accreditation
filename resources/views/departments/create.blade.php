<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    @php
        $departments = \App\Models\Department::orderBy('name')->get();
    @endphp

    <!-- Header Banner -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-[{{ $royalBlue }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h1 class="text-xl font-semibold text-gray-900">Add New Program at PSU San Carlos</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-[80vh] flex items-center justify-center py-6 bg-gray-50">
        <div class="w-full max-w-xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="px-6 py-4 bg-[{{ $royalBlue }}]/5 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-[{{ $royalBlue }}]">Program Details</h2>
                    <p class="mt-1 text-sm text-gray-500">Enter the course and department information below.</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('departments.store') }}" method="POST" class="space-y-6">
            @csrf
                        
                        <!-- Course Input -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="name" 
                                    id="name" 
                                    class="block w-full pl-10 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] outline-none transition-colors duration-200" 
                                    required 
                                    placeholder="Enter the course name"
                                    value="{{ old('name') }}">
                            </div>
                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                        <!-- Department Input -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">College Name</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="slug" 
                                    id="slug" 
                                    class="block w-full pl-10 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] outline-none transition-colors duration-200" 
                                    required 
                                    placeholder="Enter the College name"
                                    value="{{ old('slug') }}">
                            </div>
                @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <a href="{{ route('departments.index') }}" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ $royalBlue }}] transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="px-4 py-2 bg-[{{ $royalBlue }}] text-white rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ $royalBlue }}] transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Program
                </button>
            </div>
        </form>
                </div>
            </div>

            <!-- Success Message -->
        @if(session('success'))
                <div class="mt-4 p-4 bg-[{{ $goldenBrown }}]/10 text-[{{ $goldenBrown }}] rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        </div>
    </div>
</x-app-layout>
