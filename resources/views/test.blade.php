@include('components.resources')
@include('partials.links.links')

<x-favicon />

<title>Login - RestauSim</title>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>

{{-- login  --}}
<div id="LoginPage" class="flex flex-col items-center justify-center h-screen bg-gray-100 p-4">
    <div class="w-full max-w-[960px] md:max-w-[500px] lg:max-w-[960px] bg-white rounded-2xl shadow-lg">
        <div class="flex flex-col md:flex-col lg:flex-row items-center">

            {{-- image section --}}
            <div class="hidden lg:block flex-none">
                <img class="max-w-xl h-auto rounded-2xl"
                     src="{{ asset('images/fav-logo/logo-ver3.png') }}"
                     alt="RestauSim Logo">
            </div>

            <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
                <div class="flex flex-col items-center">

                    {{-- Welcome Title --}}
                    <div class="mb-4 text-center">
                        <h1 class="font-['Roboto'] text-2xl sm:text-3xl lg:text-[36px] font-bold text-black leading-tight">
                            Welcome To <br>
                            <span class="font-['Barlow'] text-3xl sm:text-4xl lg:text-[48px] font-black text-[#0D0D54]">Restau</span><span class="font-['Barlow'] text-3xl sm:text-4xl lg:text-[48px] font-black text-[#EA7C69]">Sim.</span>
                        </h1>
                    </div>

                    {{-- Sign In --}}
                    <div class="flex items-center my-6">
                        <span class="font-['Barlow'] text-2xl sm:text-3xl font-extrabold text-[#0D0D54] mr-1">Admin</span>
                        <span class="font-['Barlow'] text-2xl sm:text-3xl font-extrabold text-[#EA7C69]">Account</span>
                    </div>

                    {{-- Form --}}
                    <x-form action="{{ route('login') }}" method="POST"
                            class="w-full flex flex-col space-y-5">

                        <x-input
                            name="login"
                            label="Username/Email"
                            placeholder="Please enter your Username/Email"
                            variant="login"
                            class="w-full"
                            required
                        />

                        <x-input
                            name="password"
                            type="password"
                            label="Password"
                            placeholder="Please enter your Password"
                            variant="login"
                            class="w-full"
                            required
                        />

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">

                            <x-button variant="login" class="w-full sm:w-32 py-2 rounded-full">
                                Login
                            </x-button>
                        </div>
                    </x-form>

                </div>
            </div>

        </div>
    </div>
</div>
