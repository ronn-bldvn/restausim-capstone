@php

$role = auth()->user()->role;

$studentsLink = url(
    'faculty/student/' .
    $section->section_id
);

@endphp


<x-layouts title="Submissions">
<div class="flex-1 bg-gray-50">
    
    <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                <!-- Activities -->
                <a href="{{ auth()->user()->role === 'student'
                        ? url('student/activity/' . $section->section_id)
                        : url('faculty/activity/' . $section->section_id) }}"
                        class="{{ request()->is('faculty/activity*') || request()->is('student/activity*')
                            ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                            : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Activities
                </a>

                <!-- Announcements -->
                <a href="{{ url('faculty/announcements/' . $section->section_id) }}"
                    class="{{ request()->is('faculty/announcements*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Announcements
                </a>

                @if ($role === 'faculty' && isset($section) && $section->is_archived && (!isset($activity) || !$activity->activity_id))
                    {{-- Disabled state when no activity is available --}}
                    <span class="text-gray-400 font-medium px-4 py-4 cursor-not-allowed" title="Select an activity to view students">
                        Students
                    </span>
                @else
                    <a href="{{ $studentsLink }}"
                    class="{{ request()->is('faculty/student*')
                        || request()->is('student/student*')
                        || request()->is('faculty/archived/student/*')
                        || request()->is('student/archived/student/*')
                                ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                                : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Students
                    </a>
                @endif
                
                <a href="{{ route('faculty.simulation.all', $section->section_id) }}"
                   class="{{ request()->is('faculty/simulation/all-submissions*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Student Works
                </a>

            </div>

    <div class="container mx-auto p-4 sm:p-6">
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-[Barlow] text-2xl sm:text-3xl font-bold text-gray-800">Simulation Submissions</h1>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Total Submissions</div>
                <div class="text-xl sm:text-3xl font-bold text-[#0D0D54]">{{ $submissions->count() }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Graded</div>
                <div class="text-xl sm:text-3xl font-bold text-[#4CAF50]">
                    {{ $submissions->where('status', 'graded')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Pending</div>
                <div class="text-xl sm:text-3xl font-bold text-yellow-600">
                    {{ $submissions->where('status', 'submitted')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Average Score</div>
                <div class="text-xl sm:text-3xl font-bold text-[#EA7C69]">
                    {{ $submissions->where('status', 'graded')->avg('score') ? number_format($submissions->where('status', 'graded')->avg('score'), 2) : 'N/A' }}
                </div>
            </div>
        </div>

        <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-center">#</th>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-center">Roles</th>
                            <th class="px-4 py-3 text-center">Submitted</th>
                            <th class="px-4 py-3 text-center">Orders</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $index => $submission)
                            <tr class="border-t hover:bg-gray-50 submission-row"
                                data-status="{{ $submission->status }}"
                                data-role="{{ strtolower($submission->role_name) }}"
                                data-name="{{ strtolower($submission->user->name) }}">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/profile_images/' . $submission->user?->profile_image) }}"
                                            alt="{{ $submission->user->name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $submission->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $submission->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">{{ $submission->role_name }}</td>
                                <td class="px-4 py-3 text-center text-sm">
                                    {{ optional($submission->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-semibold">
                                    {{ $submission->summary['total_orders'] ?? 0 }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-semibold">
                                    {{ $submission->summary['total_actions'] ?? $submission->actions->count() }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($submission->status === 'graded')
                                        <span class="bg-[#E9F8EC] text-[#4CAF50] px-2 py-1 rounded-full text-xs font-medium">Graded</span>
                                    @else
                                        <span class="bg-[#FFF7DD] text-[#E3B341] px-2 py-1 rounded-full text-xs font-medium">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('faculty.simulation.review', $submission->id) }}"
                                        class="inline-block px-4 py-2 text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">No submissions yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layouts>