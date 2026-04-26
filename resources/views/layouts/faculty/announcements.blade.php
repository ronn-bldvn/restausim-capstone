@php

$role = auth()->user()->role;

$studentsLink = url(
    'faculty/student/' .
    $section->section_id
);

@endphp

<x-layouts :title="$section->class_name">
    <div class="flex-1">
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error1" :message="session('error')" />

        <div class="rounded-xl h-full shadow-sm">
            <script>
                window.currentSectionId = {{ $section->section_id }};
            </script>

            <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                <!-- Activities -->
                <a href="{{ auth()->user()->role === 'student'
                        ? url('student/activity/' . $section->section_id)
                        : url('faculty/activity/' . $section->section_id) }}"
                        class="{{ request()->is('faculty/activity*') || request()->is('student/activity*')
                            ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                            : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Activities
                </a>

                <!-- Announcements -->
                <a href="{{ url('faculty/announcements/' . $section->section_id) }}"
                    class="{{ request()->is('faculty/announcements*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Announcements
                </a>

                @if ($role === 'faculty' && isset($section) && $section->is_archived && (!isset($activity) || !$activity->activity_id))
                    {{-- Disabled state when no activity is available --}}
                    <span class="text-gray-400 font-medium px-4 py-4 cursor-not-allowed" title="Select an activity to view students">
                        Students
                    </span>
                @else
                    <a href="{{ $studentsLink }}"
                    class="{{ request()->is('faculty/student*')
                        || request()->is('student/student*')
                        || request()->is('faculty/archived/student/*')
                        || request()->is('student/archived/student/*')
                                ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                                : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Students
                    </a>
                @endif
                
                <a href="{{ route('faculty.simulation.all', $section->section_id) }}"
                   class="{{ request()->is('faculty/simulation/all-submissions*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Student Works
                </a>

            </div>

            <x-section :section="$section" variant="announce" :activity="null" />

            {{-- Announcements List --}}
            <div class="px-5 py-6 min-h-[500px] bg-gray-50">
                @forelse ($announcements as $announcement)
                    <div class="border border-gray-200 rounded-xl p-5 mb-6 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/profile_images/' . $announcement->user?->profile_image) }}"
                                     alt="profile" class="w-11 h-11 rounded-full object-cover border border-gray-100">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $announcement->user?->name ?? 'Unknown Faculty' }}</p>
                                    <p class="text-xs text-gray-500">{{ $announcement->created_at->timezone('Asia/Manila')->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if(auth()->user()->id === $announcement->user_id)
                                <div class="flex items-center gap-2">
                                    {{-- FIXED: Added data-attachments attribute --}}
                                    <button class="editAnnouncementBtn p-2 text-gray-400 hover:text-blue-600 transition"
                                        data-id="{{ $announcement->id }}"
                                        data-content="{{ $announcement->content }}"
                                        data-attachments='@json($announcement->attachments)'
                                        data-route="{{ route('announce.update', $announcement->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button class="deleteAnnouncementBtn p-2 text-gray-400 hover:text-red-600 transition"
                                        data-route="{{ route('announce.destroy', $announcement->id) }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="text-[15px] text-gray-700 leading-relaxed mb-5 px-1">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>

                        {{-- Attachment Display --}}
                        @if($announcement->attachments->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($announcement->attachments as $attachment)
                                    @php
                                        $isYoutube = $attachment->type === 'youtube';
                                        $ytId = null;
                                        if ($isYoutube) {
                                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attachment->url, $match);
                                            $ytId = $match[1] ?? null;
                                        }
                                        $displayName = $attachment->title ?: (parse_url($attachment->url, PHP_URL_HOST) ?: 'Link');
                                    @endphp

                                    <a href="{{ $attachment->type === 'file' ? asset('storage/' . $attachment->url) : $attachment->url }}"
                                       target="_blank"
                                       class="group flex items-center border border-gray-200 rounded-lg overflow-hidden hover:bg-gray-50 transition-all bg-white shadow-sm h-20">
                                        <div class="w-28 h-full bg-gray-100 flex items-center justify-center flex-shrink-0 border-r relative overflow-hidden">
                                            @if($isYoutube && $ytId)
                                                <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                                    <i class="fab fa-youtube text-white text-xl drop-shadow"></i>
                                                </div>
                                            @elseif($attachment->type === 'link')
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-link text-blue-500 text-lg"></i>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase mt-1">Link</span>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center">
                                                    <i class="far fa-file-alt text-gray-400 text-xl"></i>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase mt-1">File</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="px-4 py-2 truncate flex-1 min-w-0">
                                            <p class="text-[13px] font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors">
                                                {{ $displayName }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                                {{ $attachment->type === 'file' ? strtoupper(pathinfo($attachment->title, PATHINFO_EXTENSION)) : $attachment->url }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20">
                        {{-- <i class="fa-solid fa-bullhorn text-gray-200 text-6xl mb-4"></i> --}}
                        <p class="text-gray-500 text-lg">No announcements available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-3">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 relative max-h-[90vh] overflow-y-auto">
            <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" onclick="closeEditModal()">✕</button>
            <h2 class="text-xl font-bold mb-4">Edit Announcement</h2>

            <x-form id="editAnnouncementForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-textarea label="Activity Description" name="content" id="editAnnouncementContent" placeholder="Enter details here..." rows="5"/>

                <div id="edit-attachment-preview" class="flex flex-wrap gap-2 px-2 pb-2 mt-2">
                    {{-- Preloaded via JS --}}
                </div>

                <div class="flex items-center justify-between border-t pt-2 mt-2">
                    <div class="flex gap-4 px-2">
                        <button type="button" onclick="editAddAttachment('gdrive')" class="text-gray-500 hover:text-blue-600"><i class="fab fa-google-drive"></i></button>
                        <button type="button" onclick="editAddAttachment('youtube')" class="text-gray-500 hover:text-red-600"><i class="fab fa-youtube"></i></button>
                        <label class="cursor-pointer text-gray-500 hover:text-green-600">
                            <input type="file" name="files[]" multiple class="hidden" onchange="editPreviewFiles(this)">
                            <i class="fas fa-upload"></i>
                        </label>
                        <button type="button" onclick="editAddAttachment('link')" class="text-gray-500 hover:text-gray-900"><i class="fas fa-link"></i></button>
                    </div>
                    <x-button type="submit" variant="btnGradient">Update</x-button>
                </div>
            </x-form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-3">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" onclick="closeDeleteModal()">✕</button>
            <h2 class="text-xl font-bold mb-4">Delete Announcement</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this announcement?</p>
            <x-form id="deleteAnnouncementForm" action="">
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <x-button type="button" variant="btnNoGradient" onclick="closeDeleteModal()">Cancel</x-button>
                    <x-button type="submit" variant="btnGradient">Delete</x-button>
                </div>
            </x-form>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editAnnouncementModal');
        const deleteModal = document.getElementById('deleteAnnouncementModal');
        const previewContainer = document.getElementById('edit-attachment-preview');

        // Logic to open Edit Modal and Load Data
        document.querySelectorAll('.editAnnouncementBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Set Form Action & Content
                document.getElementById('editAnnouncementForm').action = btn.dataset.route;
                document.getElementById('editAnnouncementContent').value = btn.dataset.content;

                // Load Existing Attachments
                const attachments = btn.dataset.attachments ? JSON.parse(btn.dataset.attachments) : [];
                preloadAttachments(attachments);

                editModal.classList.remove('hidden');
            });
        });

        // Logic to open Delete Modal
        document.querySelectorAll('.deleteAnnouncementBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('deleteAnnouncementForm').action = btn.dataset.route;
                deleteModal.classList.remove('hidden');
            });
        });

        function preloadAttachments(attachments) {
            previewContainer.innerHTML = '';
            attachments.forEach(att => {
                addPillToPreview(att.type, att.title || att.url, att, true);
            });
        }

        function editAddAttachment(type) {
            const url = prompt(`Enter the ${type} URL:`);
            if (url) addPillToPreview(type, url, {type: type, url: url}, false);
        }

        function editPreviewFiles(input) {
            Array.from(input.files).forEach(file => {
                addPillToPreview('file', file.name, null, false);
            });
        }

        function addPillToPreview(type, label, dataObj, isExisting) {
            const id = Math.random().toString(36).substr(2, 9);
            const inputName = isExisting ? 'existing_attachments[]' : 'attachments[]';
            const value = dataObj ? JSON.stringify(dataObj) : '';

            const html = `
                <div class="flex items-center bg-gray-100 border border-gray-300 rounded-full px-3 py-1 text-xs" id="pill-${id}">
                    <span class="font-bold uppercase mr-2 text-gray-500">${type}</span>
                    <span class="truncate max-w-[120px] mr-2">${label}</span>
                    ${value ? `<input type="hidden" name="${inputName}" value='${value}'>` : ''}
                    <button type="button" onclick="document.getElementById('pill-${id}').remove()" class="text-red-500 font-bold">&times;</button>
                </div>
            `;
            previewContainer.insertAdjacentHTML('beforeend', html);
        }

        function closeEditModal() { editModal.classList.add('hidden'); }
        function closeDeleteModal() { deleteModal.classList.add('hidden'); }


        const createAnnounceBtn = document.getElementById('createAnnounceBtn');
        const createModal = document.getElementById('createAnnouncementModal');

        if (createAnnounceBtn) {
            createAnnounceBtn.addEventListener('click', () => {
                // Show your create modal
                document.getElementById('createAnnouncementModal').classList.remove('hidden');
            });
        }
    </script>

    @include('partials.modals.faculty.announcement')

</x-layouts>

<img src="" alt="">
