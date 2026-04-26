<div id="createAnnounceModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div
        class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-6
                max-h-[90vh] overflow-y-auto
                sm:p-8 sm:rounded-xl sm:max-w-2xl">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl sm:text-2xl">Post Announcement</h2>
            <x-button id="closeManageModal">Close</x-button>
        </div>

        <x-form method="POST" action="{{ route('announce.store') }}" enctype="multipart/form-data">
            <div class="border rounded-lg p-2">
                <input type="hidden" name="section_id" value="{{ $section->section_id }}">

                <x-textarea name="announcements" placeholder="Announce something..." rows="4"
                    variant="announceCreate" />

                <div id="attachment-preview" class="flex flex-wrap gap-2 px-2 pb-2"></div>

                <div class="flex items-center justify-between border-t pt-2 mt-2">
                    <div class="flex gap-4 px-2">
                        <button type="button" onclick="addAttachment('gdrive')"
                            class="text-gray-500 hover:text-blue-600" title="Add Google Drive file">
                            <i class="fab fa-google-drive"></i>
                        </button>

                        <button type="button" onclick="addAttachment('youtube')"
                            class="text-gray-500 hover:text-red-600" title="Add YouTube video">
                            <i class="fab fa-youtube"></i>
                        </button>
                        <label class="cursor-pointer text-gray-500 hover:text-green-600">
                            <input type="file" name="files[]" multiple class="hidden" onchange="previewFiles(this)">
                            <i class="fas fa-upload"></i>
                        </label>
                        <button type="button" onclick="addAttachment('link')" class="text-gray-500 hover:text-gray-900"
                            title="Add Link">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>

                    <x-button variant="manageSectionModal">Post</x-button>
                </div>
            </div>
        </x-form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('createAnnounceModal');
        const openModal = document.getElementById('createAnnounceBtn'); // buttons that open modal
        const closeBtn = document.getElementById('closeManageModal');

        openModal.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal (Close button)
        closeBtn.addEventListener('click', () => {
            closeModal();
        });

        // Close modal (Click outside)
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Function to reset visibility
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    function addAttachment(type) {
        const url = prompt(`Enter the ${type} URL:`);
        if (url) {
            const container = document.getElementById('attachment-preview');
            const id = Date.now();

            // Use single quotes for the value attribute: value='JSON_HERE'
            const html = `
                <div class="flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm border" id="attach-${id}">
                    <span class="mr-2 text-xs font-bold uppercase">${type}</span>
                    <span class="truncate max-w-[150px]">${url}</span>
                    <input type="hidden" name="attachments[]" value='${JSON.stringify({type: type, url: url})}'>
                    <button type="button" onclick="document.getElementById('attach-${id}').remove()" class="ml-2 text-red-500">&times;</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    }

    function previewFiles(input) {
        const container = document.getElementById('attachment-preview');

        Array.from(input.files).forEach(file => {
            const id = Date.now() + Math.random();

            const html = `
            <div class="flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm border" id="file-${id}">
                <span class="mr-2 text-xs font-bold uppercase">FILE</span>
                <span class="truncate max-w-[150px]">${file.name}</span>
                <button type="button"
                        onclick="document.getElementById('file-${id}').remove()"
                        class="ml-2 text-red-500">&times;</button>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', html);
        });
    }
</script>
