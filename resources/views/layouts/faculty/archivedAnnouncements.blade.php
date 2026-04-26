@php

$role = auth()->user()->role;

$studentsLink = url(
    'faculty/archived/student/' .
    $section->section_id
);

@endphp

<x-layouts :title="$section->class_name">
    <div class="flex-1">

        <!-- Toast / Alert messages -->
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error1" :message="session('error')" />

        <div class="rounded-xl h-full shadow-sm">

            <script>
                // Set global variable for current section
                window.currentSectionId = {{ $section->section_id }};
            </script>

            <!-- Top bar navigation -->
            <div class="bg-[#f2f2f2] h-[48px] text-xs flex border-b border-black">
                <!-- Activities -->
                <a href="{{ auth()->user()->role === 'student'
                        ? url('student/activity/' . $section->section_id)
                        : url('faculty/archived/Activity/' . $section->section_id) }}"
                        class="{{ request()->is('faculty/archived/Activity*') || request()->is('student/activity*')
                            ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                            : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Activities
                </a>

                <!-- Announcements -->
                <a href="{{ url('faculty/archived/announcements/' . $section->section_id) }}"
                    class="{{ request()->is('faculty/archived/announcements*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Announcements
                </a>

                    <a href="{{ $studentsLink }}"
                    class="{{ request()->is('faculty/archived/student*')
                        || request()->is('student/student*')
                        || request()->is('faculty/archived/student/*')
                        || request()->is('student/archived/student/*')
                                ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                                : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                        Students
                    </a>
                
                <a href="{{ route('faculty.simulation.allarchived', $section->section_id) }}"
                   class="{{ request()->is('faculty/archived/simulation/all-submissions*')
                        ? 'bg-white border-b-4 rounded-lg border-blue-500 text-black font-medium px-4 py-4'
                        : 'text-gray-600 font-medium px-4 py-4 hover:bg-[#D9D9D9] rounded-lg hover:border-b-4 hover:border-blue-500' }}">
                    Student Works
                </a>

            </div>

            <div class="bg-white border flex flex-col md:flex-row justify-between rounded-xl items-start px-8 py-6 gap-4 md:gap-0 m-5">

                <div class="flex flex-col gap-1">
                    <h2 class="text-4xl font-bold">{{ $section->class_name }}</h2>
                    <h3 class="text-2xl font-medium">{{ $section->name }}</h3>
                    <h4 class="text-xl font-normal mt-4">{{ $section->section_name }}</h4>
                </div>

                <div class="flex gap-3">
                    <x-button type="button" id="createActivityBtn" disabled>
                        <i class="fa-solid fa-bullhorn mr-2 mt-0.5" style="font-size:18px"></i>
                        Post Announcement
                    </x-button>

                    {{-- ✅ Added Exit button for create activity --}}
                    <a href="{{ url('faculty/archivedsection') }}">
                        <x-button type="button">Exit</x-button>
                    </a>
                </div>

            </div>


            {{-- <x-section :section="$section" variant="announce" :activity="null" /> --}}

            {{-- Announcements List --}}
            <div class="px-5 min-h-[500px]">
                @forelse ($announcements as $announcement)
                    <div class="border border-gray-200 rounded-[10px] p-5 flex flex-col justify-between bg-white hover:shadow-md hover:-translate-y-0.5 transition relative mb-4">
                        <div class="flex items-center justify-between">
                            <!-- Left: Profile Info -->
                            <div class="flex items-start">
                                <img src="{{ asset('storage/profile_images/' . $announcement->user?->profile_image) }}"
                                    alt="profile"
                                    class="w-[40px] h-[40px] rounded-full object-cover">
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $announcement->user?->name ?? 'Unknown Faculty' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $announcement->created_at->timezone('Asia/Manila')->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <!-- Right: Action Buttons -->
                            <div class="flex space-x-2">
                                <x-button
                                    class="editAnnouncementBtn"
                                    data-id="{{ $announcement->id }}"
                                    data-content="{{ $announcement->content }}"
                                    data-route="{{ route('announce.update', $announcement->id) }}"
                                    disabled
                                >Edit</x-button>

                                <x-button
                                    class="deleteAnnouncementBtn"
                                    data-id="{{ $announcement->id }}"
                                    data-route="{{ route('announce.destroy', $announcement->id) }}"
                                    disabled
                                >Delete</x-button>

                            </div>

                        </div>

                        <div class="mt-4 text-sm text-gray-800 leading-relaxed">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-[15px]">No announcements available.</p>
                @endforelse
                </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 relative">
            <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" onclick="closeEditModal()">✕</button>
            <h2 class="text-xl font-bold mb-4">Edit Announcement</h2>
            <form id="editAnnouncementForm" method="POST">
                @csrf
                @method('PUT')
                <textarea name="content" id="editAnnouncementContent" rows="5" class="w-full p-2 border rounded-lg mb-4"></textarea>
                <div class="flex justify-end space-x-2">
                    <x-button type="button" onclick="closeEditModal()">Cancel</x-button>
                    <x-button type="submit">Update</x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Announcement Modal -->
    <div id="deleteAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" onclick="closeDeleteModal()">✕</button>
            <h2 class="text-xl font-bold mb-4">Delete Announcement</h2>
            <p class="mb-4">Are you sure you want to delete this announcement?</p>
            <form id="deleteAnnouncementForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <x-button type="button" onclick="closeDeleteModal()">Cancel</x-button>
                    <x-button type="submit" class="bg-red-500 hover:bg-red-600">Delete</x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // EDIT ANNOUNCEMENT
        const editBtns = document.querySelectorAll('.editAnnouncementBtn');
        const editModal = document.getElementById('editAnnouncementModal');
        const editForm = document.getElementById('editAnnouncementForm');
        const editContent = document.getElementById('editAnnouncementContent');

        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const route = btn.dataset.route;
                const content = btn.dataset.content;

                editForm.action = route;
                editContent.value = content;

                editModal.classList.remove('hidden');
            });
        });

        function closeEditModal() {
            editModal.classList.add('hidden');
        }

        // DELETE ANNOUNCEMENT
        const deleteBtns = document.querySelectorAll('.deleteAnnouncementBtn');
        const deleteModal = document.getElementById('deleteAnnouncementModal');
        const deleteForm = document.getElementById('deleteAnnouncementForm');

            deleteBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const route = btn.dataset.route;
                deleteForm.action = route;
                deleteModal.classList.remove('hidden');
            });
        });

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>


    {{-- Modal for creating announcements --}}
    @include('partials.modals.faculty.announcement')
</x-layouts>
