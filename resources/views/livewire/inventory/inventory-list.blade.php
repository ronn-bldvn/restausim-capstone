<div>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventory Management') }}
            </h2>

            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                @can('create inventory')
                    <a href="{{ route('inventory.create') }}" class="w-full md:w-auto">
                        <button type="button"
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Item
                        </button>
                    </a>
                @endcan
                @can('restock inventory')
                    <a href="{{ route('inventory.restock') }}" class="w-full md:w-auto">
                        <button type="button"
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Restock
                        </button>
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="mt-6">
        <div class="mb-6 flex flex-col md:flex-row justify-between   items-start md:items-center gap-3 md:gap-0">
            {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventory Management') }}
            </h2> --}}

            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="{{ route('inventory.create') }}" class="w-full md:w-auto">
                    <button type="button"
                        class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Item
                    </button>
                </a>
                <a href="{{ route('inventory.restock') }}" class="w-full md:w-auto">
                    <button type="button"
                        class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Restock
                    </button>
                </a>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="relative w-full sm:max-w-md">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by product name or code..."
                        class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.6-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                @if($search)
                    <button
                        type="button"
                        wire:click="$set('search','')"
                        class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-sm font-semibold">
                        Clear
                    </button>
                @endif
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">

            {{-- Tabs --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-4 overflow-x-auto">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px overflow-x-auto sm:flex hidden" aria-label="Tabs">
                        <button
                            class="group flex-shrink-0 inline-flex items-center px-4 sm:px-6 py-3 border-b-2 font-medium text-sm transition-all duration-200
                                {{ (!$active) ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                            wire:click="switchTabs('')">
                            All Items
                        </button>
                        @foreach ($categories as $category)
                            <button
                                class="group flex-shrink-0 inline-flex items-center px-4 sm:px-6 py-3 border-b-2 font-medium text-sm transition-all duration-200
                                    {{ ($active == $category->id) ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                                wire:click="switchTabs({{ $category->id }})">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </nav>
                    
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 sm:hidden"
                    wire:change="switchTabs($event.target.value)">
                        <option value="">All Items</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table for md+ screens --}}
            <div class="hidden md:block bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                @if (!$inventories->isEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Stock Level
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Par Level
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Cost per Unit
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($inventories as $index => $inventory)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ++$index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @php
                                                    $imagePath = 'storage/' . $inventory->image;
                                                    $hasImage = !empty($inventory->image) && file_exists(public_path($imagePath));
                                                @endphp
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <img
                                                        src="{{ $hasImage ? asset($imagePath) : asset('images/default-placeholder.jpg') }}"
                                                        class="h-12 w-12 rounded-lg object-cover shadow-sm border-2 border-gray-200 dark:border-gray-600"
                                                        alt="{{ $inventory->name }}"
                                                    >
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        {{ $inventory->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap flex items-center justify-center">
                                            @php
                                                if ($inventory->quantity_on_hand <= 0) {
                                                    $status = 'No Stock';
                                                    $statusColor = 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400 ';
                                                } elseif ($inventory->quantity_on_hand < $inventory->par_level) {
                                                    $status = 'Below Par Level';
                                                    $statusColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
                                                } else {
                                                    $status = 'Has Stock';
                                                    $statusColor = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $statusColor }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $inventory->quantity_on_hand }}
                                                </div>
                                                <div class="ml-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $inventory->inventoryUnit->symbol }}s
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $inventory->par_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            <span>₱{{ $inventory->cost_per_unit }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-3">
                                                @can('edit inventory')
                                                    <a href="{{ route('inventory.edit', $inventory) }}"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endcan
                                                @can('delete inventory')
                                                    <button
                                                        wire:click="openDeleteModal({{ $inventory->id }})"
                                                        class="inline-flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $inventories->links() }}
                    </div>
                @endif
            </div>

            @if($showDeleteModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-black/50" wire:click="closeDeleteModal"></div>

                    {{-- Modal --}}
                    <div class="relative bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Delete inventory item?
                        </h3>

                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            This action cannot be undone.
                        </p>

                        <div class="mt-6 flex justify-end gap-2">
                            <button
                                type="button"
                                wire:click="closeDeleteModal"
                                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800">
                                Cancel
                            </button>

                            <button
                                type="button"
                                wire:click="deleteInventory"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                                <span wire:loading.remove wire:target="deleteInventory">Delete</span>
                                <span wire:loading wire:target="deleteInventory">Deleting...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Card view for mobile --}}
            <div class="md:hidden space-y-4">
                @foreach ($inventories as $inventory)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ !empty($inventory->image) && file_exists(public_path('storage/'.$inventory->image)) ? asset('storage/'.$inventory->image) : asset('images/default-placeholder.jpg') }}"
                                    class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $inventory->name }}</div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $inventory->code }}</span>
                                </div>
                            </div>
                            @php
                                if ($inventory->quantity_on_hand <= 0) { $status='No Stock'; $statusColor='bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400 whitespace-nowrap'; }
                                elseif ($inventory->quantity_on_hand < $inventory->par_level) { $status='Below Par Level'; $statusColor='bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 whitespace-nowrap'; }
                                else { $status='Has Stock'; $statusColor='bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 whitespace-nowrap'; }
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $statusColor }}">{{ $status }}</span>
                        </div>
                        <div class="flex flex-wrap justify-between text-sm text-gray-700 dark:text-gray-300 mt-2 gap-2">
                            <div>Stock: {{ $inventory->quantity_on_hand }} {{ $inventory->inventoryUnit->symbol }}s</div>
                            <div>Par Level: {{ $inventory->par_level }}</div>
                            <div>Cost: ${{ $inventory->cost_per_unit }}</div>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('inventory.edit', $inventory) }}" class="flex-1 inline-flex justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Edit</a>
                            <button wire:click="openDeleteModal({{ $inventory->id }})" class="flex-1 inline-flex justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">Remove</button>
                        </div>
                    </div>
                @endforeach
                <div class="px-2">
                    {{ $inventories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>