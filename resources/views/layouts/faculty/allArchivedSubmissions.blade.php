<x-layouts :title="'Submissions | ' . $activity->name">
<div class="flex-1 bg-gray-50">

    <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
        @include('partials.includes.topnav', ['activity' => $activity ?? null])
    </div>

    <div class="container mx-auto p-6">
        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">

            <div>
                <h1 class="font-[Barlow] text-3xl font-bold text-gray-800">Simulation Submissions</h1>
                <p class="font-[Barlow] text-gray-600">{{ $activity->name }} - {{ $activity->section->class_name }}</p>
            </div>
            <a href="{{ route('faculty/activity/activity_details', [$activity->section_id, $activity->activity_id]) }}"
                class="w-max h-min px-7 py-2 text-sm  border border-black rounded hover:bg-gray-100 transition">
                    Back to Activity
                </a>

        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-sm text-gray-600 mb-1">Total Submissions</div>
                <div class="text-3xl font-bold text-[#0D0D54]">{{ $submissions->count() }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-sm text-gray-600 mb-1">Graded</div>
                <div class="text-3xl font-bold text-[#4CAF50]">
                    {{ $submissions->where('status', 'graded')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-sm text-gray-600 mb-1">Pending</div>
                <div class="text-3xl font-bold text-yellow-600">
                    {{ $submissions->where('status', 'submitted')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-sm text-gray-600 mb-1">Average Score</div>
                <div class="text-3xl font-bold text-[#EA7C69]">
                    @php
                        $avgScore = $submissions->where('status', 'graded')->avg('score');
                    @endphp
                    {{ $avgScore ? number_format($avgScore, 2) : 'N/A' }}
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4 items-center">
                <label class="text-sm font-medium text-gray-700">Filter:</label>

                <select id="statusFilter" class="border rounded px-3 py-2">
                    <option value="all">All Status</option>
                    <option value="submitted">Pending Review</option>
                    <option value="graded">Graded</option>
                </select>

                <select id="roleFilter" class="border rounded px-3 py-2">
                    <option value="all">All Roles</option>
                    @foreach($submissions->pluck('role_name')->unique() as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>

                <x-input
                    type="text"
                    id="searchInput"
                    placeholder="Search student name..."
                    variant="submissions"
                    wrapperClass="flex-1"
                />

                <x-button
                    variant="btnGradientv1"
                    onclick="exporttoCSV()">
                    <i class="fa-solid fa-download mr-2"></i>
                        Export CSV
                </x-button>

            </div>
        </div>

        {{-- Submissions Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="w-full">
                <x-table id="" :headers="['#', 'Student', 'Role', 'Submitted','Orders', 'Actions', 'Status', 'Score', 'Action']" align="text-center">
                    @forelse($submissions as $index => $submission)
                        <tr class="hover:bg-gray-50 submission-row"
                            data-status="{{ $submission->status }}"
                            data-role="{{ $submission->role_name }}"
                            data-name="{{ strtolower($submission->user->name) }}">
                            <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
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

                            <td class="px-4 py-3 text-sm">
                                <span class="font-[Barlow] inline-flex bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-xs px-3 py-1.5 items-center justify-center text-center rounded-full font-">
                                    {{ $submission->role_name }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $submission->submitted_at ? $submission->submitted_at->format('M d, g:i A') : 'N/A' }}
                            </td>

                            <td class="px-4 py-3 text-sm font-semibold">
                                {{ $submission->session_data['total_orders'] ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3 text-sm font-semibold">
                                {{ $submission->session_data['total_actions'] ?? $submission->actions->count() }}
                            </td>

                            <td class="px-4 py-3">
                                @if($submission->status === 'graded')
                                    <span class="bg-[#E9F8EC] text-[#4CAF50] px-2 py-1 rounded-full text-xs font-medium">
                                        Graded
                                    </span>
                                @else
                                    <span class="bg-[#FFF7DD] text-[#E3B341] px-2 py-1 rounded-full text-xs font-medium">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                @if($submission->score !== null)
                                    <span class="font-bold text-[#4CAF50]">
                                        {{ $submission->score }} / {{ $activity->grades }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        ({{ number_format(($submission->score / $activity->grades) * 100, 1) }}%)
                                    </span>
                                @else
                                    <span class="text-gray-400">Not graded</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('faculty.simulation.review', $submission->id) }}"
                                    class="w-max h-min px-5 py-2 text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition">
                                    <i class="fa-solid fa-clipboard-check mr-2"></i>
                                        Review
                                </a>
                                {{-- <a href="{{ route('faculty.simulation.review', $submission->id) }}"
                                    class="w-max h-min px-5 py-2 text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition">
                                    <i class="fa-solid fa-trash mr-2"></i>
                                        Delete
                                </a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                No submissions yet
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </div>

        {{-- Summary Statistics --}}
        @if($submissions->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Performance Summary</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-2">Total Orders Processed</div>
                        <div class="text-2xl font-bold text-[#0D0D54]">
                            {{ $submissions->sum(function($s) { return $s->session_data['total_orders'] ?? 0; }) }}
                        </div>
                    </div>

                    <div class="border rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-2">Total Revenue Generated</div>
                        <div class="text-2xl font-bold text-[#4CAF50]">
                            ₱{{ number_format($submissions->sum(function($s) { return $s->session_data['total_revenue'] ?? 0; }), 2) }}
                        </div>
                    </div>

                    <div class="border rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-2">Average Session Duration</div>
                        <div class="text-2xl font-bold text-[#EA7C69]">
                            {{ number_format($submissions->avg(function($s) { return $s->session_data['duration_minutes'] ?? 0; }), 2) }} min
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Filter functionality
const statusFilter = document.getElementById('statusFilter');
const roleFilter = document.getElementById('roleFilter');
const searchInput = document.getElementById('searchInput');

function filterTable() {
    const statusValue = statusFilter.value;
    const roleValue = roleFilter.value;
    const searchValue = searchInput.value.toLowerCase();

    document.querySelectorAll('.submission-row').forEach(row => {
        const status = row.dataset.status;
        const role = row.dataset.role;
        const name = row.dataset.name;

        const statusMatch = statusValue === 'all' || status === statusValue;
        const roleMatch = roleValue === 'all' || role === roleValue;
        const searchMatch = searchValue === '' || name.includes(searchValue);

        if (statusMatch && roleMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

statusFilter.addEventListener('change', filterTable);
roleFilter.addEventListener('change', filterTable);
searchInput.addEventListener('input', filterTable);

// Export to CSV
function exportToCSV() {
    const rows = [];
    const headers = ['#', 'Student Name', 'Email', 'Role', 'Submitted', 'Orders', 'Actions', 'Status', 'Score'];
    rows.push(headers.join(','));

    document.querySelectorAll('.submission-row').forEach((row, index) => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const rowData = [
                index + 1,
                cells[1].querySelector('.font-medium')?.textContent.trim() || '',
                cells[1].querySelector('.text-xs')?.textContent.trim() || '',
                cells[2].textContent.trim(),
                cells[3].textContent.trim(),
                cells[4].textContent.trim(),
                cells[5].textContent.trim(),
                cells[6].textContent.trim(),
                cells[7].textContent.trim()
            ];
            rows.push(rowData.map(cell => `"${cell}"`).join(','));
        }
    });

    const csvContent = rows.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `submissions_{{ $activity->id }}_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
</x-layouts>
