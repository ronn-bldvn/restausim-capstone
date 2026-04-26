@props([
    'type',
    'id',
    'label',
    'placeholder' => null,
    'errorMessage' => null,
])

<div>
    <label 
        for="{{ $id }}" 
        class="
            block mb-2 text-sm font-medium 
            {{ ($errorMessage) ? 'text-red-700 dark:text-red-500' : 'text-gray-900 dark:text-white' }}
        "
        >
                {{ $label }}
    </label>

    <div class="flex">
        @if($icon ?? '')
            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                {{ $icon ?? '' }}
            </span>
        @endif
        <input 
            type="{{ $type }}" 
            id="{{ $id }}" 
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="
                [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                text-sm  w-full p-2.5 flex-1 min-w-0 border block
                dark:bg-gray-700 text-gray-900 dark:placeholder-gray-400 dark:text-white
                {{ ($icon ?? '') ? 'round-e-lg' : 'rounded-lg' }}
                {{ ($errorMessage) ? 'bg-red-50  border-red-500 focus:ring-red-500  focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500' : 'bg-gray-50 focus:ring-blue-500 focus:border-blue-500 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500'}}  
            " 
            {{ $attributes->merge() }}
        >
    </div>

    @if($errorMessage)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">
            {{ $errorMessage }}
        </p>
    @endif
</div>