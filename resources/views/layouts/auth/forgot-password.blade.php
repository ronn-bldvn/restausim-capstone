@include('components.resources')
@include('partials.links.links')

<x-favicon />

<title>Forgot Password - RestauSim</title>

<div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">

        <h2 class="text-2xl font-bold text-center text-[#0D0D54] mb-4">
            Forgot Password
        </h2>

        <p class="text-sm text-gray-600 text-center mb-6">
            Enter your registered email and we’ll send you a reset link.
        </p>

        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('error')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <x-input
                name="email"
                type="email"
                label="Email Address"
                placeholder="Enter your email"
                required
            />

            <x-button variant="login" class="w-full">
                Send Reset Link
            </x-button>
        </form>

        <div class="text-center mt-4 text-sm">
            <a href="{{ route('login') }}" class="text-[#EA7C69] hover:underline">
                Back to Login
            </a>
        </div>

    </div>
</div>
