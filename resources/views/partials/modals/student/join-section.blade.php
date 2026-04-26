<div id="openJoinSectionModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center overflow-auto p-4">
    <div class="bg-[#D8D8D8] p-6 rounded-xl shadow-lg w-full max-w-[542px] h-[383px] ">
        <div class="flex mb-6">
            <span class="text-[32px] font-normal">Join Section </span>
            <x-button type="button" id="closeModalBtn" variant="cancel">
                Back
            </x-button>
        </div>
        <div class="flex flex-col w-fit mx-auto">
            <x-input type="text" id="join_section" name="join_section" label="Join Section" placeholder="Please Enter Class Code" required/>
            <span id="error_message" class="text-red-600 text-sm mt-1 hidden"></span>
        </div>
        <div class="text-center mt-3">
            <p class="text-sm">Note: Ask your Instructor/Faculty In Charge for the Section Code </p>
        </div>
        <x-button
            type="button"
            id="join_sectionBtn"
            variant="btnGradientv1"
            class="flex flex-col mx-auto mt-9">
                Join
        </x-button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openJoinSection');
        const modal = document.getElementById('openJoinSectionModal');
        const closeBtn = document.getElementById('closeModalBtn');
        const joinBtn = document.getElementById('join_sectionBtn');
        const codeInput = document.getElementById('join_section');
        const errorMsg = document.getElementById('error_message');

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal
        closeBtn.addEventListener("click", () => {
            modal.classList.remove('flex');
            modal.classList.add("hidden");
            codeInput.value = '';
            errorMsg.classList.add('hidden');
        });

        // Close when clicking outside modal content
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
                codeInput.value = '';
                errorMsg.classList.add('hidden');
            }
        });

        // Join section handler
        joinBtn.addEventListener('click', () => {
            const code = codeInput.value.trim();

            // Validate input
            if (!code) {
                errorMsg.textContent = 'Please enter a section code';
                errorMsg.classList.remove('hidden');
                return;
            }

            // Hide error message
            errorMsg.classList.add('hidden');

            // Redirect to join route
            window.location.href = `/student/join/${code}`;
        });

        // Allow Enter key to submit
        codeInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                joinBtn.click();
            }
        });
    });
</script>
