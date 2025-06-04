<?php
$royalBlue = '#1a237e';
$goldenYellow = '#FFD700';
?>

<x-app-layout>
    <div class="bg-gradient-to-br from-[{{ $royalBlue }}]/5 to-[{{ $goldenYellow }}]/5 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-[{{ $royalBlue }}] mb-2">🎓 PSU-SCC Accreditation Portal</h1>
                <p class="text-lg text-gray-700">
                    Welcome, <span class="font-semibold text-[{{ $royalBlue }}]">Area Chair</span>! You're entrusted with leading your area's accreditation efforts.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Accreditation Info -->
                <div class="lg:col-span-2 bg-gradient-to-br from-white to-[{{ $goldenYellow }}]/10 shadow-md rounded-2xl p-8 border border-[{{ $royalBlue }}]/10">
                    <h2 class="text-2xl font-semibold text-[{{ $royalBlue }}] mb-4">🏛 About PSU-SCC Accreditation</h2>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        Pangasinan State University – San Carlos Campus (PSU-SCC) is committed to excellence in education and institutional quality. 
                        Through AACCUP accreditation, our university undergoes rigorous assessment in areas such as instruction, research, 
                        and community involvement, ensuring compliance with national academic standards.
                    </p>
                </div>

                <!-- Quick Role Overview -->
                <div class="bg-[{{ $royalBlue }}]/5 shadow-md rounded-2xl p-6 border border-[{{ $royalBlue }}]/10">
                    <h3 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-3">👤 Area Chair Responsibilities</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Lead document preparation for your assigned area.</li>
                        <li>Coordinate with Area Members and QA Officers.</li>
                        <li>Review documents before submission.</li>
                        <li>Ensure timely completion of tasks.</li>
                        <li>Maintain quality and completeness of submissions.</li>
                    </ul>
                </div>

                <!-- Guidelines -->
                <div class="lg:col-span-3 bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenYellow }}] shadow-md rounded-2xl p-8 text-white">
                    <h2 class="text-2xl font-semibold mb-4">📌 Submission Guidelines</h2>
                    <ul class="space-y-3 list-disc list-inside text-white/90">
                        <li>Verify documents submitted by Area Members for compliance and completeness.</li>
                        <li>Use the provided document templates and follow formatting standards.</li>
                        <li>Maintain communication with QA for feedback and revisions.</li>
                        <li>Organize files by subtopics and upload only relevant evidence.</li>
                        <li>Encourage members to meet internal deadlines to allow QA review time.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
