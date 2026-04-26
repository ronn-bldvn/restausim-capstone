<div id="manageViewSectionModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-40 p-3">

    <!-- Modal box -->
    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-lg shadow-lg p-6 flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold">
                <span id="sectionName" class="font-semibold text-gray-800"></span>
            </h2>
            <x-button id="closeViewDetailsModal">Close</x-button>
        </div>

        <!-- Class Code -->
        <div class="flex items-center gap-2 mb-3">
            <span
                class="font-[Barlow] inline-flex items-center justify-center text-center rounded-full font-semibold text-sm px-3 py-1.5 bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993]"
                id="classCode">
            </span>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4 mb-3">
            <!-- Students -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-users text-gray-500 text-xl"></i>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" id="studentsCount">0</h3>
                        <p class="text-sm text-gray-500">Students</p>
                    </div>
                </div>
            </div>

            <!-- Assignments -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-clipboard-list text-gray-500 text-xl"></i>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" id="assignmentsCount">0</h3>
                        <p class="text-sm text-gray-500">Activities</p>
                    </div>
                </div>
            </div>

            <!-- Graded Activities -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex items-center">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-list-check text-gray-500 text-xl"></i>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" id="gradedCount">{{ $gradedCount ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-500">Graded Activities</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-gray-200 p-1 bg-gray-300 rounded-lg gap-2 sm:gap-0">
            <button onclick="switchTab('activity')" id="tab-activity"
                class="tab-button flex-1 p-2 text-sm font-medium transition rounded-lg bg-white text-blue-900">
                Activities
            </button>
            <button onclick="switchTab('students')" id="tab-students"
                class="tab-button flex-1 p-2 text-sm font-medium transition rounded-lg text-gray-600 hover:text-gray-800">
                Students
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto mt-3">
            <!-- Activities tab -->
            <div id="content-activity" class="tab-content mt-4">
                <div id="activityList" class="mt-3 mb-3 space-y-4"></div>

                <a href="{{ url('faculty/activity/' . $section->section_id) }}">
                    <x-button variant="manageSectionModal" id="seeAllActivityPerSection">
                        <i class="fa-solid fa-clipboard-list mr-2"></i>
                        View All Activities
                    </x-button>
                </a>
            </div>

            <!-- Students tab -->
            <div id="content-students" class="tab-content hidden">
                <div id="studentsList" class="mt-3 space-y-4"></div>
            </div>
        </div>

    </div>
</div>

<script>
    // Close modal
    document.getElementById('closeViewDetailsModal').addEventListener('click', () => {
        document.getElementById('manageViewSectionModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });

    function openModal() {
        document.getElementById('manageViewSectionModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function switchTab(tabName) {
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));

        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.classList.remove('bg-white', 'text-blue-900');
            tab.classList.add('text-gray-600', 'hover:text-gray-800');
        });

        document.getElementById('content-' + tabName).classList.remove('hidden');
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('bg-white', 'text-blue-900');
        activeTab.classList.remove('text-gray-600', 'hover:text-gray-800');
    }

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('manageViewSectionModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
</script>
