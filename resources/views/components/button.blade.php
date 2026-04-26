@extends('partials.links.links')

@props([
    'variant' => 'outlined',
])

@php
    $variants = [
        'btnGradient' => 'w-max h-min px-4 py-3 text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition',
        'btnGradientv1' => 'w-max h-min px-10 py-2 text-sm bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] border border-black rounded hover:bg-gray-100 transition',
        'btnNoGradient' => 'w-max h-min px-4 py-3 text-sm bg-white border border-black rounded hover:bg-gray-100 transition',
        'filled' => 'w-max h-min bg-[#898989] text-sm text-black px-5 py-5 rounded hover:bg-gray-100 transition',
        'outlined' => 'w-max h-min px-3 py-1.5 text-sm bg-white border border-black rounded hover:bg-gray-100 transition',
        'login' => 'font-[Roboto] w-[120px] h-[40px] bg-[#EA7C69] text-white rounded-full text-[12px] font-bold cursor-pointer transition-colors duration-300 ease-in-out hover:bg-[#d96a58]',
        'cancel'=> 'ml-auto w-min h-min text-sm border px-10 py-2 border-black rounded hover:bg-gray-100 transition',
        'calendar' => 'w-max h-min px-5 py-2 text-sm border border-black rounded hover:bg-gray-100 transition',
        'modal' => 'w-max h-min px-3 py-1.5 text-sm border border-black rounded hover:bg-gray-100 transition',
        'closeX' => 'absolute top-2 right-2 text-gray-500 hover:text-gray-700 ',
        'email' => 'w-full bg-white hover:bg-gray-50 font-semibold py-3 px-4 rounded-lg border border-gray-300 transition-colors duration-200 flex items-center justify-center',
        'profileX' => 'text-gray-400 hover:text-gray-600',
        'seeStudent' => 'w-max h-min px-3 py-1.5 mr-3 text-sm border border-black rounded hover:bg-gray-100 transition',
        'joinSection' => 'w-min h-min text-sm border px-10 py-2 border-black rounded hover:bg-gray-100 transition',
        'manageSectionModal' => 'w-full bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] hover:bg-gray-50 font-semibold py-3 px-4 rounded-lg border border-gray-300 transition-colors duration-200 flex items-center justify-center',
        'manageSectionModalNoW' => 'bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] hover:bg-gray-50 font-semibold py-3 px-4 rounded-lg border border-gray-300 transition-colors duration-200 flex items-center justify-center',
        'manageSectionModalNoGra' => 'w-full bg-white hover:bg-gray-50 font-semibold py-3 px-4 rounded-lg border border-gray-300 transition-colors duration-200 flex items-center justify-center',
        'manageSectionModalSetting' => 'w-36 text-sm font-medium py-4 px-4 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-100 transition',
        'manageSectionModalNoCursor' => 'w-full bg-gradient-to-r from-[#fceabb9b] to-[#ea7c6993] hover:bg-gray-50 font-semibold py-3 px-4 cursor-not-allowed rounded-lg border border-gray-300 transition-colors duration-200 flex items-center justify-center',
        'manageSectionModalSettingNoCursor' => 'w-36 text-sm font-medium py-4 px-4 rounded-lg border border-gray-300 shadow-sm hover:bg-gray-100 transition cursor-not-allowed',
    ];

    $classes = $variants[$variant] ?? $variants['outlined'];
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
