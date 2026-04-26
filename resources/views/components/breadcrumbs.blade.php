<div class="pl-6">
    @props(['items' => []])

    @if (!empty($items))
        <nav class="text-sm mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center p-6 space-x-2 bg-green-400">
                @foreach ($items as $label => $url)
                    <li class="flex items-center">
                        @if ($url)
                            <a href="{{ $url }}"
                               class="text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                {{ $label }}
                            </a>
                            @if (!$loop->last)
                                <svg class="w-4 h-4 mx-2 text-gray-400"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        @else
                            <span class="text-gray-500 font-medium">{{ $label }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</div>
