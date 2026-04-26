<x-layouts :title="'Submissions | ' . $activity->name">
<div class="flex-1 bg-gray-50">
    <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
        @include('partials.includes.topnav', ['activity' => $activity ?? null])
    </div>

    <div class="container mx-auto p-4 sm:p-6">
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-[Barlow] text-2xl sm:text-3xl font-bold text-gray-800">Simulation Submissions</h1>
                <p class="font-[Barlow] text-sm sm:text-base text-gray-600">{{ $activity->name }} - {{ $activity->section->class_name }}</p>
            </div>
            <a href="{{ route('faculty/activity/activity_details', [$activity->section_id, $activity->activity_id]) }}"
                class="w-full sm:w-max h-min px-7 py-2 text-sm text-center border border-black rounded hover:bg-gray-100 transition">
                Back to Activity
            </a>
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
                            <th class="px-4 py-3 text-center">Score</th>
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
                                <td class="px-4 py-3 text-center text-sm">
                                    {{ $submission->score ?? 'Not graded' }}
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