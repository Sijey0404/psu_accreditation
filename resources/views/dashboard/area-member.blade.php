<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <div class="bg-gradient-to-br from-[{{ $royalBlue }}]/5 to-white min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-[{{ $royalBlue }}] mb-2">🎓 PSU-SCC Accreditation Portal</h1>
                <p class="text-lg text-gray-700">
                    Welcome, <span class="font-semibold text-[{{ $royalBlue }}]">Area Member</span>! You play an important role in preparing and submitting draft documents for your assigned area.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- About Accreditation -->
                <div class="lg:col-span-2 bg-white shadow-md rounded-2xl p-8 border border-[{{ $royalBlue }}]/10">
                    <h2 class="text-2xl font-semibold text-[{{ $royalBlue }}] mb-4">🏛 About PSU-SCC Accreditation</h2>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        Pangasinan State University – San Carlos Campus (PSU-SCC) strives to maintain high academic standards 
                        and institutional excellence. AACCUP accreditation helps ensure that our educational practices align 
                        with national and international benchmarks.
                    </p>
                </div>

                <!-- Role Overview -->
                <div class="bg-[{{ $royalBlue }}]/5 shadow-md rounded-2xl p-6 border border-[{{ $royalBlue }}]/10">
                    <h3 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-3">👤 Area Member Duties</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Assist in gathering required documents.</li>
                        <li>Upload draft files to assigned subtopics.</li>
                        <li>Collaborate with the Area Chair for improvements.</li>
                        <li>Monitor feedback and update documents as needed.</li>
                        <li>Ensure clarity and accuracy in all submissions.</li>
                    </ul>
                </div>

                <!-- Guidelines -->
                <div class="lg:col-span-3 bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenBrown }}] shadow-md rounded-2xl p-8 text-white">
                    <h2 class="text-2xl font-semibold mb-4">📌 Upload Guidelines</h2>
                    <ul class="space-y-3 list-disc list-inside text-white/90">
                        <li>Upload only relevant, complete, and properly formatted documents.</li>
                        <li>Use the platform's document uploader within your assigned subtopic.</li>
                        <li>Coordinate with the Area Chair for approvals and clarifications.</li>
                        <li>Keep file names organized by category or indicator.</li>
                        <li>Check status of uploads (e.g., pending, approved, or needs revision).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
