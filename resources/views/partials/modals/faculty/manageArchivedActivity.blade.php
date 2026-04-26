<div id="manageActivityDetails"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-40 p-4">
    <div class="bg-white w-full max-w-5xl max-h-[90vh] rounded-lg shadow-lg p-6 overflow-auto">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl md:text-2xl font-bold">
                <span>
                    Manage
                    <span id="activityName"></span>
                </span>
            </h2>
            <x-button id="closeManageModal">Close</x-button>
        </div>

        <div class="flex border-gray-200 p-1 mt-5 bg-gray-300 rounded-lg">
            <button onclick="switchTab('details')" id="tab-details"
                class="tab-button flex-1 p-2 text-sm font-medium transition rounded-lg bg-white text-blue-900">
                Activity Details
            </button>
            <button onclick="switchTab('setting')" id="tab-setting"
                class="tab-button flex-1 p-2 text-sm font-medium transition rounded-lg text-gray-600 hover:text-gray-800">
                Settings
            </button>
        </div>

        <div class="flex-1 overflow-y-auto mt-3">

            <!-- Activity Update tab -->
            <div id="content-details" class="tab-content w-full mt-4">
                <x-form id="updateActivityForm" action="#" data-original-due="">
                    @method('PUT')

                    <x-input
                        type="text"
                        name="activity_name"
                        label="Activity Name"
                        variant="manageSectionModal"
                        required
                    />

                    <x-textarea
                        label="Activity Description"
                        name="activity_description"
                        placeholder="Enter details here..."
                        rows="3"
                        required
                    />

                    
                    <!-- Hidden input for due_date -->
                    <input type="hidden" name="due_date" id="dueDateInputManage">

                    <x-button type="submit" variant="manageSectionModalNoCursor">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>
                        Update Activity Detail
                    </x-button>
                </x-form>
            </div>

            <!-- Settings tab -->
            <div id="content-setting" class="tab-content mt-4 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-medium text-red-600">Delete Course</h4>
                        <p class="mt-1 text-sm text-gray-500">Permanently delete this activity and all its submission
                        </p>
                    </div>
                    <x-button class="bg-red-600 text-white shadow-sm hover:bg-red-700 transition"
                        variant="manageSectionModalSettingNoCursor" id="openDeleteModal">
                        Delete
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Deletion</h2>
        <p class="text-gray-600 mb-6">
            Are you sure you want to delete this activity? This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-3">
            <button id="cancelDelete" type="button" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                Cancel
            </button>

            <!-- Add the actual delete form here -->
            <form id="deleteActivityForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Delete Activity
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const manageModal = document.getElementById('manageActivityDetails');
    const deleteModal = document.getElementById('deleteModal');
    const closeBtn = document.getElementById('closeManageModal');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const openDeleteBtn = document.getElementById('openDeleteModal');

    const deleteForm = document.getElementById('deleteActivityForm');
    const updateForm = document.getElementById('updateActivityForm');

    // Initialize default tab
    switchTab('details');

    // Open Manage Modal
    document.querySelectorAll('.openManageBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const activityId = btn.dataset.activityId;
            const sectionId = window.currentSectionId;

            const baseActivityUrl = @json(url('faculty/activity'));

            window.currentActivityId = activityId;
            window.currentSectionId = sectionId;

            const activities = @json($activities ?? []);
            const activity = activities.find(a => a.activity_id == activityId);

            // Fill modal fields (ONLY what you still have)
            const nameEl = manageModal.querySelector('#activityName');
            if (nameEl) nameEl.textContent = activity.name ?? '';

            const nameInput = manageModal.querySelector('[name="activity_name"]');
            if (nameInput) nameInput.value = activity.name ?? '';

            const descInput = manageModal.querySelector('[name="activity_description"]');
            if (descInput) descInput.value = activity.description ?? '';

            // Update and delete form actions
            updateForm.action = `${baseActivityUrl}/${sectionId}/${activityId}`;
            deleteForm.action = `${baseActivityUrl}/${sectionId}/${activityId}`;

            // Reset to details tab when opening
            switchTab('details');

            // Show modal
            manageModal.classList.remove('hidden');
            manageModal.classList.add('flex');
        });
    });

    // Handle update form submission (NO due date validation anymore)
    updateForm.addEventListener('submit', (e) => {
        // allow normal submit
    });

    // Open delete confirmation modal
    openDeleteBtn.addEventListener('click', () => {
        if (window.currentActivityId && window.currentSectionId) {
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        } else {
            console.error('No activity selected for deletion');
        }
    });

    // Close manage modal
    closeBtn.addEventListener('click', () => {
        manageModal.classList.add('hidden');
        manageModal.classList.remove('flex');
    });

    // Close delete modal
    cancelDeleteBtn.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
    });

    // Close modals when clicking outside
    manageModal.addEventListener('click', (e) => {
        if (e.target === manageModal) {
            manageModal.classList.add('hidden');
            manageModal.classList.remove('flex');
        }
    });

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        }
    });

    // Delete confirm
    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to delete this activity? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }

            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            manageModal.classList.add('hidden');
            manageModal.classList.remove('flex');
        });
    }
});

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
</script>
