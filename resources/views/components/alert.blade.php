@props(['type' => 'info', 'message' => null])

@php
    $types = [
        'success' => [
            'bg' => 'bg-white',
            'text' => 'text-gray-900',
            'iconBg' => 'bg-green-100',
            'iconColor' => 'text-green-500',
            'title' => 'Success!',
            'toast' => true,
        ],
        'error1' => [
            'bg' => 'bg-white',
            'text' => 'text-red-800',
            'iconBg' => 'bg-red-100',
            'iconColor' => 'text-red-600',
            'title' => 'Error',
            'toast' => true,
        ],
        'error' => [
            'bg' => 'bg-red-200',
            'text' => 'text-red-800',
            'iconBg' => '',
            'iconColor' => 'text-red-600',
            'title' => 'Error',
            'toast' => false,
        ],
        'warning' => [
            'bg' => 'bg-orange-200',
            'text' => 'text-yellow-800',
            'iconBg' => '',
            'iconColor' => 'text-yellow-600',
            'title' => 'Warning',
            'toast' => false,
        ],
        'info' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-900',
            'iconBg' => '',
            'iconColor' => 'text-blue-700',
            'title' => 'Info',
            'toast' => true,
        ],
    ];


    $config = $types[$type] ?? $types['info'];
@endphp

@if($message)
    @if($config['toast'])
        <div id="toast-{{ $type }}"
            class="fixed top-6 right-6 flex items-center w-80 p-4 rounded-xl shadow-lg border border-gray-100 {{ $config['bg'] }} transform translate-x-full opacity-0 transition-all duration-500 ease-out z-50">

            {{-- Left Icon Circle --}}
            <div class="flex-shrink-0 rounded-full {{ $config['iconBg'] }} p-2">
                @if($type === 'success')
                    <svg class="w-5 h-5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @elseif($type === 'update')
                    <svg class="w-5 h-5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m0 0l-3-3m3 3l3-3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif($type === 'error1')
                    <svg class="w-5 h-5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @endif
            </div>

            {{-- Message --}}
            <div class="ml-3 flex-1">
                <p class="font-semibold {{ $config['text'] }}">{{ $config['title'] }}</p>
                <p class="text-sm text-gray-600">{{ $message }}</p>
            </div>

            {{-- Close Button --}}
            <button type="button" class="ml-3 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Animation Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toast = document.getElementById('toast-{{ $type }}');
                if (toast) {
                    // Slide in
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full', 'opacity-0');
                        toast.classList.add('translate-x-0', 'opacity-100');
                    }, 100);

                    // Slide out after 4 seconds
                    setTimeout(() => {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 500);
                    }, 4000);
                }
            });
        </script>

    @else
        {{-- ⚠️ Inline alerts for error/warning --}}
        <div class="{{ $config['bg'] }} px-6 py-4 my-4 rounded-md text-sm flex items-center mx-auto max-w-lg">
            <svg viewBox="0 0 24 24" class="{{ $config['iconColor'] }} w-5 h-5 mr-3">
                <path fill="currentColor" d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z" />
            </svg>
            <span class="{{ $config['text'] }}">{{ $message }}</span>
        </div>
    @endif
@endif
