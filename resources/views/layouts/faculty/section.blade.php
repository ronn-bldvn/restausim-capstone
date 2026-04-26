<x-layouts :title="'Section | RestauSim'">


    <x-alert type="success" :message="session('success')" />
    <x-alert type="error1" :message="session('error')" />

    <div class="flex-1 overflow-y-auto p-5 bg-white">
        <!-- Page Header -->
        <div class="flex justify-between items-center px-5 mb-6">

            <!-- Left: Title + Subtitle -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manage Sections</h1>
                <p class="text-gray-500 mt-1">
                    Manage and track the sections you created.
                </p>
            </div>

            <!-- Right: Buttons -->
            <div class="flex gap-3">

                <x-button id="openCreateSection" class="flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-base"></i>
                    Create Section
                </x-button>

            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-5">
            @foreach ($sections as $section)
                <div
                    class="relative w-full bg-white shadow-sm border border-gray-200 rounded-xl flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition h-full overflow-hidden">

                    <a
                        href="{{ url('faculty/activity/' . $section->section_id) }}"
                        class="absolute inset-0 z-10"
                        aria-label="Open section {{ $section->class_name }} - {{ $section->section_name }}"
                    ></a>
        
                    <!-- Card Content -->
                    <div class="relative z-0 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <x-capsule :label="$section->class_code" size="xs" />
                        </div>
        
                        <!-- Class + Section Names -->
                        <div>
                            <div class="text-lg font-bold mb-2 truncate">
                                {{ $section->class_name }}
                            </div>
                            <div class="text-sm mb-1 text-gray-600">
                                {{ $section->section_name }}
                            </div>
                        </div>
        
                        <div class="flex-grow"></div>
        
                        <!-- Total Students -->
                        <div class="flex items-center gap-2 mt-3 text-sm text-gray-600">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ $section->totalStudents }} students joined</span>
                        </div>
        
                        <!-- Total Activity -->
                        <div class="flex items-center gap-2 my-3 text-sm text-gray-600">
                            <i class="fa-solid fa-clipboard-list"></i>
                            <span>Total Activity: {{ $section->totalActivity }}</span>
                        </div>
        
                        <!-- Class Code -->
                        <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
                            <i class="fa-solid fa-list-check"></i>
                            <span>Class Code: <span class="font-bold">{{ $section->share_code }}</span></span>
                        </div>
                    </div>
        
                    <!-- Buttons -->
                    <div class="relative z-20 mt-auto flex justify-end border-t pr-3">
                        <!-- Three Dots Button -->
                        <button
                            class="toggleMenuBtn p-2 rounded-full hover:bg-gray-100 transition"
                            type="button"
                        >
                            <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
                        </button>
        
                        <!-- Hidden Menu -->
                        <div
                            class="actionMenu hidden absolute right-0 bottom-12 w-44 bg-white border border-gray-200 rounded-xl shadow-lg p-2 flex flex-col gap-2 z-50"
                        >
                            <x-button
                                class="openManageBtn w-full text-left"
                                variant="manageSectionModal"
                                data-section-id="{{ $section->section_id }}"
                            >
                                Manage
                            </x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50 transition-all">
        <span id="toastMessage"></span>
    </div>

    <script>

        let currentSectionId = null;

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

        document.querySelectorAll('.openManageBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const sectionId = btn.dataset.sectionId;
                currentSectionId = sectionId;

                const section = @json($sections).find(s => s.section_id == sectionId);
                const modal = document.getElementById('manageSectionModal');
                const sectionBase = @json(url('/faculty/section'));

                // Header
                modal.querySelector('#sectionName').textContent = section.section_name;
                modal.querySelector('#manageSectionName').textContent = section.class_name;
                modal.querySelector('#openInviteModalBtn').dataset.sectionId = sectionId;

                // Fill inputs
                modal.querySelector('[name="class_name"]').value = section.class_name ?? '';
                modal.querySelector('[name="section_name"]').value = section.section_name ?? '';
                modal.querySelector('[name="class_code"]').value = section.class_code ?? section.share_code ?? '';
                modal.querySelector('[name="share_code"]').value = section.share_code ?? '';

                // Update section 
                modal.querySelector('#updateSectionForm').action = `${sectionBase}/${sectionId}`;
                modal.querySelector('.copy-invite-link').dataset.sectionId = sectionId;

                // Delete section
                document.querySelector('#deleteSectionForm').action = `${sectionBase}/${sectionId}`;

                // Archive Section
                document.querySelector('#archiveSectionForm').action = `${sectionBase}/${sectionId}/archive`;

                // Load activities for this section
                await loadActivities(sectionId);

                switchTab('details');

                modal.classList.remove('hidden');
            });
        });

        // Function to load activities
        async function loadActivities(sectionId) {
            const assignmentsList = document.getElementById('assignmentsList');

            // Show loading state
            assignmentsList.innerHTML = '<div class="text-center py-8"><i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i><p class="text-gray-500 mt-2">Loading activities...</p></div>';

            try {
                const response = await fetch(`{{ url('/faculty/section') }}/${sectionId}/activities`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Activities loaded:', data); // Debug log

                if (data.success && data.activities.length > 0) {
                    assignmentsList.innerHTML = data.activities.map(activity => {
                        const dueDate = new Date(activity.due_date);
                        const now = new Date();

                        let status = '';
                        let statusColor = '';

                        if (isNaN(dueDate)) {
                            status = 'No Due Date';
                            statusColor = 'bg-gray-200 text-gray-700 border-gray-300';
                        } else if (now < dueDate) {
                            status = 'Active';
                            statusColor = 'bg-green-100 text-green-700 border-green-300';
                        } else if (now.toDateString() === dueDate.toDateString()) {
                            status = 'Due Today';
                            statusColor = 'bg-yellow-100 text-yellow-700 border-yellow-300';
                        } else if (now > dueDate) {
                            status = 'Overdue';
                            statusColor = 'bg-red-100 text-red-700 border-red-300';
                        }

                        return `
                            <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm hover:shadow-md transition bg-white">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">${activity.title}</h3>
                                    <p class="text-sm text-gray-500">Due: ${activity.due_date}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 mr-3 text-sm font-medium border rounded-full ${statusColor}">
                                        ${status}
                                    </span>
                                </div>
                            </div>
                        `;
                    }).join('');

                } else {
                    assignmentsList.innerHTML = `
                <div class="text-center py-12">
                    <i class="fa-solid fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No activities found for this section.</p>
                </div>
            `;
                }
            } catch (error) {
                console.error('Error loading activities:', error);
                assignmentsList.innerHTML = `
            <div class="text-center py-8">
                <i class="fa-solid fa-exclamation-triangle text-red-400 text-2xl mb-2"></i>
                <p class="text-red-500">Failed to load activities. Please try again.</p>
                <p class="text-sm text-gray-500 mt-2">${error.message}</p>
            </div>
        `;
            }
        }

        document.querySelectorAll('.openViewDetailsBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const sectionId = btn.dataset.sectionId;
                currentSectionId = sectionId;
                const viewAllBtn = document.getElementById('seeAllActivityPerSection');
                if(viewAllBtn){
                    viewAllBtn.onclick = () => {
                        window.location.href = `{{ url('/faculty/activity') }}/${sectionId}`;
                    }
                }
                const section = @json($sections).find(s => s.section_id == sectionId);
                const modal = document.getElementById('manageViewSectionModal');

                // Header
                modal.querySelector('#sectionName').textContent = `${section.section_name} - ${section.class_name}`;
                modal.querySelector('#classCode').textContent = section.class_code;

                // Cards
                modal.querySelector('#studentsCount').textContent = section.totalStudents ?? 0;
                modal.querySelector('#assignmentsCount').textContent = section.totalActivity ?? 0;
                modal.querySelector('#gradedCount').textContent = section.totalGraded ?? 0;

                // Load activities for this section
                await viewDetailsActivity(sectionId);

                // Load students for this section
                await viewDetailsStudents(sectionId);

                // Default tab for opening the modal
                switchTab('activity');

                modal.classList.remove('hidden');
            });
        });

        // Function to load activities
        async function viewDetailsActivity(sectionId) {
            const assignmentsList = document.getElementById('activityList');

            // Show loading state
            assignmentsList.innerHTML = '<div class="text-center py-8"><i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i><p class="text-gray-500 mt-2">Loading activities...</p></div>';

            try {
                const response = await fetch(`{{ url('/faculty/section') }}/${sectionId}/activities`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Activities loaded:', data); // Debug log

                if (data.success && data.activities.length > 0) {
                    assignmentsList.innerHTML = data.activities.map(activity => {
                        return `
                            <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm hover:shadow-md transition bg-white">
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">${activity.title}</h3>
                                    <p class="text-sm text-gray-500">Due: ${activity.due_date}</p>
                                </div>
                                <div class="flex flex-col text-right">
                                    <h3 class="text-sm font-semibold text-gray-900 text-lg">
                                        ${activity.submitted_count}/${activity.total_students}
                                    </h3>
                                    <p class="text-xs text-gray-500">Total Submissions</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    assignmentsList.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fa-solid fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No activities found for this section.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading activities:', error);
                assignmentsList.innerHTML = `
            <div class="text-center py-8">
                <i class="fa-solid fa-exclamation-triangle text-red-400 text-2xl mb-2"></i>
                <p class="text-red-500">Failed to load activities. Please try again.</p>
                <p class="text-sm text-gray-500 mt-2">${error.message}</p>
            </div>
        `;
            }
        }

        async function viewDetailsStudents(sectionId) {
            const studentsContainer = document.getElementById('studentsList');
            studentsContainer.innerHTML = `
        <div class="text-center py-8">
            <i class="fa-solid fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <p class="text-gray-500 mt-2">Loading students...</p>
        </div>
    `;

            try {
                const response = await fetch(`{{ url('/faculty/section') }}/${sectionId}/students`);
                const data = await response.json();

                if (data.success && data.students.length > 0) {
                    studentsContainer.innerHTML = `
                <div class="divide-y">
                    ${data.students.map(student => {
                        // Split roles by comma and create badges
                        const roles = student.simulation_roles
                            ? student.simulation_roles.split(', ').map(role =>
                                `<span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">${role}</span>`
                            ).join(' ')
                            : '<span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">No role assigned yet</span>';

                        return `
                            <div class="border border-gray-200 rounded-xl p-4 mb-4 flex justify-between items-center shadow-sm hover:shadow-md transition bg-white">
                                <div>
                                    <p class="font-medium text-gray-800">${student.name}</p>
                                    <p class="text-sm text-gray-500">${student.email}</p>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
                } else {
                    studentsContainer.innerHTML = `
                <div class="text-center py-12">
                    <i class="fa-solid fa-users text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No students have joined this section yet.</p>
                </div>
            `;
                }
            } catch (error) {
                console.error('Error loading students:', error);
                studentsContainer.innerHTML = `
            <p class="text-center py-8 text-red-500">
                Failed to load students.
            </p>
        `;
            }
        }

        // close modal
        document.getElementById('closeViewDetailsModal').addEventListener('click', () => {
            document.getElementById('manageViewSectionModal').classList.add('hidden');
        });

        // Close modal
        document.getElementById('closeManageModal').addEventListener('click', () => {
            document.getElementById('manageSectionModal').classList.add('hidden');
        });

        // Copy invite link functionality
        document.querySelectorAll('.copy-invite-link').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                const sectionId = e.currentTarget.dataset.sectionId;

                try {
                    const response = await fetch(`{{ url('/faculty/section') }}/${sectionId}/copy-invite-link`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Copy to clipboard
                        await navigator.clipboard.writeText(data.link);

                        // Show success toast
                        showToast('✅ Invite link copied to clipboard!', 'success');

                        // Close dropdown
                        document.querySelectorAll("[id^='menuDropdown-']").forEach(d => {
                            d.classList.add("hidden");
                        });
                    } else {
                        showToast('❌ Failed to copy link', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('❌ Failed to copy link', 'error');
                }
            });
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;

            // Set color based on type
            if (type === 'success') {
                toast.className = 'fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all';
            } else {
                toast.className = 'fixed top-5 right-5 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all';
            }

            // Show toast
            toast.classList.remove('hidden');

            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

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

@include('partials.modals.faculty.createSection')
@include('partials.modals.faculty.createActivity')
@include('partials.modals.faculty.seeAllStudents', ['users' => $students])
@include('partials.modals.faculty.addStudent')
@include('partials.modals.faculty.manage', ['section' => null])
{{-- @include('partials.modals.faculty.viewdetails', ['section' => $section]) --}}
@include('partials.modals.faculty.viewdetails')

{{-- <div class="flex flex-col text-right">
    <span class="text-sm mb-2">Simulation Role(s):</span>
    <div class="flex flex-wrap gap-1 justify-end">
        ${roles}
    </div>
</div> --}}
