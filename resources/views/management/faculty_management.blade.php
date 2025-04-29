<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">👥 Faculty Management</h1>
        <p class="text-gray-600 mb-4">Manage Area Members under your supervision.</p>

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add new faculty member -->
        <form action="{{ route('faculty.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="name" placeholder="Name" class="border p-2 rounded" required>
                <input type="email" name="email" placeholder="Email" class="border p-2 rounded" required>
                <input type="password" name="password" placeholder="Password" class="border p-2 rounded" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Add Faculty</button>
        </form>

        <!-- Faculty members list -->
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border">
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <!-- Edit -->
                            <form action="{{ route('faculty.update', $user->id) }}" method="POST" class="flex space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $user->name }}" class="border p-1 rounded">
                                <input type="email" name="email" value="{{ $user->email }}" class="border p-1 rounded">
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded">Update</button>
                            </form>

                            <!-- Delete -->
                            <form action="{{ route('faculty.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
