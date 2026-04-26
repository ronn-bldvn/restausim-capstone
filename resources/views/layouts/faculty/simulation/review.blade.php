<x-layouts :title="'Review Submission | ' . $submission->user->name">
    @php
        $summary = $submission->summary ?? [];
        $minutes = (int) ($summary['duration_minutes'] ?? 0);
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $groupedActions = $submission->actions->groupBy(function ($action) {
            $roleName = data_get($action->action_data, 'role_name', 'Unknown Role');
            return $roleName . '||' . $action->session_id;
        });

        $displayTotalActions = $summary['total_actions'] ?? $submission->actions->count();
        $displayTotalOrders = $summary['total_orders'] ?? 0;
        $displayTotalRevenue = $summary['total_revenue'] ?? 0;
        $displayTotalSessions = $summary['total_sessions'] ?? $submission->sessions->count();

        $profileImage = $submission->user?->profile_image
            ? asset('storage/profile_images/' . $submission->user->profile_image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($submission->user->name ?? 'User') . '&background=E5E7EB&color=374151';
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-orange-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

            {{-- Page Header --}}
            <div class="mb-5 sm:mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-indigo-200">
                            Submission Review
                        </span>
                        @if($submission->status === 'graded')
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                Graded
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                Pending
                            </span>
                        @endif
                    </div>

                    <h1 class="font-[Barlow] text-xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-slate-900 leading-tight">
                        Review Submission
                    </h1>
                    <p class="mt-1 text-sm sm:text-base text-slate-600">
                        Evaluate the performance, actions, and session activity of
                        <span class="font-semibold text-slate-800">{{ $submission->user->name }}</span>.
                    </p>
                </div>

                <a href="{{ url()->previous() }}"
                    class="self-start shrink-0 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 sm:px-5 sm:py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Back
                </a>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 items-start min-w-0 overflow-hidden">

                {{-- Left/Main Content --}}
                <div class="lg:col-span-2 space-y-5 sm:space-y-6 min-w-0 w-full">

                    {{-- Student Profile Card --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="h-1.5 sm:h-2 bg-gradient-to-r from-indigo-500 via-orange-400 to-rose-400"></div>

                        <div class="p-4 sm:p-5 lg:p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="shrink-0">
                                    <img src="{{ $profileImage }}"
                                        alt="{{ $submission->user->name }}"
                                        class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl object-cover ring-4 ring-slate-100 shadow-sm">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 truncate">
                                                {{ $submission->user->name }}
                                            </h2>
                                            <p class="text-xs sm:text-sm text-slate-500 break-all">
                                                {{ $submission->user->email }}
                                            </p>

                                            <div class="mt-2 sm:mt-3 flex flex-wrap gap-1.5 sm:gap-2">
                                                @forelse($summary['roles'] ?? [] as $role)
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 sm:px-3 sm:py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                                                        {{ ucfirst($role) }}
                                                    </span>
                                                @empty
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                                                        No roles found
                                                    </span>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="shrink-0 rounded-xl bg-slate-50 px-3 py-2.5 sm:px-4 sm:py-3 ring-1 ring-slate-200 sm:text-right self-start">
                                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                                                Submitted At
                                            </p>
                                            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-semibold text-slate-800 whitespace-nowrap">
                                                {{ optional($submission->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Stats --}}
                    <div class="grid grid-cols-3 gap-3 sm:gap-4">
                        {{-- Passed Activities --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-slate-500 leading-tight">Passed Activities</p>
                                    <h3 class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-bold text-slate-900">{{ $displayTotalSessions }}</h3>
                                </div>
                                <div class="rounded-xl bg-indigo-50 p-2 sm:p-2.5 text-indigo-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Orders --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-slate-500 leading-tight">Total Orders</p>
                                    <h3 class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-bold text-emerald-600">{{ $displayTotalOrders }}</h3>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-2 sm:p-2.5 text-emerald-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 4.5h16.5v3H3.75v-3zm1.5 4.5h13.5l-1.125 9.75H6.375L5.25 9zm4.5 3h4.5" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Total Actions --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-slate-500 leading-tight">Total Actions</p>
                                    <h3 class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-bold text-amber-600">{{ $displayTotalActions }}</h3>
                                </div>
                                <div class="rounded-xl bg-amber-50 p-2 sm:p-2.5 text-amber-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Included Activities --}}
                    @if($submission->sessions->count() > 0)
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 px-4 py-3 sm:px-6 sm:py-4">
                                <div>
                                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900">Included Activities</h2>
                                    <p class="text-xs sm:text-sm text-slate-500">Sessions included in this submission.</p>
                                </div>
                                <span class="self-start sm:self-auto inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200 shrink-0">
                                    {{ $submission->sessions->count() }} activity(s)
                                </span>
                            </div>

                            <div class="p-4 sm:p-5 lg:p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    @foreach($submission->sessions as $session)
                                        <div class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-3 sm:p-4 transition hover:border-orange-200 hover:bg-orange-50/40 hover:shadow-sm">
                                            <div class="flex items-start justify-between gap-2">
                                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">
                                                    {{ ucfirst($session->role_name) }}
                                                </h3>
                                                <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-0.5 sm:px-3 sm:py-1 text-xs font-medium ring-1
                                                    {{ $session->status === 'submitted'
                                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                                                        : 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                                                    {{ ucfirst($session->status) }}
                                                </span>
                                            </div>

                                            <div class="mt-3 grid grid-cols-2 gap-2 sm:gap-3">
                                                <div class="rounded-xl bg-white p-2.5 sm:p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Started</p>
                                                    <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-medium text-slate-700">
                                                        {{ optional($session->started_at)->format('M d, Y h:i A') ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="rounded-xl bg-white p-2.5 sm:p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Submitted</p>
                                                    <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm font-medium text-slate-700">
                                                        {{ optional($session->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right/Grade Panel --}}
                <div class="lg:col-span-1 order-first lg:order-none w-full min-w-0">
                    <div class="lg:sticky lg:top-6 space-y-5 sm:space-y-6">

                        {{-- Grade Form --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden w-full">
                            <div class="bg-gradient-to-r from-orange-100 via-amber-50 to-rose-100 px-4 py-3 sm:px-5 sm:py-4 border-b border-slate-200">
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Grade Submission</h2>
                                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">
                                    Assign a score and provide clear feedback for the student.
                                </p>
                            </div>

                            <div class="p-4 sm:p-5 lg:p-6">
                                <form action="{{ route('faculty.simulation.grade', $submission->id) }}" method="POST" class="space-y-4 sm:space-y-5 w-full min-w-0">
                                    @csrf

                                    <div class="w-full min-w-0">
                                        <label class="mb-1.5 sm:mb-2 block text-sm font-semibold text-slate-700">
                                            Score <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="score"
                                            value="{{ old('score', $submission->score) }}"
                                            required
                                            class="block w-full box-border rounded-xl border bg-white px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-300 @error('score') border-red-500 @else border-slate-300 @enderror"
                                            placeholder="Enter score"
                                        >
                                        @error('score')
                                            <p class="mt-1.5 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="w-full min-w-0">
                                        <label class="mb-1.5 sm:mb-2 block text-sm font-semibold text-slate-700">Feedback</label>
                                        <textarea
                                            name="feedback"
                                            rows="5"
                                            class="block w-full box-border resize-y rounded-xl border bg-white px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-300 @error('feedback') border-red-500 @else border-slate-300 @enderror"
                                            placeholder="Write feedback for the student..."
                                        >{{ old('feedback', $submission->feedback) }}</textarea>
                                        @error('feedback')
                                            <p class="mt-1.5 text-xs sm:text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button
                                        type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-amber-200 via-orange-300 to-rose-300 px-4 py-2.5 sm:py-3 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-slate-300 transition hover:scale-[1.01] hover:shadow-md active:scale-[0.99]"
                                    >
                                        Return Grade
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Actions Table — full width below the grid --}}
            <div class="rounded-2xl mt-4 sm:mt-6 border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-4 py-3 sm:px-6 sm:py-4">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900">Actions Done in the Activities</h2>
                    <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">
                        Review the recorded student activity and interactions in each session.
                    </p>
                </div>

                {{-- Horizontal scroll wrapper for the table on small screens --}}
                <div id="actions-container" class="w-full p-3 sm:p-4 lg:p-6">
                    <div class="overflow-x-auto -mx-1 px-1">
                        @include('partials.ajax.actions-table', ['actions' => $actions])
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).on('click', '#actions-container .pagination a', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $('#actions-container').html(response);
                    $('html, body').animate({
                        scrollTop: $('#actions-container').offset().top - 120
                    }, 300);
                },
                error: function() {
                    alert('Failed to load page');
                }
            });
        });
    </script>
</x-layouts>
