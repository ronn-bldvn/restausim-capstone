<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF TOKEN (REQUIRED FOR FETCH POST REQUESTS) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'RestauSim' }}</title>

    <x-favicon />
    @include('partials.links.links')

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="font-[Barlow]">
    @include('partials.includes.header')

    <div class="flex min-h-screen">
        @include('partials.includes.sidebar')

        <div id="content-wrapper" class="flex flex-1 overflow-hidden transition-all duration-300 md:ml-20 ml-0">

            {{ $slot }}
        </div>
    </div>

    @vite('resources/js/app.js')
</body>

</html>
