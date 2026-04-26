<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Inventory') }}
            </h2>

            <a href="{{ route('inventory.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    </x-slot>

    <form wire:submit.prevent="update" enctype="multipart/form-data" class="">
            <div class="flex flex-col items-center p-5 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-full dark:border-gray-700 dark:bg-gray-800 mb-5">
                <div class="flex flex-col justify-between p-4 leading-normal w-full">
                    <div class="mb-3">
                        <div class="grid md:grid-cols-2 gap-3">

                            <x-form.input-field
                                id="name"
                                label="Name"
                                type="text"
                                wire:model="name"
                            />

                            <x-form.input-select
                                id="discount_type"
                                label="Applies To"
                                placeholder="choose type"
                                :selectData="$applies_to_data"
                                :errorMessage="$errors->first('type')"
                                wire:model.change="type"
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="grid md:grid-cols-3 gap-3 items-end">

                            <x-form.input-select
                                id="discount_type"
                                label="Discount Type"
                                placeholder="choose type"
                                :selectData="$discount_type_data"
                                :errorMessage="$errors->first('discount_type')"
                                wire:model="discount_type"
                            />
                            
                            <x-form.input-field
                                id="value"
                                label="Amount"
                                type="number"
                                step="0.01"
                                wire:model="discount_value"
                            />

                            <label class="inline-flex items-center cursor-pointer mb-3">
                                <input type="checkbox" value="" class="sr-only peer" wire:model="is_vat_exempt">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Vat Exempt</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            @if($show_item_select)
            <div class=" p-5 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-full dark:border-gray-700 dark:bg-gray-800 mb-5">
                <!-- Action Button -->
                <div class="mb-6">
                    <button type="button"
                        class="items-center px-4 py-2.5 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                        wire:click="openSelectModal">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Select Item
                        </span>
                    </button>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                    @foreach ($selectedMenuItem as $item)
                        <div
                            class="cursor-pointer transition-transform duration-200 hover:-translate-y-1 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800 hover:shadow-md overflow-hidden flex flex-col"
                        >
                            <!-- Image -->
                            <div class="relative">
                                <img
                                    class="h-28 w-full object-cover"
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->name }}"
                                />
                            </div>

                            <!-- Content -->
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <h5
                                    class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                    title="{{ $item->name }}"
                                >
                                    {{ $item->name }}
                                </h5>

                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    ₱{{ number_format($item->price, 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        <button type="submit" class="w-full mt-5 items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">Submit</button>
    </form>

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
                                    <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden transition-all {{ (array_search($inventory->id, array_column($this->selectedMenuItem, 'id')) !== false) ? 'ring-2 ring-blue-600 dark:ring-blue-500 shadow-lg':'shadow hover:shadow-md border border-gray-200 dark:border-gray-600' }}">
                                        <div class="relative aspect-square">
                                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $inventory->image) }}" alt="{{ $inventory->name }}" />
                                            @if (array_search($inventory->id, array_column($this->selectedMenuItem, 'id')) !== false)
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
                                {{ count($selectedMenuItem) }} item(s) selected
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
</div>