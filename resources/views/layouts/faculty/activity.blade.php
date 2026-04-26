@php

$role = auth()->user()->role;

$studentsLink = url(
    'faculty/student/' .
    $section->section_id
);

@endphp

<x-layouts :title="$section->class_name">

    <div class="flex-1">

        {{-- Toast / Alert messages --}}
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error1" :message="session('error')" />

        <div class="rounded-xl h-full shadow-sm">

            <script>
                // Set global variable for current section
                window.currentSectionId = {{ $section->section_id }};
            </script>

            {{-- Top bar navigation --}}
            <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                <!-- Activities -->
                <a href="{{ auth()->user()->role === 'student'
                        ? url('student/activity/' . $section->section_id)
                        : url('faculty/activity/' . $section->section_id) }}"
                        class="{{ request()->is('faculty/activity*') || request()->is('student/activity*')
                            ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                            : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Activities
                </a>

                <!-- Announcements -->
                <a href="{{ url('faculty/announcements/' . $section->section_id) }}"
                    class="{{ request()->is('faculty/announcements*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Announcements
                </a>

                @if ($role === 'faculty' && isset($section) && $section->is_archived && (!isset($activity) || !$activity->activity_id))
                    {{-- Disabled state when no activity is available --}}
                    <span class="text-gray-400 font-medium px-4 py-4 cursor-not-allowed" title="Select an activity to view students">
                        Students
                    </span>
                @else
                    <a href="{{ $studentsLink }}"
                    class="{{ request()->is('faculty/student*')
                        || request()->is('student/student*')
                        || request()->is('faculty/archived/student/*')
                        || request()->is('student/archived/student/*')
                                ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                                : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Students
                    </a>
                @endif
                
                <a href="{{ route('faculty.simulation.all', $section->section_id) }}"
                   class="{{ request()->is('faculty/simulation/all-submissions*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Student Works
                </a>

            </div>

            <x-section :section="$section" variant="create" :activity="null" />

            <div class="flex flex-row gap-5">

                <!-- Right Column -->
                <div class="flex-1">
                    @if($activities->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-3 px-5 items-stretch">
                            @foreach ($activities as $activity)
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $dueDate = $activity->due_date ? \Carbon\Carbon::parse($activity->due_date) : null;
                                    // Determine status
                                    if (!$dueDate) {
                                        $status = 'no-due-date';
                                        $statusLabel = 'No Due Date';
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                    } elseif ($dueDate->isToday()) {
                                        $status = 'due-today';
                                        $statusLabel = 'Due Today';
                                        $statusClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif ($dueDate->isPast()) {
                                        $status = 'overdue';
                                        $statusLabel = 'Overdue';
                                        $statusClass = 'bg-red-100 text-red-700';
                                    } else {
                                        $status = 'active';
                                        $statusLabel = 'Active';
                                        $statusClass = 'bg-green-100 text-green-700';
                                    }
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-2xl p-5 flex flex-col justify-between h-full shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                                        <a href="{{ route('faculty/activity/activity_details', [$section->section_id, $activity->activity_id]) }}" class="block">
                                            <div class="space-y-3">
                                                {{-- Title --}}
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1 hover:underline">
                                                        {{ $activity->name }}
                                                    </h3>
                                                </div>
                                    
                                                {{-- Description --}}
                                                <p class="text-sm text-gray-600 line-clamp-3 min-h-[60px]">
                                                    {{ $activity->description ?: 'No description provided.' }}
                                                </p>
                                    
                                                {{-- Meta --}}
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                                        <i class="fa-solid fa-user-tag"></i>
                                                        {{ ucfirst($activity->role->name ?? 'No role assigned') }}
                                                    </span>
                                    
                                                </div>
                                            </div>
                                        </a>
                                    
                                        {{-- Footer --}}
                                        <div class="relative mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                Assigned Role: <span class="font-semibold text-gray-700">{{ ucfirst($activity->role->name ?? '—') }}</span>
                                            </div>
                                    
                                            <div class="relative">
                                                {{-- Three Dots Button --}}
                                                <button
                                                    class="toggleMenuBtn p-2 rounded-full hover:bg-gray-100 transition"
                                                    type="button"
                                                >
                                                    <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
                                                </button>
                                    
                                                {{-- Hidden Menu --}}
                                                <div
                                                    class="actionMenu hidden absolute right-0 bottom-12 w-44 bg-white border border-gray-200 rounded-xl shadow-lg p-2 flex flex-col gap-2 z-50"
                                                >
                                                    <x-button
                                                        class="openManageBtn"
                                                        data-activity-id="{{ $activity->activity_id }}"
                                                        data-section-id="{{ $section->section_id }}"
                                                        data-activity-name="{{ $activity->name }}"
                                                        variant="manageSectionModalNoW"
                                                    >
                                                        Manage
                                                    </x-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            No activities yet for this section.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @include('partials.modals.faculty.createActivity')
    @include('partials.modals.faculty.studentSubmissions')
    @include('partials.modals.faculty.manageActivity')

    <script>
        
        document.addEventListener('DOMContentLoaded', function () {

            const toggleButtons = document.querySelectorAll('.toggleMenuBtn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();

                    // Close other open menus
                    document.querySelectorAll('.actionMenu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });

                    // Toggle this one
                    const menu = this.nextElementSibling;
                    menu.classList.toggle('hidden');
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function () {
                document.querySelectorAll('.actionMenu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });

        });

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('createActivityForm');
            const dueDateInput = document.getElementById('due_date');
            const warning = document.getElementById('dueDateWarning');

            if (form && dueDateInput && warning) {
                form.addEventListener('submit', (e) => {
                    if (!dueDateInput.value) {
                        e.preventDefault(); // Prevent form submission
                        warning.classList.remove('hidden');
                        dueDateInput.classList.add('border-red-500');
                    } else {
                        warning.classList.add('hidden');
                        dueDateInput.classList.remove('border-red-500');
                    }
                });

                // Hide warning automatically once user selects a date
                dueDateInput.addEventListener('change', () => {
                    if (dueDateInput.value) {
                        warning.classList.add('hidden');
                        dueDateInput.classList.remove('border-red-500');
                    }
                });
            }
        });
    </script>

</x-layouts>


