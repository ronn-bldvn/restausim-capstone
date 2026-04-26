@props([
    'firstId',
    'label',
    'numberPlaceholder' => null,
    'secondId',
    'selectPlaceholder' => null,
    'selectData',
    'errorMessageOne' => null,
    'errorMessageTwo' => null,
])

<div class="">
    <label 
        for="{{ $firstId }}" 
        class="
            block mb-1 sm:mb-2 text-xs sm:text-sm font-medium
            {{ ($errorMessageOne || $errorMessageTwo) ? 'text-red-700 dark:text-red-500' : 'text-gray-900 dark:text-white' }}
        "
    >
        {{ $label }}
    </label>

    <div class="flex">
        <div class="flex w-full">
            @if ($icon ?? null)
                <span class="inline-flex items-center px-2 sm:px-3 text-xs sm:text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                    {{ $icon ?? '' }}
                </span>
            @endif

            <input 
                type="number" 
                id="{{ $firstId }}" 
                placeholder="{{ $numberPlaceholder }}" 
                class="
                    block min-w-16 w-full z-20 border-e-2 border
                    p-2 sm:p-2.5
                    text-xs sm:text-sm
                    dark:bg-gray-700 text-gray-900 dark:placeholder-gray-400 dark:text-white
                    [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                    {{ ($icon ?? '') ? '' : 'rounded-s-lg' }}
                    {{ ($errorMessageOne) ? 'bg-red-50 border-red-500 focus:ring-red-500 focus:border-red-500 dark:border-red-500' : 'bg-gray-50 border-e-gray-50 border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:border-e-gray-700 dark:border-gray-600 dark:focus:border-blue-500' }}
                " 
                {{ $attributes->merge(['wire:model.live.debounce.1000ms' => $firstModel, 'wire:keydown.enter.prevent' => '']) }}
            />
        </div>

        <div 
            class="
                shrink-0 z-10 inline-flex items-center
                text-xs sm:text-sm
                font-medium text-center
                rounded-e-lg focus:ring-4 focus:outline-none
                text-gray-900 bg-gray-100 border border-gray-300
                hover:bg-gray-200 focus:ring-gray-100
                dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700
                dark:text-white dark:border-gray-600
            "
        >
            <select 
                id="{{ $secondId }}" 
                class="
                    block w-full h-full
                    px-1.5 sm:px-2
                    text-[11px] sm:text-xs
                    rounded-e-lg border border-s-2
                    dark:bg-gray-700 text-gray-900 dark:placeholder-gray-400 dark:text-white
                    {{ ($errorMessageTwo) ? 'bg-red-50 border-red-500 focus:ring-red-500 focus:border-red-500 dark:border-red-500' : 'bg-gray-50 border-gray-300 border-s-gray-100 dark:border-s-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500' }}
                "
                {{ $attributes->merge(['wire:model.change' => $secondModel]) }}
            >
                @if($selectPlaceholder)
                    <option value="">{{ $selectPlaceholder }}</option>
                @endif

                @foreach ($selectData as $data)
                    <option value="{{ $data->id }}">
                        {{ Str::ucfirst($data->name) }}/s ({{ $data->symbol }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if($errorMessageOne || $errorMessageTwo)
        <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-500">
            @if($errorMessageOne === $errorMessageTwo)
                {{ $errorMessageOne }}
            @elseif($errorMessageOne)
                {{ $errorMessageOne }}
            @elseif($errorMessageTwo)
                {{ $errorMessageTwo }}
            @else
                {{ $errorMessageOne }} {{ $errorMessageTwo }}
            @endif
        </p>
    @endif
</div>