<div id="manageStudentSubmissions" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-40 p-4">
    <div class="bg-white w-full md:max-w-4xl rounded-lg shadow-lg p-4 md:p-6 max-h-[85vh] md:max-h-[90vh] flex flex-col">

        <div class="flex items-center justify-between mb-4 flex-shrink-0">
            <h2 class="text-xl md:text-2xl font-bold">
                Submission in <span class="activity-title"></span>
            </h2>
            <button id="closeModalBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition">Close</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6 flex-shrink-0">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 p-2 md:p-3 rounded-full">
                        <i class="fa-solid fa-users text-blue-600 text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900" id="modalSubmittedCount">0/0</h3>
                        <p class="text-xs md:text-sm text-gray-500">Students Submitted</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 p-2 md:p-3 rounded-full">
                        <i class="fa-solid fa-clipboard-check text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900" id="modalGradedCount">0/0</h3>
                        <p class="text-xs md:text-sm text-gray-500">Graded Activity</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-100 p-2 md:p-3 rounded-full">
                        <i class="fa-solid fa-clock text-yellow-600 text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900" id="modalPendingCount">0</h3>
                        <p class="text-xs md:text-sm text-gray-500">Pending Review</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex border-gray-200 p-1 bg-gray-100 rounded-lg mb-4 flex-shrink-0">
            <button onclick="switchTab('assigned')" id="tab-assigned"
                class="tab-button flex-1 p-2 text-xs md:text-sm font-medium transition rounded-lg bg-white text-blue-900 shadow-sm">
                Assigned <span id="badge-assigned" class="ml-1 md:ml-2 text-xs bg-gray-200 px-2 py-0.5 rounded-full">0</span>
            </button>
            <button onclick="switchTab('turnedin')" id="tab-turnedin"
                class="tab-button flex-1 p-2 text-xs md:text-sm font-medium transition rounded-lg text-gray-600 hover:text-gray-800">
                Turned In <span id="badge-turnedin" class="ml-1 md:ml-2 text-xs bg-gray-200 px-2 py-0.5 rounded-full">0</span>
            </button>
            <button onclick="switchTab('graded')" id="tab-graded"
                class="tab-button flex-1 p-2 text-xs md:text-sm font-medium transition rounded-lg text-gray-600 hover:text-gray-800">
                Graded <span id="badge-graded" class="ml-1 md:ml-2 text-xs bg-gray-200 px-2 py-0.5 rounded-full">0</span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto min-h-0 -mx-1 px-1">
            <div id="content-assigned" class="tab-content">
                <div id="list-assigned" class="space-y-3">
                </div>
            </div>

            <div id="content-turnedin" class="tab-content hidden">
                <div id="list-turnedin" class="space-y-3">
                </div>
            </div>

            <div id="content-graded" class="tab-content hidden">
                <div id="list-graded" class="space-y-3">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-content {
        display: none;
    }
    .tab-content:not(.hidden) {
        display: block;
    }

    /* Ensure proper scrolling on mobile */
    #manageStudentSubmissions .flex-1 {
        -webkit-overflow-scrolling: touch;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('manageStudentSubmissions');
        const closeBtn = document.getElementById('closeModalBtn');
        const titleSpan = document.querySelector('#manageStudentSubmissions .activity-title');

        // Open Modal Logic
        document.querySelectorAll('.openSubmissionBtn').forEach(btn => {
            btn.addEventListener("click", async () => {
                let activityName = btn.dataset.activityName;
                let activityId = btn.dataset.activityId;

                // 1. Setup Modal
                titleSpan.textContent = activityName;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                switchTab('assigned'); // Default to assigned

                // 2. Show Loading State
                setLoadingState();

                // 3. Fetch Data
                await fetchSubmissionData(activityId);
            });
        });

        // Close Logic
        closeBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        });
    });

    async function fetchSubmissionData(activityId) {
        try {
            const response = await fetch(`/room/public/faculty/activity/${activityId}/submissions`);
            const data = await response.json();

            if (data.success) {
                // Update Cards
                document.getElementById('modalSubmittedCount').textContent = `${data.counts.submitted}/${data.counts.total_students}`;
                document.getElementById('modalGradedCount').textContent = `${data.counts.graded}/${data.counts.total_students}`;
                document.getElementById('modalPendingCount').textContent = data.counts.pending;

                // Update Tab Badges
                document.getElementById('badge-assigned').textContent = data.lists.assigned.length;
                document.getElementById('badge-turnedin').textContent = data.lists.turnedin.length;
                document.getElementById('badge-graded').textContent = data.lists.graded.length;

                // Render Lists
                renderList('list-assigned', data.lists.assigned, 'assigned');
                renderList('list-turnedin', data.lists.turnedin, 'turnedin');
                renderList('list-graded', data.lists.graded, 'graded');
            }
        } catch (error) {
            console.error('Error fetching submissions:', error);
            alert('Failed to load submission data.');
        }
    }

    function renderList(elementId, students, type) {
        const container = document.getElementById(elementId);

        if (students.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">No students found in this category.</p>
                </div>`;
            return;
        }

        container.innerHTML = students.map(student => {
            let statusBadge = '';
            let actionBtn = '';

            if (type === 'assigned') {
                statusBadge = `<span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">Not Submitted</span>`;
            } else if (type === 'turnedin') {
                statusBadge = `<span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Needs Grading</span>`;
            } else if (type === 'graded') {
                statusBadge = `<span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Score: ${student.score}</span>`;
            }

            return `
                <div class="flex items-center justify-between bg-white p-3 md:p-4 rounded-xl border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm md:text-base flex-shrink-0">
                            ${student.name.charAt(0)}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-gray-800 text-sm md:text-base truncate">${student.name}</h4>
                            <p class="text-xs text-gray-500 truncate">${student.email}</p>
                            ${student.submitted_at ? `<p class="text-xs text-gray-400 mt-1">Submitted: ${student.submitted_at}</p>` : ''}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0 ml-2">
                        ${statusBadge}
                        ${actionBtn}
                    </div>
                </div>
            `;
        }).join('');
    }

    function setLoadingState() {
        const loadingHtml = '<div class="p-10 text-center text-gray-500"><i class="fa-solid fa-spinner fa-spin text-2xl"></i><p class="mt-2 text-sm">Loading...</p></div>';
        document.getElementById('list-assigned').innerHTML = loadingHtml;
        document.getElementById('list-turnedin').innerHTML = loadingHtml;
        document.getElementById('list-graded').innerHTML = loadingHtml;
        document.getElementById('modalSubmittedCount').textContent = '...';
        document.getElementById('modalGradedCount').textContent = '...';
        document.getElementById('modalPendingCount').textContent = '...';
    }

    function switchTab(tabName) {
        // Hide all tab contents
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));

        // Reset all tab buttons
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.classList.remove('bg-white', 'text-blue-900', 'shadow-sm');
            tab.classList.add('text-gray-600', 'hover:text-gray-800');
        });

        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');

        // Highlight selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('bg-white', 'text-blue-900', 'shadow-sm');
        activeTab.classList.remove('text-gray-600', 'hover:text-gray-800');
    }
</script>
