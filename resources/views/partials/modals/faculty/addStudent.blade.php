<div id="openAddStudentModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center overflow-auto p-4">
    <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col relative w-full max-w-5xl max-h-[90vh] text-center">
        ILALAGAY PA BA TO??????????????????
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('AddStudentBtn');
        const modal = document.getElementById('openAddStudentModal');
        const closeBtn = document.getElementById('closeModalBtn');

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal
        closeBtn.addEventListener("click", () => {
            modal.classList.remove('flex');
            modal.classList.add("hidden");
        });

        // Close when clicking outside modal content
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");

            }
        });
    });
</script>
