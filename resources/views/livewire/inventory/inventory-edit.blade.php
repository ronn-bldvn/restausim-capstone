<div>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Update Inventory Details') }}
            </h2>

            <a href="{{ route('inventory.index') }}"
                class="inline-block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    </x-slot>

    <div class="flex flex-col md:flex-row justify-end items-start md:items-center gap-3 md:gap-0">


            <a href="{{ route('inventory.index') }}"
                class="inline-block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>

    <form enctype="multipart/form-data" wire:submit.prevent="update" class="mt-5">
        <div class="flex flex-col lg:flex-row items-center md:space-x-6 lg:p-5 md:p-4 sm:p-3 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">

            <!-- Image Upload -->
            <div class="w-full md:w-80 mb-5 md:mb-0 flex items-center justify-center">
                <x-form.input-image
                    id="image"
                    :image="$image"
                    :storedImage="$storedImage"
                    wire:model="image"
                    :errorMessage="$errors->first('image')"
                />
            </div>

            <!-- Form Fields -->
            <div class="flex-1 flex flex-col space-y-5">

                <!-- Basic Information -->
                <div>
                    <h5 class="text-xl font-bold dark:text-white mb-3">Basic Information</h5>
                    <div class="grid lg:grid-cols-2 md:gap-3 gap-2">
                        <x-form.input-field
                            id="name"
                            label="Name"
                            type="text"
                            wire:model="name"
                            :errorMessage="$errors->first('name')"
                        />

                        <x-form.input-select
                            id="inventory_unit"
                            label="Inventory Unit"
                            placeholder="choose unit"
                            :selectData="$units"
                            :isUnit="true"
                            :errorMessage="$errors->first('inventory_unit_id')"
                            wire:model.live="inventory_unit_id"
                        />

                        <x-form.input-select
                            id="category"
                            label="Inventory Category"
                            placeholder="choose category"
                            :selectData="$categories"
                            :errorMessage="$errors->first('inventory_category_id')"
                            wire:model.live="inventory_category_id"
                        />

                        {{-- <div class="relative w-full">
                            <select id="inventory_unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                wire:model.live="inventory_unit_id">
                                <option value="">Inventory Unit</option>
                                @foreach ($units as $unitData)
                                    <option value="{{ $unitData->id }}">{{ Str::ucfirst($unitData->name) }} ({{ $unitData->symbol }})</option>
                                @endforeach
                            </select>
                        </div> --}}

                        {{-- <div>
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                            <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                wire:model="inventory_category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>

                <!-- Cost Information -->
                <div>
                    <h5 class="text-xl font-bold dark:text-white mb-3">Cost Information</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-form.input-number-select
                            firstId="cost"
                            label="Unit Cost"
                            :first-model="'unit_cost'"
                            secondId="cost_unit"
                            selectPlaceholder="Choose Unit"
                            :second-model="'cost_unit_id'"
                            :selectData="$filteredUnits"
                            :errorMessageOne="$errors->first('unit_cost')"
                            :errorMessageTwo="$errors->first('cost_unit_id')"
                        >
                            <x-slot name="icon">
                                ₱
                            </x-slot>
                        </x-form.input-number-select>

                        {{-- <x-form.floating-input id="cost" label="Unit Cost" type="number" wire:model="unit_cost" />
                        <div class="relative w-full">
                            <select id="cost_unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                wire:model="cost_unit_id">
                                <option value="">Cost Unit</option>
                                @foreach ($filteredUnits as $filteredUnit)
                                    <option value="{{ $filteredUnit->id }}">{{ Str::ucfirst($filteredUnit->name) }} ({{ $filteredUnit->symbol }})</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>

                <!-- Stock Information -->
                <div>
                    <h5 class="text-xl font-bold dark:text-white mb-3">Stock Information</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <x-form.input-field
                            id="par_level"
                            label="Par Level"
                            type="number"
                            wire:model="par_level"
                            :errorMessage="$errors->first('par_level')"
                        />

                        {{-- <x-form.floating-input id="par_level" label="Par Level" type="number" wire:model="par_level" /> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full text-white bg-blue-700 mt-5 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
        </button>
    </form>
</div>