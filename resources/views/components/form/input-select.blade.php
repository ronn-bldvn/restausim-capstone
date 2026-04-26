@props([
    'id',
    'label',
    'placeholder' => null,
    'selectData',
    'isGrouped' => false,
    'isUnit' => false,
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
    <select 
        id="{{ $id }}" 
        class="
            rounded-e-lg border border-s-2h-full text-sm rounded-lg block w-full p-2.5
            dark:bg-gray-700 text-gray-900 dark:placeholder-gray-400 dark:text-white
            {{ ($errorMessage) ? 'bg-red-50  border-red-500 focus:ring-red-500  focus:border-red-500 dark:border-red-500' : 'bg-gray-50 border-gray-300  border-s-gray-100 dark:border-s-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500 ' }}
            
        "
        {{ $attributes->merge() }}
    >
            <option value="">{{ $placeholder }}</option>

            @if ($isGrouped)
                @foreach ($selectData as $index => $data)
                    <optgroup label="{{ Str::ucfirst($index) }}">
                        @foreach ($data as $value)
                            @if (!$isUnit)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ Str::ucfirst($value->name) }} ({{ $value->symbol }})</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @foreach ($selectData as $data)
                    @if (!$isUnit)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @else
                        <option value="{{ $data->id }}">{{ Str::ucfirst($data->name) }} ({{ $data->symbol }})</option>
                    @endif
                @endforeach
            @endif
    </select>
    @if($errorMessage)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">
            {{ $errorMessage }}
        </p>
    @endif
</div>