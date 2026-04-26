<x-layouts :title="'Faculty Dashboard | RestauSim'">
    <x-alert type="success" :message="session('success')" />

    <div class="flex-1 bg-slate-50 min-h-screen p-6 lg:p-10 overflow-y-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="font-[Barlow] text-3xl font-bold text-slate-900 tracking-tight">
                    Welcome back, {{ Auth::user()->name }}!</span>
                </h1>
                <p class="text-slate-500 mt-1 text-base">Here's a summary of the restaurant simulation performance today.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 my-8 px-4">

            <!-- Total Courses -->
            <button type="button"
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 text-left"
                data-modal="modal-sections">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Total Section</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalSections }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </button>

            <!-- Total Students -->
            <button type="button"
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 text-left"
                data-modal="modal-students">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Total Students</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalStudents }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </button>
            {{-- <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Total Students</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalStudents }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div> --}}

            <!-- Total Activities -->
            <button type="button"
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 text-left"
                data-modal="modal-activities">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Total Activities</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalActivities }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
            </button>
            {{-- <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Total Activities</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalActivities }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
            </div> --}}

            <!-- Graded Activities -->
            <button type="button"
                class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 text-left"
                data-modal="modal-graded">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Graded Activities</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $gradedCount ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </button>
            {{-- <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-gray-500 text-sm font-medium mb-3">Graded Activities</h2>
                        <p class="text-3xl font-bold text-slate-900 mb-2">{{ $gradedCount ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-indigo-50 p-3.5 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div> --}}

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900">Your Sections</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($sections as $section)
                            <a href="{{ url('faculty/activity/' . $section->section_id) }}" class="group bg-white border border-slate-200 p-5 rounded-2xl transition-all hover:border-[#EA7C69] hover:shadow-lg">
                                <h3 class="font-bold text-slate-900 group-hover:text-[#EA7C69]">{{ $section->class_name }}</h3>
                                <p class="text-slate-500 text-sm mt-1">{{ $section->section_name }}</p>
                            </a>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="{{ url('faculty/section') }}" class="text-sm font-semibold text-[#EA7C69]">View All &rarr;</a>
                    </div>
                </section>

                <section id="activities-wrapper">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Activities</h2>
                    @include('partials.ajax.activity')
                </section>
            </div>

            <div class="space-y-8">
            
                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Recent Activities</h2>
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($recentActivities as $index => $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index !== count($recentActivities) - 1)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-100"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-slate-900 font-medium">{{ $activity->description }}</p>
                                                    <p class="text-xs text-slate-400 mt-1 tracking-widest font-bold">{{ $activity->action }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

{{-- KPI MODALS --}}

{{-- Sections Modal --}}
<div id="modal-sections" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Sections</h3>
                <p class="text-xs text-slate-500">List of your sections</p>
            </div>
            <button type="button" class="kpi-close rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                Close
            </button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
            @if(($sections ?? collect())->count())
                <ul class="divide-y divide-slate-100">
                    @foreach($sections as $section)
                        <li class="py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $section->class_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $section->section_name }}</p>
                                </div>
                                <a class="text-sm font-semibold text-[#EA7C69] hover:underline"
                                   href="{{ url('faculty/activity/' . $section->section_id) }}">
                                    Open →
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">No sections found.</p>
            @endif
        </div>
    </div>
</div>

{{-- Students Modal --}}
<div id="modal-students" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Students</h3>
                <p class="text-xs text-slate-500">List of students</p>
            </div>
            <button type="button" class="kpi-close rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                Close
            </button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
            @if(($students ?? collect())->count())
                <ul class="divide-y divide-slate-100">
                    @foreach($students as $student)
                        <li class="py-3">
                            <p class="font-semibold text-slate-900">{{ $student->user->name ?? '—' }}</p>
                            <p class="text-sm text-slate-500">{{ $student->user->email ?? '—' }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">No students found.</p>
            @endif
        </div>
    </div>
</div>

{{-- Activities Modal --}}
<div id="modal-activities" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Activities</h3>
                <p class="text-xs text-slate-500">List of activities</p>
            </div>
            <button type="button" class="kpi-close rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                Close
            </button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
            @if(($activities ?? collect())->count())
                <ul class="divide-y divide-slate-100">
                    @foreach($activities as $a)
                        <li class="py-3">
                            <p class="font-semibold text-slate-900">{{ $a->name ?? $a->title ?? 'Activity' }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">No activities found.</p>
            @endif
        </div>
    </div>
</div>

{{-- Graded Modal --}}
<div id="modal-graded" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Graded Activities</h3>
                <p class="text-xs text-slate-500">List of graded submissions</p>
            </div>
            <button type="button" class="kpi-close rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                Close
            </button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
            @if(($gradedActivities ?? collect())->count())
                <ul class="divide-y divide-slate-100">
                    @foreach($gradedActivities as $g)
                        @php
                            $activityNames = $g->sessions
                                ->pluck('activity.name')
                                ->filter()
                                ->unique()
                                ->implode(', ');
                        @endphp
        
                        <li class="py-3">
                            <p class="font-semibold text-slate-900">
                                {{ $activityNames ?: '—' }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Student: {{ $g->user->name ?? '—' }}
                                @if(!is_null($g->score))
                                    • Score: {{ $g->score }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500">No graded activities found.</p>
            @endif
        </div>
    </div>
</div>

</x-layouts>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        // OPEN MODAL (click KPI)
        $(document).on('click', '[data-modal]', function () {
            const id = $(this).data('modal');
            $('#' + id).removeClass('hidden').addClass('flex');
        });

        // CLOSE MODAL (button)
        $(document).on('click', '.kpi-close', function () {
            $(this).closest('.fixed').addClass('hidden').removeClass('flex');
        });

        // CLOSE MODAL (click outside panel)
        $(document).on('click', '.fixed', function (e) {
            if (e.target === this) {
                $(this).addClass('hidden').removeClass('flex');
            }
        });

        // CLOSE MODAL (ESC)
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                $('[id^="modal-"]').addClass('hidden').removeClass('flex');
            }
        });

    });

    $(document).ready(function() {
        $(document).on('click', '.ajax-pagination a', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            // Add a loading state (optional)
            $('#activities-wrapper').css('opacity', '0.5');

            $.ajax({
                url: url,
                success: function(data) {
                    // Replace the inner content of the wrapper with the new partial
                    $('#activities-wrapper').html('<h2 class="text-xl font-bold text-slate-900 mb-4">Activities</h2>' + data);
                    $('#activities-wrapper').css('opacity', '1');

                    // Smooth scroll back to section header (optional)
                    $('html, body').animate({
                        scrollTop: $("#activities-wrapper").offset().top - 100
                    }, 500);
                },
                error: function() {
                    alert('Activities could not be loaded.');
                    $('#activities-wrapper').css('opacity', '1');
                }
            });
        });
    });
</script>
