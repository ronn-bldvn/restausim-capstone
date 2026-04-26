<x-layouts :title="'Review Submission'">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold mb-2">Review Submission</h1>
            <p class="text-gray-600">{{ $submission->user->name }} — {{ $submission->batch_code }}</p>
            <p class="text-sm text-gray-500 mt-1">
                Roles: {{ implode(', ', $submission->summary['roles'] ?? []) }}
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Summary</h2>

                <div class="space-y-3 text-sm">
                    <div><strong>Submitted At:</strong> {{ optional($submission->submitted_at)->format('M d, Y h:i A') }}</div>
                    <div><strong>Total Sessions:</strong> {{ $submission->summary['total_sessions'] ?? 0 }}</div>
                    <div><strong>Total Orders:</strong> {{ $submission->summary['total_orders'] ?? 0 }}</div>
                    <div><strong>Total Actions:</strong> {{ $submission->summary['total_actions'] ?? 0 }}</div>
                    <div><strong>Total Revenue:</strong> ₱{{ number_format($submission->summary['total_revenue'] ?? 0, 2) }}</div>
                    <div><strong>Duration:</strong> {{ $submission->summary['duration_minutes'] ?? 0 }} min</div>
                    <div><strong>Auto Score:</strong> {{ $submission->score ?? 'N/A' }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($submission->status) }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Grade Submission</h2>

                <form action="{{ route('faculty.simulation.grade', $submission->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Score</label>
                        <input type="number" step="0.01" name="score" value="{{ old('score', $submission->score) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Feedback</label>
                        <textarea name="feedback" rows="5" class="w-full border rounded px-3 py-2">{{ old('feedback', $submission->feedback) }}</textarea>
                    </div>

                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Save Grade
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-lg font-semibold mb-4">Evaluated Actions</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Session ID</th>
                            <th class="px-4 py-3 text-left">Action</th>
                            <th class="px-4 py-3 text-left">Correct</th>
                            <th class="px-4 py-3 text-left">Points</th>
                            <th class="px-4 py-3 text-left">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submission->actions as $action)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $action->session_id }}</td>
                                <td class="px-4 py-3">{{ $action->action_type }}</td>
                                <td class="px-4 py-3">{{ $action->is_correct ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-3">{{ $action->points_earned }}</td>
                                <td class="px-4 py-3">{{ optional($action->timestamp)->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">No evaluated actions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts>