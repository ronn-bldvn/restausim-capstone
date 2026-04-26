<div id="createActivityModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-3">
    <div class="bg-[#D8D8D8] w-full max-w-[542px] max-h-[90vh] h-auto rounded-xl shadow-lg
            p-4 md:p-6 overflow-y-auto">

        <x-form method="post" action="{{ route('activities.store') }}" id="createActivityForm">
            <div class="flex mb-4">
                <span class="text-[32px] font-normal"> Create Activity </span>
                <x-button type="button" id="closeActivitylBtn" variant="cancel">Cancel</x-button>
            </div>

            <div class="flex flex-col mt-9">
                <div class="flex flex-col">
                    <x-input type="text" name="name" id="activity_name" label="Activity Name"
                        placeholder="Please Enter Activity Name" labelVariant="calendar" required />
                    <x-textarea label="Activity Description" name="description"
                        placeholder="Enter details here..." rows="3" required/>
                </div>

                <div class="flex flex-col mt-2">
                    <label for="activity_role" class="text-sm font-poppins font-medium text-gray-700 mb-2">
                        Select Role for this Activity
                    </label>
                    <select name="role" id="activity_role" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ease-in-out duration-150 cursor-pointer"
                        required>
                        <option value="" disabled selected>Choose a role...</option>
                        <option value="5">Manager</option>
                        <option value="1">Cashier</option>
                        <option value="3">Kitchen Staff</option>
                        <option value="2">Waiter</option>
                        <option value="4">Front Desk</option>
                    </select>
                </div>

                {{-- Warning message (hidden by default) --}}
                <p id="dueDateWarning" class="text-red-500 text-sm mt-2 hidden">
                    Please select a due date or check "No due date" before creating the activity.
                </p>

                {{-- Hidden inputs --}}
                <input type="hidden" name="due_date" id="dueDateInput">
                <input type="hidden" name="code" id="codeInput">
                <input type="hidden" name="section_id" id="section_id">

                {{-- Submit Button --}}
                <div class="flex flex-col items-center mt-5">
                    <x-button id="SaveActivityBtn" variant="btnGradientv1">Create Activity</x-button>
                </div>
            </div>
        </x-form>
    </div>
</div>

<!-- Calendar / Time Picker -->
<x-date-time-picker />

