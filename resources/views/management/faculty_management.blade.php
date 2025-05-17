<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <div class="bg-gradient-to-br from-[{{ $royalBlue }}]/5 to-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[{{ $royalBlue }}]">Faculty Management</h1>
                    <p class="text-gray-600">Manage Area Members under your supervision</p>
                </div>
                <button onclick="openAddModal()" 
                    class="bg-[{{ $royalBlue }}] hover:bg-opacity-90 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Add New Faculty</span>
                </button>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-[{{ $goldenBrown }}]/10 text-[{{ $goldenBrown }}] rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Faculty Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-[{{ $royalBlue }}]/10">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[{{ $royalBlue }}]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Faculty Member</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-[{{ $royalBlue }}]/10 text-[{{ $royalBlue }}] flex items-center justify-center font-semibold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">Area Member</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <button onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')" 
                                            class="text-[{{ $goldenBrown }}] hover:text-[{{ $goldenBrown }}]/80 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('faculty.destroy', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.parentElement)" 
                                                class="text-red-500 hover:text-red-600 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-lg font-medium">No faculty members found</p>
                                        <p class="text-sm text-gray-400">Add faculty members to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Faculty Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add New Faculty Member</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('faculty.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" 
                        class="bg-[{{ $royalBlue }}] text-white px-4 py-2 rounded-lg hover:bg-opacity-90">
                        Add Faculty
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Faculty Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Faculty Member</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="editName" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="editEmail" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                        <input type="password" name="password" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]"
                            placeholder="Leave blank to keep current password">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" 
                        class="bg-[{{ $royalBlue }}] text-white px-4 py-2 rounded-lg hover:bg-opacity-90">
                        Update Faculty
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('flex');
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, name, email) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const nameInput = document.getElementById('editName');
            const emailInput = document.getElementById('editEmail');

            form.action = `/faculty-management/${id}`;
            nameInput.value = name;
            emailInput.value = email;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('flex');
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(form) {
            if (confirm('Are you sure you want to remove this faculty member?')) {
                form.submit();
            }
        }
    </script>
</x-app-layout>
