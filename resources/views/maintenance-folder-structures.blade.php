<x-app-layout>
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-2xl font-bold mb-6">Edit Folder Structures</h1>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full border rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Area Key</th>
                    <th class="p-2 text-left">Area Name</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($structures as $structure)
                <tr class="border-t">
                    <td class="p-2">{{ $structure->area_key }}</td>
                    <td class="p-2">{{ $structure->area_name }}</td>
                    <td class="p-2 text-center">
                        <a href="{{ route('folder-structures.edit', $structure->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            <a href="{{ route('maintenance.test') }}" class="text-gray-600 hover:underline">&larr; Back to Maintenance</a>
        </div>
    </div>
</x-app-layout> 