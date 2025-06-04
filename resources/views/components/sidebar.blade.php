@props(['departments'])

<?php
$royalBlue = '#1a237e';
$goldenYellow = '#FFD700';
?>

<div class="bg-gradient-to-br from-[{{ $royalBlue }}] to-[{{ $goldenYellow }}] text-white w-64 h-screen max-h-screen flex-shrink-0 overflow-hidden">
    <!-- Logo and Title Section -->
    <div class="p-4 bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenYellow }}] border-b border-white/20">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="PSU Logo" class="h-12 w-auto">
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-white">PSU-SCC</h1>
                <p class="text-sm text-white/90 italic -mt-1">Accreditation Portal</p>
            </div>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="overflow-y-auto h-[calc(100vh-5rem)] p-4">
        <ul class="space-y-1">
            <li><a href="{{ route('dashboard') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                <span class="mr-2">📊</span>
                <span>Dashboard</span>
            </a></li>

        @if(auth()->user()->role === 'QA')
                <li><a href="{{ route('qa.pending') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📂</span>
                    <span>Pending Approvals</span>
                </a></li>
                <li><a href="{{ route('qa.approved') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">✅</span>
                    <span>Approved Documents</span>
                </a></li>
                <li><a href="{{ route('qa.rejected') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">❌</span>
                    <span>Rejected Documents</span>
                </a></li>
                <li><a href="{{ route('user.management') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">👤</span>
                    <span>User Management</span>
                </a></li>
                <li><a href="{{ route('reports.accreditation') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📜</span>
                    <span>Accreditation Report</span>
                </a></li>
                <li><a href="{{ route('documents.view.page') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📂</span>
                    <span>View Documents</span>
                </a></li>
        @endif

        @if(auth()->user()->role === 'Accreditor')
                <li><a href="{{ route('documents.view.page') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📂</span>
                    <span>View Documents</span>
                </a></li>
                <li><a href="{{ route('reports.evaluation') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📜</span>
                    <span>Evaluation Reports</span>
                </a></li>            
                <li><a href="{{ route('user.management') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">👤</span>
                    <span>User Management</span>
                </a></li>
        @endif

        @if(auth()->user()->role === 'Area Chair')
                <li><a href="{{ route('area.upload') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📤</span>
                    <span>Upload Area Documents</span>
                </a></li>            
                <li><a href="{{ route('documents.view.page') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📂</span>
                    <span>View Documents</span>
                </a></li>
                <li><a href="{{ route('my.documents.approved') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">✅</span>
                    <span>Approved Documents</span>
                </a></li>
                <li><a href="{{ route('my.documents.rejected') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">❌</span>
                    <span>Rejected Documents</span>
                </a></li>
                <li><a href="{{ route('faculty.management') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">👤</span>
                    <span>Faculty Management</span>
                </a></li>
        @endif

        @if(auth()->user()->role === 'Area Member')
                <li><a href="{{ route('documents.view.page') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">📂</span>
                    <span>View Documents</span>
                </a></li>
                <li><a href="{{ route('my.documents.approved') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">✅</span>
                    <span>Approved Documents</span>
                </a></li>
                <li><a href="{{ route('my.documents.rejected') }}" class="flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                    <span class="mr-2">❌</span>
                    <span>Rejected Documents</span>
                </a></li>
        @endif
    </ul>

        <hr class="my-4 border-white/20">

        <h3 class="text-md font-semibold mb-2 text-white/90">Programs</h3>
        <ul class="space-y-1">
        @forelse($departments as $department)
            <li>
                    <button onclick="toggleSubtopics('{{ $department->id }}')" class="w-full text-left flex items-center py-2 hover:bg-white/20 px-2 rounded transition-colors duration-150">
                        <span class="mr-2">📁</span>
                        <span>{{ $department->name }}</span>
                </button>

                    <ul id="subtopics-{{ $department->id }}" class="hidden ml-4 space-y-1">
                    @forelse($department->subtopics as $subtopic)
                            <li class="flex justify-between items-center py-1">
                                <a href="{{ route('subtopics.show', $subtopic->id) }}" class="flex-1 flex items-center px-2 text-sm hover:bg-white/20 rounded transition-colors duration-150">
                                    <span class="mr-2">📄</span>
                                    <span>{{ $subtopic->name }}</span>
                            </a>

                            @if(in_array(auth()->user()->role, ['QA', 'Accreditor']))
                                    <div class="flex space-x-2 px-2">
                                        <a href="{{ route('subtopics.edit', $subtopic->id) }}" class="text-white/80 hover:text-white text-xs">✏️</a>
                                    <form action="{{ route('subtopics.destroy', $subtopic->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                            <button type="submit" class="text-white/80 hover:text-white text-xs">🗑️</button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @empty
                            <li class="text-white/60 px-2 text-sm">No contents available</li>
                    @endforelse

                    @if(in_array(auth()->user()->role, ['QA', 'Accreditor']))
                        <form action="{{ route('subtopics.store') }}" method="POST" class="mt-2 px-2">
                            @csrf
                            <input type="hidden" name="department_id" value="{{ $department->id }}">
                                <select name="subtopic" class="w-full p-2 bg-[{{ $royalBlue }}] text-white rounded border border-white/20 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-white [&>option]:bg-[{{ $royalBlue }}] [&>option]:text-white hover:[&>option]:bg-[{{ $royalBlue }}]/80" required>
                                    <option value="">Select Area...</option>
                                    <option value="Area I: Vision, Mission, Goals and Objectives">Area I: Vision, Mission, Goals and Objectives</option>
                                    <option value="AREA II: FACULTY">AREA II: FACULTY</option>
                                    <option value="AREA III: CURRICULUM AND INSTRUCTIONS">AREA III: CURRICULUM AND INSTRUCTIONS</option>
                                    <option value="AREA IV: SUPPORT TO STUDENTS">AREA IV: SUPPORT TO STUDENTS</option>
                                    <option value="AREA V: RESEARCH">AREA V: RESEARCH</option>
                                    <option value="AREA VI: EXTENSION AND COMMUNITY INVOLVEMENT">AREA VI: EXTENSION AND COMMUNITY INVOLVEMENT</option>
                                    <option value="Area VII: Research Agenda and Priorities">Area VII: Research Agenda and Priorities</option>
                                    <option value="Area VIII: Campus and Site">Area VIII: Campus and Site</option>
                                    <option value="Area IX: Laboratory Management and Safety">Area IX: Laboratory Management and Safety</option>
                                    <option value="Area X: Organizational Structure">Area X: Organizational Structure</option>
                                </select>
                                <button type="submit" class="w-full bg-white text-[{{ $royalBlue }}] px-3 py-2 rounded text-xs font-semibold hover:bg-white/90 transition-colors duration-150 flex items-center justify-center gap-1">
                                    <span>➕</span>
                                    <span>Add Area</span>
                                </button>
                        </form>
                    @endif
                </ul>
            </li>
        @empty
                <li class="text-white/60 px-2">No departments available</li>
        @endforelse
    </ul>

    @if(in_array(auth()->user()->role, ['QA', 'Accreditor']))
            <a href="{{ route('departments.create') }}" class="block mt-4 bg-white text-[{{ $royalBlue }}] px-3 py-2 rounded text-center hover:bg-white/90 transition-colors duration-150">
            ➕ Add Programs
        </a>
    @endif
    </div>
</div>

<script>
    function toggleSubtopics(departmentId) {
        let subtopicList = document.getElementById("subtopics-" + departmentId);
        subtopicList.classList.toggle("hidden");
    }
</script>
