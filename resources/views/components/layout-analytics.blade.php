<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'RestauSim - Analytics' }}</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>

<body class="font-[Barlow] bg-gray-100 text-gray-900">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="hidden md:flex md:w-64 flex-col bg-[#0B1220] text-gray-200">
        <div class="h-16 px-5 flex items-center border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3v18h18M7 14l3-3 4 4 7-7"/>
                    </svg>
                </div>
                <div>
                    <div class="font-extrabold leading-4">RestauSim</div>
                    <div class="text-xs text-gray-400">Analytics Suite</div>
                </div>
            </div>
        </div>

        <nav class="px-3 py-4 space-y-1">
            <a href="{{ route('reportsAnalytics.index') }}"
               class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition
               {{ request()->routeIs('reportsAnalytics.index') ? 'bg-white/10 ring-1 ring-white/10' : '' }}">
                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3v18h18M7 14l3-3 4 4 7-7"/>
                </svg>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>

            <a href="#"
               class="group flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition opacity-80">
                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M9 14l6-6m-5.5.5h.01m4.99 5h.01"/>
                </svg>
                <span class="text-sm font-semibold">Reports (soon)</span>
            </a>
        </nav>

        <div class="mt-auto p-4 text-xs text-gray-400 border-t border-white/10">
            © {{ date('Y') }} RestauSim
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 min-w-0">
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-gray-200">
            <div class="px-4 md:px-8 h-16 flex items-center justify-between">
                <div>
                    <div class="text-lg md:text-xl font-extrabold">
                        {{ $headerTitle ?? 'Analytics Dashboard' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $headerSubtitle ?? 'Sales • Orders • Profit • Inventory Usage' }}
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="window.print()"
                            class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50">
                        Print
                    </button>
                    <button
                        class="inline-flex items-center px-3 py-2 rounded-lg border border-transparent text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
                        Export
                    </button>
                </div>
            </div>
        </header>

        <div class="px-4 md:px-8 py-6">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
