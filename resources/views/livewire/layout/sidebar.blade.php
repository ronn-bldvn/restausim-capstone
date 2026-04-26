<?php
use Livewire\Volt\Component;
new class extends Component {
    //
}; ?>

<div class="h-full flex flex-col">

    {{-- ── Nav Links ──────────────────────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto mt-4 px-2 space-y-0.5">

        {{-- Inventory --}}
        @canany(['view inventory','create inventory','edit inventory','restock inventory','delete inventory'])
        <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                <path d="M12 3C6.49 3 2 7.49 2 13v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-6c0-5.51-4.49-10-10-10m4 12H8v-2h8zm-8 4v-2h8v2zm12 0h-2v-6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v6H4v-6c0-4.41 3.59-8 8-8s8 3.59 8 8z"/>
            </svg>
            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Inventory</span>
        </x-nav-link>
        @endcanany

        {{-- Menu --}}
        @canany(['view inventory','create inventory','edit inventory','restock inventory','delete inventory'])
        <x-nav-link :href="route('menu.index')" :active="request()->routeIs('menu.*')" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                <path d="M10 9H8V2H6v7H4V2H2v8c0 1.65 1.35 3 3 3h1v9h2v-9h1c1.65 0 3-1.35 3-3V2h-2zm8-7c-2.4 0-4 3.76-4 6.25 0 2.21 1.28 4.05 3 4.58V22h2v-9.17c1.72-.53 3-2.37 3-4.58C22 5.76 20.4 2 18 2"/>
            </svg>
            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Menu</span>
        </x-nav-link>
        @endcanany

        {{-- Floor Plan / Orders --}}
        @canany(['view floorplan','create floorplan','view table','manage table','take orders','apply discount','process payment'])
        <x-nav-link :href="route('floorplan.index')" :active="request()->routeIs('floorplan.*')" wire:navigate>
            @can('create floorplan')
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                    <path d="m21.51 6.14-5-3a.99.99 0 0 0-.87-.08L8.09 5.89 3.51 3.14a.99.99 0 0 0-1.01-.01c-.31.18-.51.51-.51.87v13c0 .35.18.68.49.86l5 3c.26.16.58.19.87.08l7.55-2.83 4.59 2.75c.16.1.34.14.51.14s.34-.04.49-.13c.31-.18.51-.51.51-.87V7a.99.99 0 0 0-.49-.86M7 18.23l-3-1.8V5.77l3 1.8v10.67Zm8-1.93-6 2.25V7.69l6-2.25zm5 1.93-3-1.8V5.77l3 1.8v10.67Z"/>
                </svg>
                <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Floor Plan</span>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                    <path d="M19 12h-1V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v9H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h1v4h2v-4h8v4h2v-4h1c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1M8 4h8v8H8zm10 12H6v-2h12z"/>
                </svg>
                <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Orders</span>
            @endcan
        </x-nav-link>
        @endcanany

        {{-- Kitchen --}}
        @canany(['view kitchen orders','manage kitchen orders'])
        <x-nav-link :href="route('kitchen.dashboard')" :active="request()->routeIs('kitchen.*')" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                <path d="M2 19h20v2H2zM13 5.05V3h-2v2.05c-5.05.5-9 4.77-9 9.95v1c0 .55.45 1 1 1h18c.55 0 1-.45 1-1v-1c0-5.18-3.95-9.45-9-9.95M4 15c0-4.41 3.59-8 8-8s8 3.59 8 8z"/>
            </svg>
            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Kitchen</span>
        </x-nav-link>
        @endcanany

        {{-- Discounts --}}
        @canany(['create discount','edit discount','view discount','delete discount'])
        <x-nav-link :href="route('discount.index')" :active="request()->routeIs('discount.*')" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                <path d="M11.71 2.29A1 1 0 0 0 11 2H6c-.27 0-.52.11-.71.29l-3 3A1 1 0 0 0 2 6v5c0 .27.11.52.29.71l10 10c.2.2.45.29.71.29s.51-.1.71-.29l8-8a.996.996 0 0 0 0-1.41zM13 19.58l-9-8.99V6.42l2.41-2.41h4.17l9 9-6.59 6.59Z"/>
                <path d="M8 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2"/>
            </svg>
            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Discounts</span>
        </x-nav-link>
        @endcanany

        {{-- Reports & Analytics --}}
        @can('view reports and analytics')
        <x-nav-link :href="route('reportsAnalytics.index')" :active="request()->routeIs('reportsAnalytics.*')">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="shrink-0">
                <path d="M3 15h2v6H3zm4-2h2v8H7zm4-1h2v9h-2zm4 1h2v8h-2zm4-5h2v13h-2z"/>
                <path d="m19.21 2.38-4.87 6.21-5-4-6.13 7.79 1.58 1.24 4.87-6.21 5 4 6.13-7.79z"/>
            </svg>
            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black text-white">Reports &amp; Analytics</span>
        </x-nav-link>
        @endcan

    </nav>

    {{-- ── Recent Activity (if used) ──────────────────────────────────── --}}
    <div class="mt-2 px-2 space-y-2">
        <livewire:layout.recent-activity />
    </div>

</div>