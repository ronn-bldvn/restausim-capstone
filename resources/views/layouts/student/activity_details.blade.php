<x-layouts :title="($activity->name ?? 'Activity') . ' | ' . ($section->class_name ?? 'Section')">

<div class="flex-1">
    <div class="rounded-xl h-full shadow-sm">

        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
            @include('partials.includes.topnav')
        </div>

        @php
            $userId = auth()->id();
            $activityId = $activity->activity_id;

            $allRoleSessions = \App\Models\SimulationSession::where('user_id', $userId)
                ->where('activity_id', $activityId)
                ->get();

            $submittedOrGradedByRole = $allRoleSessions
                ->whereIn('status', ['submitted', 'graded'])
                ->groupBy('role_name');

            $inProgressByRole = $allRoleSessions
                ->where('status', 'in_progress')
                ->groupBy('role_name');

            $totalRoles = $userRoles ? $userRoles->count() : 0;

            $completedRoles = $submittedOrGradedByRole->count();

            $allCompleted = $totalRoles > 0 && $completedRoles === $totalRoles;

            $completedSessions = $submittedOrGradedByRole->keys()->toArray();
        @endphp

        <div class="flex flex-col lg:flex-row">

            {{-- LEFT COLUMN --}}
            <div class="flex flex-col flex-1">

                <x-activity-card
                    :activity="$activity"
                    :users="$users"
                    :section="$section"
                    :activityRole="$activityRole"
                />

                @if($activity->role->name)

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3 sm:p-5 m-3 sm:m-5 max-w-full lg:max-w-fit">

                        {{-- @foreach($userRoles as $i => $role) --}}

                            @php
                                $roleName = $activity->role->name;
                                $hasSubmission = in_array($roleName, $completedSessions);
                                $hasInProgress = $inProgressByRole->has($roleName);
                                $i = 1;

                                $roleData = [
                                    'cashier' => [
                                        'desc' => 'Manages customer payment processing and billing transactions.',
                                        'features' => ['Customer Order', 'POS Receipt Generation']
                                    ],
                                    'manager' => [
                                        'desc' => 'Oversees restaurant operations, performance analytics, and staff monitoring.',
                                        'features' => ['Inventory Overview', 'Staff Management', 'Reports']
                                    ],
                                    'kitchen staff' => [
                                        'desc' => 'Receives and prepares food orders from waiters in real time.',
                                        'features' => ['Kitchen Queue', 'Order Management']
                                    ],
                                    'waiter' => [
                                        'desc' => 'Manages customer interaction, order creation, and coordination with kitchen staff.',
                                        'features' => ['Table Assignment', 'Order Taking']
                                    ],
                                    'host' => [
                                        'desc' => 'Handles customer seating and table assignments.',
                                        'features' => ['Table Assignment', 'Reservations']
                                    ],
                                ];
                            @endphp
                            @php
                                $roleImages = [
                                    'cashier' => 'cashier.png',
                                    'manager' => 'manager.png',
                                    'kitchen staff' => 'kitchenstaff.png',
                                    'waiter' => 'waiter.png',
                                    'host' => 'hostfrontdesk.png',
                                ];
                            @endphp
                            <x-class-card
                                :activity="$activity"
                                :users="$users"
                                :role="$roleName"
                                :hasSubmission="$hasSubmission"
                                :hasInProgress="$hasInProgress"
                                :background="asset('images/backgrounds/' . ($roleImages[$roleName] ?? 'default.png'))"
                                :profile="asset('images/profiles/' . ($roleImages[$roleName] ?? 'default.png'))"
                                :description="$roleData[$roleName]['desc'] ?? 'No description available.'"
                                :features="$roleData[$roleName]['features'] ?? []"
                                :id="'card-' . $i"
                            />

                        {{-- @endforeach --}}

                    </div>

                @else
                    <div class="p-4 sm:p-6 text-gray-600 italic text-sm sm:text-base">
                        You have no assigned roles for this activity.
                    </div>
                @endif

                @php
                    $hasOpenSessions = \App\Models\SimulationSession::where('user_id', auth()->id())
                        ->whereNull('submission_id')
                        ->where('status', 'in_progress')
                        ->exists();
                @endphp


            </div>

            @php
                $gradedSessions = \App\Models\SimulationSession::where('user_id', auth()->id())
                    ->where('activity_id', $activity->activity_id)
                    ->where('status', 'graded')
                    ->latest('submitted_at')
                    ->get();

                $submittedSessions = \App\Models\SimulationSession::where('user_id', auth()->id())
                    ->where('activity_id', $activity->activity_id)
                    ->where('status', 'submitted')
                    ->latest('submitted_at')
                    ->get();
            @endphp

            {{-- RIGHT COLUMN: YOUR WORK --}}
            <div class="m-3 sm:m-5 px-4 sm:px-8 py-4 sm:py-6">

                <div class="mt-4 sm:mt-6 w-full lg:w-[411px] bg-white px-4 sm:px-8 py-4 sm:py-6 rounded-xl shadow-md border">

                    <h3 class="font-[Poppins] text-base sm:text-lg font-semibold mb-3 sm:mb-4">Grades & Feedback</h3>

                    {{-- IF NO SESSIONS AT ALL --}}
                    @if($gradedSessions->count() === 0 && $submittedSessions->count() === 0)
                        <p class="text-gray-500 text-xs sm:text-sm italic">
                            No submissions yet for this activity.
                        </p>
                    @endif


                    {{-- ⭐ GRADED SESSIONS --}}
                    @if($gradedSessions->count() > 0)
                        <h4 class="font-semibold text-green-700 text-sm sm:text-base mb-2">Graded</h4>

                        <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6">
                            @foreach($gradedSubmissions as $submission)
                                <div class="p-3 sm:p-4 rounded-lg bg-green-50 border border-green-200">
                                    <div class="flex justify-between items-center">
                                        <p class="font-bold text-green-700 text-sm sm:text-base">
                                            {{ number_format($submission->score, 0) }} / 100
                                        </p>
                                    </div>

                                    <div class="mt-2 text-xs sm:text-sm text-gray-700">
                                        <p class="break-words">{{ $submission->feedback }}</p>
                                    </div>

                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-2">
                                        Graded on {{ $submission->updated_at->format('F d, Y h:i A') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif


                    {{-- ⭐ SUBMITTED BUT NOT YET GRADED --}}
                    @if($submittedSessions->count() > 0)
                        <h4 class="font-semibold text-yellow-600 text-sm sm:text-base mb-2">Awaiting Grading</h4>

                        <div class="space-y-2 sm:space-y-3">
                            @foreach($submittedSessions as $session)
                                <div class="p-3 sm:p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">
                                            {{ ucfirst($session->role_name) }}
                                        </p>

                                        <p class="font-bold text-yellow-600 text-xs sm:text-sm">
                                            Submitted
                                        </p>
                                    </div>

                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-2">
                                        Submitted on {{ $session->submitted_at ? $session->submitted_at->format('F d, Y h:i A') : '—' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>
</div>

</x-layouts>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[id^="card-"]').forEach(card => {

        const uid = card.id.replace("card-", "");
        const modal = document.getElementById("modal-" + uid);
        const startBtn = modal?.querySelector(".start-simulation-btn");

        const hasSubmitted = card.dataset.hasSubmission === "true";
        const role = card.dataset.role;

        if (!modal) return;

        // OPEN modal
        card.onclick = () => modal.classList.remove("hidden");

        // CLOSE modal
        modal.querySelectorAll(".close-btn").forEach(btn =>
            btn.onclick = () => modal.classList.add("hidden")
        );
        modal.onclick = e => {
            if (e.target === modal) modal.classList.add("hidden");
        };

        // If already submitted
        if (hasSubmitted && startBtn) {
            startBtn.disabled = true;
            startBtn.innerHTML = "✓ Already Submitted";
            startBtn.classList.remove("bg-gradient-to-r","from-blue-600","to-blue-700");
            startBtn.classList.add("bg-gray-400","cursor-not-allowed");

            startBtn.addEventListener("click", () => {
                alert("You have already submitted this role simulation.");
            });
            return;
        }

        // START simulation
        startBtn?.addEventListener("click", async () => {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const activityId = {{ $activity->activity_id }};
            const originalText = startBtn.innerHTML;

            startBtn.disabled = true;
            startBtn.innerHTML = "Starting...";

            try {
                const res = await fetch("{{ url('/simulation/start') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        activity_id: activityId,
                        role_name: role
                    })
                });

                const data = await res.json();

                if (data.already_submitted)
                    return alert("You have already submitted this role simulation.");

                if (!data.success)
                    return alert(data.error || "Unable to start simulation.");

                const roleSlug = role.toLowerCase().replace(/[^a-z0-9]/g, '');
                const paths = {
                    cashier: "cashier",
                    kitchenstaff: "kitchen",
                    manager: "manager",
                    waiter: "waiter",
                    host: "host",
                };

                if (!paths[roleSlug]) {
                    alert("Simulation for this role is coming soon!");
                    startBtn.disabled = false;
                    startBtn.innerHTML = originalText;
                    return;
                }

                window.location.href =
                    `{{ url('/') }}/student/simulation/${paths[roleSlug]}/${activityId}?session=${data.session_id}`;

            } catch (e) {
                console.error(e);
                alert("Error starting simulation.");
                startBtn.disabled = false;
                startBtn.innerHTML = originalText;
            }
        });

    });

});
</script>
