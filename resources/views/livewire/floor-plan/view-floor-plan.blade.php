<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Floorplan Management') }}
            </h2>

            {{-- Action Buttons --}}
            {{-- <div class="flex flex-wrap gap-3">
                <a href="{{ route('floorplan.create') }}">
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Create
                    </button>
                </a>
            </div> --}}
        </div>
    </x-slot>
    
    <div x-data x-init="initKonva()" class="grid lg:grid-cols-[2fr_1fr] grid-cols-1 gap-5 overflow-x-hidden">
        <div class="">
            <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 mb-3">

                @if (auth()->user()->can('create floorplan') || auth()->user()->can('manage table'))
                    <button
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-md text-white"
                        :class="{
                            'bg-blue-600 hover:bg-blue-700': @js($combineMode) === false,
                            'bg-purple-600 hover:bg-purple-700': @js($combineMode) === true
                        }"
                        wire:click="toggleCombineMode"
                    >
                        {{ $combineMode ? 'Exit Select Mode' : 'Select Mode' }}
                    </button>
                @endif

                @if(auth()->user()->can('create floorplan'))
                    <button
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white disabled:opacity-50"
                        wire:click="deleteSelected"
                        @disabled(count($selectedTableIds) < 1)
                    >
                        Delete Selected ({{ count($selectedTableIds) }})
                    </button>
                @endcan

                @if(auth()->user()->can('manage table'))
                    <button
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white disabled:opacity-50"
                        wire:click="confirmCombine"
                        @disabled(count($selectedTableIds) < 2)
                    >
                        Combine Selected ({{ count($selectedTableIds) }})
                    </button>
                @endcan

                @error('combine')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror

            </div>
            <div id="container" style="width:100%; background:#f3f4f6; overflow: auto;" wire:ignore></div>
        </div>

        <!-- Sidebar -->
        <aside class="
                w-full shrink-0
                bg-white dark:bg-gray-900
                rounded-xl shadow-md p-6
                border border-gray-200 dark:border-gray-700
                transition-all duration-500 overflow-hidden
                {{ $showSideBar || $mode === 'editor'
                    ? 'opacity-100 translate-x-0 visible'
                    : 'opacity-0 translate-x-6 invisible pointer-events-none'
                }}
            "
        >
            @if($showSideBar || $mode === 'editor')
                @if($mode === 'view')
                    <div class="flex justify-between">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $currentTableLabel ?? $currentTable->name }}
                        </h3>
                        <div>
                            @if($currentTable->status === 'available')
                            <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">Available</span>
                            @elseif($currentTable->status === 'reserved')
                            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">Reserved</span>
                            @elseif($currentTable->status === 'occupied')
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Occupied</span>
                            @elseif($currentTable->status === 'dirty')
                            <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">Dirty</span>
                            @endif
                        </div>
                    </div>
                    <ul
                        class="flex flex-wrap justify-center gap-1 text-sm font-medium
                            text-gray-500 border-b border-gray-200
                            dark:border-gray-700 dark:text-gray-400"
                    >
                        <!-- All tab -->
                        @canAny(['manage table', 'add reservation'])
                            <li>
                                <div
                                    wire:click="switchTabs('table')"
                                    class="px-4 py-2 rounded-lg cursor-pointer transition-all duration-200
                                    {{ ($tab === 'table')
                                        ? 'text-blue-600 bg-blue-50
                                        dark:bg-gray-800 dark:text-blue-400'
                                        : 'hover:text-gray-700 hover:bg-gray-100
                                        dark:hover:bg-gray-800 dark:hover:text-gray-300'
                                    }}"
                                >
                                    Reservations
                                </div>
                            </li>
                        @endcan
                        @canany(['take orders', 'apply discount', 'process payment'])
                            <li>
                                <div
                                    wire:click="switchTabs('orders')"
                                    class="px-4 py-2 rounded-lg cursor-pointer transition-all duration-200
                                    {{ ($tab === 'orders')
                                        ? 'text-blue-600 bg-blue-50
                                        dark:bg-gray-800 dark:text-blue-400'
                                        : 'hover:text-gray-700 hover:bg-gray-100
                                        dark:hover:bg-gray-800 dark:hover:text-gray-300'
                                    }}"
                                >
                                    Orders
                                </div>
                            </li>
                        @endcan
                    </ul>

                    @if($tab === 'table')
                    <div class="h-[73vh] flex flex-col justify-between">
                        <div>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <tbody>
                                    @foreach ($reservations as $reservationIndex => $reservation)
                                        <tr 
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4">
                                                {{ $reservation['name'] }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $reservation['time'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                            @can('add reservation')
                                <button type="button"
                                    class="w-full text-white bg-green-700 hover:bg-green-800
                                            focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800
                                            font-medium rounded-lg text-sm px-5 py-3 transition mb-2 flex justify-center"
                                    wire:click="openReservationModal()">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add reservation
                                </button>
                            @endcan
                            @can('manage table')
                                @if($currentTable->status !== 'available')
                                <button type="button"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800
                                            focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800
                                            font-medium rounded-lg text-sm px-5 py-3 transition mb-2 flex justify-center"
                                    wire:click="openSwitchModal">
                                    Switch Table
                                </button>
                                @endif
                                @if($currentTable->combinedItems()->exists())
                                    <button type="button"
                                        class="w-full text-white bg-red-700 hover:bg-red-800
                                                                    focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800
                                                                    font-medium rounded-lg text-sm px-5 py-3 transition mb-2 flex justify-center"
                                        wire:click="uncombineCurrent">
                                        Uncombine
                                    </button>
                                @endif
                                @if($currentTable->status === 'dirty')
                                <button type="button"
                                    class="w-full text-white bg-blue-700 hover:bg-blue-800
                                            focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800
                                            font-medium rounded-lg text-sm px-5 py-3 transition mb-2 flex justify-center"
                                    wire:click="cleanTable()">
                                    Table Cleaned
                                </button>
                                @endif
                            @endcan
                            @if($currentTable->combinedItems()->exists())
                                @can('take orders')
                                    <a
                                        href="{{ route('order.combined.create', optional($currentTable->combinedItems()->first()->combinedTable)->id) }}">
                                        <button type="button" class="w-full text-white bg-purple-700 hover:bg-purple-800
                                                                        focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800
                                                                        font-medium rounded-lg text-sm px-5 py-3 transition mb-2">
                                            Add Order (Combined)
                                        </button>
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>
                    @elseif($tab === 'orders')
                    <div class="h-[73vh] flex flex-col justify-between overflow-auto">
                        <div>
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <tbody>
                                    @foreach ($orders as $index => $order)
                                        <tr 
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4">
                                                <h6 class="text-lg font-bold dark:text-white">{{ $order['menu_name']}}</h6>
                                                <ul>
                                                    @foreach ($order['customizations'] as $custom)
                                                        <li class="font-semibold ml-3 list-disc">
                                                            {{ $custom['name'] }}
                                                            @if($custom['quantity'] > 0)
                                                                {{ round($custom['quantity'], 2) }}x
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            {{-- <td class="px-6 py-4">
                                                ₱{{ $order['price'] }}
                                            </td> --}}
                                            <td class="px-6 py-4">
                                                @if($order['status'] === 'pending')
                                                <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Pending</span>
                                                @elseif($order['status'] === 'preparing')
                                                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">Preparing</span>
                                                @elseif($order['status'] === 'completed')
                                                <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                            @can('take orders')
                                @php($combinedItem = $currentTable->combinedItems()->with('combinedTable')->first())
                                @if(!$combinedItem)
                                    <a href="{{ route('order.create', $currentTable) }}">
                                        <button type="button" class="w-full text-white bg-blue-600 hover:bg-blue-700
                                                        focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800
                                                        font-medium rounded-lg text-sm px-5 py-3 transition mb-2">
                                            Add Order
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('order.combined.create', $combinedItem->combinedTable) }}">
                                        <button type="button" class="w-full text-white bg-purple-700 hover:bg-purple-800
                                                        focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800
                                                        font-medium rounded-lg text-sm px-5 py-3 transition mb-2">
                                            Add Order (Combined)
                                        </button>
                                    </a>
                                @endif
                            @endcan
                            @can('apply discount')
                                @if($currentOrder !== null && $ordersStatus)
                                    <a href="{{ route('order.checkout', $currentOrder) }}">
                                        <button type="button" class="w-full text-white bg-green-700 hover:bg-green-800
                                                        focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800
                                                        font-medium rounded-lg text-sm px-5 py-3 transition mb-2">
                                            Bill Out
                                        </button>
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                    @endif
                @elseif($mode === 'editor')
                    <div class="">
                        <form wire:submit.prevent="addTable" class="gap-5 flex flex-col">
                            <x-form.input-field
                                id="name"
                                label="Name"
                                type="text"
                                wire:model="table_name"
                                :errorMessage="$errors->first('table_name')"
                            />

                            <x-form.input-field
                                id="capacity"
                                label="Table Capacity (1-6)"
                                type="number"
                                wire:model="table_capacity"
                                :errorMessage="$errors->first('table_capacity')"
                            />

                            <x-form.input-select
                                id="shape"
                                label="Select Shape"
                                placeholder="choose shape"
                                :selectData="$table_shape_data"
                                :errorMessage="$errors->first('table_shape')"
                                wire:model="table_shape"
                            />
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                        </form>
                    </div>
                @endif
            @endif
        </aside>
    </div>

    {{-- Resrvation MODAL --}}
    @if ($showReservationModal)
        @teleport('body')
        <div id="default-modal" tabindex="-1" aria-hidden="true"
            class=" fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <form wire:submit.prevent="saveReservation()"
                    class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Reservation
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal" wire:click="closeReservationModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4 overflow-y-scroll h-96">

                        <x-form.input-field
                            id="name"
                            label="Reservee Name"
                            type="text"
                            wire:model="name"
                            :errorMessage="$errors->first('name')"
                        />

                        <label 
                            for="dateTime" 
                            class="
                                block text-sm font-medium 
                                {{ ($errors->first('dateTime')) ? 'text-red-700 dark:text-red-500' : 'text-gray-900 dark:text-white' }}
                            "
                            >
                                    Date Time Picker
                        </label>

                        {{-- DATE TIME PICKER --}}
                        <div wire:ignore>
                            <div x-data="lwDatetimePicker(@entangle('due_date'))"
                                class="relative w-full flex justify-center align-middle"
                                x-effect="$wire.set('dateTime', formatted)">

                                <input id="dateTime" type="hidden" x-model="formatted" wire:model="dateTime">

                                <!-- Preview Trigger -->
                                <div @click="open = true" class="w-full px-4 py-3 rounded-lg border cursor-pointer
                                                                    bg-white border-gray-300
                                                                    dark:bg-gray-800 dark:border-gray-600
                                                                    hover:ring-2 hover:ring-blue-500
                                                                    transition">

                                    <!-- If No Value -->
                                    <template x-if="!formatted">
                                        <span class="text-gray-400 dark:text-gray-500">
                                            Select date & time
                                        </span>
                                    </template>

                                    <!-- If Value Exists -->
                                    <template x-if="formatted">
                                        <div class="flex flex-col">

                                            <!-- Date Preview -->
                                            <span 
                                                class="text-sm font-medium text-gray-800 dark:text-gray-100"
                                                x-text="new Date(formatted.replace(' ', 'T'))
                                                        .toLocaleDateString(undefined, {
                                                            year: 'numeric',
                                                            month: 'long',
                                                            day: 'numeric'
                                                        })"
                                            ></span>

                                            <!-- Time Preview -->
                                            <span 
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="new Date(formatted.replace(' ', 'T'))
                                                        .toLocaleTimeString(undefined, {
                                                            hour: 'numeric',
                                                            minute: '2-digit'
                                                        })"
                                            ></span>

                                        </div>
                                    </template>
                                </div>

                                <!-- Dropdown -->
                                <div 
                                    x-show="open"
                                    @click.away="open = false"
                                    x-transition
                                    class="absolute z-50 mt-12 w-80 p-4 rounded-xl shadow-2xl border border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-700"
                                >

                                    <!-- Month Navigation -->
                                    <div class="flex justify-between items-center mb-4">
                                        <button 
                                            @click="prevMonth()"
                                            class="px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-white"
                                            type="button"
                                        >
                                            ←
                                        </button>

                                        <div 
                                            class="font-semibold text-gray-800 dark:text-gray-100"
                                            x-text="monthYear"
                                        ></div>

                                        <button 
                                            @click="nextMonth()"
                                            class="px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-white"
                                            type="button"
                                        >
                                            →
                                        </button>
                                    </div>

                                    <!-- Days Header -->
                                    <div class="grid grid-cols-7 text-xs text-center mb-2 text-gray-500 dark:text-gray-400">
                                        <template x-for="day in daysShort">
                                            <div x-text="day"></div>
                                        </template>
                                    </div>

                                    <!-- Calendar -->
                                    <div class="grid grid-cols-7 gap-1 text-sm">
                                        <template x-for="blank in blanks">
                                            <div></div>
                                        </template>

                                        <template x-for="day in daysInMonth">
                                            <button
                                                @click="selectDate(day)"
                                                :class="selectedDay === day 
                                                    ? 'bg-blue-600 text-white dark:bg-blue-500' 
                                                    : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200'"
                                                class="w-8 h-8 rounded-full flex items-center justify-center transition"
                                                x-text="day"
                                                type="button"
                                            ></button>
                                        </template>
                                    </div>

                                    <!-- Time Selector -->
                                    <div class="mt-4 flex gap-2 items-center">

                                        <select 
                                            x-model="hour"
                                            class="border rounded px-2 py-1 bg-white text-gray-800 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                                        >
                                            <template x-for="h in 24">
                                                <option 
                                                    :value="String(h-1).padStart(2,'0')" 
                                                    x-text="String(h-1).padStart(2,'0')"
                                                ></option>
                                            </template>
                                        </select>

                                        <span class="text-gray-700 dark:text-gray-300">:</span>

                                        <select 
                                            x-model="minute"
                                            class="border rounded px-2 py-1 bg-white text-gray-800 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                                        >
                                            <template x-for="m in 60">
                                                <option 
                                                    :value="String(m-1).padStart(2,'0')" 
                                                    x-text="String(m-1).padStart(2,'0')"
                                                ></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($errors->first('name'))
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ $errors->first('dateTime') }}
                            </p>
                        @endif
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="default-modal" type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                        <button data-modal-hide="default-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                            wire:click="closeReservationModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endteleport
    @endif

    {{-- Switch Table Modal --}}
    @if ($switchModal)
        @teleport('body')
        <div id="default-modal" tabindex="-1" aria-hidden="true"
            class=" fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <form wire:submit.prevent="switchTable()" class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Reservation
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal" wire:click="closeSwitchModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4 overflow-y-scroll h-96">
                        <select id="inventory_unit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            wire:model.change="tableToSwitch">
                            <option value="">Select Table</option>
                            @foreach ($tables as $index => $table)
                                @if($table->status === 'available' && $table->combinedItems->isEmpty())
                                    <option value="{{ $table->id }}">{{ $table->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="default-modal" type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                        <button data-modal-hide="default-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                            wire:click="closeSwitchModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endteleport
    @endif
    {{-- Combine Confirm MODAL --}}
    @if ($showCombineConfirm)
        @teleport('body')
        <div
            class="fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div
                    class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Confirm Combine
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            wire:click="cancelCombine">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Combine {{ count($selectedTableIds) }} tables into a group? Only available tables from the same
                            floorplan can be combined.
                        </p>
                    </div>
                    <div
                        class="flex items-center justify-end gap-2 p-4 md:p-5 border-t border-gray-200 dark:border-gray-600">
                        <button type="button"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300"
                            wire:click="cancelCombine">Cancel</button>
                        <button type="button" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700"
                            wire:click="combineSelected">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif
    {{-- Combine Confirm MODAL --}}
    @if ($showDeleteConfirm)
        @teleport('body')
        <div
            class="fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div
                    class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Confirm Delete
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            wire:click="closeDeleteConfirm">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Are you sure you want to delte this table?
                        </p>
                    </div>
                    <div
                        class="flex items-center justify-end gap-2 p-4 md:p-5 border-t border-gray-200 dark:border-gray-600">
                        <button type="button"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300"
                            wire:click="closeDeleteConfirm">Cancel</button>
                        <button type="button" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700"
                            wire:click="deleteTable">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>

<script>
    window.tables = @json($floorplan->tables);
    window.selectedIds = @json($selectedTableIds);
    function initKonva() {

        console.log(window.tables);
        
        if (window.stage) {
            console.log('destroyed');
            window.stage.destroy();
        }

        let tables = window.tables;
        // let walls = @json($floorplan->walls);
        let mode = @json($mode);
        let combineMode = @json($combineMode);
        let selectedIds = window.selectedIds;

        console.log(tables);
        console.log(selectedIds);

        const stageWidth = 800;
        const stageHeight = 800;

        window.stage = new Konva.Stage({
            container: 'container',
            draggable: false
        });

        const stageSize = 800;

        function fitStage() {
            const container = document.getElementById("container");

            const containerWidth = container.offsetWidth;

            const scale = containerWidth / stageSize;

            stage.width(stageSize * scale);
            stage.height(stageSize * scale);
            stage.scale({ x: scale, y: scale });
        }

        window.addEventListener("resize", fitStage);

        fitStage();
        
        const layer = new Konva.Layer();
        stage.add(layer);

        const gridSize = 20;

        // vertical lines
        for (let i = 0; i <= stageSize / gridSize; i++) {
        layer.add(new Konva.Line({
            points: [i * gridSize, 0, i * gridSize, stageSize],
            stroke: '#e5e7eb',
            strokeWidth: 1,
        }));
        }

        // horizontal lines
        for (let j = 0; j <= stageSize / gridSize; j++) {
        layer.add(new Konva.Line({
            points: [0, j * gridSize, stageSize, j * gridSize],
            stroke: '#e5e7eb',
            strokeWidth: 1,
        }));
        }

        function getColor(status) {
            switch (status) {
                case 'reserved': return '#713e12';
                case 'occupied': return '#1e3a8a';
                case 'dirty': return '#7f1d1d';
                default: return '#14532d';
            }
        }

        function createTable(data) {
            const group = new Konva.Group({
                x: data.x,
                y: data.y,
                rotation: data.rotation,
                draggable: mode === 'editor',
                dragBoundFunc: function (pos) {

                    const stage = this.getStage();
                    const stageWidth = stage.width();
                    const stageHeight = stage.height();

                    const box = this.getClientRect({ relativeTo: stage });

                    // 🔹 Snap to grid
                    let snappedX = Math.round(pos.x / gridSize) * gridSize;
                    let snappedY = Math.round(pos.y / gridSize) * gridSize;

                    // 🔹 Keep inside stage
                    const newX = Math.max(
                        75,
                        Math.min(snappedX, stageWidth - box.width + 75)
                    );

                    const newY = Math.max(
                        75,
                        Math.min(snappedY, stageHeight - box.height + 65)
                    );

                    return {
                        x: newX,
                        y: newY
                    };
                }
            });

            let isSelected = selectedIds.includes(data.id) ?? null;

            let tableShape;
            if (data.shape === 'rectangle') {
                tableShape = new Konva.Rect({
                    width: data.width ?? 220,
                    height: data.height ?? 120,
                    offsetX: (data.width ?? 220) / 2,
                    offsetY: (data.height ?? 120) / 2,
                    fill: (mode === 'editor') ? 'black' : getColor(data.status),
                    stroke: (data.combined_items && data.combined_items.length > 0) ? '#6b21a8' : 'black',
                    strokeWidth: 2
                });
            }
            else if (data.shape === 'square') {
                tableShape = new Konva.Rect({
                    width: data.width ?? 100,
                    height: data.height ?? 100,
                    offsetX: (data.width ?? 100) / 2,
                    offsetY: (data.height ?? 100) / 2,
                    fill: (mode === 'editor') ? 'black' : getColor(data.status),
                    stroke: (isSelected) ? '#f97316' : (data.combined_items && data.combined_items.length > 0) ? '#6b21a8' : 'black',
                    strokeWidth: 2
                });
            }
            else {
                tableShape = new Konva.Circle({
                    radius: (data.width ?? 100) / 2,
                    fill: (mode === 'editor') ? 'black' : getColor(data.status),
                    stroke: (isSelected) ? '#f97316' : (data.combined_items && data.combined_items.length > 0) ? '#6b21a8' : 'black',
                    strokeWidth: 2
                });
            }

            group.add(tableShape);

            let offy = 20;

            function labelForCombined(data) {
                if (!data.combined_items || data.combined_items.length === 0) {
                    return data.name;
                }
                const combined = data.combined_items[0];
                if (!combined || !combined.combined_table || !combined.combined_table.tables) {
                    console.log(combined.combined_table.tables);
                    return data.name;
                }
                const names = combined.combined_table.tables.map(t => t.name);
                offy += 10;
                return names.join(',\n');
            }

            tableLabel = new Konva.Text({
                fontSize: 20,
                fontFamily: 'Calibri',
                text: labelForCombined(data),
                fill: 'white',
                padding: 10,
                width: 150,
                align: 'center',
                offsetX: 75,
                offsetY: offy,
            });

            group.add(tableLabel);

            // Chairs using SVG Path
            regenerateChairs(group, data.capacity, tableShape);

            if (mode === 'editor') {
                group.on('dragend transformend', function () {
                    // Ensure Livewire exists
                    if (window.Livewire && typeof Livewire.dispatch === 'function') {
                        Livewire.dispatch('updateTable', {
                            id: data.id,
                            x: group.x(),
                            y: group.y(),
                            rotation: group.rotation(),
                            width: data.width,
                            height: data.height
                        });
                    } else {
                        console.warn('Livewire is not loaded yet.');
                    }
                });

                group.on('click tap', function () {
                    if (combineMode) {
                        Livewire.dispatch('tableClicked', {
                            id: data.id,
                        });
                        let isSelected = selectedIds.includes(data.id);
                        tableShape.stroke(isSelected ? '#f97316' : (data.combined_items && data.combined_items.length > 0) ? '#6b21a8' : 'black');
                    } else {
                        Livewire.dispatch('tableClicked', {
                            id: data.id,
                        });
                    }
                });

                
                group.on('mouseenter', () => stage.container().style.cursor = 'pointer');
                group.on('mouseleave', () => stage.container().style.cursor = 'default');
            }

            if (mode === 'view') {
                group.on('click tap', function () {
                    if (combineMode) {
                        Livewire.dispatch('tableClicked', {
                            id: data.id,
                        });
                        let isSelected = selectedIds.includes(data.id);
                        tableShape.stroke(isSelected ? '#f97316' : (data.combined_items && data.combined_items.length > 0) ? '#6b21a8' : 'black');
                    } else {
                        Livewire.dispatch('tableClicked', {
                            id: data.id,
                        });
                    }
                });

                group.on('mouseenter', () => stage.container().style.cursor = 'pointer');
                group.on('mouseleave', () => stage.container().style.cursor = 'default');
            }

            layer.add(group);
        }

        function regenerateChairs(group, capacity, tableShape) {
            // Remove old chairs
            group.find('.chair').forEach(c => c.destroy());

            const radius = tableShape.radius ? tableShape.radius() : Math.max(tableShape.width(), tableShape.height()) / 2;
            const distance = radius + 25;

            for (let i = 0; i < capacity; i++) {
                const angle = (i / capacity) * Math.PI * 2;

                const chair = new Konva.Path({
                    data: 'M 230.54 141.62 L 233.45 165.46 C 234 168.97 232.48 172.47 229.57 174.4 C 219.61 176 209.46 176 199.5 174.4 C 196.59 172.47 195.06 168.97 195.62 165.46 L 198.53 141.62 C 199.09 138.98 201.86 136.95 205.32 136.65 L 223.75 136.65 C 227.2 136.95 229.98 138.98 230.54 141.62 Z M 204.52 136.65 L 199.54 130.36 M 210.06 136.65 L 208.4 129.46 M 216.71 136.65 L 218.92 129.46 M 222.24 136.65 L 227.78 130.36 M 229.44 130.69 C 219.19 129.06 208.69 129.06 198.43 130.69 L 194 126.72 C 207.12 124.01 220.76 124.01 233.87 126.72 Z',
                    fill: '#000000',
                    scale: { x: 1, y: 1 },
                    rotation: (angle * 180 / Math.PI) + 90 + group.rotation(), // follow table rotation
                    offsetX: 215,  // center chair path
                    offsetY: 120,
                    name: 'chair'
                });

                chair.x(Math.cos(angle) * distance);
                chair.y(Math.sin(angle) * distance);

                // Always render chairs on top of table shape within group
                group.add(chair);
                chair.moveToBottom();
            }
        }

        function createWall(data) {
            wall = new Konva.Rect({
                x: data.x,
                y: data.y,
                width: data.width ?? 10,
                height: data.height ?? 120,
                fill: 'black',
                stroke: 'black',
                strokeWidth: 2,
                rotation: data.rotation,
                draggable: mode === 'editor',
                selectable: true,
                dragBoundFunc: function (pos) {
                    const stageWidth = stage.width();
                    const stageHeight = stage.height();

                    const newX = Math.max(0, Math.min(pos.x, stageWidth - this.width()));
                    const newY = Math.max(0, Math.min(pos.y, stageHeight - this.height()));

                    return {
                        x: newX,
                        y: newY
                    };
                }
            });

            if (mode === 'editor') {
                wall.on('dragend transformend', function () {
                    // Ensure Livewire exists
                    if (window.Livewire && typeof Livewire.dispatch === 'function') {
                        Livewire.dispatch('updateWall', {
                            id: data.id,
                            x: wall.x(),
                            y: wall.y(),
                            rotation: wall.rotation(),
                            width: wall.width(),
                            height: wall.height()
                        });
                    } else {
                        console.warn('Livewire is not loaded yet.');
                    }
                });

                wall.on('click', function () {
                    alert('Table: ' + data.name + ' (' + data.status + ')');
                });
            }

            if (mode === 'view') {
                wall.on('click', function () {
                    alert('Table: ' + data.name + ' (' + data.status + ')');
                });
            }

            layer.add(wall);
        }

        // Transformer
        const transformer = new Konva.Transformer({
            rotateEnabled: true,
            // centeredScaling: true,
            rotationSnaps: [0, 45, 90, 135, 180, 225, 270, 315],
            rotationSnapTolerance: 5, // degrees
            enabledAnchors: mode === 'editor' ? ['top-center', 'bottom-center'] : []
        });
        layer.add(transformer);

        layer.on('click', function (e) {
            if (mode !== 'editor') return;

            const group = e.target;

            if (group && e.target.getAttr('selectable')) {
                transformer.nodes([group]);
                transformer.boundBoxFunc()
            } else {
                transformer.nodes([]);
            }

            layer.draw();
        });

        tables.forEach(t => createTable(t));
        // walls.forEach(w => createWall(w));
        layer.draw();

        // Zoom
        // stage.on('wheel', (e) => {
        //     e.evt.preventDefault();
        //     const scaleBy = 1.05;
        //     const oldScale = stage.scaleX();
        //     const pointer = stage.getPointerPosition();
        //     const mousePointTo = {
        //         x: (pointer.x - stage.x()) / oldScale,
        //         y: (pointer.y - stage.y()) / oldScale
        //     };
        //     const newScale = e.evt.deltaY > 0 ? oldScale / scaleBy : oldScale * scaleBy;
        //     stage.scale({ x: newScale, y: newScale });
        //     stage.position({
        //         x: pointer.x - mousePointTo.x * newScale,
        //         y: pointer.y - mousePointTo.y * newScale
        //     });
        //     stage.batchDraw();
        // });

    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('refreshKonva', (event) => {
            console.log('reload');
            console.log(event);
            window.tables = event[0];
            window.selectedIds = event[1];
            initKonva();
        });
    });
</script>
@assets
    <script src="https://unpkg.com/konva@9/konva.min.js"></script>
@endassets