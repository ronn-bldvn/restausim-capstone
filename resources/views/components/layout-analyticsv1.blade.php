<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@vite('resources/css/app.css')
@vite('resources/js/app.js')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<title>Reporting and Analytics | Chew & Cheer</title>

<link href="https://fonts.cdnfonts.com/css/buttershine-serif" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"/>
<link href="https://fonts.cdnfonts.com/css/bricolage-grotesque" rel="stylesheet"/>

<x-favicon></x-favicon>
</head>


<body class="min-h-screen overflow-x-hidden">

<div class="flex h-screen">

<!-- MOBILE OVERLAY -->
<div id="sidebarOverlay"
class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"
onclick="closeSidebar()"></div>


<!-- SIDEBAR -->
<aside id="sidebar"
class="fixed md:static inset-y-0 left-0 z-50
w-64 sm:w-72 md:w-64
bg-gray-100 dark:bg-gray-800
flex flex-col
transform -translate-x-full md:translate-x-0
transition-transform duration-200 ease-in-out">

<!-- LOGO -->
<div class="pt-4 flex flex-col items-center px-4">
    <img
        src="{{ asset('images/fav-logo/restau-logo.png') }}"
        alt="Chew & Cheer Logo"
        class="w-8 h-8 sm:w-12 sm:h-12 md:w-16 md:h-16 object-cover rounded-full"
    />

    <p class="text-base sm:text-lg font-[buttershine-serif] mt-2 text-black dark:text-white text-center">
        Chew & Cheer
    </p>
</div>


<div class="my-6 px-4">
<div class="border-t border-gray-400"></div>
</div>


<!-- NAVIGATION -->
<nav class="flex-1 overflow-y-auto px-2 space-y-1">

<!-- Dashboard -->
<a href="{{ route('reportsAnalytics.index') }}"
class="group flex items-center px-4 py-3 rounded-xl transition-all
{{ request()->routeIs('reportsAnalytics.index')
? 'bg-gray-200 text-gray-900 shadow-md font-bold dark:bg-gray-700 dark:text-white'
: 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-200 dark:hover:bg-gray-700/60' }}">

<i class="fa-solid fa-table-columns w-5"></i>
<span class="ml-3 text-sm truncate">Dashboard</span>

</a>


<!-- Inventory Reports -->
<a href="{{ route('reports.inventory') }}"
class="flex items-center px-4 py-3 rounded-xl transition-all
{{ request()->routeIs('reports.inventory')
? 'bg-gray-200 text-gray-900 shadow-md font-bold dark:bg-gray-700 dark:text-white'
: 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-200 dark:hover:bg-gray-700/60' }}">

<i class="fa-solid fa-clipboard-list w-5"></i>
<span class="ml-3 text-sm truncate">Inventory Reports</span>

</a>


<!-- Product Performance -->
<a href="{{ route('reports.items') }}"
class="flex items-center px-4 py-3 rounded-xl transition-all
{{ request()->routeIs('reports.items')
? 'bg-gray-200 text-gray-900 shadow-md font-bold dark:bg-gray-700 dark:text-white'
: 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-200 dark:hover:bg-gray-700/60' }}">

<i class="fa-solid fa-chart-line w-5"></i>
<span class="ml-3 text-sm truncate">Product Performance</span>

</a>


<!-- Transactions -->
<a href="{{ route('reports.payments-reports') }}"
class="flex items-center px-4 py-3 rounded-xl transition-all
{{ request()->routeIs('reports.payments-reports')
? 'bg-gray-200 text-gray-900 shadow-md font-bold dark:bg-gray-700 dark:text-white'
: 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-200 dark:hover:bg-gray-700/60' }}">

<i class="fa-solid fa-receipt w-5"></i>
<span class="ml-3 text-sm truncate">Transaction Report</span>

</a>


<!-- Inventory Usage -->
<a href="{{ route('reports.inventoryUsage') }}"
class="flex items-center px-4 py-3 rounded-xl transition-all
{{ request()->routeIs('reports.inventoryUsage')
? 'bg-gray-200 text-gray-900 shadow-md font-bold dark:bg-gray-700 dark:text-white'
: 'text-gray-700 hover:bg-gray-200/70 dark:text-gray-200 dark:hover:bg-gray-700/60' }}">

<i class="fa-solid fa-boxes-stacked w-5"></i>
<span class="ml-3 text-sm truncate">Inventory Usage</span>

</a>

</nav>



<!-- PROFILE -->
<div class="p-4 bg-gray-100 dark:bg-gray-800">

<div class="flex justify-between items-center gap-3">

<img
src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}"
alt="Profile Image"
class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover shrink-0 max-w-full"
/>

<div class="flex flex-col min-w-0 flex-1">

<span class="text-sm font-bold text-black dark:text-white truncate">
{{ Auth::user()->name }}
</span>

<span class="text-xs text-black dark:text-white truncate">
{{ ucfirst(Auth::user()->role) }}
</span>

</div>

<a href="{{ route('inventory.index') }}">
<i class="fa-solid fa-arrow-left text-white"></i>
</a>

</div>

</div>

</aside>



<!-- RIGHT SIDE -->
<div class="flex-1 flex flex-col min-w-0">

<!-- HEADER -->
<header class="bg-gray-100 dark:bg-gray-800 px-4 sm:px-6 py-4 shadow-md">

<div class="flex items-center gap-4">

<button
class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 text-white"
onclick="openSidebar()">

<i class="fa-solid fa-bars"></i>

</button>


<h1 class="text-lg sm:text-2xl md:text-4xl text-black dark:text-white font-['Bricolage_Grotesque'] font-black truncate">

Reporting & Analytics

</h1>

</div>

</header>



<!-- MAIN -->
<main class="flex-1 overflow-y-auto bg-white">

{{ $slot }}

</main>

</div>

</div>



<script>

function openSidebar() {

document.getElementById("sidebar").classList.remove("-translate-x-full");
document.getElementById("sidebarOverlay").classList.remove("hidden");
document.body.classList.add("overflow-hidden");

}


function closeSidebar() {

document.getElementById("sidebar").classList.add("-translate-x-full");
document.getElementById("sidebarOverlay").classList.add("hidden");
document.body.classList.remove("overflow-hidden");

}


window.matchMedia("(min-width: 768px)").addEventListener("change", (e) => {

if (e.matches) closeSidebar();

});

</script>

</body>
</html>