@props([
    'label' => null,
    'name',
    'placeholder' => '',
    'rows' => 2,
    'value' => '',
    'variant' => 'default',
    'required' => false,
])

@php

$variants = [
        'default' => 'w-full h-full mt-2 border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'textareaMax' => 'w-[328px] h-full mt-2 border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'review' => 'w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
        'announceCreate' => 'w-full h-full mt-2 rounded-lg px-3 py-2 border border-transparent focus:outline-none focus:ring-0 focus:border-transparent',

    ];

    $classes = $variants[$variant] ?? $variants['default'];

@endphp


<div class="mb-2">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-black">
            {{ $label }}

            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $attributes->get('id', $name) }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => $classes
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
