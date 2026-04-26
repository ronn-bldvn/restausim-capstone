@include('components.resources')
@include('partials.links.links')

<x-favicon />

<title>Reset Password - RestauSim</title>

<div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">

        <h2 class="text-2xl font-bold text-center text-[#0D0D54] mb-6">
            Reset Password
        </h2>

        {{-- Alerts --}}
        <x-alert type="error" :message="session('error')" />

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <x-input
                name="email"
                type="email"
                label="Email"
                placeholder="someone@clsu2.edu.ph"
                required
                variant="reset"
            />

            {{-- Password --}}
            <div class="relative">
                <x-input
                    name="password"
                    type="password"
                    label="New Password"
                    placeholder="********"
                    required
                    variant="reset"
                    id="password"
                />

                <span
                    class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                    onclick="togglePassword('password', this)"
                >
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>

            {{-- Confirm Password --}}
            <div class="relative">
                <x-input
                    name="password_confirmation"
                    type="password"
                    label="Confirm Password"
                    placeholder="********"
                    required
                    variant="reset"
                    id="confirmPassword"
                />

                <span
                    class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                    onclick="togglePassword('confirmPassword', this)"
                >
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>

            <x-button variant="login" class="w-full">
                Reset Password
            </x-button>
        </form>

    </div>
</div>


<script>
function togglePassword(id, icon) {
    const input = document.getElementById(id);
    const i = icon.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        i.classList.remove("fa-eye");
        i.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        i.classList.remove("fa-eye-slash");
        i.classList.add("fa-eye");
    }
}
</script>