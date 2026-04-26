<div class="container">
    <h1 class="text-2xl font-bold mb-4">Attempt #{{ $attempt->attempt_id }}</h1>

    <div class="bg-white p-4 rounded shadow mb-4">
        <p><strong>Student:</strong> {{ $attempt->student->name ?? 'N/A' }}</p>
        <p><strong>Section:</strong> {{ $attempt->section_id }}</p>
        <p><strong>Activity:</strong> {{ $attempt->activity_id }}</p>
        <p><strong>Started:</strong> {{ $attempt->started_at }}</p>
        <p><strong>Finished:</strong> {{ $attempt->finished_at }}</p>
        <p><strong>Auto Score:</strong> {{ $attempt->auto_score ?? '-' }}</p>
        <p><strong>Faculty Score:</strong> {{ $attempt->faculty_score ?? '-' }}</p>
        <p><strong>Remarks:</strong> {{ $attempt->faculty_remarks ?? '-' }}</p>
    </div>

    <div class="bg-white p-4 rounded shadow mb-4">
        <h2 class="font-semibold mb-2">Action Timeline</h2>
        <ul>
        @foreach($attempt->logs as $log)
            <li class="mb-2">
                <div class="text-sm text-gray-600">{{ $log->action_time ? $log->action_time->format('Y-m-d H:i:s') : $log->created_at->format('Y-m-d H:i:s') }}</div>
                <div class="font-medium">{{ $log->action }}</div>
                <pre class="text-xs">@if($log->details) {{ json_encode($log->details, JSON_PRETTY_PRINT) }} @endif</pre>
            </li>
        @endforeach
        </ul>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Grade</h2>
        @if(session('success')) <div class="text-green-600">{{ session('success') }}</div> @endif
        <form method="POST" action="{{ route('faculty.simulation.grade', $attempt->attempt_id) }}">
            @csrf
            <div class="mb-2">
                <label>Score (0-100)</label>
                <input type="number" name="faculty_score" min="0" max="100" value="{{ $attempt->faculty_score }}" class="border p-2 w-24">
            </div>
            <div class="mb-2">
                <label>Remarks</label>
                <textarea name="faculty_remarks" rows="4" class="border p-2 w-full">{{ $attempt->faculty_remarks }}</textarea>
            </div>
            <button class="btn">Save Grade</button>
        </form>
    </div>
</div>
