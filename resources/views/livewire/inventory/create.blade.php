
<div>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Inventory') }}
            </h2>

            <a href="{{ route('inventory.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
    </x-slot>

    <form wire:submit.prevent="store" enctype="multipart/form-data" class="">
        <div class="flex flex-col md:flex-row justify-end items-start md:items-center gap-3 md:gap-0">

            <a href="{{ route('inventory.index') }}"
                class="inline-block mb-5 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    &larr; Back
            </a>
        </div>
        <div class="flex flex-col lg:flex-row items-center md:space-x-6 lg:p-5 md:p-4 sm:p-3 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
            <div class="w-full md:w-80 mb-5 md:mb-0 flex items-center justify-center">
                <x-form.input-image
                    id="image"
                    :image="$inventoryDataToSave['image']"
                    wire:model="inventoryDataToSave.image"
                    :errorMessage="$errors->first('inventoryDataToSave.image')"
                />
            </div>
            <div class="flex flex-col justify-between p-4 leading-normal w-full gap-5">
                <div class="">
                    <h5 class="text-xl font-bold dark:text-white mb-3">Basic Information</h5>
                    <div class="grid lg:grid-cols-2 md:gap-3 gap-2">

                        <x-form.input-field
                            id="name"
                            label="Name"
                            type="text"
                            wire:model="inventoryDataToSave.name"
                            :errorMessage="$errors->first('inventoryDataToSave.name')"
                        />

                        <x-form.input-select
                            id="inventory_unit"
                            label="Inventory Unit"
                            placeholder="choose unit"
                            :selectData="$units"
                            :isGrouped="true"
                            :isUnit="true"
                            :errorMessage="$errors->first('inventoryDataToSave.inventory_unit_id')"
                            wire:model.live="inventoryDataToSave.inventory_unit_id"
                        />

                        <x-form.input-select
                            id="category"
                            label="Inventory Category"
                            placeholder="choose category"
                            :selectData="$categories"
                            :errorMessage="$errors->first('inventoryDataToSave.inventory_category_id')"
                            wire:model.live="inventoryDataToSave.inventory_category_id"
                        />
                    </div>
                </div>
                <div>
                    <h5 class="text-xl font-bold dark:text-white mb-3">Cost Information</h5>
                    <div class="grid lg:grid-cols-2 md:gap-3 gap-2">

                        <x-form.input-number-select
                            firstId="cost"
                            label="Unit Cost"
                            :first-model="'inventoryDataToSave.cost'"
                            secondId="cost_unit"
                            selectPlaceholder="Choose Unit"
                            :second-model="'inventoryDataToSave.cost_unit_id'"
                            :selectData="$filteredUnits"
                            :errorMessageOne="$errors->first('inventoryDataToSave.cost')"
                            :errorMessageTwo="$errors->first('inventoryDataToSave.cost_unit_id')"
                        >
                            <x-slot name="icon">
                                ₱
                            </x-slot>
                        </x-form.input-number-select>

                    </div>
                </div>
                <div>
                    <h5 class="text-xl font-bold dark:text-white mb-3">Stock Information</h5>
                    <div class="grid lg:grid-cols-2 md:gap-3 gap-2">

                        <x-form.input-number-select
                            firstId="quantity"
                            label="Initial Quantity"
                            :first-model="'inventoryDataToSave.opening_quantity'"
                            secondId="opening_unit"
                            selectPlaceholder="Choose Unit"
                            :second-model="'inventoryDataToSave.opening_quantity_unit_id'"
                            :selectData="$filteredUnits"
                            :errorMessageOne="$errors->first('inventoryDataToSave.opening_quantity')"
                            :errorMessageTwo="$errors->first('inventoryDataToSave.opening_quantity_unit_id')"
                        />

                        <x-form.input-field
                            id="par_level"
                            label="Par Level"
                            type="number"
                            wire:model="inventoryDataToSave.par_level"
                            :errorMessage="$errors->first('inventoryDataToSave.par_level')"
                        />
                    </div>
                </div>
            </div>
        </div>


        <button type="submit" class="w-full mt-5 items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">Submit</button>
    </form>
</div>