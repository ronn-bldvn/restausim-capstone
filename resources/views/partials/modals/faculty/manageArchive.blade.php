<div id="manageSectionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-40 p-3">
    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-lg shadow-lg p-6 flex flex-col overflow-hidden">

        <x-alert type="success" :message="session('success')" />
        <x-alert type="error1" :message="session('error')" />

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold">
                Manage <span id="sectionName" class="font-semibold text-gray-800"></span>
            </h2>
            <x-button id="closeManageModal">Close</x-button>
        </div>

        <p id="manageSectionName" class="text-lg text-gray-600"></p>

        <div class="flex mt-3 border-gray-200 p-1 bg-gray-300 rounded-lg">
            <button onclick="switchTab('details')" id="tab-details"
                class="tab-button flex-1 p-2 text-sm font-medium transition rounded-lg bg-white text-blue-900">
                Section Details
            </button>
            <button onclick="switchTab('assignments')" id="tab-assignments"
                class="tab-button flex-1 p-2 text-sm rounded-lg font-medium transition text-gray-600 hover:text-gray-800">
                Activities
            </button>
            <button onclick="switchTab('settings')" id="tab-settings"
                class="tab-button flex-1 p-2 text-sm rounded-lg font-medium transition text-gray-600 hover:text-gray-800">
                Settings
            </button>
        </div>

        <!-- Content -->
        <div class="mt-3 max-h-[60vh] overflow-y-auto">

            <!-- Course Details Tab -->
            <div id="content-details" class="tab-content w-full">
                <x-form id="updateSectionForm" action="">
                    @method('PUT')
                    <x-input type="text" name="class_name" label="Class Name" variant="manageSectionModal" />
                    <x-input type="text" name="section_name" label="Section Name" variant="manageSectionModal" />
                    <x-input type="text" name="class_code" label="Class Code" variant="noCursor" readonly />
                    <x-input type="text" name="share_code" label="Share Code" variant="noCursor" readonly />
                    <x-button variant="manageSectionModal"><i class="fa-solid fa-floppy-disk mr-1"></i>
                        Update Section Details
                    </x-button>
                </x-form>
            </div>

            <!-- Assignments Tab -->
            <div id="content-assignments" class="tab-content hidden">
                <x-button variant="manageSectionModal" id="createActivityBtn">
                    <i class="fa-solid fa-plus mr-1"></i>
                    Add Assignment
                </x-button>
                <div id="assignmentsList" class="mt-3 space-y-4">
                    <!-- Assignments will be loaded here -->
                </div>
            </div>

            <!-- Settings Tab -->
            <div id="content-settings" class="tab-content hidden">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-8 space-y-8">

                    <h2 class="text-xl font-bold text-gray-900">Section Settings</h2>

                    <div class="flex items-center justify-between pb-6 border-b">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Copy Section Link</h4>
                            <p class="mt-1 text-sm text-gray-500">Copy this section link to share with your students</p>
                        </div>
                        <x-button class="bg-white text-gray-700 hover:bg-gray-50 copy-invite-link" data-section-id=""
                            variant="manageSectionModalSetting">
                            Copy Invite Link
                        </x-button>
                    </div>

                    <div class="flex items-center justify-between pb-6 border-b">

                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Invite Students</h4>
                            <p class="mt-1 text-sm text-gray-500">Invite Student via their email</p>
                        </div>

                        <x-button id="openInviteModalBtn"
                                data-section-id=""
                                class="bg-white text-gray-700 hover:bg-gray-50"
                                variant="manageSectionModalSetting">
                            Invite
                        </x-button>

                    </div>

                    <!-- Archive Course -->
                    <div class="flex items-center justify-between pb-6 border-b">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Archive Course</h4>
                            <p class="mt-1 text-sm text-gray-500">Move this course to archived courses</p>
                        </div>
                        <x-form id="archiveSectionForm" action="">
                            @method('PUT')
                            <x-button class="bg-white text-gray-700 hover:bg-gray-50"
                                variant="manageSectionModalSetting">
                                Archive
                            </x-button>
                        </x-form>
                    </div>

                    <!-- Delete Course -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium text-red-600">Delete Course</h4>
                            <p class="mt-1 text-sm text-gray-500">Permanently delete this course and all its data</p>
                        </div>
                        <x-button class="bg-red-600 text-white shadow-sm hover:bg-red-700 transition"
                            variant="manageSectionModalSetting" id="openDeleteModal">
                            Delete
                        </x-button>
                    </div>
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
            Are you sure you want to delete this section? This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-3">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                Cancel
            </button>
            <x-form id="deleteSectionForm" action="">
                @method('DELETE')
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Yes, Delete
                </button>
            </x-form>
        </div>
    </div>
</div>

