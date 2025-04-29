@props(['departments'])

<div class="bg-white text-blue-600 w-64 h-screen max-h-screen p-4 flex-shrink-0 overflow-y-auto">
    <h2 class="text-lg font-bold mb-4">Navigation</h2>

    <ul>
        <li><a href="{{ route('dashboard') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">📊 Dashboard</a></li>
        <!-- Static sidebar items remain unchanged -->

        @if(auth()->user()->role == 'QA')
            <li><a href="{{ route('qa.pending') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">📂 Pending Approvals</a></li>
            <li><a href="{{ route('qa.approved') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">✅ Approved Documents</a></li>
            <li><a href="{{ route('qa.rejected') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">❌ Rejected Documents</a></li>
            <li><a href="{{ route('user.management') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">👤 User Management</a></li>
            <li><a href="{{ route('qa.reports') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">📜 Reports</a></li>

        @elseif(auth()->user()->role == 'DCC')
            <li><a href="{{ route('dcc.upload') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">📤 Upload Documents</a></li>
            <li><a href="{{ route('dcc.submissions') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">📁 My Submissions</a></li>

        @elseif(auth()->user()->role == 'Accreditor')
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📂 View Documents</a></li>
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📥 Request Additional Files</a></li>
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📜 Evaluation Reports</a></li>
            <li><a href="{{ route('user.management') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">👤 User Management</a></li>

        @elseif(auth()->user()->role == 'Area Chair')
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📤 Upload Area Documents</a></li>
            <li><a href="{{ route('faculty.management') }}" class="block py-2 hover:bg-blue-100 px-2 rounded">👥 Faculty Management</a></li>

        @elseif(auth()->user()->role == 'Area Member')
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📤 Upload Draft Documents</a></li>
            <li><a href="#" class="block py-2 hover:bg-blue-100 px-2 rounded">📌 Assigned Tasks</a></li>
        @endif
    </ul>

    <hr class="my-4 border-blue-300">

    <h3 class="text-md font-semibold mb-2">Programs</h3>
    <ul>
        @forelse($departments as $department)
            <li>
                <button onclick="toggleSubtopics('{{ $department->id }}')" class="w-full text-left block py-2 hover:bg-blue-100 px-2 rounded">
                    📁 {{ $department->name }}
                </button>

                <ul id="subtopics-{{ $department->id }}" class="hidden ml-4">
                    @forelse($department->subtopics as $subtopic)
                        <li class="flex justify-between items-center">
                            <a href="{{ route('subtopics.show', $subtopic->id) }}" class="block py-1 px-2 text-sm hover:bg-blue-100 rounded">
                                📄 {{ $subtopic->name }}
                            </a>
                            <div class="flex space-x-2">
                                <!-- Edit -->
                                <a href="{{ route('subtopics.edit', $subtopic->id) }}" class="text-blue-400 hover:text-blue-600 text-xs">✏️</a>
                                <!-- Delete -->
                                <form action="{{ route('subtopics.destroy', $subtopic->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">🗑️</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-blue-300 px-2 text-sm">No contents available</li>
                    @endforelse

                    <!-- Add Subtopic -->
                    <form action="{{ route('subtopics.store') }}" method="POST" class="mt-2 px-2">
                        @csrf
                        <input type="hidden" name="department_id" value="{{ $department->id }}">
                        <input type="text" name="subtopic" class="border rounded p-2 w-full text-blue-600 text-sm" placeholder="Add new..." required>
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-xs">➕ Add</button>
                    </form>
                </ul>
            </li>
        @empty
            <li class="text-blue-300 px-2">No departments available</li>
        @endforelse
    </ul>

    <!-- Add Department Button -->
    <a href="{{ route('departments.create') }}" class="block mt-4 bg-blue-500 text-white px-3 py-2 rounded text-center">
        ➕ Add Programs
    </a>
</div>

<!-- JavaScript to Toggle Subtopics -->
<script>
    function toggleSubtopics(departmentId) {
        let subtopicList = document.getElementById("subtopics-" + departmentId);
        subtopicList.classList.toggle("hidden");
    }
</script>
