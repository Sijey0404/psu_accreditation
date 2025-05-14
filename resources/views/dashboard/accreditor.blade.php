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
                    Welcome, <span class="font-semibold text-[{{ $royalBlue }}]">Accreditor</span>! Your role is essential in reviewing and validating submitted documents for quality and compliance.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Accreditation Info -->
                <div class="lg:col-span-2 bg-white shadow-md rounded-2xl p-8 border border-[{{ $royalBlue }}]/10">
                    <h2 class="text-2xl font-semibold text-[{{ $royalBlue }}] mb-4">🏛 About PSU-SCC Accreditation</h2>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        Pangasinan State University – San Carlos Campus (PSU-SCC) engages in continuous quality assurance through 
                        accreditation by AACCUP. This process validates the university's performance in instruction, research, and 
                        community engagement to uphold academic integrity and institutional excellence.
                    </p>
                </div>

                <!-- Role Overview -->
                <div class="bg-[{{ $royalBlue }}]/5 shadow-md rounded-2xl p-6 border border-[{{ $royalBlue }}]/10">
                    <h3 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-3">👤 Accreditor Responsibilities</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Review approved documents from QA officers.</li>
                        <li>Lock and finalize accepted documents.</li>
                        <li>Verify compliance with accreditation indicators.</li>
                        <li>Leave feedback if corrections are needed.</li>
                        <li>Ensure all files meet the required standards.</li>
                    </ul>
                </div>

                <!-- Guidelines -->
                <div class="lg:col-span-3 bg-gradient-to-r from-[{{ $royalBlue }}] to-[{{ $goldenBrown }}] shadow-md rounded-2xl p-8 text-white">
                    <h2 class="text-2xl font-semibold mb-4">📌 Document Review Guidelines</h2>
                    <ul class="space-y-3 list-disc list-inside text-white/90">
                        <li>Only review files that have passed QA approval.</li>
                        <li>Check for completeness, authenticity, and format adherence.</li>
                        <li>Use the "Lock" option to finalize documents.</li>
                        <li>Communicate with QA or Area Chair for any unclear submissions.</li>
                        <li>Maintain impartiality and uphold quality standards throughout the review process.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
