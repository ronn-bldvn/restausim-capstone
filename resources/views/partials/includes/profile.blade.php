<x-layouts title="Profile | RestauSim">

<div class="flex flex-col w-full p-4 sm:p-8 lg:p-16 font-[Barlow]">
    <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-800">My Profile</h1>

    <!-- Profile Section -->
    <div class="border border-gray-300 rounded-lg shadow-md p-4 sm:p-6 bg-white">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800">My Profile</h3>
                <x-button id="editProfileBtn" class="w-full sm:w-auto">
                    <span class="mr-2">Edit</span>
                    <i class="fa-solid fa-pencil"></i>
                </x-button>
        </div>

        <!-- Display Mode -->
        <div id="profileView">
            <div class="flex flex-col sm:flex-row border-b pb-4 sm:pb-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 mx-auto sm:mx-0 mb-4 sm:mb-0">
                    <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}"
                        alt="Profile Image"
                        class="w-20 h-20 sm:w-[90px] sm:h-[90px] rounded-full object-cover border-4 border-gray-200 shadow-sm">
                </div>
                <div class="flex flex-col sm:ml-6 text-center sm:text-left">
                    <h2 class="text-xl sm:text-2xl font-bold mb-2 text-gray-800">{{ Auth::user()->name }}</h2>
                    <span class="text-gray-600 text-base sm:text-lg font-medium capitalize">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
                <div class="space-y-4 sm:space-y-6">
                    <div>
                        <x-label>Full Name</x-label>
                        <p class="text-gray-800 font-medium break-words">{{ Auth::user()->name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <x-label>Email</x-label>
                        <p class="text-gray-800 font-medium break-all">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div>
                        <x-label>Username</x-label>
                        <p class="text-gray-800 font-medium break-words">{{ Auth::user()->username ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <x-label>Role</x-label>
                        <p class="text-gray-800 font-medium capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <x-form action="" id="profileForm" class="hidden" enctype="multipart/form-data">
            <!-- Profile Image + Name -->
            <div class="flex flex-col sm:flex-row border-b pb-4 sm:pb-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 mx-auto sm:mx-0 mb-4 sm:mb-0">
                    <img id="previewImage"
                        src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}"
                        alt="Profile Image"
                        class="w-20 h-20 sm:w-[90px] sm:h-[90px] rounded-full object-cover border-4 border-gray-200 shadow-sm mb-3">
                    <x-label for="profileImageInput" variant="profilebtn" class="text-sm sm:text-base">Change Photo</x-label>
                    <x-input id="profileImageInput" type="file" name="profile_image" class="hidden" accept='image/*'/>
                </div>
                <div class="flex flex-col sm:ml-6 w-full">
                    <x-label variant="default1">Full Name</x-label>
                    <x-input type="text" name="name" value="{{ Auth::user()->name }}" class="border rounded p-2 w-full text-sm sm:text-base"/>
                    <label class="text-gray-400 text-sm mb-1 mt-3 sm:mt-4">Role (readonly)</label>
                    <x-input name="" type="text" value="{{ Auth::user()->role }}" readonly class="border rounded p-2 w-full text-sm sm:text-base"/>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
                <div class="space-y-4 sm:space-y-6">
                    <div>
                        <x-label variant="default1">Email</x-label>
                        <x-input type="email" name="email" value="{{ Auth::user()->email }}" class="border rounded p-2 w-full text-sm sm:text-base"/>
                    </div>
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div>
                        <x-label variant="default1">Username</x-label>
                        <x-input type="text" name="username" value="{{ Auth::user()->username }}" class="border rounded p-2 w-full text-sm sm:text-base"/>
                    </div>
                </div>
            </div>

            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-3">
                <x-button variant="btnGradient" class="w-full sm:w-auto">Save</x-button>
                <x-button type="button" variant="btnNoGradient" id="cancelEdit" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </x-form>
    </div>

    <!-- Account Settings Section -->
    <div class="border border-gray-300 rounded-lg shadow-md p-4 sm:p-6 mt-4 sm:mt-6 bg-white">
        <div class="flex flex-row items-center justify-between mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Account Settings</h3>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:gap-8">
            <div class="space-y-4 sm:space-y-6">
                <div class="flex flex-col">
                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3 sm:gap-4">
                        @if (Auth::user()->google_id === null)
                            <x-button id="changePasswordBtn" variant="btnGradient" class="w-full sm:w-auto text-sm sm:text-base">
                                <i class="fa-solid fa-key mr-2"></i>
                                Change Password
                            </x-button>
                        @endif
                        <x-button id="deleteAccountBtn" variant="btnGradient" class="w-full sm:w-auto text-sm sm:text-base">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete Account
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Change Password</h3>
            <x-button id="closePasswordModal" variant="profileX">
                <i class="fa-solid fa-times"></i>
            </x-button>
        </div>
        <x-form id="passwordForm" action="">
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <div class="relative">
                        <x-input type="password" name="current_password" id="currentPassword" class="w-full border rounded p-2 text-sm sm:text-base" required/>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 items-center toggle-password hidden" data-target="currentPassword">
                            <i class="fa-solid fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <x-label variant="pass">New Password</x-label>
                    <div class="relative">
                        <x-input type="password" name="new_password" id="newPassword" class="w-full border rounded p-2 text-sm sm:text-base" required/>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 items-center toggle-password hidden" data-target="newPassword">
                            <i class="fa-solid fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <div class="relative">
                        <x-input type="password" name="new_password_confirmation" id="confirmPassword" class="w-full border rounded p-2 text-sm sm:text-base" required/>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 items-center toggle-password hidden" data-target="confirmPassword">
                            <i class="fa-solid fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-6">
                <x-button variant="btnGradient" class="w-full sm:w-auto">Update Password</x-button>
                <x-button type="button" variant="btnNoGradient" id="cancelPasswordModal" class="w-full sm:w-auto">Cancel</x-button>
            </div>
        </x-form>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-red-600">Delete Account</h3>
            <button id="closeDeleteModal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 mb-2 text-sm sm:text-base">Are you sure you want to delete your account?</p>
            <p class="text-red-600 text-sm font-medium">This action cannot be undone!</p>
        </div>
        <x-form action="" id="deleteForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Enter your password to confirm:</label>
                <div class="relative">
                    <x-input type="password" name="password" id="deletePassword" class="w-full border rounded p-2 text-sm sm:text-base" required/>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 items-center toggle-password hidden" data-target="deletePassword">
                        <i class="fa-solid fa-eye text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <x-button variant="btnGradient" class="w-full sm:w-auto">
                    Delete
                </x-button>
                <x-button id="cancelDeleteModal" variant="btnNoGradient" class="w-full sm:w-auto">
                    Cancel
                </x-button>
            </div>
        </x-form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Profile edit functionality
    $("#editProfileBtn").click(function() {
        $("#profileView").addClass("hidden");
        $("#profileForm").removeClass("hidden");
    });

    $("#cancelEdit").click(function() {
        $("#profileForm").addClass("hidden");
        $("#profileView").removeClass("hidden");
    });

    $("#profileImageInput").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $("#previewImage").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Profile form submission
    $("#profileForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('profile.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Profile updated successfully!');
                    location.reload();
                } else {
                    alert(response.message || 'Update failed');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    let messages = [];
                    for (let k in errors) messages.push(errors[k][0]);
                    alert("Validation failed:\n" + messages.join("\n"));
                } else {
                    let msg = xhr.responseJSON?.message || "Something went wrong. Please try again.";
                    alert("Error ("+xhr.status+"): " + msg);
                }
            }
        });
    });

    // Change Password Modal
    $("#changePasswordBtn").click(function() {
        $("#passwordModal").removeClass("hidden").addClass("flex");
    });

    $("#closePasswordModal, #cancelPasswordModal").click(function() {
        $("#passwordModal").addClass("hidden").removeClass("flex");
        $("#passwordForm")[0].reset();
    });

    // Password form submission
    $("#passwordForm").submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('profile.change-password') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Password changed successfully!');
                    $("#passwordModal").addClass("hidden").removeClass("flex");
                    $("#passwordForm")[0].reset();
                } else {
                    alert(response.message || 'Password change failed');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    let messages = [];
                    for (let k in errors) messages.push(errors[k][0]);
                    alert("Validation failed:\n" + messages.join("\n"));
                } else {
                    let msg = xhr.responseJSON?.message || "Something went wrong. Please try again.";
                    alert("Error ("+xhr.status+"): " + msg);
                }
            }
        });
    });

    // Download Profile Data
    $("#downloadDataBtn").click(function() {
        window.open("{{ route('profile.download-data') }}", '_blank');
    });

    // Delete Account Modal
    $("#deleteAccountBtn").click(function() {
        $("#deleteModal").removeClass("hidden").addClass("flex");
    });

    $("#closeDeleteModal, #cancelDeleteModal").click(function() {
        $("#deleteModal").addClass("hidden").removeClass("flex");
        $("#deleteForm")[0].reset();
    });

    // Delete form submission
    $("#deleteForm").submit(function(e) {
        e.preventDefault();

        if (!confirm("Are you absolutely sure? This will permanently delete your account and all associated data.")) {
            return;
        }

        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('profile.delete-account') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Account deleted successfully');
                    window.location.href = "{{ route('login') }}";
                } else {
                    alert(response.message || 'Account deletion failed');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    let messages = [];
                    for (let k in errors) messages.push(errors[k][0]);
                    alert("Validation failed:\n" + messages.join("\n"));
                } else {
                    let msg = xhr.responseJSON?.message || "Something went wrong. Please try again.";
                    alert("Error ("+xhr.status+"): " + msg);
                }
            }
        });
    });

    // Show/hide eye icon based on input content
    $('#currentPassword, #newPassword, #confirmPassword, #deletePassword').on('input', function() {
        const input = $(this);
        const toggleButton = input.siblings('.toggle-password');

        if (input.val().length > 0) {
            toggleButton.removeClass('hidden');
            input.addClass('pr-10');
        } else {
            toggleButton.addClass('hidden');
            input.removeClass('pr-10');
            input.attr('type', 'password');
            toggleButton.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Password visibility toggle
    $('.toggle-password').click(function() {
        const targetId = $(this).data('target');
        const targetInput = $('#' + targetId);
        const icon = $(this).find('i');

        if (targetInput.attr('type') === 'password') {
            targetInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            targetInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Reset eye visibility when modals are closed
    $("#closePasswordModal, #cancelPasswordModal").click(function() {
        $("#passwordModal").addClass("hidden").removeClass("flex");
        $("#passwordForm")[0].reset();
        $('.toggle-password').addClass('hidden');
        $('#currentPassword, #newPassword, #confirmPassword').removeClass('pr-10').attr('type', 'password');
        $('.toggle-password i').removeClass('fa-eye-slash').addClass('fa-eye');
    });

    $("#closeDeleteModal, #cancelDeleteModal").click(function() {
        $("#deleteModal").addClass("hidden").removeClass("flex");
        $("#deleteForm")[0].reset();
        $('[data-target="deletePassword"]').addClass('hidden');
        $('#deletePassword').removeClass('pr-10').attr('type', 'password');
        $('[data-target="deletePassword"] i').removeClass('fa-eye-slash').addClass('fa-eye');
    });

    // Close modals when clicking outside
    $(document).click(function(e) {
        if ($(e.target).is("#passwordModal")) {
            $("#passwordModal").addClass("hidden").removeClass("flex");
            $("#passwordForm")[0].reset();
            $('.toggle-password').addClass('hidden');
            $('#currentPassword, #newPassword, #confirmPassword').removeClass('pr-10').attr('type', 'password');
            $('.toggle-password i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
        if ($(e.target).is("#deleteModal")) {
            $("#deleteModal").addClass("hidden").removeClass("flex");
            $("#deleteForm")[0].reset();
            $('[data-target="deletePassword"]').addClass('hidden');
            $('#deletePassword').removeClass('pr-10').attr('type', 'password');
            $('[data-target="deletePassword"] i').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>

</x-layouts>
