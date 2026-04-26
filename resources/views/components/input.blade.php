@props([
    'type' => 'text',
    'name', // required
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'variant' => 'default',
    'labelVariant' => 'default',
    'wrapperClass' => 'mb-4',
    'readonly' => false,
    'required' => false,
])

@php
    // Input styles
    $styles = [
        'default' => 'w-full mt-1 bg-white border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'auth' => 'w-full mt-1 bg-gray-50/10 border border-gray-300 text-white text-sm rounded-lg focus:ring-[#EA7C69] focus:border-[#EA7C69] block p-3 placeholder-gray-400',
        'reset' => 'w-full mt-1 bg-gray-50/10 border border-gray-300 text-black text-sm rounded-lg focus:ring-[#EA7C69] focus:border-[#EA7C69] block p-3 placeholder-gray-400',
        'review' => 'w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
        'joinSection' => 'bg-gray-100 w-[328px] h-[48px] mt-2 border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'manageSectionModal' => 'w-full h-full mt-2 border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'noCursor' => 'w-full mt-2 border border-gray-300 rounded-lg px-3 py-2 cursor-not-allowed',
        'noCursorSection' => 'w-full mt-1 bg-white border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-300',
        'submissions' => 'w-full border rounded px-3 py-2',
    ];

    // Label styles
    $labelStyles = [
        'default' => 'block text-sm font-medium text-black',
        'section' => 'block text-xl font-medium text-black',
        'calendar' => 'block text-sm font-medium text-black font-[Poppins]',
        'auth' => ' text-sm font-medium text-white font-[Barlow]',
    ];

    // Safe value for old input
    $safeValue = $name ? old($name, $value) : ($value ?? '');

    // Readonly class
    $readonlyClass = $readonly ? ' bg-gray-100 cursor-not-allowed' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if ($label)
        <label for="{{ $name }}" class="{{ $labelStyles[$labelVariant] ?? $labelStyles['default'] }} mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $attributes->get('id', $name) }}"
        value="{{ $safeValue }}"
        placeholder="{{ $placeholder }}"
        @if($readonly) readonly @endif
        {{ $attributes->merge(['class' => ($styles[$variant] ?? $styles['default']) . $readonlyClass]) }}
    >

    @if ($name)
        @error($name)
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
        @enderror
    @endif
</div>
