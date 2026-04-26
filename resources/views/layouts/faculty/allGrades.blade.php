<x-layouts title="Student Works | Grades">

    <div class="flex-1">
        <div class="bg-white rounded-xl shadow-sm">

            {{-- Search and Filter Bar --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    {{-- Search Input --}}
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search by student name or activity..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- Section Filter --}}
                    <select id="sectionFilter"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                        <option value="">All Sections</option>
                        @foreach($sessions->pluck('activity.section')->unique('id')->filter() as $section)
                            <option value="{{ $section->id }}">{{ $section->section_name }} : {{ $section->class_name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Export Button --}}
                    <button
                        class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- Group submissions by section/activity --}}
            <div class="divide-y ">
                @php
                    $groupedSessions = $sessions->groupBy(function ($session) {
                        return $session->activity->section->section_name . ' : ' . $session->activity->section->class_name;
                    });
                @endphp

                @foreach($groupedSessions as $sectionName => $sectionSessions)
                    <div class="group-section" data-section-id="{{ $sectionSessions->first()->activity->section->id }}">
                        {{-- Section Header (Collapsible) --}}
                        <div class="px-6 py-4 hover:bg-gray-100 cursor-pointer flex items-center justify-between transition-colors"
                            onclick="toggleSection(this)">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform section-chevron"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $sectionName }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                        <span>{{ $sectionSessions->count() }} submissions</span>
                                        <span>•</span>
                                        <span>{{ $sectionSessions->where('score', '!=', null)->count() }} graded</span>
                                        <span>•</span>
                                        <span>Avg: {{ number_format($sectionSessions->avg('score') ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-gray-400 text-sm">Click to expand</span>
                        </div>

                        {{-- Section Content (Collapsible) --}}
                        <div class="section-content" style="display: none;">
                            <table class="w-full">
                                <thead class="">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Activity Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Role</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Submitted</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sectionSessions as $session)
                                        <tr class="hover:bg-gray-50 transition-colors submission-row"
                                            data-student="{{ strtolower($session->user->name) }}"
                                            data-activity="{{ strtolower($session->activity->name ?? '') }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $session->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $session->activity->name ?? 'Unknown Activity' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $session->role_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $session->submitted_at ? $session->submitted_at->format('M d, Y') : 'Not Submitted' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if($session->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($session->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                    {{ ucfirst($session->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ $session->score ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Toggle section collapse/expand
        function toggleSection(header) {
            const content = header.nextElementSibling;
            const chevron = header.querySelector('.section-chevron');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            filterSubmissions();
        });

        // Section filter functionality
        document.getElementById('sectionFilter').addEventListener('change', function (e) {
            filterSubmissions();
        });

        function filterSubmissions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const sectionId = document.getElementById('sectionFilter').value;

            const sections = document.querySelectorAll('.group-section');
            let visibleSections = 0;

            sections.forEach(section => {
                const sectionMatches = !sectionId || section.dataset.sectionId === sectionId;
                const rows = section.querySelectorAll('.submission-row');
                let visibleRows = 0;

                rows.forEach(row => {
                    const student = row.dataset.student;
                    const activity = row.dataset.activity;
                    const matchesSearch = !searchTerm || student.includes(searchTerm) || activity.includes(searchTerm);

                    if (matchesSearch && sectionMatches) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleRows > 0 && sectionMatches) {
                    section.style.display = '';
                    visibleSections++;
                } else {
                    section.style.display = 'none';
                }
            });

            document.getElementById('visibleCount').textContent = visibleSections;
        }
    </script>

</x-layouts>
