<x-app-layout>
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-blue-800">User Management</h1>
                <p class="text-gray-600">Manage users based on their roles</p>
            </div>
            <button 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition"
                onclick="openModal('createUserModal')"
            >
                + Create New User
            </button>
        </div>

        <!-- User Table -->
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Role</th>
                        <th class="py-3 px-6 text-left">Created At</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-6">{{ $user->id }}</td>
                        <td class="py-3 px-6">{{ $user->name }}</td>
                        <td class="py-3 px-6">{{ $user->email }}</td>
                        <td class="py-3 px-6">{{ $user->role }}</td>
                        <td class="py-3 px-6">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="py-3 px-6 text-center">
                            <button 
                                class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded-lg mr-2"
                                onclick="openEditModal({{ $user }})"
                            >
                                Edit
                            </button>
                            <button 
                                class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg"
                                onclick="openDeleteModal({{ $user->id }})"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-2xl p-8 shadow-lg">
            <h2 class="text-2xl font-bold text-blue-700 mb-4">Create New User</h2>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" required class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" required class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700">Role</label>
                    <select name="role" required class="w-full p-2 border rounded-lg mt-1">
                        <option value="QA">QA</option>
                        <option value="Area Member">Area Member</option>
                        <option value="Area Chair">Area Chair</option>
                        <option value="Accreditor">Accreditor</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg" onclick="closeModal('createUserModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-2xl p-8 shadow-lg">
            <h2 class="text-2xl font-bold text-yellow-500 mb-4">Edit User</h2>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editUserId">
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="editUserName" required class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="editUserEmail" required class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password <span class="text-sm text-gray-500">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" class="w-full p-2 border rounded-lg mt-1" />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700">Role</label>
                    <select name="role" id="editUserRole" required class="w-full p-2 border rounded-lg mt-1">
                        <option value="QA">QA</option>
                        <option value="Area Member">Area Member</option>
                        <option value="Area Chair">Area Chair</option>
                        <option value="Accreditor">Accreditor</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg" onclick="closeModal('editUserModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-2xl p-8 shadow-lg text-center">
            <h2 class="text-2xl font-bold text-red-600 mb-6">Are you sure?</h2>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-4">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg" onclick="closeModal('deleteUserModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Script -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
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