<script>

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('datetime-modal');
    const openBtn = document.getElementById('selectDueDateBtn');
    const closeBtn = document.getElementById('closeDateTimeModal');
    const setDueDateBtn = document.getElementById('setDueDateBtn');
    const createActivityModal = document.getElementById('createActivityModal');
    const createActivityBtn = document.getElementById('createActivityBtn');
    const closeActivityBtn = document.getElementById('closeActivitylBtn');
    const form = document.getElementById('createActivityForm');
    const dueDateInput = document.getElementById('dueDateInput');
    const warning = document.getElementById('dueDateWarning');
    const noDueDateCheckbox = document.getElementById('noDueDateCheckbox');
    const selectedDueDateSpan = document.getElementById('selectedDueDate');
    const saveActivityBtn = document.getElementById('SaveActivityBtn');

    // Open create activity modal
    if (createActivityBtn) {
        createActivityBtn.addEventListener('click', () => {
            if (!currentSectionId) {
                alert("No section selected.");
                return;
            }

            const sectionInput = createActivityModal.querySelector('input[name="section_id"]');
            sectionInput.value = currentSectionId;

            createActivityModal.classList.remove('hidden');
            createActivityModal.classList.add('flex');

            console.log("Opening Create Activity for section:", currentSectionId);
        });
    }

    // Close modal
    if (closeActivityBtn) {
        closeActivityBtn.addEventListener('click', () => {
            createActivityModal.classList.add('hidden');
            createActivityModal.classList.remove('flex');
            form.reset();

            if (selectedDueDateSpan) selectedDueDateSpan.textContent = '';
            if (dueDateInput) dueDateInput.value = '';
            if (noDueDateCheckbox) noDueDateCheckbox.checked = false;
            if (warning) warning.classList.add('hidden');

            if (openBtn) {
                openBtn.disabled = false;
                openBtn.style.opacity = '1';
                openBtn.style.cursor = 'pointer';
            }

            // Reset save button state
            if (saveActivityBtn) {
                saveActivityBtn.disabled = false;
                saveActivityBtn.innerHTML = 'Create Activity';
                saveActivityBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }

            // Clear all field errors on close
            document.querySelectorAll('.field-error').forEach(el => el.remove());
            ['activity_name', 'activity_role'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            });
            const textarea = document.querySelector('textarea[name="description"]');
            if (textarea) textarea.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        });
    }

    // Open date-time modal
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }

    // Close date-time modal
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    // Handle "No Due Date" checkbox
    if (noDueDateCheckbox) {
        noDueDateCheckbox.addEventListener('change', () => {
            if (warning) warning.classList.add('hidden');

            if (noDueDateCheckbox.checked) {
                if (dueDateInput) dueDateInput.value = '';
                if (selectedDueDateSpan) selectedDueDateSpan.textContent = 'No due date';
                if (openBtn) {
                    openBtn.disabled = true;
                    openBtn.style.opacity = '0.5';
                    openBtn.style.cursor = 'not-allowed';
                }
            } else {
                if (selectedDueDateSpan) selectedDueDateSpan.textContent = '';
                if (openBtn) {
                    openBtn.disabled = false;
                    openBtn.style.opacity = '1';
                    openBtn.style.cursor = 'pointer';
                }
            }
        });
    }

    // AM/PM toggle
    document.addEventListener('click', (e) => {
        if (e.target.id === 'amPm') {
            e.preventDefault();
            const current = e.target.textContent.trim().toUpperCase();
            e.target.textContent = current === 'PM' ? 'AM' : 'PM';
        }
    });

    // Save selected datetime
    if (setDueDateBtn) {
        setDueDateBtn.addEventListener('click', () => {
            const dateText = document.getElementById('selected-date').value;
            const originalDate = document.getElementById('selected-date').getAttribute('data-original-date');
            const hour = document.getElementById('hourValue').value.padStart(2, '0');
            const minute = document.getElementById('minuteValue').value.padStart(2, '0');
            const ampm = document.getElementById('amPm').textContent.trim().toUpperCase();

            if (dateText && originalDate) {
                const parsedDate = new Date(originalDate);
                let hour24 = parseInt(hour, 10);
                if (ampm === 'PM' && hour24 < 12) hour24 += 12;
                if (ampm === 'AM' && hour24 === 12) hour24 = 0;

                parsedDate.setHours(hour24);
                parsedDate.setMinutes(parseInt(minute, 10));
                parsedDate.setSeconds(0);

                const mysqlDateTime =
                    `${parsedDate.getFullYear()}-${String(parsedDate.getMonth() + 1).padStart(2, '0')}-${String(parsedDate.getDate()).padStart(2, '0')} ${String(parsedDate.getHours()).padStart(2, '0')}:${String(parsedDate.getMinutes()).padStart(2, '0')}:${String(parsedDate.getSeconds()).padStart(2, '0')}`;

                if (selectedDueDateSpan) {
                    selectedDueDateSpan.textContent = parsedDate.toLocaleString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                }

                if (dueDateInput) dueDateInput.value = mysqlDateTime;
                if (noDueDateCheckbox) noDueDateCheckbox.checked = false;
                if (warning) warning.classList.add('hidden');
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    // Submit with validation + loading state
    if (saveActivityBtn) {
        saveActivityBtn.addEventListener('click', (e) => {
            e.preventDefault();

            // --- Field Validation ---
            let hasError = false;

            const activityName = document.getElementById('activity_name');
            const activityDescription = document.querySelector('textarea[name="description"]');
            const activityRole = document.getElementById('activity_role');

            // Reset previous errors
            [activityName, activityDescription, activityRole].forEach(el => {
                if (el) {
                    el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    const prev = el.parentElement.querySelector('.field-error');
                    if (prev) prev.remove();
                }
            });

            // Validate Name
            if (!activityName || activityName.value.trim() === '') {
                hasError = true;
                activityName.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const msg = document.createElement('p');
                msg.className = 'field-error text-red-500 text-xs mt-1';
                msg.textContent = 'Activity name is required.';
                activityName.parentElement.appendChild(msg);
            }

            // Validate Description
            if (!activityDescription || activityDescription.value.trim() === '') {
                hasError = true;
                activityDescription.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const msg = document.createElement('p');
                msg.className = 'field-error text-red-500 text-xs mt-1';
                msg.textContent = 'Activity description is required.';
                activityDescription.parentElement.appendChild(msg);
            }

            // Validate Role
            if (!activityRole || activityRole.value === '') {
                hasError = true;
                activityRole.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const msg = document.createElement('p');
                msg.className = 'field-error text-red-500 text-xs mt-1';
                msg.textContent = 'Please select a role.';
                activityRole.parentElement.appendChild(msg);
            }

            if (hasError) return;

            // --- Due Date Validation ---
            const hasDate = dueDateInput ? dueDateInput.value !== '' : true;
            const hasNoDateChecked = noDueDateCheckbox ? noDueDateCheckbox.checked : true;

            if (!hasDate && !hasNoDateChecked) {
                if (warning) warning.classList.remove('hidden');
                return;
            }

            if (warning) warning.classList.add('hidden');

            if (noDueDateCheckbox && noDueDateCheckbox.checked && dueDateInput) {
                dueDateInput.value = '';
            }

            // Disable button and show loading state
            saveActivityBtn.disabled = true;
            saveActivityBtn.innerHTML = `
                <svg class="animate-spin inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Creating Activity...
            `;
            saveActivityBtn.classList.add('opacity-70', 'cursor-not-allowed');

            setTimeout(() => {
                form.submit();
            }, 100);
        });
    }

    // Clear field errors on input
    ['activity_name', 'activity_role'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => {
                el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                const msg = el.parentElement.querySelector('.field-error');
                if (msg) msg.remove();
            });
            el.addEventListener('change', () => {
                el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                const msg = el.parentElement.querySelector('.field-error');
                if (msg) msg.remove();
            });
        }
    });

    const textarea = document.querySelector('textarea[name="description"]');
    if (textarea) {
        textarea.addEventListener('input', () => {
            textarea.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            const msg = textarea.parentElement.querySelector('.field-error');
            if (msg) msg.remove();
        });
    }
});
</script>