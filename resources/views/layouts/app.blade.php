<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />
    <title>
        {{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/buttershine-serif" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.cdnfonts.com/css/anton" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/bricolage-grotesque" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/inter" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-[#D9D9D9] overflow-x-hidden">
    <div class="flex h-screen">

        <!-- MOBILE OVERLAY -->
        <div
            id="sidebarOverlay"
            class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"
            onclick="closeSidebar()">
        </div>

        <!-- SIDEBAR -->
        <aside
            id="sidebar"
            class="fixed md:static inset-y-0 left-0 z-50 w-72 md:w-64 bg-white dark:bg-gray-800 border-r
                   border-gray-200 dark:border-gray-700 text-gray-300 flex flex-col
                   transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">

            <div class="pt-4 flex flex-col justify-center items-center px-4">
                <img
                    src="{{ asset('images/fav-logo/restau-logo.png') }}"
                    alt="Chew & Cheer Logo"
                    class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full" />
                <p class="text-lg md:text-3xl font-[buttershine-serif] mt-2 text-white text-center">
                    Chew & Cheer
                </p>
            </div>

            <div class="my-6 flex justify-center items-center px-4">
                <div class="w-full border-t border-gray-300"></div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto">
                @php
                    use Illuminate\Support\Str;

                    $recentLogsRaw = \App\Models\ActivityLog::query()
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->take(11)
                        ->get();

                    $hasLogs = $recentLogsRaw->isNotEmpty();
                    $hasMoreThan10 = $recentLogsRaw->count() > 10;
                    $recentLogs = $recentLogsRaw->take(10);
                @endphp

                @if (
                    auth()->user()->can('view inventory') ||
                        auth()->user()->can('create inventory') ||
                        auth()->user()->can('edit inventory') ||
                        auth()->user()->can('restock inventory') ||
                        auth()->user()->can('delete inventory')
                )
                    <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 3C6.49 3 2 7.49 2 13v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-6c0-5.51-4.49-10-10-10m4 12H8v-2h8zm-8 4v-2h8v2zm12 0h-2v-6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v6H4v-6c0-4.41 3.59-8 8-8s8 3.59 8 8z">
                            </path>
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Inventory</span>
                    </x-nav-link>
                @endif

                @if (
                    auth()->user()->can('view inventory') ||
                        auth()->user()->can('create inventory') ||
                        auth()->user()->can('edit inventory') ||
                        auth()->user()->can('restock inventory') ||
                        auth()->user()->can('delete inventory')
                )
                    <x-nav-link :href="route('menu.index')" :active="request()->routeIs('menu.*')" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M10 9H8V2H6v7H4V2H2v8c0 1.65 1.35 3 3 3h1v9h2v-9h1c1.65 0 3-1.35 3-3V2h-2zm8-7c-2.4 0-4 3.76-4 6.25 0 2.21 1.28 4.05 3 4.58V22h2v-9.17c1.72-.53 3-2.37 3-4.58C22 5.76 20.4 2 18 2">
                            </path>
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Menu</span>
                    </x-nav-link>
                @endif

                @if (
                    auth()->user()->can('view floorplan') ||
                        auth()->user()->can('create floorplan') ||
                        auth()->user()->can('view table') ||
                        auth()->user()->can('manage table') ||
                        auth()->user()->can('take orders') ||
                        auth()->user()->can('apply discount') ||
                        auth()->user()->can('process payment')
                )
                    <x-nav-link :href="route('floorplan.index')" :active="request()->routeIs('floorplan.*')" wire:navigate>
                        @if (auth()->user()->can('create floorplan'))
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="m21.51 6.14-5-3a.99.99 0 0 0-.87-.08L8.09 5.89 3.51 3.14a.99.99 0 0 0-1.01-.01c-.31.18-.51.51-.51.87v13c0 .35.18.68.49.86l5 3c.26.16.58.19.87.08l7.55-2.83 4.59 2.75c.16.1.34.14.51.14s.34-.04.49-.13c.31-.18.51-.51.51-.87V7a.99.99 0 0 0-.49-.86M7 18.23l-3-1.8V5.77l3 1.8v10.67Zm8-1.93-6 2.25V7.69l6-2.25zm5 1.93-3-1.8V5.77l3 1.8v10.67Z" />
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Floor Plan</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 12h-1V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v9H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h1v4h2v-4h8v4h2v-4h1c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1M8 4h8v8H8zm10 12H6v-2h12z">
                                </path>
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Orders</span>
                        @endif
                    </x-nav-link>
                @endif

                @if (
                    auth()->user()->can('view kitchen orders') ||
                        auth()->user()->can('manage kitchen orders')
                )
                    <x-nav-link :href="route('kitchen.dashboard')" :active="request()->routeIs('kitchen.*')" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M2 19h20v2H2zM13 5.05V3h-2v2.05c-5.05.5-9 4.77-9 9.95v1c0 .55.45 1 1 1h18c.55 0 1-.45 1-1v-1c0-5.18-3.95-9.45-9-9.95M4 15c0-4.41 3.59-8 8-8s8 3.59 8 8z">
                            </path>
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Kitchen</span>
                    </x-nav-link>
                @endif

                @if (
                    auth()->user()->can('create discount') ||
                        auth()->user()->can('edit discount') ||
                        auth()->user()->can('view discount') ||
                        auth()->user()->can('delete discount')
                )
                    <x-nav-link :href="route('discount.index')" :active="request()->routeIs('discount.*')" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M11.71 2.29A1 1 0 0 0 11 2H6c-.27 0-.52.11-.71.29l-3 3A1 1 0 0 0 2 6v5c0 .27.11.52.29.71l10 10c.2.2.45.29.71.29s.51-.1.71-.29l8-8a.996.996 0 0 0 0-1.41zM13 19.58l-9-8.99V6.42l2.41-2.41h4.17l9 9-6.59 6.59Z">
                            </path>
                            <path d="M8 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2"></path>
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Discounts</span>
                    </x-nav-link>
                @endif

                @if (auth()->user()->can('view reports and analytics'))
                    <x-nav-link :href="route('reportsAnalytics.index')" :active="request()->routeIs('reportsAnalytics.*')" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M3 15h2v6H3zm4-2h2v8H7zm4-1h2v9h-2zm4 1h2v8h-2zm4-5h2v13h-2z"></path>
                            <path d="m19.21 2.38-4.87 6.21-5-4-6.13 7.79 1.58 1.24 4.87-6.21 5 4 6.13-7.79z"></path>
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">
                            Reports and Analytics
                        </span>
                    </x-nav-link>
                @endif

                <div class="mt-2 space-y-2">
                    <livewire:layout.recent-activity />
                </div>
            </nav>

            <!-- Bottom Profile -->
            <div class="p-4 h-20 bg-gray-100 dark:bg-gray-900">
                <div class="flex flex-row justify-between items-center">
                    <img
                        src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}"
                        alt="Profile Image"
                        class="w-[32px] h-[32px] md:w-[40px] md:h-[40px] rounded-full object-cover" />

                    <div class="flex flex-col">
                        <span class="font-['Bricolage_Grotesque'] font-black text-white">{{ Auth::user()->name }}</span>
                        <span class="font-['Bricolage_Grotesque'] font-black text-white text-xs">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>

                    <a href="{{ route('student.section') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M9 13h7v-2H9V7l-6 5 6 5z"></path>
                            <path d="M19 3h-7v2h7v14h-7v2h7c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- RIGHT SIDE -->
        <div class="flex-1 flex flex-col min-w-0 md:ml-0">

            <!-- HEADER -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between gap-3">
                    <button
                        class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 text-white"
                        onclick="openSidebar()"
                        aria-label="Open sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="flex justify-between items-center w-full">
                        <h1 class="text-xl sm:text-3xl md:text-4xl text-white font-['Bricolage_Grotesque'] font-black">
                            {{ $headerTitle ?? '' }}
                        </h1>
                    </div>

                    <div class="hidden sm:block"></div>
                </div>
            </header>

            <!-- MAIN -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-100 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div
        x-data="{ show: false, message: '', type: '' }"
        x-on:toast.window="
            show = true;
            message = $event.detail.message;
            type = $event.detail.type;
            setTimeout(() => show = false, 3000);
        "
        x-show="show"
        x-transition
        class="fixed top-5 right-5 z-50">
        <div
            id="toast-success"
            :class="{
                'border-green-600': type === 'success',
                'border-red-600': type === 'error',
                'border-blue-600': type === 'info'
            }"
            class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800 border"
            role="alert">

            <div
                class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
                x-show="type === 'success'">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
                <span class="sr-only">Check icon</span>
            </div>

            <div
                class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200"
                x-show="type === 'error'">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                </svg>
                <span class="sr-only">Error icon</span>
            </div>

            <div
                class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200"
                x-show="type === 'info'">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
                </svg>
                <span class="sr-only">Warning icon</span>
            </div>

            <div class="ms-3 text-sm font-normal">
                <span x-text="message"></span>
            </div>
        </div>
    </div>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleMenu(menuId, arrowId) {
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(arrowId);

            if (!menu) return;

            if (menu.classList.contains('max-h-0')) {
                menu.classList.remove('max-h-0');
                menu.classList.add('max-h-40');
                if (arrow) arrow.classList.add('rotate-180');
            } else {
                menu.classList.add('max-h-0');
                menu.classList.remove('max-h-40');
                if (arrow) arrow.classList.remove('rotate-180');
            }
        }

        window.matchMedia('(min-width: 768px)').addEventListener('change', (e) => {
            if (e.matches) closeSidebar();
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>

</html>