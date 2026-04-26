<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Kitchen
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">

        @foreach ($orders as $tableId => $order)

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg dark:bg-gray-800">

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                {{-- Table Header --}}
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 w-full" colspan="3">
                            {{ $order['table_name'] ?? 'Table '.$tableId }}
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($order['items'] ?? [] as $index => $item)

                        @if(($item['status'] ?? '') !== 'completed')

                        @php
                            $isPending = $item['status'] === 'pending';
                        @endphp

                        <tr
                            wire:key="order-{{ $item['item_id'] }}"
                            x-data="timer('{{ $item['created_at'] ?? null }}')"
                            x-init="start()"
                            class="border-b transition hover:brightness-95
                            {{ $isPending
                                ? 'bg-yellow-50 dark:bg-yellow-900/30'
                                : 'bg-blue-50 dark:bg-blue-900/30' }}">

                            {{-- Item Info --}}
                            <td class="px-4 py-3 w-full">

                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mb-1
                                    {{ $isPending
                                        ? 'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100'
                                        : 'bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-100' }}">
                                    ● {{ ucfirst($item['status']) }}
                                </span>

                                <h2 class="text-base font-semibold text-gray-900 dark:text-white break-words">
                                    {{ $item['item_name'] }}
                                </h2>

                                {{-- Customizations --}}
                                @if(!empty($item['customizations']))
                                <ul class="text-xs list-disc list-inside text-gray-500 dark:text-gray-400 mt-1">
                                    @foreach ($item['customizations'] as $custom)
                                        <li>{{ $custom['name'] }}</li>
                                    @endforeach
                                </ul>
                                @endif

                                {{-- Timer (visible on mobile, inline below name) --}}
                                <span x-text="formatted" class="sm:hidden inline-block mt-1 text-xs font-mono text-gray-500 dark:text-gray-400"></span>

                            </td>

                            {{-- Timer (hidden on mobile, shown on sm+) --}}
                            <td class="hidden sm:table-cell px-4 py-3 font-mono text-sm whitespace-nowrap">
                                <span x-text="formatted"></span>
                            </td>

                            {{-- Action --}}
                            <td class="px-3 py-3 text-right sm:text-center whitespace-nowrap">

                                <button
                                    wire:click="updateStatus('{{ $item['item_id'] }}','{{ $item['status'] }}','{{ $tableId }}','{{ $index }}')"
                                    class="px-3 py-2 text-xs sm:text-sm font-medium rounded-lg border focus:ring-4
                                    {{ $isPending
                                        ? 'bg-yellow-100 text-yellow-800 border-yellow-300 hover:bg-yellow-200 focus:ring-yellow-200'
                                        : 'bg-blue-100 text-blue-800 border-blue-300 hover:bg-blue-200 focus:ring-blue-200' }}">

                                    {{ $isPending ? 'Start' : 'Finish' }}

                                </button>

                            </td>

                        </tr>

                        @endif

                    @empty

                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-400 text-sm">
                                No active orders
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @endforeach

    </div>
</div>


{{-- Timer Script --}}
<script>
function timer(receivedAt) {
    return {
        seconds: 0,
        formatted: '00:00',
        interval: null,

        start() {
            if (!receivedAt) return

            const startTime = new Date(receivedAt)

            this.update(startTime)

            this.interval = setInterval(() => {
                this.update(startTime)
            }, 1000)
        },

        update(startTime) {
            const now = new Date()
            this.seconds = Math.floor((now - startTime) / 1000)

            const minutes = Math.floor(this.seconds / 60)
            const seconds = this.seconds % 60

            this.formatted =
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0')
        }
    }
}
</script>
