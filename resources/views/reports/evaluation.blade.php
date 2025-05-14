<?php
$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            📜 Evaluation Reports
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[{{ $royalBlue }}]">Evaluation Reports</h1>
                <p class="text-gray-600">Create and manage area evaluation reports</p>
            </div>

            <!-- Area Selection and Report Creation -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form action="{{ route('reports.evaluation.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Area Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Area</label>
                            <select name="area" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" required>
                                <option value="">Choose an area...</option>
                                <option value="Area I">Area I: Vision, Mission, Goals and Objectives</option>
                                <option value="Area II">Area II: Faculty</option>
                                <option value="Area III">Area III: Curriculum and Instructions</option>
                                <option value="Area IV">Area IV: Support to Students</option>
                                <option value="Area V">Area V: Research</option>
                                <option value="Area VI">Area VI: Extension and Community Involvement</option>
                                <option value="Area VII">Area VII: Research Agenda and Priorities</option>
                                <option value="Area VIII">Area VIII: Campus and Site</option>
                                <option value="Area IX">Area IX: Laboratory Management and Safety</option>
                                <option value="Area X">Area X: Organizational Structure</option>
                            </select>
                        </div>

                        <!-- Program Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Program</label>
                            <select name="program" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" required>
                                <option value="">Choose a program...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Evaluation Content -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
                            <textarea name="strengths" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" placeholder="List the strengths of this area..." required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Areas for Improvement</label>
                            <textarea name="improvements" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" placeholder="List areas that need improvement..." required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Recommendations</label>
                            <textarea name="recommendations" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" placeholder="Provide recommendations for improvement..." required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <select name="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[{{ $royalBlue }}] focus:border-[{{ $royalBlue }}]" required>
                                <option value="">Select a rating...</option>
                                <option value="5">5 - Outstanding</option>
                                <option value="4">4 - Very Good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-[{{ $royalBlue }}] text-white px-6 py-2 rounded-lg hover:bg-[{{ $royalBlue }}]/90 transition-colors duration-200 flex items-center gap-2">
                            <span>Save Evaluation</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Previous Evaluations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-[{{ $royalBlue }}] mb-4">Previous Evaluations</h2>
                
                @if(isset($evaluations) && count($evaluations) > 0)
                    <div class="space-y-4">
                        @foreach($evaluations as $evaluation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-[{{ $royalBlue }}] transition-colors duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-[{{ $royalBlue }}]">{{ $evaluation->area }}</h3>
                                        <p class="text-sm text-gray-600">{{ $evaluation->program }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[{{ $goldenBrown }}] text-white">
                                        Rating: {{ $evaluation->rating }}
                                    </span>
                                </div>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="font-medium text-gray-700">Strengths</p>
                                        <p class="text-gray-600">{{ $evaluation->strengths }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Areas for Improvement</p>
                                        <p class="text-gray-600">{{ $evaluation->improvements }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Recommendations</p>
                                        <p class="text-gray-600">{{ $evaluation->recommendations }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button class="text-[{{ $royalBlue }}] hover:text-[{{ $royalBlue }}]/80 text-sm">Edit</button>
                                    <button class="text-red-600 hover:text-red-700 text-sm">Delete</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-4">No evaluations found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
