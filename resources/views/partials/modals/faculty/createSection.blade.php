    <x-form id="createSectionForm" method="POST" action="{{ route('faculty.section.store') }}">

        <div id="createSectionModal" class="fixed inset-0 bg-black/50 items-center justify-center hidden z-50 p-3">
            <div class="bg-[#D8D8D8] p-6 rounded-xl shadow-lg w-full max-w-[542px] h-auto overflow-auto">
                <div class="flex mb-6">
                    <span class="text-[32px] font-normal">Create Section</span>
                    <x-button type="button" id="closeModalBtn" variant="cancel">Back</x-button>
                </div>

                <div class="flex flex-col">
                    <x-input
                    type="text"
                    id="section_name"
                    name="section_name"
                    label="Section Name"
                    placeholder="e.g. BSHM, BSTM"
                    required
                />

                <x-input
                    type="text"
                    id="class_code"
                    name="class_code"
                    label="Class Code (Auto-generated)"
                    placeholder="Auto-generated"
                    variant="noCursorSection"
                    readonly
                />

                <x-input
                    type="text"
                    id="class_name"
                    name="class_name"
                    label="Class Name"
                    placeholder="Please Enter Class Name"
                    required
                />

                </div>

                <div class="flex flex-col w-fit mx-auto mt-2">
                    <x-button type="submit" variant="btnGradientv1">Create Section</x-button>
                </div>
            </div>
        </div>
    </x-form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openCreateSection');
        const modal = document.getElementById('createSectionModal');
        const closeBtn = document.getElementById('closeModalBtn');
        const form = document.getElementById('createSectionForm');
        const sectionNameInput = document.getElementById('section_name');
        const classCodeInput = document.getElementById('class_code');
        const classNameInput = document.getElementById('class_name');

        // Auto-fill class code based on section name
        sectionNameInput.addEventListener('input', () => {
            const val = sectionNameInput.value.trim().toUpperCase();

            if (val === 'BSHM') {
                classCodeInput.value = 'HM 1135';
                setError(sectionNameInput, '');
            } else if (val === 'BSTM') {
                classCodeInput.value = 'TM 1135';
                setError(sectionNameInput, '');
            } else {
                classCodeInput.value = '';
                if (val === '') {
                    setError(sectionNameInput, 'Section name is required.');
                } else {
                    setError(sectionNameInput, 'Only BSHM or BSTM is allowed.');
                }
            }
        });

        classNameInput.addEventListener('input', () => {
            if (classNameInput.value.trim() === '') {
                setError(classNameInput, 'Class name is required.');
            } else {
                setError(classNameInput, '');
            }
        });

        // Form submission validation
        form.addEventListener('submit', (e) => {
            let isValid = true;
            const sectionVal = sectionNameInput.value.trim().toUpperCase();
            const classNameVal = classNameInput.value.trim();

            if (sectionVal === '') {
                setError(sectionNameInput, 'Section name is required.');
                isValid = false;
            } else if (sectionVal !== 'BSHM' && sectionVal !== 'BSTM') {
                setError(sectionNameInput, 'Only BSHM or BSTM is allowed.');
                isValid = false;
            }

            if (classNameVal === '') {
                setError(classNameInput, 'Class name is required.');
                isValid = false;
            }

            if (!isValid) e.preventDefault();
        });

        // Set or clear error message below an input
        function setError(input, message) {
            let errorEl = input.parentElement.querySelector('.field-error');
            if (!errorEl) {
                errorEl = document.createElement('p');
                errorEl.classList.add('field-error', 'text-red-500', 'text-xs', 'mt-1');
                input.parentElement.appendChild(errorEl);
            }
            errorEl.textContent = message;
        }

        function resetModal() {
            sectionNameInput.value = '';
            classCodeInput.value = '';
            classNameInput.value = '';
            document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        }

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            resetModal();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                resetModal();
            }
        });
    });
</script>
