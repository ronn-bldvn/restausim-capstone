<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 h-auto lg:h-[85vh]">
        {{-- ORDER SUMMARY --}}
        <div class="dark:bg-gray-800 p-5 gap-5">
            <h5 class="text-xl pb-3 font-bold dark:text-white border-b-2">Order Summary</h5>

            <div class="overflow-auto h-auto lg:h-[73vh]">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top">
                                    {{ $order['quantity'] }}
                                </th>

                                <td class="px-6 py-4">
                                    <h6 class="text-lg font-bold dark:text-white">{{ $order['menu_name'] }}</h6>

                                    @if (!empty($order['customizations']))
                                        <ul>
                                            @foreach ($order['customizations'] as $custom)
                                                <li class="font-semibold ml-3 list-disc">
                                                    {{ $custom['name'] }}
                                                    @if ($custom['quantity'] > 0 && $custom['type'] === 'add')
                                                        {{ round($custom['quantity'], 2) }}x
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-top">
                                    ₱{{ round($order['line_gross_amount'], 2) }}
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <button
                                        type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                        wire:click="openDiscountModal({{ $index }})"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                    No completed items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="dark:bg-gray-800 p-5">
            {{-- TABS --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group cursor-pointer {{ $activeTab === 'discount' ? 'text-blue-600 border-blue-600 active dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                            wire:click="changeActiveTab('discount')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ $activeTab === 'discount' ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                            </svg>
                            Discount
                        </button>
                    </li>

                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group cursor-pointer {{ $activeTab === 'service' ? 'text-blue-600 border-blue-600 active dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                            wire:click="changeActiveTab('service')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ $activeTab === 'service' ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            Service
                        </button>
                    </li>
                </ul>
            </div>

            <div class="pt-5">
                <div class="min-h-56">
                    @if ($activeTab === 'discount')
                        <div class="mb-4">
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                wire:model.change="orderDiscountType"
                            >
                                <option value="">Select Discount</option>
                                <option value="custom">Custom Discount</option>

                                @foreach ($orderDiscountSelection as $discount)
                                    <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($orderDiscountType === 'custom')
                            <div class="relative z-0 mb-5 group grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Discount Amount
                                    </label>
                                    <input
                                        type="number"
                                        step="any"
                                        min="0"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        wire:model.live.debounce.500ms="orderDiscount.discount_amount"
                                    />
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Discount Percentage
                                    </label>
                                    <input
                                        type="number"
                                        step="any"
                                        min="0"
                                        max="100"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        wire:model.live.debounce.500ms="orderDiscount.discount_percentage"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1">
                                <button
                                    type="button"
                                    class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 flex justify-center content-center"
                                    wire:click="applyOrderDiscountType('clear')"
                                >
                                    Clear
                                </button>
                            </div>
                        @endif
                    @elseif ($activeTab === 'service')
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Service Charge (%):
                            </label>
                            <input
                                type="number"
                                step="any"
                                min="0"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                wire:model.live.debounce.500ms="service_charge"
                            />
                        </div>
                    @endif
                </div>

                {{-- SALE SUMMARY --}}
                <div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b-2 border-t-4 dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-2">
                                    <div class="flex justify-between">
                                        <span>Vat Sales:</span>
                                        <span>₱{{ round($vat_sales, 2) }}</span>
                                    </div>
                                </td>
                            </tr>

                            <tr class="bg-white border-b-2 dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-2">
                                    <div class="flex justify-between">
                                        <span>Vat Exempt Sales:</span>
                                        <span>₱{{ round($vat_exempt_sales, 2) }}</span>
                                    </div>
                                </td>
                            </tr>

                            <tr class="bg-white border-b-2 dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-2">
                                    <div class="flex justify-between">
                                        <span>Vat:</span>
                                        <span>₱{{ round($total_vat, 2) }}</span>
                                    </div>
                                </td>
                            </tr>

                            @if ($service_charge_amount > 0)
                                <tr class="bg-white border-b-2 dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-2">
                                        <div class="flex justify-between">
                                            <span>Service Charge:</span>
                                            <span>₱{{ round($service_charge_amount, 2) }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @if ($total_discount > 0)
                                <tr class="bg-white border-b-4 dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-2">
                                        <div class="flex justify-between">
                                            <span>Discount:</span>
                                            <span>- ₱{{ round($total_discount, 2) }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th scope="row" class="px-4 py-3 font-medium text-lg text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex justify-between">
                                        <span>Amount Due:</span>
                                        <span>₱{{ round($amount_due, 2) }}</span>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-end mt-4">
                        <button
                            type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                            wire:click="applyOrderLevelDiscount"
                            wire:loading.attr="disabled"
                            wire:target="applyOrderLevelDiscount"
                        >
                            <span wire:loading.remove wire:target="applyOrderLevelDiscount">
                                Proceed to Payment
                            </span>
                            <span wire:loading wire:target="applyOrderLevelDiscount">
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DISCOUNT MODAL --}}
    @if ($showDiscountModal)
        @teleport('body')
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
                <div class="relative w-full max-w-2xl max-h-[90vh]">
                    <form wire:submit.prevent="saveItemDiscount" class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700 overflow-hidden">
                        {{-- Modal header --}}
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Discounts
                            </h3>

                            <button
                                type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                wire:click="closeDiscountModal"
                            >
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        {{-- Modal body --}}
                        <div class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-[70vh]">
                            @if ($itemOrder)
                                {{-- ITEM DATA --}}
                                <div>
                                    <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">
                                        Item: {{ $itemOrder->item->name }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        Base Price: ₱{{ round($itemOrder->final_unit_price, 2) }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                                        Quantity: {{ round($itemOrder->quantity_ordered, 2) }}
                                    </p>
                                </div>

                                {{-- DISCOUNTS --}}
                                <div>
                                    <div>
                                        <h6 class="text-lg font-bold dark:text-white mb-1">Discount</h6>
                                    </div>

                                    <div class="mb-4">
                                        <select
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                            wire:model.change="itemDiscount.discount_type"
                                        >
                                            <option value="">Select Discount</option>
                                            <option value="custom">Custom Discount</option>

                                            @foreach ($discountSelection as $discount)
                                                <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($itemDiscount['discount_type'] === 'custom')
                                        <div class="relative z-0 mb-5 group grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Discount Amount
                                                </label>
                                                <input
                                                    type="number"
                                                    step="any"
                                                    min="0"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                                    wire:model.live.debounce.500ms="itemDiscount.discount_amount"
                                                    wire:blur="resetIfEmpty"
                                                />
                                                @error('itemDiscount.discount_amount')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Discount Percentage
                                                </label>
                                                <input
                                                    type="number"
                                                    step="any"
                                                    min="0"
                                                    max="100"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                                    wire:model.live.debounce.500ms="itemDiscount.discount_percentage"
                                                    wire:blur="resetIfEmpty"
                                                />
                                                @error('itemDiscount.discount_percentage')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="grid">
                                            <button
                                                type="button"
                                                class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 flex justify-center content-center"
                                                wire:click="resetDiscountValues"
                                            >
                                                Clear
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                {{-- NOTES --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note:</label>
                                    <textarea
                                        rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        wire:model="itemDiscount.notes"
                                    ></textarea>
                                </div>

                                {{-- SUMMARY --}}
                                <div class="border-t-2 pt-5 text-white">
                                    <div class="space-y-1">
                                        <div>Vat: ₱{{ round($itemDiscount['vat_amount'], 2) }}</div>
                                        <div>Discount: ₱{{ round($itemDiscount['discount_amount'], 2) }}</div>
                                        <div>Final Price: ₱{{ round($itemDiscount['final_unit_price'], 2) }}</div>
                                        <div>Total Item Price: ₱{{ round($itemDiscount['line_gross_amount'], 2) }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Modal footer --}}
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button
                                type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                                wire:loading.attr="disabled"
                                wire:target="saveItemDiscount"
                            >
                                <span wire:loading.remove wire:target="saveItemDiscount">Save</span>
                                <span wire:loading wire:target="saveItemDiscount">Saving...</span>
                            </button>

                            <button
                                type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                wire:click="closeDiscountModal"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endteleport
    @endif
</div>