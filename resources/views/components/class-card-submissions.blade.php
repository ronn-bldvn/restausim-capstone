@props(['role', 'background', 'id'])

{{-- Simplified Card - Only Role and Background --}}
<div id="{{ $id }}"
     class="w-56 h-56 rounded-lg border overflow-hidden bg-[#C7C7C7] flex flex-col cursor-pointer hover:shadow-lg transition"
     data-role="{{ $role }}">

    {{-- Background Image --}}
    <div class="flex-1 overflow-hidden">
        <img src="{{ $background }}"
             alt="{{ $role }}"
             class="w-full h-full object-cover">
    </div>

    {{-- Role Label --}}
    <div class="text-sm font-bold mx-3 m-2">
        Simulation Role: {{ $role }}
    </div>
</div>
