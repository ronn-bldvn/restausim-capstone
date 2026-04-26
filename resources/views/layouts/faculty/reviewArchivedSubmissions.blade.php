<x-layouts :title="'Review Simulation - ' . $session->user->name">
    <div class="flex-1 bg-gray-50">
        <div class="container mx-auto p-6">
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Simulation Review</h1>
                    <p class="text-gray-600">{{ $session->activity->name }} - {{ $session->role_name }}</p>
                </div>
                <a href="{{ route('faculty.simulation.all', $session->activity_id) }}"
                    class="w-max h-min px-5 py-2 text-sm border border-black rounded hover:bg-gray-100 transition">
                    Back to Student Submissions
                </a>
            </div>

            {{-- Student Info Card --}}
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/profile_images/' . $session->user?->profile_image) }}"
                            alt="{{ $session->user->name }}" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h2 class="text-2xl font-semibold">{{ $session->user->name }}</h2>
                            <p class="text-gray-600">{{ $session->user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div
                            class="flex items-center justify-center px-3 py-1 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-sm text-[#0D0D54] rounded-full ">
                            Submitted</div>
                        <div class="font-semibold my-3">
                            {{ $session->submitted_at ? $session->submitted_at->format('M d \a\t g:i A') : 'Not submitted' }}
                        </div>
                        <div class="mt-2">
                            @if($session->status === 'graded')
                                <span class="bg-[#E9F8EC] text-[#4CAF50] px-3 py-1 rounded-full text-sm font-medium">
                                    Graded
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Pending Review
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                {{-- Performance Metrics --}}
                <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span><i class="fa-solid fa-chart-area mr-2"></i></span> Performance Metrics
                    </h3>

                    @if($session->session_data)
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($session->session_data as $key => $value)
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <div class="text-sm text-[#EA7C69] mb-1">
                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                    </div>
                                    <div class="text-2xl font-bold text-[#0D0D54]">
                                        @if(is_array($value))
                                            <ul class="list-disc pl-5 text-sm">
                                                @foreach($value as $subKey => $subValue)
                                                    <li>
                                                        {{ is_string($subKey) ? ucwords(str_replace('_', ' ', $subKey)) : 'Item ' . ($loop->index + 1) }}:
                                                        {{ is_array($subValue) ? json_encode($subValue) : $subValue }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif(is_numeric($value))
                                            @if(str_contains($key, 'revenue') || str_contains($key, 'value'))
                                                ₱{{ number_format($value, 2) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        @elseif(is_bool($value))
                                            {{ $value ? 'Yes' : 'No' }}
                                        @elseif(is_null($value))
                                            <span class="text-gray-400 text-sm">N/A</span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No metrics available</p>
                    @endif
                </div>

                {{-- Action Summary --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span><i class="fa-solid fa-chart-line mr-2"></i></span> Action Summary
                    </h3>

                    <div class="space-y-3">
                        @php
                            $actionTypes = $session->actions->groupBy('action_type');
                        @endphp

                        @forelse($actionTypes as $type => $actions)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </span>
                                <span
                                    class="bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-[#0D0D54] px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $actions->count() }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No actions recorded</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Action Timeline --}}
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    <span><i class="fa-solid fa-timeline mr-2"></i></span> Action Timeline
                </h3>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($session->actions->sortBy('timestamp') as $action)
                        <div class="border-l-4 border-[#EA7C69] pl-4 py-3 bg-gray-50 rounded-r">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-gray-800">
                                    {{ ucwords(str_replace('_', ' ', $action->action_type)) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($action->timestamp)->setTimezone('Asia/Manila')->format('g:i:s A') }}
                                </span>
                            </div>

                            <div class="bg-white p-3 rounded text-sm text-[#4A4E8A] overflow-x-auto">
                                @if(is_array($action->action_data))
                                    <div class="space-y-3">
                                        @foreach($action->action_data as $k => $v)
                                            <div>
                                                <div class="font-semibold text-[#0D0D54] mb-1">
                                                    {{ is_string($k) ? ucwords(str_replace('_', ' ', $k)) : 'Item ' . ($loop->index + 1) }}:
                                                </div>
                                                <div class="ml-4">
                                                    @if(is_array($v))
                                                        {{-- Check if it's a list of order items --}}
                                                        @if(array_is_list($v) && count($v) > 0 && is_array($v[0] ?? null))
                                                            <div class="space-y-2">
                                                                @foreach($v as $index => $item)
                                                                    <div class="bg-gray-50 p-2 rounded border border-gray-200">
                                                                        <div class="font-medium text-gray-600 mb-1">{{ $index + 1 }}.</div>
                                                                        @if(is_array($item))
                                                                            <div class="ml-3 space-y-1">
                                                                                @foreach($item as $itemKey => $itemValue)
                                                                                    <div class="flex justify-between text-xs">
                                                                                        <span
                                                                                            class="text-gray-600">{{ ucwords(str_replace('_', ' ', $itemKey)) }}:</span>
                                                                                        <span class="font-medium text-[#EA7C69]">
                                                                                            @if(str_contains(strtolower($itemKey), 'price') || str_contains(strtolower($itemKey), 'amount') || str_contains(strtolower($itemKey), 'total') || str_contains(strtolower($itemKey), 'cost'))
                                                                                                ₱{{ number_format($itemValue, 2) }}
                                                                                            @else
                                                                                                {{ $itemValue }}
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            {{ $item }}
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            {{-- Regular nested array --}}
                                                            <div class="space-y-1">
                                                                @foreach($v as $nestedKey => $nestedValue)
                                                                    <div class="flex justify-between">
                                                                        <span
                                                                            class="text-[#EA7C69] text-xs">{{ is_string($nestedKey) ? ucwords(str_replace('_', ' ', $nestedKey)) : ($nestedKey + 1) }}:</span>
                                                                        <span class="font-medium text-[#EA7C69] text-xs">
                                                                            @if(is_array($nestedValue))
                                                                                {{ json_encode($nestedValue) }}
                                                                            @elseif(is_numeric($nestedValue) && (str_contains(strtolower((string) $nestedKey), 'price') || str_contains(strtolower((string) $nestedKey), 'amount') || str_contains(strtolower((string) $nestedKey), 'total') || str_contains(strtolower((string) $nestedKey), 'cost')))
                                                                                ₱{{ number_format($nestedValue, 2) }}
                                                                            @elseif(is_bool($nestedValue))
                                                                                {{ $nestedValue ? 'Yes' : 'No' }}
                                                                            @elseif(is_null($nestedValue))
                                                                                <span class="text-gray-400">N/A</span>
                                                                            @else
                                                                                {{ $nestedValue }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @elseif(is_numeric($v) && (str_contains(strtolower($k), 'price') || str_contains(strtolower($k), 'amount') || str_contains(strtolower($k), 'total') || str_contains(strtolower($k), 'cost') || str_contains(strtolower($k), 'subtotal') || str_contains(strtolower($k), 'discount')))
                                                        <span class="font-medium text-[#EA7C69]">₱{{ number_format($v, 2) }}</span>
                                                    @elseif(is_bool($v))
                                                        {{ $v ? 'Yes' : 'No' }}
                                                    @elseif(is_null($v))
                                                        <span class="text-[#EA7C69]">N/A</span>
                                                    @else
                                                        <span class="text-[#EA7C69]">{{ $v }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(is_null($action->action_data))
                                    <span class="text-gray-400">No data</span>
                                @elseif(is_numeric($action->action_data))
                                    {{ number_format($action->action_data, 2) }}
                                @elseif(is_bool($action->action_data))
                                    {{ $action->action_data ? 'Yes' : 'No' }}
                                @else
                                    {{ $action->action_data }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No actions recorded</p>
                    @endforelse
                </div>
            </div>

            {{-- Grading Section --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    <span><i class="fa-solid fa-pencil mr-2"></i></span> Grades and Feedback
                </h3>

                @if($session->status === 'graded')
                    <div class="bg-white border shadow-sm rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[#4CAF50] font-semibold">Already Graded</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Score</div>
                                <div class="text-2xl font-bold text-[#4CAF50]">
                                    {{ $session->score }} / {{ $session->activity->grades }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Feedback</div>
                                <div class="text-gray-800">
                                    {{ $session->feedback ?? 'No feedback provided' }}
                                </div>
                            </div>
                        </div>
                        <x-button
                            class="mt-4"
                            variant="btnGradientv1"
                            onclick="enableRegrade()">
                                Regrade Submission
                        </x-button>
                    </div>
                @endif

                <form id="gradingForm" @if($session->status === 'graded') style="display: none;" @endif>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Score (out of {{ $session->activity->grades }})
                            </label>
                            <x-input
                                type="number"
                                id="score"
                                name="score"
                                min="0"
                                max="{{ $session->activity->grades }}"
                                step="0.01"
                                value="{{ $session->score }}"
                                variant="review"
                                wrapperClass=""
                                />
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Grade Percentage
                            </label>
                            <input type="text" id="percentage"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100" readonly>
                        </div> --}}
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Feedback
                        </label>
                        <x-textarea
                            id="feedback"
                            name="feedback"
                            rows="4"
                            placeholder="Provide feedback to the student..."
                            :value="$session->feedback"
                        />
                    </div>

                    <div class="flex gap-4">
                        <x-button variant="btnGradient">
                            Return Grade
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const MAX_SCORE = {{ $session->activity->grades }};
        const GRADE_URL = '{{ route("faculty.simulation.grade", ["sessionId" => $session->id]) }}';

        // Calculate percentage
        document.getElementById('score')?.addEventListener('input', function () {
            const score = parseFloat(this.value) || 0;
            const percentage = ((score / MAX_SCORE) * 100).toFixed(2);
            document.getElementById('percentage').value = percentage + '%';
        });

        // Trigger on load
        if (document.getElementById('score')) {
            document.getElementById('score').dispatchEvent(new Event('input'));
        }

        // Enable regrade
        function enableRegrade() {
            if (confirm('Are you sure you want to regrade this submission?')) {
                document.getElementById('gradingForm').style.display = 'block';
            }
        }

        // Submit grading
        document.getElementById('gradingForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const score = parseFloat(document.getElementById('score').value);
            const feedback = document.getElementById('feedback').value;

            if (score > MAX_SCORE) {
                alert(`Score cannot exceed ${MAX_SCORE}`);
                return;
            }

            try {
                const response = await fetch(GRADE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ score, feedback })
                });

                if (!response.ok) {
                    const err = await response.json();
                    console.error('Server error:', err);
                    alert('❌ ' + (err.message || 'Failed to submit grade.'));
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    alert('✅ Grade submitted successfully!');
                    window.location.reload();
                } else {
                    alert('❌ Failed to submit grade. Please try again.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('❌ An unexpected error occurred. Please try again.');
            }
        });
    </script>
</x-layouts>
