<x-layouts :title="($activity->name ?? 'Activity') . ' | ' . ($section->class_name ?? 'Section')">

<div class="flex-1">
    <div class="rounded-xl h-full shadow-sm">

        <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
            @include('partials.includes.topnav')
        </div>

        @php
            $userId = auth()->id();
            $activityId = $activity->activity_id;

            // Load all sessions only once
            $sessions = \App\Models\SimulationSession::where('user_id', $userId)
                ->where('activity_id', $activityId)
                ->get()
                ->groupBy('role_name');

            // Assigned roles
            $totalRoles = $userRoles ? $userRoles->count() : 0;

            // Completed roles
            $completedRoles = $sessions->filter(function($roleSessions) {
                return $roleSessions->contains(fn($s) => in_array($s->status, ['submitted', 'graded']));
            })->count();

            $allCompleted = $totalRoles > 0 && $completedRoles === $totalRoles;

            // Quick lookup for left cards
            $completedSessions = $sessions->keys()->toArray();
        @endphp

        <div class="flex flex-row">

            {{-- LEFT COLUMN --}}
            <div class="flex flex-col flex-1">

                <div class="bg-white flex flex-col md:flex-col rounded-xl items-start px-8 py-6 gap-4 md:gap-0 m-5">
                    <div class="flex flex-col w-full">
                        <div class="flex flex-row">
                            <h2 class="font-[Poppins] text-4xl font-semibold">{{ $activity->name }}</h2>
                            <x-button variant="cancel">
                                <a href="{{ url('student/archived/Activity/' . $section->section_id) }}">
                                    Exit
                                </a>
                            </x-button>

                        </div>
                        <h3 class="font-[Barlow] text-base mt-3 font-medium">
                            {{ $activity->user?->name ?? 'Unknown Faculty' }}
                            <span class="mx-2">•</span>
                            {{ $activity->created_at->timezone('Asia/Manila')->format('M d') }}
                        </h3>
                        <div class="flex justify-between w-full">
                            <span class="font-[Barlow] text-base mt-3 font-medium">{{ $activity->grades }} points</span>
                            <span class="font-[Barlow] text-base mt-5 font-medium">
                                Due Date:
                                @if($activity->due_date)
                                    {{ \Carbon\Carbon::parse($activity->due_date)->format('M d - g:i a') }}
                                @else
                                    No Due Date
                                @endif
                            </span>

                        </div>
                        <div class="flex-1 border-t border-gray-400 mt-5"></div>
                        <div class="my-6">
                            <span class="font-[Barlow] text-base font-medium leading-relaxed">Activity Description: {{ $activity->description }}</span>
                        </div>
                        <div class="flex-1 border-t border-gray-400"></div>
                    </div>
                </div>

                @if($userRoles && $userRoles->count() > 0)

                    <div class="grid grid-cols-2 gap-4 p-5 m-5 max-w-fit">

                        @foreach($userRoles as $i => $role)

                            @php
                                $roleName = strtolower(str_replace([' ', '/'], '', $role->name));
                                $hasSubmission = isset($sessions[$role->name]);

                                $roleData = [
                                    'cashier' => [
                                        'desc' => 'Manages customer payment processing and billing transactions.',
                                        'features' => ['Customer Order', 'POS Receipt Generation']
                                    ],
                                    'manager' => [
                                        'desc' => 'Oversees restaurant operations, performance analytics, and staff monitoring.',
                                        'features' => ['Inventory Overview', 'Staff Management', 'Reports']
                                    ],
                                    'kitchenstaff' => [
                                        'desc' => 'Receives and prepares food orders from waiters in real time.',
                                        'features' => ['Kitchen Queue', 'Order Management']
                                    ],
                                    'waiter' => [
                                        'desc' => 'Manages customer interaction, order creation, and coordination with kitchen staff.',
                                        'features' => ['Table Assignment', 'Order Taking']
                                    ],
                                    'hostfrontdesk' => [
                                        'desc' => 'Handles customer seating and table assignments.',
                                        'features' => ['Table Assignment', 'Order Taking']
                                    ],
                                ];
                            @endphp

                            <x-class-card
                                :activity="$activity"
                                :users="$users"
                                :role="$role->name"
                                :hasSubmission="$hasSubmission"
                                :background="asset('images/backgrounds/' . $roleName . '.png')"
                                :profile="asset('images/profiles/' . $roleName . '.png')"
                                :description="$roleData[$roleName]['desc'] ?? 'No description available.'"
                                :features="$roleData[$roleName]['features'] ?? []"
                                :id="'card-' . $i"
                                data-role="{{ $role->name }}"
                                data-has-submission="{{ $hasSubmission ? 'true' : 'false' }}"
                            />

                        @endforeach

                    </div>

                @else
                    <div class="p-6 text-gray-600 italic">
                        You have no assigned roles for this activity.
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN: YOUR WORK --}}
            <div class="m-5 px-8 py-6">

                <div class="mt-6 w-[411px] bg-white px-8 py-6 rounded-xl shadow-md border">

                    <h3 class="font-[Poppins] text-lg font-semibold mb-4">Grades & Feedback</h3>

                    {{-- IF NO SESSIONS AT ALL --}}
                    @if($gradedSessions->count() === 0 && $submittedSessions->count() === 0)
                        <p class="text-gray-500 text-sm italic">
                            No submissions yet for this activity.
                        </p>
                    @endif


                    {{-- ⭐ GRADED SESSIONS --}}
                    @if($gradedSessions->count() > 0)
                        <h4 class="font-semibold text-green-700 text-base mb-2">Graded</h4>

                        <div class="space-y-4 mb-6">
                            @foreach($gradedSessions as $session)
                                <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-gray-800">
                                            {{ ucfirst($session->role_name) }}
                                        </p>
                                        <p class="font-bold text-green-700">
                                            {{ $session->score }} / 100
                                        </p>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-700">
                                        <p><span class="font-medium">Feedback:</span></p>
                                        <p>{{ $session->feedback }}</p>
                                    </div>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Graded on {{ $session->updated_at->format('F d, Y h:i A') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif


                    {{-- ⭐ SUBMITTED BUT NOT YET GRADED --}}
                    @if($submittedSessions->count() > 0)
                        <h4 class="font-semibold text-yellow-600 text-base mb-2">Awaiting Grading</h4>

                        <div class="space-y-3">
                            @foreach($submittedSessions as $session)
                                <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-gray-800">
                                            {{ ucfirst($session->role_name) }}
                                        </p>

                                        <p class="font-bold text-yellow-600">
                                            Submitted
                                        </p>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-2">
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
                    hostfrontdesk: "host",
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
