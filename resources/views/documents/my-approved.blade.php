<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <!-- Header Banner -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-[{{ $royalBlue }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h1 class="text-xl font-semibold text-gray-900">Approved Documents</h1>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <form method="GET" action="{{ request()->url() }}" id="filterForm" class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Search by title or folder..." 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] outline-none">
                            </div>
                            <!-- Filters -->
                            <div class="flex gap-4">
                                <select name="file_type" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] outline-none">
                                    <option value="">All File Types</option>
                                    <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="docx" {{ request('file_type') == 'docx' ? 'selected' : '' }}>DOCX</option>
                                    <option value="xlsx" {{ request('file_type') == 'xlsx' ? 'selected' : '' }}>XLSX</option>
                                </select>
                                <input type="date" name="date" value="{{ request('date') }}" 
                                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}] outline-none">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Documents Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[{{ $royalBlue }}]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Folder</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">File Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Approved Date</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                    @if($doc->approval_feedback)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Feedback: {{ $doc->approval_feedback }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $doc->folder->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[{{ $goldenBrown }}]/10 text-[{{ $goldenBrown }}]">
                                        {{ strtoupper($doc->file_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $doc->approver->name ?? 'Unknown' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $doc->approved_at ? \Carbon\Carbon::parse($doc->approved_at)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                        class="bg-[{{ $royalBlue }}] text-white px-3 py-1.5 rounded-lg hover:bg-opacity-90 transition-colors duration-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-lg font-medium">No approved documents found</p>
                                        <p class="text-sm text-gray-400">Your approved documents will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($documents->hasPages())
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 