<dialog id="inviteModal" class="modal">
    <form method="POST" id="inviteSectionForm" class="modal-box max-w-md p-6 rounded-2xl">
        @csrf

        <h2 class="text-xl font-bold mb-4">Invite Students</h2>

        <label class="block text-sm font-medium">Emails</label>
        <input name="emails" id="emailsInput"
            class="w-full border rounded-lg p-2 mt-1 mb-4"
            placeholder="Type or paste emails" required>

        <p class="text-sm text-gray-500 mb-4">
            You can add multiple emails. Paste lists or use suggestions.
        </p>

        <div class="flex justify-end gap-2">
            <button type="button"
                onclick="inviteModal.close()"
                class="px-4 py-2 rounded border">
                Cancel
            </button>

            <button class="px-4 py-2 rounded bg-blue-600 text-white">
                Send Invite
            </button>
        </div>
    </form>
</dialog>

<script>

    // Close modal
    document.getElementById('closeManageModal').addEventListener('click', () => {
        document.getElementById('manageSectionModal').classList.add('hidden');
    });


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
                    // document.querySelectorAll("[id^='menuDropdown-']").forEach(d => {
                    //     d.classList.add("hidden");
                    // });
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
            toast.className = 'fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transition-all';
        } else {
            toast.className = 'fixed top-5 right-5 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transition-all';
        }

        // Show toast
        toast.classList.remove('hidden');

        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function switchTab(tabName) {
        // Hide all tab contents
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));

        // Remove active class from all tabs
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.classList.remove('bg-white', 'text-blue-900', 'border-blue-900');
            tab.classList.add('text-gray-600', 'hover:text-gray-800');
        });

        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');

        // Add active class to selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('bg-white', 'text-blue-900', 'border-blue-900', 'rounded-lg', 'text-sm');
        activeTab.classList.remove('text-gray-600', 'hover:text-gray-800');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const openModal = document.getElementById('openDeleteModal');
        const modal = document.getElementById('deleteModal');
        const cancel = document.getElementById('cancelDelete');
        const confirm = document.getElementById('confirmDelete');
        const deleteForm = document.getElementById('deleteSectionForm');

        openModal.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        cancel.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        confirm.addEventListener('click', () => {
            modal.classList.add('hidden');
            deleteForm.submit();
        });
    });

function openManageSectionModal(section) {
    document.getElementById('sectionName').innerText = section.section_name;
    document.getElementById('manageSectionName').innerText = section.class_name;

    document.querySelector("#openInviteModalBtn").dataset.sectionId = section.section_id;

    document.getElementById("updateSectionForm").action =
        `/faculty/section/${section.section_id}`;

    document.getElementById("archiveSectionForm").action =
        `/faculty/section/${section.section_id}/archive`;

    document.getElementById("deleteSectionForm").action =
        `/faculty/section/${section.section_id}`;
}

document.addEventListener('DOMContentLoaded', () => {

    const suggestedEmails = @json($students->pluck('email')); // backend suggestions

    const input = document.getElementById('emailsInput');

    // Initialize Tagify
    const tagify = new Tagify(input, {
        delimiters: ", \n", // comma, space, newline
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, // simple email regex
        whitelist: suggestedEmails,
        dropdown: {
            enabled: 1,
            classname: "tags-look",
            maxItems: 10,
            position: "text",
            highlightFirst: true
        },
        editTags: 1,       // allow editing invalid tags
        duplicates: false  // prevent duplicate emails
    });

    // Handle paste: split multi-line or comma-separated emails
    tagify.on('paste', function(e) {
        let clipboard = e.clipboardData || window.clipboardData;
        let pasteContent = clipboard.getData('text');

        let emails = pasteContent.split(/[\s,]+/).filter(email => email.length > 0);

        tagify.addTags(emails);  // add each email individually
        e.preventDefault();
    });

    // Optional: highlight invalid emails
    tagify.on('invalid', function(e){
        console.log('Invalid email:', e.detail.data.value);
    });

    // Open modal and handle form submission
    document.querySelectorAll('#openInviteModalBtn').forEach(button => {
        button.addEventListener('click', () => {
            const sectionId = button.getAttribute('data-section-id');
            if (!sectionId) return console.error("No section_id found.");

            const form = document.getElementById('inviteSectionForm');
            form.action = `{{ url('/faculty/section') }}/${sectionId}/invite`;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Only valid tags
                const validTags = tagify.value
                    .map(item => item.value.trim())
                    .filter(email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email));

                if (validTags.length === 0) {
                    alert('Please enter at least one valid email.');
                    return;
                }

                // Remove duplicates
                const uniqueEmails = [...new Set(validTags)];

                // Hidden input for JSON
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'emails';
                hiddenInput.value = JSON.stringify(uniqueEmails);
                form.appendChild(hiddenInput);

                form.submit();
            }, { once: true });

            inviteModal.showModal();
        });
    });
});


</script>
