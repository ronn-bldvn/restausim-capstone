@php

$role = Auth::user()->role;

@endphp

<header class="sticky top-0 z-40 flex items-center h-[58px] border-b border-black bg-white">
    <!-- Hamburger Menu - Responsive width -->
    <div class="w-16 md:w-[76px] h-full flex items-center justify-center bg-white">
        <button id="hamburger-btn" class="text-gray-600 hover:text-gray-800 transition-colors p-2">
            <i class="fa-solid fa-bars text-lg md:text-xl"></i>
        </button>
    </div>

    <!-- Logo Section - Responsive -->
    @if ($role === 'faculty' || $role === 'student')
        <div class="w-[200px] md:w-[250px] h-full bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] flex items-center justify-center px-[15px]"
            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 15% 100%)">
            <div class="logo-content">
                <h1 class="text-[24px] md:text-[32px] font-black text-[#0D0D54] font-[Barlow]">
                    @if (Auth::user()->role === 'faculty')
                        <a href="{{ route('faculty.dashboard') }}">
                            Restau<span class="text-[#EA7C69]">Sim.</span>
                        </a>
                    @else
                        <a href="{{ route('student.section') }}">
                            Restau<span class="text-[#EA7C69]">Sim.</span>
                        </a>
                    @endif
                </h1>
            </div>
        </div>
    @endif

    @if ($role === 'admin')
        <div class="w-[200px] md:w-[250px] h-full flex items-center justify-center px-[15px]">
            <div class="logo-content">
                <h1 class="text-[24px] md:text-[32px] font-black text-[#0D0D54] font-[Barlow]">
                    <a href="{{ route('faculty.dashboard') }}">
                        Admin<span class="text-[#EA7C69] ml-1">Dashboard</span>
                    </a>
                </h1>
            </div>
        </div>
    @endif

    @if ($role === 'superadmin')
        <div class="w-[200px] md:w-[250px] h-full flex items-center justify-center px-[15px]">
            <div class="logo-content">
                <h1 class="text-[24px] md:text-[32px] font-black text-[#0D0D54] font-[Barlow]">
                    <a href="{{ route('superadmin.dashboard') }}">
                        FER<span class="text-[#EA7C69] ml-1">Dashboard</span>
                    </a>
                </h1>
            </div>
        </div>
    @endif

    <!-- Spacer to push content to the right -->
    <div class="flex-1"></div>

    <!-- Right Section - Responsive -->
    <div class="flex items-center gap-[10px] md:gap-[20px] pr-[10px] md:pr-[20px]">
        <a href="{{ route('profile') }}" class="">
            <!-- Profile -->
            <div class="flex items-center gap-[8px] md:gap-[10px]">
                <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="Profile Image"
                    class="w-[32px] h-[32px] md:w-[40px] md:h-[40px] rounded-full object-cover" />

                <div class="hidden sm:flex flex-col leading-[1.2]">
                    <span class="text-[12px] md:text-[14px] font-semibold">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] md:text-[12px] text-gray-500">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
        </a>
    </div>
</header>
