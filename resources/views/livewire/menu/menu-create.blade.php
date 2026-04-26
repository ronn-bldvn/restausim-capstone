<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Menu Item') }}
            </h2>

            <a href="{{ route('menu.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    </x-slot>
    <div class="flex justify-end items-center">

            <a href="{{ route('menu.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    <form wire:submit.prevent="save" class="max-w-7xl mx-auto">
        {{-- MENU INFO --}}
        <div class="p-2 sm:p-3 md:p-4 lg:p-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-6">Menu Info</h3>

            <div class="flex flex-col items-center lg:flex-row gap-6">
                {{-- Image Upload --}}
                <div class="w-full md:w-96 flex-shrink-0">
                    <x-form.input-image
                        id="image"
                        :image="$image"
                        wire:model="image"
                        :errorMessage="$errors->first('image')"
                    />
                </div>

                {{-- Form Fields --}}
                <div class="flex-1 space-y-5 w-full">
                    <div class="w-full">
                        <x-form.input-field
                            id="name"
                            label="Name"
                            type="text"
                            wire:model="name"
                            :errorMessage="$errors->first('name')"
                        />
                    </div>

                    <div class="w-full">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Description</label>
                        <textarea
                            id="description"
                            rows="4"
                            class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 placeholder:text-gray-400"
                            placeholder="Write your thoughts here..."
                            wire:model="description"
                        ></textarea>
                    </div>

                    <div class="relative z-0 mb-5 group">

                        <x-form.input-select
                            id="category"
                            label="Category"
                            placeholder="choose category"
                            :selectData="$menuItemCategories"
                            :errorMessage="$errors->first('menu_item_category_id')"
                            wire:model="menu_item_category_id"
                        />
                    </div>

                    <div class="relative z-0 mb-5 group grid sm:grid-cols-3 gap-5 ">
                        <x-form.input-field
                            id="price"
                            label="Price"
                            type="number"
                            step="0.01"
                            wire:model="price"
                            :errorMessage="$errors->first('price')"
                        >
                            <x-slot name="icon">
                                ₱
                            </x-slot>
                        </x-form.input-field>

                        <x-form.input-field
                            id="cost"
                            label="Cost"
                            type="number"
                            step="0.01"
                            wire:model="cost"
                            :errorMessage="$errors->first('cost')"
                            disabled
                        >
                            <x-slot name="icon">
                                ₱
                            </x-slot>
                        </x-form.input-field>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="true" class="sr-only peer" wire:model="is_vat_exempt">
                            <div class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Vat Exempt</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- INGREDIENTS --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
            <h3 class="text-3xl font-bold dark:text-white mb-6 text-center">Ingredients</h3>

            <button
                type="button"
                class="mb-6 m px-5 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none"
                wire:click="openInventoryModal('base')"
            >
                Add Ingredient
            </button>

            <ul class="space-y-6 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($ingredients as $index => $ingredient)
                    <li class="pt-6 first:pt-0" wire:key="ingredient-{{ $ingredient['uid'] }}">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ingredient {{ $index + 1 }}</h4>

                        <div class="flex flex-col lg:flex-row gap-4 items-center">
                            {{-- Image --}}
                            <div class="flex flex-col lg:flex-row gap-4 items-center">
                                <img class="w-24 h-24 rounded-lg object-cover" src="{{ asset('storage/' . $ingredient['image']) }}" alt="{{ $ingredient['name'] }}">
                                <p class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                    {{ $ingredient['name'] }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                {{-- Removable Toggle --}}
                                <div class="flex items-center">
                                    <label for="removable-{{ $index }}" class="inline-flex items-center cursor-pointer">
                                        <input
                                            id="removable-{{ $index }}"
                                            type="checkbox"
                                            wire:model="removableIngredients"
                                            value="{{ $ingredient['uid'] }}"
                                            class="absolute w-0 h-0 opacity-0 peer"
                                        >
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Can Remove</span>
                                    </label>
                                </div>
                                @php
                                    $filteredUnits = $units[$ingredient['unit_category']] ?? [];
                                @endphp
                                
                                <div class="flex flex-col items-center">
                                    <x-form.input-number-select
                                        firstId="quantity"
                                        label="Quantity"
                                        :first-model="'ingredients.' . $index . '.quantity_used'"
                                        secondId="quantity_unit"
                                        :second-model="'ingredients.' . $index . '.unit_of_measurement_id'"
                                        :selectData="$filteredUnits"
                                        :errorMessageOne="$errors->first('ingredients.'. $index .'.quantity_used')"
                                        :errorMessageTwo="$errors->first('ingredients.'. $index .'.unit_of_measurement_id')"
                                    >
                                        <x-slot name="icon">
                                            ₱
                                        </x-slot>
                                    </x-form.input-number-select>
                                </div>
                                <div class="inline-flex items-center align-middle text-base font-semibold text-heading">
                                    <x-form.input-field
                                        id="ingredient_cost"
                                        label="Cost"
                                        type="number"
                                        step="0.01"
                                        wire:model="ingredients.{{ $index }}.cost"
                                        :errorMessage="$errors->first('ingredients.'.$index.'.cost')"
                                        disabled
                                    >
                                        <x-slot name="icon">
                                            ₱
                                        </x-slot>
                                    </x-form.input-field>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-2">
                                <button
                                    type="button"
                                    class="px-4 py-2 bg-[#69EACA] text-black font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                                    wire:click="openInventoryModal('base', '{{ $ingredient['uid'] }}')"
                                >
                                    Add Alternative
                                </button>
                                <button
                                    type="button"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none"
                                    wire:click="removeIngredient('base', '{{ $ingredient['uid'] }}')"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>

                        {{-- Alternative Ingredients --}}
                        @php
                            $hasAlternatives = collect($alternativeIngredients)->where('ingredient_uid', $ingredient['uid'])->isNotEmpty();
                        @endphp

                        @if($hasAlternatives)
                            <div class="mt-6 ml-0 lg:ml-12 pl-6 border-l-2 border-gray-200 dark:border-gray-700">
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alternative Ingredients</h5>

                                <ul class="space-y-4">
                                    @foreach ($alternativeIngredients as $alternativeIndex => $alternativeIngredient)
                                        @if($ingredient['uid'] == $alternativeIngredient['ingredient_uid'])
                                            <li class="flex flex-col lg:flex-row gap-4 items-center">
                                                {{-- Image --}}
                                                <div class="flex-shrink-0 flex flex-col items-center">
                                                    <img class="w-16 h-16 rounded-lg object-cover" src="{{ asset('storage/'. $alternativeIngredient['image']) }}" alt="{{ $alternativeIngredient['name'] }}">
                                                    <p class="text-base font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $alternativeIngredient['name'] }}
                                                    </p>
                                                </div>

                                                <div class="flex flex-col sm:flex-row gap-2">
                                                    <div class="flex flex-col items-center">
                                                        @php
                                                            $filteredUnits = $units[$alternativeIngredient['unit_category']] ?? []
                                                        @endphp
                                                        <x-form.input-number-select
                                                            firstId="alternative_quantity"
                                                            label="Quantity"
                                                            :first-model="'alternativeIngredients.' . $alternativeIndex . '.quantity_used'"
                                                            secondId="alternative_quantity_unit"
                                                            :second-model="'alternativeIngredients.' . $alternativeIndex . '.unit_of_measurement_id'"
                                                            :selectData="$filteredUnits"
                                                            :errorMessageOne="$errors->first('alternativeIngredients.'.$alternativeIndex.'.quantity_used')"
                                                            :errorMessageTwo="$errors->first('alternativeIngredients.'.$alternativeIndex.'.unit_of_measurement_id')"
                                                        >
                                                            <x-slot name="icon">
                                                                ₱
                                                            </x-slot>
                                                        </x-form.input-number-select>
                                                    </div>
                                                    <div class="inline-flex items-center align-middle text-base font-semibold text-heading">
                                                        <x-form.input-field
                                                            id="alternative_ingredient_cost"
                                                            label="Cost"
                                                            type="number"
                                                            step="0.01"
                                                            wire:model="alternativeIngredients.{{ $alternativeIndex }}.cost"
                                                            :errorMessage="$errors->first('alternativeIngredients.'. $alternativeIndex .'.cost')"
                                                            disabled
                                                        >
                                                            <x-slot name="icon">
                                                                ₱
                                                            </x-slot>
                                                        </x-form.input-field>
                                                    </div>
                                                    <div class="inline-flex items-center align-middle text-base font-semibold text-heading">
                                                        <x-form.input-field
                                                            id="alternative_ingredient_price"
                                                            label="Price"
                                                            type="number"
                                                            step="0.01"
                                                            wire:model="alternativeIngredients.{{ $alternativeIndex }}.price"
                                                            :errorMessage="$errors->first('alternativeIngredients.'. $alternativeIndex .'.price')"
                                                        >
                                                            <x-slot name="icon">
                                                                ₱
                                                            </x-slot>
                                                        </x-form.input-field>
                                                    </div>
                                                </div>

                                                {{-- Remove Button --}}
                                                <div class="flex items-end h-full">
                                                    <button
                                                        type="button"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none"
                                                        wire:click="removeIngredient('base', '{{ $ingredient['uid'] }}', '{{ $alternativeIngredient['uid'] }}')"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ADDITIONAL INGREDIENTS --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 mb-6">
            <h3 class="text-3xl font-bold dark:text-white mb-6 text-center">Additional Ingredients</h3>

            <button
                type="button"
                class="mb-6 m px-5 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none"
                wire:click="openInventoryModal('additional')"
            >
                Add Ingredient
            </button>

            <ul class="space-y-6 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($additionalIngredients as $additionalIndex => $additionalIngredient)
                    <li class="pt-6 first:pt-0">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ingredient {{ $additionalIndex + 1 }}</h4>

                        <div class="flex flex-col lg:flex-row gap-4 items-center">
                            {{-- Image --}}
                            <div class="flex-shrink-0">
                                <img class="w-24 h-24 rounded-lg object-cover" src="{{ asset('storage/' . $additionalIngredient['image']) }}" alt="{{ $additionalIngredient['name'] }}">
                                <p class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                    {{ $additionalIngredient['name'] }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="flex flex-col items-center">
                                    @php
                                        $filteredUnits = $units[$additionalIngredient['unit_category']] ?? []
                                    @endphp
                                    <x-form.input-number-select
                                        firstId="additional_quantity"
                                        label="Quantity"
                                        :first-model="'additionalIngredients.' . $additionalIndex . '.quantity_used'"
                                        secondId="additional_quantity_unit"
                                        :second-model="'additionalIngredients.' . $additionalIndex . '.unit_of_measurement_id'"
                                        :selectData="$filteredUnits"
                                        :errorMessageOne="$errors->first('additionalIngredients.'.$additionalIndex.'.quantity_used')"
                                        :errorMessageTwo="$errors->first('additionalIngredients.'.$additionalIndex.'.unit_of_measurement_id')"
                                    >
                                        <x-slot name="icon">
                                            ₱
                                        </x-slot>
                                    </x-form.input-number-select>
                                </div>
                                <div class="inline-flex items-center align-middle text-base font-semibold text-heading">
                                    <x-form.input-field
                                        id="additional_ingredient_cost"
                                        label="Cost"
                                        type="number"
                                        step="0.01"
                                        wire:model="additionalIngredients.{{ $additionalIndex }}.cost"
                                        :errorMessage="$errors->first('additionalIngredients.'. $additionalIndex .'.cost')"
                                        disabled
                                    >
                                        <x-slot name="icon">
                                            ₱
                                        </x-slot>
                                    </x-form.input-field>
                                </div>
                                <div class="inline-flex items-center align-middle text-base font-semibold text-heading">
                                    <x-form.input-field
                                        id="additional_ingredient_price"
                                        label="Price"
                                        type="number"
                                        step="0.01"
                                        wire:model="additionalIngredients.{{ $additionalIndex }}.price"
                                        :errorMessage="$errors->first('additionalIngredients.'. $additionalIndex .'.price')"
                                    >
                                        <x-slot name="icon">
                                            ₱
                                        </x-slot>
                                    </x-form.input-field>
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <div class="flex items-end h-full">
                                <button
                                    type="button"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none"
                                    wire:click="removeIngredient('additional', '{{ $additionalIngredient['uid'] }}')"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            wire:target="save"
            wire:loading.attr="disabled" 
            class="w-full mb-6 m px-5 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none"
        >
            <span wire:loading.remove wire:target="save">Save</span>
            <span wire:loading wire:target="save">Saving...</span>
        </button>
    </form>

    {{-- Error Display --}}
    @if($errors->any())
        <div class="p-8 max-w-7xl mx-auto">
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Validation errors:</span>
                <ul class="mt-2 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Inventory Modal --}}
    @if ($showInventoryModal)
        @teleport('body')
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-50">
            <div class="relative w-full max-w-4xl max-h-[90vh] bg-white rounded-lg shadow-lg dark:bg-gray-800 flex flex-col">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700 rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Inventory
                    </h3>
                    <div class="relative w-96">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" wire:model.live.debounce.1000ms="searchInput"/>
                    </div>
                    <button
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        wire:click="closeInventoryModal"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 p-5">
                    {{-- Category Tabs --}}
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700 overflow-x-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-gray-500 [&::-webkit-scrollbar-track]:bg-gray-200">
                        <ul class="flex flex-nowrap text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                            <li class="mr-2 flex-shrink-0">
                                <button
                                    type="button"
                                    class="inline-block p-4 rounded-t-lg transition-colors {{ (!$active) ? 'text-blue-600 bg-gray-100 dark:bg-gray-700 dark:text-blue-500':'hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-gray-300' }}"
                                    wire:click="switchTabs('')"
                                >
                                    All
                                </button>
                            </li>
                            @foreach ($categories as $category)
                                <li class="mr-2 flex-shrink-0">
                                    <button
                                        type="button"
                                        wire:click="switchTabs({{ $category->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="switchTabs"
                                        class="inline-block p-4 rounded-t-lg transition-colors {{ ($active == $category->id) ? 'text-blue-600 bg-gray-100 dark:bg-gray-700 dark:text-blue-500':'hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-gray-300' }}"
                                        wire:click="switchTabs({{ $category->id }})"
                                    >
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Inventory Grid --}}
                    <div 
                        wire:loading.remove
                        wire:target="switchTabs,gotoPage"
                        class="">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-3 overflow-y-auto max-h-80 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-gray-500 [&::-webkit-scrollbar-track]:bg-gray-200">
                            @foreach ($inventories as $inventory)
                                <div
                                    class="cursor-pointer group"
                                    wire:key="inventory-{{ $inventory->id }}"
                                    wire:click="addIngredient({{ $inventory->id }})"
                                >
                                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 transition-all hover:shadow-md hover:scale-105">
                                        <div class="aspect-square overflow-hidden">
                                            <img class="w-full h-full object-cover group-hover:opacity-90" src="{{ asset('storage/' . $inventory->image) }}" alt="{{ $inventory->name }}" />
                                        </div>
                                        <div class="p-3">
                                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $inventory->name }}
                                            </h5>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $inventory->code }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        @if($inventories->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">No inventory items found</p>
                            </div>
                        @endif
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            {{ $inventories->links() }}
                        </div>
                    </div>

                    <div
                        wire:loading
                        wire:target="switchTabs,gotoPage"
                        class=""
                    >
                        @for ($i = 0; $i < 10; $i++)
                            <div class="animate-pulse">
                                <div class="bg-gray-200 dark:bg-gray-600 rounded-lg aspect-square"></div>
                                <div class="mt-2 h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4"></div>
                                <div class="mt-1 h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2"></div>
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>