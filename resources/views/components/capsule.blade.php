@props([
    'label' => '',
    'color' => 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993]',
    'size' => 'xxs',
])

@php
    $sizeClass = match($size) {
        'sm' => 'text-sm px-3 py-1.5',
        'xs' => 'text-xs px-3 py-1.5',
        'xxs' => 'text-[8px] px-2.5 py-1',
        default => 'text-[8px] px-2.5 py-1',
    };
@endphp

<span
    {{ $attributes->merge([
        'class' => "font-[Barlow] inline-flex items-center justify-center text-center rounded-full font-semibold {$sizeClass} {$color}",
    ]) }}
>
    {{ $label }}
</span>

{{-- font-[Barlow] inline-flex bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] text-sm items-center justify-center text-center rounded-full font-semibold --}}
