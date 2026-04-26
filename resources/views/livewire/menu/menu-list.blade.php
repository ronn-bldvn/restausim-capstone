<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Menu Management') }}
            </h2>
            <!-- Add Button -->
            <div class="">
                @can('create menu')
                    <a href="{{ route('menu.create') }}">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Menu Item
                        </button>
                    </a>
                @endcan
            </div>
        </div>

    </x-slot>

    <div class="">
        <div class="flex justify-end items-center my-6">
            <!-- Add Button -->
            <div class="">
                <a href="{{ route('menu.create') }}">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2.5 bg-[#EA7C69] text-white font-medium rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Menu Item
                    </button>
                </a>
            </div>
        </div>
        <div class="max-w-7xl">

            <!-- Category Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-4 overflow-x-auto">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="hidden sm:flex -mb-px" aria-label="Tabs">
                        <button
                            class="px-6 py-4 text-sm font-medium transition-colors duration-150 border-b-2 {{ (!$active) ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                            wire:click="switchTabs('')">
                            All Items
                        </button>
                        @foreach ($categories as $category)
                            <button
                                class="px-6 py-4 text-sm font-medium transition-colors duration-150 border-b-2 {{ ($active == $category->id) ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
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

            <div class="shadow-sm overflow-hidden">

    @if (!$menuItems->isEmpty())

        <!-- ===================== -->
        <!-- MOBILE CARD LAYOUT -->
        <!-- ===================== -->
        <div class="grid gap-5 md:hidden ">
            @foreach ($menuItems as $menuItem)
                <div class="p-4 space-y-3 bg-white dark:bg-gray-800 rounded-lg">

                    <!-- Top Section -->
                    <div class="flex gap-4">
                        <img src="{{ asset('storage/' . $menuItem->image) }}"
                             class="w-20 h-20 object-cover rounded-lg shadow"
                             alt="{{ $menuItem->name }}">

                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-base">
                                {{ $menuItem->name }}
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                ₱{{ number_format($menuItem->price, 2) }}
                            </p>

                            <!-- Availability -->
                            @if($menuItem->is_available ?? true)
                                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Available
                                </span>
                            @else
                                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Unavailable
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        @php
                            $categoryColors = [
                                'Starters/Appetizers' => 'bg-green-100 text-green-800',
                                'Soups & Salads' => 'bg-blue-100 text-blue-800',
                                'Main Courses' => 'bg-red-100 text-red-800',
                                'Pasta & Risotto' => 'bg-pink-100 text-pink-800',
                                'Sides / Accompaniments' => 'bg-yellow-100 text-yellow-800',
                                'Desserts' => 'bg-orange-100 text-orange-800',
                                'Beverages' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp

                        <span class="px-2 py-1 rounded-xl text-xs font-medium
                            {{ $categoryColors[$menuItem->category->name] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $menuItem->category->name ?? 'N/A' }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $menuItem->description ?? 'No description' }}
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-2 border-t dark:border-gray-700">
                        <a href="{{ route('menu.edit', $menuItem) }}"
                           class="text-blue-600 dark:text-blue-400 text-sm font-medium">
                            Edit
                        </a>

                        <button wire:click="openDeleteModal({{ $menuItem->id }})"
                                class="text-red-600 dark:text-red-400 text-sm font-medium">
                            Delete
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
        @endif

            <!-- Menu Items Table -->
            <div class="hidden md:block overflow-x-auto">
                <div class="bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                @if (!$menuItems->isEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text">
                                        Image
                                    </th>
                                    <th scope="col" class="px-6 py-4 text">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-4 text">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-4 text">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 text">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($menuItems as $menuItem)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <img src="{{ asset('storage/' . $menuItem->image) }}"
                                                class="w-16 h-16 object-cover rounded-lg shadow-sm"
                                                alt="{{ $menuItem->name }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $menuItem->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                                @php
                                                    $categoryColors = [
                                                        'Starters/Appetizers' => 'bg-green-100 text-green-800',
                                                        'Soups & Salads' => 'bg-blue-100 text-blue-800',
                                                        'Main Courses' => 'bg-red-100 text-red-800',
                                                        'Pasta & Risotto' => 'bg-pink-100 text-pink-800',
                                                        'Sides / Accompaniments' => 'bg-yellow-100 text-yellow-800',
                                                        'Desserts' => 'bg-orange-100 text-orange-800',
                                                        'Beverages' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                @endphp
                                            <span class="px-2 py-1 rounded-xl text-sm font-medium whitespace-nowrap
                                                {{ $categoryColors[$menuItem->category->name] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $menuItem->category->name ?? 'N/A' }}
                                            </span>

                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                ₱{{ number_format($menuItem->price, 2) }}
                                            </div>                                        </td>
                                        <td class="px-6 py-4">
                                            @if($menuItem->is_available ?? true)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Unavailable
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-400 max-w-xs whitespace">
                                                {{ $menuItem->description ?? 'No description' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center space-x-3">
                                                @can('edit menu')
                                                    <a href="{{ route('menu.edit', $menuItem) }}"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400 font-medium transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endcan
                                                @can('delete menu')
                                                    <button
                                                        wire:click="openDeleteModal({{ $menuItem->id }})"
                                                        class="inline-flex items-center text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 font-medium transition-colors duration-150">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $menuItems->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No menu items found</h3>
                        @can('create menu')
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new menu item.</p>
                            <div class="mt-6">
                                <a href="{{ route('menu.create') }}">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Menu Item
                                    </button>
                                </a>
                            </div>
                        @endcan
                    </div>
                @endif
            </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDeleteModal"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Delete Menu Item
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this menu item? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="deleteMenu"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Delete
                        </button>
                        <button
                            type="button"
                            wire:click="closeDeleteModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>