@props([
    'id', 
    'image', 
    'storedImage' => null,
    'errorMessage' => null,
])

<div class="w-full">
    <label 
        for="{{ $id }}" 
        class="
            flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer
            bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600
            {{ ($errorMessage) ? 'border-red-300   dark:border-red-600dark:hover:border-red-500' : 'border-gray-300   dark:border-gray-600 dark:hover:border-gray-500 '}}
        "
    >
        @if($image)
            <img src="{{ $image->temporaryURL() }}" alt="">
        @elseif($storedImage)
            <img src="{{ asset('storage/' . $storedImage) }}" class="object-contain w-full h-full" alt="Stored Image">
        @else
            <div 
                class="flex flex-col items-center justify-center pt-5 pb-6"
            >
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span></p>
                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, or JPG </p>
            </div>
        @endif
        <input id="{{ $id }}" type="file" class="hidden"  {{ $attributes->merge() }}/>
    </label>
    @if($errorMessage)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500 text-center">
            {{ $errorMessage }}
        </p>
    @endif
</div>