<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Restock Inventory') }}
            </h2>

            <a href="{{ route('inventory.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <!-- Action Button -->
            <div class="flex justify-between items-center mb-6">
                <button type="button"
                    class="items-center px-4 py-2.5 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                    wire:click="openSelectModal">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Select Inventory
                    </span>
                </button>

                <a href="{{ route('inventory.index') }}"
                    class="inline-block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        &larr; Back
                </a>
            </div>

            <!-- Main Content -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit.prevent="restock">
                        @if (!empty($selectedInventories))
                            <!-- Desktop Table View -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 w-32">
                                                Image
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Product
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Cost/Unit
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Current Stock
                                            </th>
                                            <th scope="col" class="px-6 py-3 min-w-[280px]">
                                                Stock to Add
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($selectedInventories as $index => $selected)
                                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4">
                                                    <img src="{{ asset('storage/' . $selected['image']) }}"
                                                         class="w-20 h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                                                         alt="{{ $selected['name'] }}">
                                                </td>
                                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                    {{ $selected['name'] }}
                                                </td>
                                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                    ₱{{ number_format((float)$selected['cost_per_unit'], 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                        {{ $selected['quantity'] }} {{ $selected['unit_of_measurement'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex-1">
                                                            <input
                                                                id="quantity-{{ $index }}"
                                                                type="number"
                                                                min="0"
                                                                step="0.01"
                                                                placeholder="Quantity"
                                                                wire:model="selectedInventories.{{ $index }}.addQuantity"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            />
                                                        </div>
                                                        <div class="flex-1">
                                                            <select
                                                                id="unit-{{ $index }}"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                wire:model="selectedInventories.{{ $index }}.addUnitOfMeasurement">
                                                                @foreach ($units as $unitIndex => $unit)
                                                                    @if ($selected['unit_category'] == $unitIndex)
                                                                        @foreach ($unit as $unitData)
                                                                            <option value="{{ $unitData->symbol }}">{{ Str::ucfirst($unitData->name) }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="md:hidden space-y-4">
                                @foreach ($selectedInventories as $index => $selected)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex gap-4 mb-4">
                                            <img src="{{ asset('storage/' . $selected['image']) }}"
                                                 class="w-20 h-20 object-cover rounded-lg"
                                                 alt="{{ $selected['name'] }}">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                                    {{ $selected['name'] }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Cost: ₱{{ number_format((float)$selected['cost_per_unit'], 2) }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Current: {{ $selected['quantity'] }} {{ $selected['unit_of_measurement'] }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock to Add</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    placeholder="Quantity"
                                                    wire:model="selectedInventories.{{ $index }}.addQuantity"
                                                    class="bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                />
                                                <select
                                                    class="bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                    wire:model="selectedInventories.{{ $index }}.addUnitOfMeasurement">
                                                    @foreach ($units as $unitIndex => $unit)
                                                        @if ($selected['unit_category'] == $unitIndex)
                                                            @foreach ($unit as $unitData)
                                                                <option value="{{ $unitData->symbol }}">{{ Str::ucfirst($unitData->name) }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            {{-- <div class="mt-6">
                                {{ $inventories->links() }}
                            </div> --}}

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full mt-6 px-5 py-3 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Submit Restock
                                </span>
                            </button>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <h3 class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">No Items Selected</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Click "Select Inventory" to add items for restocking</p>
                            </div>
                        @endif
                    </form>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">Please fix the following errors:</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showSelectModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeSelectModal"></div>

            <!-- Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Select Inventory Items
                        </h3>
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            wire:click="closeSelectModal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <!-- Category Tabs -->
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <div class="flex overflow-x-auto scrollbar-hide -mb-px space-x-2">
                                <button
                                    class="whitespace-nowrap px-4 py-3 text-sm font-medium transition-colors border-b-2 {{ (!$active) ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500':'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                                    wire:click="switchTabs('')">
                                    All
                                </button>
                                @foreach ($categories as $category)
                                    <button
                                        class="whitespace-nowrap px-4 py-3 text-sm font-medium transition-colors border-b-2 {{ ($active == $category->id) ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500':'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                                        wire:click="switchTabs({{ $category->id }})">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Inventory Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 max-h-[400px] overflow-y-auto pr-2">
                            @foreach ($inventories as $inventory)
                                <div class="group cursor-pointer transition-transform hover:scale-105"
                                    wire:key="inventory-{{ $inventory->id }}"
                                    wire:click="addInventory({{ $inventory->id }})">
                                    <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden transition-all {{ (array_search($inventory->id, array_column($this->selectedInventories, 'id')) !== false) ? 'ring-2 ring-blue-600 dark:ring-blue-500 shadow-lg':'shadow hover:shadow-md border border-gray-200 dark:border-gray-600' }}">
                                        <div class="relative aspect-square">
                                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $inventory->image) }}" alt="{{ $inventory->name }}" />
                                            @if (array_search($inventory->id, array_column($this->selectedInventories, 'id')) !== false)
                                                <div class="absolute top-2 right-2 bg-blue-600 text-white rounded-full p-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-3">
                                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $inventory->name }}">
                                                {{ $inventory->name }}
                                            </h5>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $inventory->quantity }} {{ $inventory->unit_of_measurement }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 darkbg-gray-800 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ count($selectedInventories) }} item(s) selected
                            </p>
                            <button type="button"
                                class="px-4 py-2 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                                wire:click="closeSelectModal">
                                Done
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endteleport
    @endif

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>
