<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <!-- Header Banner -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-[{{ $royalBlue }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h1 class="text-xl font-semibold text-gray-900">Programs at PSU San Carlos</h1>
                </div>
                <a href="{{ route('departments.create') }}" 
                    class="inline-flex items-center px-4 py-2 bg-[{{ $royalBlue }}] text-white rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ $royalBlue }}] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Program
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                @if($departments->isEmpty())
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No programs found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new program.</p>
                        <div class="mt-6">
                            <a href="{{ route('departments.create') }}" class="inline-flex items-center px-4 py-2 bg-[{{ $royalBlue }}] text-white rounded-lg hover:bg-opacity-90">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add New Program
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[{{ $royalBlue }}]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($departments as $department)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $department->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $department->slug }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('departments.show', $department->slug) }}" 
                                                class="text-[{{ $royalBlue }}] hover:text-[{{ $royalBlue }}]/80 mr-3">
                                                View
                                            </a>
                                            <a href="#" 
                                                class="text-[{{ $goldenBrown }}] hover:text-[{{ $goldenBrown }}]/80">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mt-4 p-4 bg-[{{ $goldenBrown }}]/10 text-[{{ $goldenBrown }}] rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 