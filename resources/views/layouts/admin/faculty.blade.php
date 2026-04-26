<x-layouts>
    <div class="flex-1 p-6 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Faculty List</h1>
                <p class="text-sm text-gray-500">Manage faculty accounts and information</p>
            </div>

            <x-button variant="btnGradientv1" id="addFacultyModal">
                <i class="fa-solid fa-plus mr-1"></i>
                Add Faculty Account
            </x-button>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full">
            <div class="overflow-x-auto">
                <x-table :headers="['Faculty', 'Email', 'Username', 'Account Created' ,'Actions']" :align="'text-center'">
                    @forelse ($faculty as $faculties)
                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition" data-id="{{ $faculties->id }}"
                            data-name="{{ $faculties->name }}" data-email="{{ $faculties->email }}"
                            data-username="{{ $faculties->username }}">
                            <!-- Faculty -->
                            <td class="py-3 pl-6">
                                <div class="flex justify-start items-center gap-3">
                                    <img src="{{ asset('storage/profile_images/' . $faculties->profile_image) }}"
                                        alt="{{ $faculties->name }}" class="w-10 h-10 rounded-full object-cover border">
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $faculties->name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{ $faculties->email }}
                            </td>

                            <!-- Username -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{ $faculties->username }}
                            </td>

                            <!-- Account Created -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                {{
                                    $faculties->created_at->isCurrentYear()
                                    ? $faculties->created_at->format('M d \\a\\t h:i A')
                                    : $faculties->created_at->format('M d, Y \\a\\t h:i A')
                                }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <x-button variant="btnGradientv1" id="editFacultyModal">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i>
                                        Edit
                                    </x-button>

                                    <x-button variant="btnGradientv1" id="deleteFacultyModal">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Delete
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-500">
                                    No faculty accounts found.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>

    <div id="facultyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-1/3 p-6 relative">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add Faculty Account</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <x-form action="{{ route('admin.faculty.create') }}">
                <!-- Name Field -->
                <div class="mb-4">
                    <x-input type="text" name="name" id="name" label="Full Name" placeholder="John Doe" required
                        variant="review" value="{{ old('name') }}" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-4">
                    <x-input type="email" name="email" id="email" label="Email" placeholder="someone@example.com"
                        required variant="review" value="{{ old('email') }}" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username Field -->
                <div class="mb-4">
                    <x-input type="text" name="username" id="username" label="Username" placeholder="johndoe123"
                        required variant="review" value="{{ old('username') }}" />
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <x-input type="password" name="password" id="password" label="Password" placeholder="*********"
                        required variant="review" value="pass123" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div class="mb-4">
                    <x-input type="password" name="password_confirmation" id="password_confirmation"
                        label="Confirm Password" placeholder="*********" required variant="review" value="pass123" />
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <span class="text-sm text-red-600">Note: The default password is pass123</span>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelModal"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Add</button>
                </div>
            </x-form>
        </div>
    </div>

    <!-- Edit Faculty Modal -->
    <div id="editFacultyModalWindow"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-1/3 p-6 relative">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edit Faculty Account</h3>
                <button id="closeEditModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Form -->
            <x-form id="editFacultyForm" method="POST" action="{{ route('admin.faculty.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="faculty_id" id="editFacultyId">

                <x-input type="text" name="name" id="editFacultyName" label="Full Name" required />
                <x-input type="email" name="email" id="editFacultyEmail" label="Email" required />
                <x-input type="text" name="username" id="editFacultyUsername" label="Username" required />

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelEditModal"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </x-form>
        </div>
    </div>

    <!-- Delete Faculty Modal -->
    <div id="deleteFacultyModalWindow"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-1/3 p-6 relative">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Delete Faculty Account</h3>
                <button id="closeDeleteModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <p>Are you sure you want to delete this faculty account?</p>

            <x-form id="deleteFacultyForm" method="POST" action="{{ route('admin.faculty.delete') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="faculty_id" id="deleteFacultyId">

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelDeleteModal"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Delete</button>
                </div>
            </x-form>
        </div>
    </div>


    <!-- JS to handle modal -->
    <script>
        const modal = document.getElementById('facultyModal');
        const openBtn = document.getElementById('addFacultyModal');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelModal');

        // Open modal
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Close modal
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Close modal when clicking outside the content
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Keep modal open if there are errors
        @if($errors->any())
            modal.classList.remove('hidden');
        @endif

        // Edit Modal
const editButtons = document.querySelectorAll('#editFacultyModal');
        const editModal = document.getElementById('editFacultyModalWindow');
        const closeEditBtn = document.getElementById('closeEditModal');
        const cancelEditBtn = document.getElementById('cancelEditModal');

        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                editModal.classList.remove('hidden');

                // Populate form fields
                document.getElementById('editFacultyId').value = row.dataset.id;
                document.getElementById('editFacultyName').value = row.dataset.name;
                document.getElementById('editFacultyEmail').value = row.dataset.email;
                document.getElementById('editFacultyUsername').value = row.dataset.username;
            });
        });

        closeEditBtn.addEventListener('click', () => editModal.classList.add('hidden'));
        cancelEditBtn.addEventListener('click', () => editModal.classList.add('hidden'));

        // Delete Modal
        const deleteButtons = document.querySelectorAll('#deleteFacultyModal');
        const deleteModal = document.getElementById('deleteFacultyModalWindow');
        const closeDeleteBtn = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDeleteModal');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                deleteModal.classList.remove('hidden');

                document.getElementById('deleteFacultyId').value = row.dataset.id;
            });
        });

        closeDeleteBtn.addEventListener('click', () => deleteModal.classList.add('hidden'));
        cancelDeleteBtn.addEventListener('click', () => deleteModal.classList.add('hidden'));

        // Close when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === editModal) editModal.classList.add('hidden');
            if (e.target === deleteModal) deleteModal.classList.add('hidden');
        });

    </script>
</x-layouts>
