<x-layouts :title="'Submissions | ' . $activity->name">
<div class="flex-1 bg-gray-50">

    <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
        @include('partials.includes.topnav', ['activity' => $activity ?? null])
    </div>

    <div class="container mx-auto p-4 sm:p-6">
        {{-- Header --}}
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="font-[Barlow] text-2xl sm:text-3xl font-bold text-gray-800">Simulation Submissions</h1>
                <p class="font-[Barlow] text-sm sm:text-base text-gray-600">{{ $activity->name }} - {{ $activity->section->class_name }}</p>
            </div>
            <a href="{{ route('faculty/activity/activity_details', [$activity->section_id, $activity->activity_id]) }}"
                class="w-full sm:w-max h-min px-7 py-2 text-sm text-center border border-black rounded hover:bg-gray-100 transition">
                    Back to Activity
                </a>

        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Total Submissions</div>
                <div class="text-xl sm:text-3xl font-bold text-[#0D0D54]">{{ $submissions->count() }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Graded</div>
                <div class="text-xl sm:text-3xl font-bold text-[#4CAF50]">
                    {{ $submissions->where('status', 'graded')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Pending</div>
                <div class="text-xl sm:text-3xl font-bold text-yellow-600">
                    {{ $submissions->where('status', 'submitted')->count() }}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4">
                <div class="text-xs sm:text-sm text-gray-600 mb-1">Average Score</div>
                <div class="text-xl sm:text-3xl font-bold text-[#EA7C69]">
                    @php
                        $avgScore = $submissions->where('status', 'graded')->avg('score');
                    @endphp
                    {{ $avgScore ? number_format($avgScore, 2) : 'N/A' }}
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center">
                <label class="text-sm font-medium text-gray-700">Filter:</label>

                <select id="statusFilter" class="border rounded px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="all">All Status</option>
                    <option value="submitted">Pending Review</option>
                    <option value="graded">Graded</option>
                </select>

                <select id="roleFilter" class="border rounded px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="all">All Roles</option>
                    @foreach($submissions->pluck('role_name')->unique() as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>

                <x-input
                    type="text"
                    name="search"
                    id="searchInput"
                    placeholder="Search student name..."
                    variant="submissions"
                    wrapperClass="flex-1 w-full"
                />

                <x-button
                    variant="btnGradientv1"
                    class="w-full sm:w-auto"
                    onclick="exportToCSV()">
                    <i class="fa-solid fa-download mr-2"></i>
                        Export CSV
                </x-button>

            </div>
        </div>

        {{-- Submissions Table - Desktop View --}}
        <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
            <div class="w-full overflow-x-auto">
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
                                <span class="font-[Barlow] inline-flex bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-xs px-3 py-1.5 items-center justify-center text-center rounded-full">
                                    {{ $submission->role_name }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{
                                    $submission->submitted_at
                                    ? $submission->submitted_at->isCurrentYear()
                                        ? $submission->created_at->format('M d \\a\\t h:i A')
                                        : $submission->created_at->format('M d, Y \\a\\t h:i A')
                                    : 'N/A'
                                }}
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
                                    class="inline-block whitespace-nowrap px-4 xl:px-5 py-2 text-xs xl:text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition">
                                    <i class="fa-solid fa-clipboard-check mr-1 xl:mr-2"></i>
                                    Review
                                </a>
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

        {{-- Submissions Cards - Mobile/Tablet View --}}
        <div class="lg:hidden space-y-4">
            @forelse($submissions as $index => $submission)
                <div class="bg-white rounded-lg shadow-md p-4 submission-row"
                     data-status="{{ $submission->status }}"
                     data-role="{{ $submission->role_name }}"
                     data-name="{{ strtolower($submission->user->name) }}">

                    {{-- Student Info --}}
                    <div class="flex items-center gap-3 mb-3 pb-3 border-b">
                        <img src="{{ asset('storage/profile_images/' . $submission->user?->profile_image) }}"
                             alt="{{ $submission->user->name }}"
                             class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $submission->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $submission->user->email }}</div>
                        </div>
                        <span class="font-[Barlow] bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-xs px-3 py-1.5 rounded-full">
                            {{ $submission->role_name }}
                        </span>
                    </div>

                    {{-- Submission Details --}}
                    <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                        <div>
                            <span class="text-gray-600">Submitted:</span>
                            <div class="font-medium">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, g:i A') : 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <div>
                                @if($submission->status === 'graded')
                                    <span class="bg-[#E9F8EC] text-[#4CAF50] px-2 py-1 rounded-full text-xs font-medium">
                                        Graded
                                    </span>
                                @else
                                    <span class="bg-[#FFF7DD] text-[#E3B341] px-2 py-1 rounded-full text-xs font-medium">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600">Orders:</span>
                            <div class="font-semibold">{{ $submission->session_data['total_orders'] ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Actions:</span>
                            <div class="font-semibold">{{ $submission->session_data['total_actions'] ?? $submission->actions->count() }}</div>
                        </div>
                    </div>

                    {{-- Score --}}
                    <div class="mb-3 pb-3 border-b">
                        <span class="text-gray-600 text-sm">Score:</span>
                        @if($submission->score !== null)
                            <div class="mt-1">
                                <span class="font-bold text-[#4CAF50] text-lg">
                                    {{ $submission->score }} / {{ $activity->grades }}
                                </span>
                                <span class="text-xs text-gray-500 ml-2">
                                    ({{ number_format(($submission->score / $activity->grades) * 100, 1) }}%)
                                </span>
                            </div>
                        @else
                            <div class="text-gray-400 text-sm mt-1">Not graded</div>
                        @endif
                    </div>

                    {{-- Action Button --}}
                    <a href="{{ route('faculty.simulation.review', $submission->id) }}"
                        class="block w-full px-5 py-2.5 text-sm text-center bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition">
                        <i class="fa-solid fa-clipboard-check mr-2"></i>
                        Review Submission
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                    No submissions yet
                </div>
            @endforelse
        </div>

        {{-- Summary Statistics --}}
        @if($submissions->count() > 0)
            <div class="mt-4 sm:mt-6 bg-white rounded-lg shadow p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold mb-4">Performance Summary</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div class="border rounded-lg p-3 sm:p-4">
                        <div class="text-xs sm:text-sm text-gray-600 mb-2">Total Orders Processed</div>
                        <div class="text-xl sm:text-2xl font-bold text-[#0D0D54]">
                            {{ $submissions->sum(function($s) { return $s->session_data['total_orders'] ?? 0; }) }}
                        </div>
                    </div>

                    <div class="border rounded-lg p-3 sm:p-4">
                        <div class="text-xs sm:text-sm text-gray-600 mb-2">Total Revenue Generated</div>
                        <div class="text-xl sm:text-2xl font-bold text-[#4CAF50]">
                            ₱{{ number_format($submissions->sum(function($s) { return $s->session_data['total_revenue'] ?? 0; }), 2) }}
                        </div>
                    </div>

                    <div class="border rounded-lg p-3 sm:p-4">
                        <div class="text-xs sm:text-sm text-gray-600 mb-2">Average Session Duration</div>
                        <div class="text-xl sm:text-2xl font-bold text-[#EA7C69]">
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

    let rowIndex = 1;
    document.querySelectorAll('.submission-row').forEach((row) => {
        if (row.style.display !== 'none') {
            const studentName = row.querySelector('.font-medium')?.textContent.trim() || '';
            const email = row.querySelector('.text-xs.text-gray-500')?.textContent.trim() || '';
            const role = row.dataset.role || '';
            const status = row.dataset.status || '';

            // Get data from data attributes for mobile cards
            const submittedText = row.querySelector('[class*="Submitted"]')?.nextElementSibling?.textContent.trim() || 'N/A';
            const ordersText = row.querySelector('[class*="Orders"]')?.nextElementSibling?.textContent.trim() || 'N/A';
            const actionsText = row.querySelector('[class*="Actions"]')?.nextElementSibling?.textContent.trim() || 'N/A';
            const scoreText = row.querySelector('.font-bold.text-\\[\\#4CAF50\\]')?.textContent.trim() || 'Not graded';

            const rowData = [
                rowIndex++,
                studentName,
                email,
                role,
                submittedText,
                ordersText,
                actionsText,
                status,
                scoreText
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
