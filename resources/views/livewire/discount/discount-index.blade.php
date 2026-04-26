<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventory Management') }}
            </h2>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3">
                @can('create discount')
                    <a href="{{ route('discount.create') }}">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create New Discount
                        </button>
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            
            {{-- Category Tabs with improved design --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden p-5">
                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3">
                    @can('create discount')
                        <a href="{{ route('discount.create') }}">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create New Discount
                            </button>
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Data Table with enhanced design --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm overflow-hidden mt-3">
                @if (!$discounts->isEmpty())
                    <div class="overflow-x-auto  hidden lg:block">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Discount
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Vat
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($discounts as $index => $discount)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ++$index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $discount->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($discount->type === 'order')
                                                Order
                                            @else
                                                <button type="button" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" wire:click="openItemsModal({{ $discount->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                        Items
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ($discount->discount_type === 'amount') ? '₱' : '' }}{{ $discount->discount_value }}{{ ($discount->discount_type === 'percentage') ? '%' : '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $discount->is_vat_exempt ? 'VAT exempt' : 'VATable' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-start gap-3">
                                                @can('edit discount')
                                                    <a href="{{ route('discount.edit', $discount) }}"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endcan
                                                @can('delete discount')
                                                    <button
                                                        wire:click="openDeleteModal({{ $discount->id }})"
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

                    <div class="lg:hidden space-y-4 gap-3">
                        @foreach ($discounts as $index => $discount)
                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-700">

                                <!-- Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $discount->name }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">

                                    <div class="text-sm text-gray-500 dark:text-gray-400 flex justify-between">
                                        <span class="font-medium">Amount:</span>
                                        <span>
                                            {{ ($discount->discount_type === 'amount') ? '₱' : '' }}
                                            {{ $discount->discount_value }}
                                            {{ ($discount->discount_type === 'percentage') ? '%' : '' }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="font-medium">VAT:</span>
                                        <span>
                                            {{ $discount->is_vat_exempt ? 'VAT Exempt' : 'VATable' }}
                                        </span>
                                    </div>

                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-4 mt-4 border-t pt-3">

                                    <button
                                        type="button"
                                        class="text-blue-600 dark:text-blue-400 text-sm"
                                        wire:click="openItemsModal({{ $discount->id }})">
                                        View Items
                                    </button>

                                    @can('edit discount')
                                        <a
                                            href="{{ route('discount.edit', $discount) }}"
                                            class="text-blue-600 dark:text-blue-400 text-sm flex items-center gap-1">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete discount')
                                        <button
                                            wire:click="openDeleteModal({{ $discount->id }})"
                                            class="text-red-600 dark:text-red-400 text-sm flex items-center gap-1">
                                            Remove
                                        </button>
                                    @endcan

                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $discounts->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Records Found</h3>
                        @can('create discount')
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Get started by adding a new discount.</p>
                            <div class="mt-6">
                                <a href="{{ route('discount.create') }}">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add First Discount
                                    </button>
                                </a>
                            </div>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Enhanced Delete Modal --}}
    @if($showDeleteModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeDeleteModal"></div>

                {{-- Center modal --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white" id="modal-title">
                                    Delete Discount
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this discount? This action cannot be undone and all associated data will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button
                            type="button"
                            wire:click="deleteDiscount"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            Delete Discount
                        </button>
                        <button
                            type="button"
                            wire:click="closeDeleteModal"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2.5 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endteleport
    @endif

    <!-- Modal -->
    @if ($showItemsModal)
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
                            Discount Items
                        </h3>
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            wire:click="closeItemsModal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach ($discountItems as $item)
                                <div
                                    class="transition-transform duration-200 hover:-translate-y-1 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800 hover:shadow-md overflow-hidden flex flex-col"
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
                </div>
            </div>
        </div>
    @endteleport
    @endif
</div>