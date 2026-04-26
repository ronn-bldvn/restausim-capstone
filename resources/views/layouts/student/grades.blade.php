<x-layouts title="Grades">

<div class="flex-1 font-[Barlow] overflow-y-auto">
    <div class="p-6 md:p-14 w-full h-full">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="font-[Barlow] text-4xl font-bold text-black mb-2">Activity Scores</h1>
                <p class="text-black">Track your academic performance across all activities</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl p-5 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Total Activities</p>
                            <p class="text-2xl font-bold text-black mt-1">{{ $totalActivities }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Graded</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['graded'] ?? 0 }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Pending</p>
                            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                        <div class="bg-amber-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Submitted</p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['submitted'] ?? 0 }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <x-table :headers="['Activity Name', 'Role', 'Submitted', 'Status', 'Score']">
                        @forelse ($activities as $activity)
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="text-black">{{ $activity->name ?? 'No Activity' }}</span>
                                </td>
                    
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-black">
                                        {{ $activity->role_names ?: '—' }}
                                    </span>
                                </td>
                    
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $activity->submitted_at_display ? $activity->submitted_at_display->format('M d, Y') : 'Not submitted' }}
                                </td>
                    
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'graded' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Graded'],
                                            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Pending'],
                                            'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Submitted'],
                                            'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'In Progress'],
                                            'not_started' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Not Started'],
                                        ];
                    
                                        $config = $statusConfig[$activity->grade_status] ?? [
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-700',
                                            'label' => ucfirst(str_replace('_', ' ', $activity->grade_status)),
                                        ];
                                    @endphp
                    
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                    
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity->grade_score !== null)
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-bold {{ $activity->grade_score >= 90 ? 'text-green-600' : ($activity->grade_score >= 75 ? 'text-blue-600' : 'text-amber-600') }}">
                                                {{ $activity->grade_score }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-black">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-black font-medium text-lg">No activities found</p>
                                        <p class="text-black text-sm mt-1">Your activity scores will appear here once available</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                    
                    <div class="mt-4 flex justify-center space-x-2 ajax-pagination">
                        @if ($activities->onFirstPage())
                            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $activities->previousPageUrl() }}"
                               class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">
                                Previous
                            </a>
                        @endif
                    
                        <span class="px-5 py-1 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] rounded text-sm">
                            Page {{ $activities->currentPage() }} of {{ $activities->lastPage() }}
                        </span>
                    
                        @if ($activities->hasMorePages())
                            <a href="{{ $activities->nextPageUrl() }}"
                               class="px-3 py-1 bg-gray-200 text-black rounded text-sm hover:bg-gray-300">
                                Next
                            </a>
                        @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded text-sm cursor-not-allowed">Next</span>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts>
