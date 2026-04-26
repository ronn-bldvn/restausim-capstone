@props([
    'headers' => [],
    'align' => 'text-left',
])

<div class="overflow-x-auto rounded-lg shadow w-full">
    <table class="min-w-full table-auto border border-gray-200 divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                @foreach ($headers as $header)
                    <th class="px-4 py-2 text-sm font-semibold text-gray-700 {{ $align }}">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>
