<x-layouts title="My Submissions">
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">My Submissions</h1>

            @if($hasOpenSessions)
                <form action="{{ route('student.simulations.submit-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Submit All Open Sessions
                    </button>
                </form>
            @endif
        </div>

        @if(!$hasOpenSessions)
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                All active open sessions have already been submitted.
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Simulation</th>
                        <th class="px-4 py-3 text-left">Submitted</th>
                        <th class="px-4 py-3 text-left">Roles</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                        <th class="px-4 py-3 text-left">Score</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">
                                    {{ $submission->simulation_name ?? 'Simulation Submission' }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($submission->submitted_at)->format('M d \a\t h:i A') ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ !empty($submission->summary['roles']) ? implode(', ', $submission->summary['roles']) : 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $submission->summary['total_actions'] ?? 0 }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $submission->score ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs {{ $submission->status === 'graded' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts>