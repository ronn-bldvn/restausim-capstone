<x-layouts :title="'Dashboard | RestauSim'">

<div class="flex-1 p-5 overflow-y-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <p class="font-[Barlow] font-semibold text-black text-2xl my-1">
                Welcome back, <span>{{ Auth::user()->name }}!</span>
            </p>
            <span class="font-[Barlow] mt-1 text-sm">Here's what happening to RestauSim today.</span>
        </div>
    </div>

    <!-- Summary Card-->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 my-8 px-4">
        <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-500 text-sm font-medium mb-3">Total Students</h2>
                    <p class="text-3xl font-bold text-slate-900 mb-2">{{ $studentCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-500 text-sm font-medium mb-3">Total Faculty</h2>
                    <p class="text-3xl font-bold text-slate-900 mb-2">{{ $facultyCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-500 text-sm font-medium mb-3">Total Roles</h2>
                    <p class="text-3xl font-bold text-slate-900 mb-2">{{ $roleCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white w-full p-6 rounded-2xl shadow-sm border border-gray-100 transition-all transform hover:-translate-y-1 hover:shadow-lg cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h2 class="text-gray-500 text-sm font-medium mb-3">Total Sections</h2>
                    <p class="text-3xl font-bold text-slate-900 mb-2">{{ $sectionCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recently Joined Students -->
        <div class="bg-white border rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-semibold text-black">
                    Recently Joined Students
                </h4>
            </div>

            <ul class="divide-y divide-gray-100">
                @forelse ($studentJoin as $activity)
                    <li class="py-3 flex flex-col gap-1  hover:bg-gray-50 rounded-lg px-2 transition">
                        <span class="text-sm font-medium text-black">
                            {{ $activity->description }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{
                                $activity->created_at->isCurrentYear()
                                ? $activity->created_at->format('M d \\a\\t h:i A')
                                : $activity->created_at->format('M d, Y \\a\\t h:i A')
                            }}
                        </span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-400">
                        No recent student joins
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Faculty -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h4 class="text-lg font-semibold text-gray-800">
                Faculty Accounts
            </h4>
        </div>

        <x-button variant="btnGradientv1" id="addFacultyModal">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Faculty
        </x-button>
    </div>

    <!-- Faculty List -->
    <div class="space-y-3">
        @forelse ($faculty as $faculties)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <div class="flex items-center gap-4">
                    <!-- Avatar -->
                     <img src="{{ asset('storage/profile_images/' . $faculties->profile_image) }}"
                                        alt="{{ $faculties->name }}" class="w-10 h-10 rounded-full object-cover border">

                    <!-- Info -->
                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $faculties->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $faculties->email }}
                        </p>
                    </div>
                </div>

            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-10">
                <i class="fa-solid fa-user-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">
                    No faculty accounts created yet
                </p>
            </div>
        @endforelse
    </div>
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

</script>
</x-layouts>
