@props([
    'variant' => 'default',
    'for' => null,
])

@php
    $variants = [
        'default' => 'text-gray-400 text-sm',
        'profilebtn' => 'cursor-pointer px-4 py-2 border text-black text-sm font-medium rounded-lg hover:shadow-md transition-shadow duration-200',
        'default1' => 'text-gray-400 text-sm mb-1',
        'pass' => 'block text-sm font-medium text-gray-700 mb-1',
    ];

    $classes = $variants[$variant] ?? $variants['default'];
@endphp

<label @if($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</label>
