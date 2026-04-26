<div class="flex">
    <div class=" w-full h-[85vh] grid lg:grid-cols-[6fr_4fr] grid-cols-1 gap-5 ">
        {{-- MENU ITEMS --}}
        <div id="menu" class="dark:bg-gray-800 p-3">
            {{-- CATEGORIES --}}
            <ul class="flex-wrap justify-center gap-1 text-sm font-medium  hidden sm:flex
                    text-gray-500 border-b border-gray-200
                    dark:border-gray-700 dark:text-gray-400">
                <!-- All tab -->
                <li>
                    <div wire:click="switchTabs('')" class="px-4 py-2 rounded-lg cursor-pointer transition-all duration-200
                        {{ (!$active)
                            ? 'text-blue-600 bg-blue-50 border-b-2 border-blue-600
                            dark:bg-gray-800 dark:text-blue-400'
                            : 'hover:text-gray-700 hover:bg-gray-100
                            dark:hover:bg-gray-800 dark:hover:text-gray-300'
                        }}">
                        All
                    </div>
                </li>

                @foreach ($categories as $category)
                    <li>
                        <button type="button" wire:click="switchTabs({{ $category->id }})" wire:loading.attr="disabled"
                            wire:target="switchTabs" class="px-4 py-2 rounded-lg cursor-pointer transition-all duration-500
                                            {{ ($active == $category->id)
                                ? 'text-blue-600 bg-blue-50 border-b-2 border-blue-600
                                                dark:bg-gray-800 dark:text-blue-400'
                                : 'hover:text-gray-700 hover:bg-gray-100
                                                dark:hover:bg-gray-800 dark:hover:text-gray-300'
                                            }}">
                            {{ $category->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 sm:hidden"
                wire:change="switchTabs($event.target.value)">
                <option value="">All Items</option>
                @foreach ($categories as $category)
                    <option class="text-xs" value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            {{-- MENU ITEMS --}}
            <div id="data" wire:loading.class="pointer-events-none opacity-50" wire:target="switchTabs,gotoPage"
                class="mb-2 mt-5 grid gap-3 content-start
                       grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4
                       overflow-auto h-[60vh]">
                @foreach ($menuItems as $item)
                    @php($state = $menuStock[$item->id] ?? ['zero' => false, 'low' => []])
                    <div class="relative transition-transform duration-200 hover:-translate-y-1 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800 hover:shadow-md overflow-hidden flex flex-col min-h-[10rem] {{ $state['zero'] ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'cursor-pointer' }}"
                        @if(!$state['zero']) wire:click="tryOpenItem({{ $item->id }})" @endif>
                        <!-- Image -->
                        <div class="relative">
                            <img class="h-20 sm:h-24 lg:h-20 xl:h-24 w-full object-cover"
                                src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}" />
                            @if($state['zero'])
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="text-white font-extrabold text-3xl tracking-widest">86</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-2 flex-1 flex flex-col justify-between">
                            <h5 class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate"
                                title="{{ $item->name }}">
                                {{ $item->name }}
                            </h5>

                            <span class="text-xs sm:text-sm font-semibold text-emerald-600 dark:text-emerald-400 mt-1">
                                ₱{{ number_format($item->price, 2) }}
                            </span>

                            @if(!$state['zero'] && !empty($state['low']))
                                <div class="mt-1 text-xs text-orange-600 dark:text-orange-400">
                                    ⚠ Low stock:
                                    {{ implode(', ', array_slice($state['low'], 0, 2)) }}{{ count($state['low']) > 2 ? '…' : '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($menuItems->total() > 12)
                <div class="p-2 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $menuItems->links() }}
                </div>
            @endif
        </div>

        {{-- ORDERS --}}
        <div id="separator-sidebar" class="" aria-label="Sidebar">
            <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800 grid grid-rows-[1fr_5fr_3fr]">
                {{-- HEAD --}}
                <div class="pt-3 pl-3 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-xl font-bold dark:text-white">{{ $tableLabel }}</h5>
                </div>

                {{-- CURRENT ORDERS --}}
                <div class="relative overflow-y-auto h-[45vh] shadow-md sm:rounded-lg" x-data="{ stickToBottom: true }"
                    x-ref="orders" x-on:scroll="
                        stickToBottom =
                            ($refs.orders.scrollTop + $refs.orders.clientHeight)
                            >= ($refs.orders.scrollHeight - 10)
                    " x-on:orders-updated.window="
                        if (stickToBottom) {
                            $nextTick(() => {
                                $refs.orders.scrollTop = $refs.orders.scrollHeight
                            })
                        }
                    ">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 hidden xl:block">
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white w-fit">
                                        @if($order['status'] === 'new')
                                            <div class="relative flex items-center max-w-[8rem]">
                                                <button type="button" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                    wire:click="decrementOrderQuantity({{ $index }})">
                                                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                    </svg>
                                                </button>
                                                <input type="text" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    wire:model="orders.{{ $index }}.quantity" />
                                                <button type="button" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                    wire:click="incrementOrderQuantity({{ $index }})">
                                                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            {{ $order['quantity'] }}
                                        @endif
                                    </th>
                                    <td class="px-4 py-2">
                                        <h6 class="text-lg font-bold dark:text-white">{{ $order['menu_name'] }}</h6>
                                        <ul>
                                            @foreach ($order['customizations'] as $custom)
                                                <li class="font-semibold ml-3 list-disc">
                                                    {{ $custom['name'] }}
                                                    @if($custom['quantity'] > 0)
                                                        {{ $custom['quantity'] }}x
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4">
                                        ₱{{ $order['price'] }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($order['status'] === 'new')
                                            <button type="button"
                                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2.5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                wire:click="removeOrders({{ $index }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="space-y-4 xl:hidden">
                        @foreach ($orders as $index => $order)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                {{-- Top section --}}
                                <div class="flex justify-between items-center">
                                    {{-- Quantity --}}
                                    <div>
                                        @if($order['status'] === 'new')
                                            <div class="relative flex items-center max-w-[8rem]">
                                                <button type="button" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                    wire:click="decrementOrderQuantity({{ $index }})">
                                                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                    </svg>
                                                </button>
                                                <input type="text" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    wire:model="orders.{{ $index }}.quantity" />
                                                <button type="button" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                    wire:click="incrementOrderQuantity({{ $index }})">
                                                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            {{ $order['quantity'] }}
                                        @endif
                                    </div>

                                    {{-- Remove button --}}
                                    @if($order['status'] === 'new')
                                        <button type="button"
                                            class="text-white bg-red-700 hover:bg-red-800 rounded-lg p-2"
                                            wire:click="removeOrders({{ $index }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <h6 class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $order['menu_name'] }}
                                    </h6>
                                    <ul class="mt-1">
                                        @foreach ($order['customizations'] as $custom)
                                            <li class="ml-4 list-disc text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                {{ $custom['name'] }}
                                                @if($custom['quantity'] > 0)
                                                    {{ $custom['quantity'] }}x
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Price --}}
                                <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                    Price: <span class="font-semibold">₱{{ $order['price'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TOTAL PRICE --}}
                <div class="pt-4 mt-4 space-y-2 grid grid-rows-[4fr_3fr] font-medium border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between px-5">
                        <h3 class="text-3xl font-bold dark:text-white">Total:</h3>
                        <h3 class="text-3xl font-bold dark:text-white">₱{{ $totalPrice }}</h3>
                    </div>
                    <div class="flex justify-between">
                        <button
                            type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            wire:click="saveOrders"
                            wire:target="saveOrders"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveOrders">Send To Kitchen</span>
                            <span wire:loading wire:target="saveOrders">Sending To Kitchen...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Customization Modal --}}
    @if ($showCustomizationModal)
        @teleport('body')
        <div id="default-modal" aria-hidden="true"
            class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900 bg-opacity-50">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <form wire:submit.prevent="addToOrders" class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $menu->name }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            wire:click="closeCustomizationModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4 h-96 overflow-auto">
                        <div class="space-y-4">
                            <h6 class="text-lg font-bold dark:text-white">Ingredients: </h6>
                            @foreach ($menu->ingredients as $ingredient)
                                <div class="space-y-4">
                                    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                            <div class="flex items-center ps-3">
                                                <input id="{{ $ingredient->id }}" type="radio" value="default"
                                                    name="{{ $ingredient->id }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                                    wire:model="ordersToAdd.ingredients.{{ $ingredient->id }}">
                                                <label for="{{ $ingredient->id }}"
                                                    class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                    {{ $ingredient->inventory->name }}
                                                </label>
                                            </div>
                                        </li>
                                        @foreach ($menu->customizations as $customization)
                                            @if($customization->ingredient_id == $ingredient->id)
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                    <div class="flex items-center ps-3">
                                                        <input id="{{ $customization->id }}" type="radio" value="{{ $customization->id }}"
                                                            name="{{ $ingredient->id }}"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                                            wire:model="ordersToAdd.ingredients.{{ $ingredient->id }}">
                                                        <label for="{{ $customization->id }}"
                                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            {{ ($customization->action == 'remove') ? 'No ' . $customization->ingredient->inventory->name : $customization->inventory->name }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-4">
                            <h6 class="text-lg font-bold dark:text-white">Add-ons</h6>
                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($menu->customizations as $customization)
                                    @if ($customization->action == 'add')
                                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                            <div class="flex items-center p-3">
                                                <div class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                    Extra {{ $customization->inventory->name }}: ₱{{ $customization->price }}
                                                </div>
                                                <div class="relative flex items-center max-w-[8rem]">
                                                    <button type="button"
                                                        class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                        wire:click="decrementAdditionalIngredient({{ $customization->id }})">
                                                        <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                        </svg>
                                                    </button>
                                                    <input type="text" id="{{ $customization->id }}"
                                                        class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        wire:model="ordersToAdd.additionalIngredients.{{ $customization->id }}" />
                                                    <button type="button"
                                                        class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                                        wire:click="incrementAdditionalIngredient({{ $customization->id }})">
                                                        <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex flex-col sm:flex-row justify-between items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <div>
                            <div class="flex items-center gap-3 p-3">
                                <div class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Quantity:
                                </div>
                                <div class="relative flex items-center max-w-[8rem]">
                                    <button type="button"
                                        class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                        wire:click="decrementOrderQuantity()">
                                        <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                        </svg>
                                    </button>
                                    <input type="text" id="item-quantity"
                                        class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-16 py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        wire:model="ordersToAdd.quantity" />
                                    <button type="button"
                                        class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none"
                                        wire:click="incrementOrderQuantity()">
                                        <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-base dark:border-gray-600">
                            <span class="text-gray-700 dark:text-gray-200">Total: </span>
                            <span class="ml-1 font-extrabold text-emerald-600 dark:text-emerald-400">
                                ₱{{ number_format($modalLineTotal, 2) }}
                            </span>
                        </div>

                        <div class="mt-3 sm:mt-0">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
                            </button>
                            <button type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                wire:click="closeCustomizationModal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endteleport
    @endif
</div>