@props(['active'])

@php
$classes = ($active ?? false)
    ? 'flex items-center w-full px-3 py-3 text-lg font-medium
       border-l-4 border-indigo-600
       bg-gray-100 dark:bg-gray-700
       text-gray-900 dark:text-white'
    : 'flex items-center w-full px-3 py-3 text-lg font-medium
       border-l-4 border-transparent
       text-gray-600 dark:text-gray-300
       hover:bg-gray-100 dark:hover:bg-gray-700
       hover:border-gray-300
       transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
