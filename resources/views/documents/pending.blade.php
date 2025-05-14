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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h1 class="text-xl font-semibold text-gray-900">Pending Documents at PSU San Carlos</h1>
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
                                    placeholder="Search by title, category or uploader..." 
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Uploaded By</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">File Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $doc->category ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $doc->uploader->name ?? 'Unknown' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[{{ $goldenBrown }}]/10 text-[{{ $goldenBrown }}]">
                                        {{ strtoupper($doc->file_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $doc->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                            class="bg-[{{ $royalBlue }}] text-white px-3 py-1.5 rounded-lg hover:bg-opacity-90 transition-colors duration-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                    View
                                </a>
                                        <button onclick="openApproveModal({{ $doc->id }}, '{{ $doc->title }}')"
                                            class="bg-[{{ $royalBlue }}] text-white px-3 py-1.5 rounded-lg hover:bg-opacity-90 transition-colors duration-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        Approve
                                    </button>
                                        <button onclick="openRejectModal({{ $doc->id }}, '{{ $doc->title }}')"
                                            class="bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-opacity-90 transition-colors duration-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        Reject
                                    </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-lg font-medium">No pending documents found</p>
                                        <p class="text-sm text-gray-400">Documents pending approval will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($documents, 'hasPages') && $documents->hasPages())
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Document</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to approve "<span id="approveDocTitle" class="font-medium"></span>"?</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Feedback (Optional)</label>
                <textarea id="approveFeedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" placeholder="Add any feedback or comments..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeApproveModal()" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                <button onclick="submitApproval()" class="bg-[{{ $royalBlue }}] text-white px-4 py-2 rounded-lg hover:bg-opacity-90">
                    Confirm Approval
                </button>
                            </div>
                        </div>
                    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Document</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to reject "<span id="rejectDocTitle" class="font-medium"></span>"?</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea id="rejectReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" placeholder="Please provide a reason for rejection..." required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeRejectModal()" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                <button onclick="submitRejection()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-opacity-90">
                    Confirm Rejection
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentDocId = null;

        function openApproveModal(docId, docTitle) {
            currentDocId = docId;
            document.getElementById('approveDocTitle').textContent = docTitle;
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
            document.getElementById('approveFeedback').value = '';
            currentDocId = null;
        }

        function openRejectModal(docId, docTitle) {
            currentDocId = docId;
            document.getElementById('rejectDocTitle').textContent = docTitle;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejectReason').value = '';
            currentDocId = null;
        }

        function submitApproval() {
            if (!currentDocId) return;

            const feedback = document.getElementById('approveFeedback').value;
            const formData = new FormData();
            formData.append('feedback', feedback);
            formData.append('_token', '{{ csrf_token() }}');

            // Show loading state
            const submitButton = document.querySelector('[onclick="submitApproval()"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'Processing...';
            submitButton.disabled = true;

            fetch(`/documents/${currentDocId}/approve`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Document approved successfully' + (data.feedback ? ' with feedback.' : '.'));
                    window.location.reload();
                } else {
                    // Show error message
                    alert(data.message || 'Error approving document. Please try again.');
                    // Reset button state
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving document. Please try again.');
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        function submitRejection() {
            if (!currentDocId) return;

            const reason = document.getElementById('rejectReason').value;
            if (!reason.trim()) {
                alert('Please provide a reason for rejection.');
                return;
            }

            const formData = new FormData();
            formData.append('reason', reason);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/documents/${currentDocId}/reject`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error rejecting document. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error rejecting document. Please try again.');
            });
        }
    </script>
</x-app-layout>
