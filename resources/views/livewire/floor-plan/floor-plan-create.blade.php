<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload Floor Plan') }}
        </h2>
    </x-slot>
    @if($errors->any())
        <ul>
        @foreach ($errors->all() as $message)
            <li class=" text-red-500">
            {{ $message }}
            </li>
        @endforeach
        </ul>
    @endif
    <div class="grid grid-cols-2 m-5 h-[70vh]">
        <form wire:submit.prevent="save" class="m-10" enctype="multipart/form-data">
            @csrf
            <div class="p-5">
            <x-form.floating-input
                label="Name"
                id="name"
                type="text"
                wire:model="name"
                />
            <div class=" pt-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                <input name="filepath" 
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                    aria-describedby="file_input_help" 
                    id="file_input" 
                    type="file"
                    wire:model="filepath">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG</p>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-5">Submit</button>
            </div>
        </form>
        <div class="flex items-center justify-center">
            @if ($filepath)
                <div class="w-full h-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4">
                    <img
                        src="{{ $filepath->temporaryURL() }}"
                        alt="Menu preview"
                        class="w-full h-full object-contain rounded-lg"
                    >
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl">
                    <p class="text-sm text-gray-400">
                        File preview will appear here
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
