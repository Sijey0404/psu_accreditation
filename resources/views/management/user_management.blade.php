<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <div class="bg-gradient-to-br from-[#1a237e]/5 to-white min-h-screen p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#1a237e]">User Management</h1>
                    <p class="text-gray-600">Manage users and their roles in the accreditation system</p>
                </div>
                <button 
                        class="bg-[#1a237e] hover:bg-[#1a237e]/90 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md transition-all duration-200 flex items-center space-x-2"
                    onclick="openModal('createUserModal')"
                >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Create New User</span>
                </button>
            </div>

            <!-- User Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-[#1a237e]/10">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-[#1a237e]">
                    <tr>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-[5%]">ID</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-[25%]">Name</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-[25%]">Email</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-[15%]">Role</th>
                            <th class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-[15%]">Created At</th>
                            <th class="px-4 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-[15%]">Actions</th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-[#1a237e]/10 text-[#1a237e] flex items-center justify-center font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role == 'QA') bg-blue-100 text-blue-800
                                    @elseif($user->role == 'Accreditor') bg-purple-100 text-purple-800
                                    @elseif($user->role == 'Area Chair') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                            <button 
                                        class="bg-[#1a237e] hover:bg-[#1a237e]/90 text-white py-1.5 px-3 rounded-lg transition-colors duration-150 flex items-center"
                                onclick="openEditModal({{ $user }})"
                            >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                Edit
                            </button>
                            <button 
                                        class="bg-[#1a237e] hover:bg-[#1a237e]/90 text-white py-1.5 px-3 rounded-lg transition-colors duration-150 flex items-center"
                                onclick="openDeleteModal({{ $user->id }})"
                            >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                Delete
                            </button>
                                </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

                <!-- Pagination -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing 
                            <span class="font-medium">{{ $users->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $users->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $users->total() }}</span>
                            results
                        </div>
                        <div>
                            {{ $users->links('pagination::tailwind')->with(['class' => 'rounded-lg shadow-sm']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-[400px] rounded-xl shadow-xl transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-[#1a237e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Create New User</h2>
                    </div>
                    <button onclick="closeModal('createUserModal')" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <!-- Name Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" name="name" required 
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-[#1a237e] focus:border-[#1a237e] transition-colors duration-200" 
                                placeholder="Enter user's name"/>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email" name="email" required 
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-[#1a237e] focus:border-[#1a237e] transition-colors duration-200" 
                                placeholder="Enter email address"/>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <p class="text-sm text-gray-500 mb-2">Default password: Psuedu123</p>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="createUserPassword" required 
                                class="pl-10 pr-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-[#1a237e] focus:border-[#1a237e] transition-colors duration-200" 
                                placeholder="Enter password"
                                value="Psuedu123"/>
                            <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('createUserPassword', 'createPasswordEyeIcon', 'createPasswordEyeOffIcon')">
                                <svg id="createPasswordEyeIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="createPasswordEyeOffIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                </div>
                </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                </div>
                            <select name="role" required 
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-[#1a237e] focus:border-[#1a237e] transition-colors duration-200 appearance-none bg-white">
                                <option value="">Select a role...</option>
                        <option value="Area Member">Area Member</option>
                        <option value="Area Chair">Area Chair</option>
                    </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <button type="button" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a237e] transition-colors duration-200"
                        onclick="closeModal('createUserModal')">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-[#1a237e] text-white rounded-lg hover:bg-[#1a237e]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a237e] transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-2xl p-8 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-[#b87a3d]">Edit User</h2>
                <button onclick="closeModal('editUserModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editUserId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="editUserName" required 
                            class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-[#b87a3d]/20 focus:border-[#b87a3d] transition-colors duration-200" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="editUserEmail" required 
                            class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-[#b87a3d]/20 focus:border-[#b87a3d] transition-colors duration-200" />
                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password 
                            <span class="text-sm text-gray-500">(Leave blank to keep current)</span>
                        </label>
                        <p class="text-sm text-gray-500 mb-2">Default password: Psuedu123</p>
                        <div class="relative">
                            <input type="password" name="password" id="editUserPassword"
                                class="pr-10 w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-[#1a237e]/20 focus:border-[#1a237e] transition-colors duration-200" 
                                placeholder="Psuedu123"/>
                            <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('editUserPassword', 'editPasswordEyeIcon', 'editPasswordEyeOffIcon')">
                                <svg id="editPasswordEyeIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="editPasswordEyeOffIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                </div>
                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" id="editUserRole" required 
                            class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-[#b87a3d]/20 focus:border-[#b87a3d] transition-colors duration-200">
                        <option value="QA">QA</option>
                        <option value="Area Member">Area Member</option>
                        <option value="Area Chair">Area Chair</option>
                        <option value="Accreditor">Accreditor</option>
                    </select>
                </div>
                </div>
                <div class="flex justify-end space-x-2 mt-8">
                    <button type="button" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200" 
                        onclick="closeModal('editUserModal')">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-[#1a237e] text-white rounded-lg hover:bg-[#1a237e]/90 transition-colors duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-2xl p-8 shadow-lg">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Delete User</h2>
                <p class="text-gray-500 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                    <div class="flex justify-center space-x-3">
                        <button type="button" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200" 
                            onclick="closeModal('deleteUserModal')">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-[#1a237e] text-white rounded-lg hover:bg-[#1a237e]/90 transition-colors duration-200">
                            Delete User
                        </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, eyeIconId, eyeOffIconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeIconId);
            const eyeOffIcon = document.getElementById(eyeOffIconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        function openEditModal(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserEmail').value = user.email;
            document.getElementById('editUserRole').value = user.role;
            document.getElementById('editUserForm').action = '/users/' + user.id;
            openModal('editUserModal');
        }

        function openDeleteModal(userId) {
            document.getElementById('deleteUserForm').action = '/users/' + userId;
            openModal('deleteUserModal');
        }
    </script>
</x-app-layout>
