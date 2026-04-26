{{-- resources/views/livewire/reporting-analytics.blade.php --}}

<div>
    {{-- ═══════════════════════════════════════════════════════
    FILTER BAR
    ═══════════════════════════════════════════════════════ --}}
    @if (in_array($activeTab, ['dashboard', 'inventory']))
        <div class="mb-4 flex flex-wrap items-center gap-2">
            @foreach (['today' => 'Today', 'yesterday' => 'Yesterday', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year', 'custom' => 'Custom'] as $val => $lbl)
                <button wire:click="$set('filter','{{ $val }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-['Bricolage_Grotesque'] font-black transition-all
                                {{ $filter === $val
                    ? 'bg-gray-900 dark:bg-white text-white dark:text-gray-900 shadow-md'
                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    {{ $lbl }}
                </button>
            @endforeach

            @if (in_array($filter, ['month', 'year']))
                <select wire:model.live="year"
                    class="text-xs font-['Bricolage_Grotesque'] font-black bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    @foreach ($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            @endif

            @if ($filter === 'custom')
                <div class="flex items-center gap-1">
                    <input type="date" wire:model.live="startDate"
                        class="text-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 [color-scheme:light] dark:[color-scheme:dark]" />
                    <span class="text-gray-400 dark:text-gray-500 text-xs">–</span>
                    <input type="date" wire:model.live="endDate"
                        class="text-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 [color-scheme:light] dark:[color-scheme:dark]" />
                </div>
            @endif

            @if ($periodLabel)
                <span
                    class="ml-auto text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 px-3 py-1.5 rounded-lg">
                    {{ $periodLabel }}
                </span>
            @endif
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
    REPORT EXPORT BUTTONS
    ═══════════════════════════════════════════════════════ --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <button wire:click="exportSalesReport" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-['Bricolage_Grotesque'] font-black
                   bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500
                   text-white shadow-sm transition-all active:scale-95 disabled:opacity-60">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
            </svg>
            <span wire:loading.remove wire:target="exportSalesReport">Sales Report</span>
            <span wire:loading wire:target="exportSalesReport">Exporting…</span>
        </button>

        <button wire:click="exportInventoryReport" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-['Bricolage_Grotesque'] font-black
                   bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-500 dark:hover:bg-yellow-400
                   text-white dark:text-gray-900 shadow-sm transition-all active:scale-95 disabled:opacity-60">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
            </svg>
            <span wire:loading.remove wire:target="exportInventoryReport">Inventory Report</span>
            <span wire:loading wire:target="exportInventoryReport">Exporting…</span>
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════
    TAB NAV
    ═══════════════════════════════════════════════════════ --}}
    <div
        class="mb-5 flex gap-1 bg-white dark:bg-gray-800 rounded-xl p-1 shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        @php
            $tabs = [
                ['id' => 'dashboard', 'label' => 'Dashboard'],
                ['id' => 'inventory', 'label' => 'Inventory'],
                ['id' => 'items', 'label' => 'Menu Items'],
                ['id' => 'financial', 'label' => 'Financial'],
                ['id' => 'inventory_usage', 'label' => 'Usage by Day'],
            ];
        @endphp

        @foreach ($tabs as $tab)
            <button wire:click="setTab('{{ $tab['id'] }}')"
                class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-['Bricolage_Grotesque'] font-black transition-all whitespace-nowrap
                        {{ $activeTab === $tab['id']
            ? 'bg-gray-900 dark:bg-gray-600 text-white shadow'
            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div wire:loading.delay
        class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4 font-['Bricolage_Grotesque']">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        Loading...
    </div>

    {{-- ═══════════════════════════════════════════════════════
    DASHBOARD
    ═══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'dashboard')
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 mb-5">
            @php
                $kpis = [
                    [
                        'label' => 'Total Orders',
                        'value' => number_format($analytics['total_orders']),
                        'sub' => 'completed',
                        'accent' => 'text-blue-500',
                    ],
                    [
                        'label' => 'Gross Sales',
                        'value' => '₱' . number_format($analytics['gross_sales'], 2),
                        'sub' => 'from orders',
                        'accent' => 'text-emerald-500',
                    ],
                    [
                        'label' => 'Paid Sales',
                        'value' => '₱' . number_format($analytics['paid_sales'], 2),
                        'sub' => 'from payments',
                        'accent' => 'text-green-500',
                    ],
                    [
                        'label' => 'Net Profit',
                        'value' => '₱' . number_format($analytics['net_profit'], 2),
                        'sub' => number_format($analytics['profit_margin'], 1) . '% margin',
                        'accent' => $analytics['net_profit'] >= 0 ? 'text-yellow-500' : 'text-red-500',
                    ],
                    [
                        'label' => 'Items Sold',
                        'value' => number_format($analytics['total_items_sold']),
                        'sub' => 'units',
                        'accent' => 'text-purple-500',
                    ],
                    [
                        'label' => 'Avg Order',
                        'value' => '₱' . number_format($analytics['average_order_value'], 2),
                        'sub' => 'per order',
                        'accent' => 'text-cyan-500',
                    ],
                ];
            @endphp

            @foreach ($kpis as $k)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-400 mb-1">{{ $k['label'] }}</p>
                    <p class="text-xl font-['Bricolage_Grotesque'] font-black {{ $k['accent'] }} leading-tight">
                        {{ $k['value'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $k['sub'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            @php
                $spots = [
                    [
                        'label' => 'Top by Qty',
                        'name' => $analytics['top_selling_item_quantity']['name'],
                        'stat' => number_format($analytics['top_selling_item_quantity']['quantity']) . ' units',
                        'icon' => '🏆',
                        'accent' => 'text-yellow-500',
                    ],
                    [
                        'label' => 'Top by Revenue',
                        'name' => $analytics['top_selling_item_revenue']['name'],
                        'stat' => '₱' . number_format($analytics['top_selling_item_revenue']['revenue'], 2),
                        'icon' => '💰',
                        'accent' => 'text-green-500',
                    ],
                    [
                        'label' => 'Most Profitable',
                        'name' => $analytics['most_profitable_item']['name'],
                        'stat' => '₱' . number_format($analytics['most_profitable_item']['profit'], 2) . ' profit',
                        'icon' => '📈',
                        'accent' => 'text-blue-500',
                    ],
                    [
                        'label' => 'Top Inventory',
                        'name' => $analytics['most_used_inventory']['name'],
                        'stat' => number_format($analytics['most_used_inventory']['quantity']) . ' used',
                        'icon' => '📦',
                        'accent' => 'text-purple-500',
                    ],
                ];
            @endphp

            @foreach ($spots as $s)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm flex items-start gap-3">
                    <span class="text-2xl">{{ $s['icon'] }}</span>
                    <div class="min-w-0">
                        <p class="text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
                        <p class="font-['Bricolage_Grotesque'] font-black text-gray-800 dark:text-gray-100 truncate text-sm">
                            {{ $s['name'] }}</p>
                        <p class="text-xs {{ $s['accent'] }} mt-0.5 font-['Bricolage_Grotesque'] font-black">{{ $s['stat'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top 8
                    Items — Revenue</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-top-revenue"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top 8
                    Items — Quantity</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-top-qty"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top 8
                    Inventory Usage</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-inv-usage"></canvas>
                </div>
            </div>

            @if (in_array($filter, ['week', 'month', 'year']))
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Payment
                        Mix</h3>
                    <div class="relative h-60" wire:ignore>
                        <canvas id="chart-paymix"></canvas>
                    </div>
                </div>
            @endif
        </div>

        @if (in_array($filter, ['week', 'month', 'year']))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                @foreach ([['id' => 'chart-sales-ot', 'title' => 'Gross Sales Over Time'], ['id' => 'chart-paid-ot', 'title' => 'Paid Sales Over Time'], ['id' => 'chart-orders-ot', 'title' => 'Orders Over Time']] as $ch)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                        <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">
                            {{ $ch['title'] }}</h3>
                        <div class="relative h-48" wire:ignore>
                            <canvas id="{{ $ch['id'] }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <script type="application/json" id="report-chart-data-dashboard">
                {!! json_encode([
            'tab' => 'dashboard',
            'filter' => $filter,
            'top' => $charts['top'],
            'inventory_usage' => $charts['inventory_usage'],
            'sales' => $charts['sales'] ?? null,
            'paid' => $charts['paid'] ?? null,
            'orders' => $charts['orders'] ?? null,
            'paymix' => $charts['paymix'] ?? null,
        ]) !!}
            </script>
    @endif

    {{-- ═══════════════════════════════════════════════════════
    INVENTORY
    ═══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'inventory')
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            @php
                $invKpis = [
                    [
                        'label' => 'Ingredients Used',
                        'value' => number_format($invReport['total_ingredients_used'], 2),
                        'accent' => 'text-yellow-500',
                    ],
                    [
                        'label' => 'Most Consumed',
                        'value' => $invReport['most_consumed']['name'],
                        'accent' => 'text-blue-500',
                        'sub' => number_format($invReport['most_consumed']['quantity'], 2) . ' units',
                    ],
                    [
                        'label' => 'Below Par',
                        'value' => $invReport['below_par']['count'],
                        'accent' => 'text-orange-500',
                    ],
                    [
                        'label' => 'Out of Stock',
                        'value' => $invReport['out_of_stock']['count'],
                        'accent' => 'text-red-500',
                    ],
                ];
            @endphp

            @foreach ($invKpis as $k)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-400 mb-1">{{ $k['label'] }}</p>
                    <p class="text-xl font-['Bricolage_Grotesque'] font-black {{ $k['accent'] }} truncate">{{ $k['value'] }}</p>
                    @if (!empty($k['sub']))
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $k['sub'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top 8
                    Consumed Ingredients</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-inv-consumed"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-3">Ingredient
                    Breakdown</h3>
                <div class="overflow-auto max-h-64">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-xs font-['Bricolage_Grotesque'] text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                                <th class="text-left py-2 pr-3 font-black">Ingredient</th>
                                <th class="text-right py-2 font-black">Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invReport['ingredient_breakdown'] as $row)
                                <tr
                                    class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="py-2 pr-3 font-['Bricolage_Grotesque'] text-gray-700 dark:text-gray-300">
                                        {{ $row['name'] }}</td>
                                    <td
                                        class="py-2 text-right font-['Bricolage_Grotesque'] font-black text-yellow-600 dark:text-yellow-400 font-mono">
                                        {{ number_format($row['total_used'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No data
                                        for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-orange-500 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Below Par Level ({{ $invReport['below_par']['count'] }})
                </h3>

                <div class="overflow-auto max-h-72">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                                <th class="text-left py-2 pr-3">Item</th>
                                <th class="text-right py-2 pr-3">On Hand</th>
                                <th class="text-right py-2 pr-3">Par</th>
                                <th class="text-right py-2">Gap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invReport['below_par']['items'] as $item)
                                <tr
                                    class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="py-2 pr-3 font-['Bricolage_Grotesque'] text-gray-700 dark:text-gray-300">
                                        {{ $item['name'] }}</td>
                                    <td class="py-2 pr-3 text-right font-mono text-orange-500 text-xs">
                                        {{ number_format($item['quantity'], 2) }}</td>
                                    <td class="py-2 pr-3 text-right font-mono text-gray-400 dark:text-gray-500 text-xs">
                                        {{ number_format($item['par_level'], 2) }}</td>
                                    <td class="py-2 text-right font-mono text-red-500 text-xs font-black">
                                        -{{ number_format($item['gap'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">All items
                                        above par.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $invReport['below_par']['pagination']->links() }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-red-500 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Out of Stock ({{ $invReport['out_of_stock']['count'] }})
                </h3>

                <div class="overflow-auto max-h-72">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                                <th class="text-left py-2 pr-3">Item</th>
                                <th class="text-right py-2">Par Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invReport['out_of_stock']['items'] as $item)
                                <tr
                                    class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="py-2 pr-3 font-['Bricolage_Grotesque'] text-gray-700 dark:text-gray-300">
                                        {{ $item['name'] }}</td>
                                    <td class="py-2 text-right font-mono text-gray-400 dark:text-gray-500 text-xs">
                                        {{ number_format($item['par_level'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No
                                        out-of-stock items.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script type="application/json" id="report-chart-data-inventory">
                {!! json_encode([
            'tab' => 'inventory',
            'consumed' => $invReport['top_consumed_chart'],
        ]) !!}
            </script>
    @endif

    {{-- ═══════════════════════════════════════════════════════
    MENU ITEMS
    ═══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'items')
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-5">
            @php
                $iKpis = [
                    [
                        'label' => 'Items Sold',
                        'value' => number_format($itemsKpis['totalItemsSold']),
                        'accent' => 'text-blue-500',
                    ],
                    [
                        'label' => 'Total Revenue',
                        'value' => '₱' . number_format($itemsKpis['totalRevenue'], 2),
                        'accent' => 'text-emerald-500',
                    ],
                    [
                        'label' => 'Total Profit',
                        'value' => '₱' . number_format($itemsKpis['totalProfit'], 2),
                        'accent' => 'text-yellow-500',
                    ],
                    [
                        'label' => 'Avg Margin',
                        'value' => number_format($itemsKpis['avgMargin'], 1) . '%',
                        'accent' => 'text-purple-500',
                    ],
                    [
                        'label' => 'Unique Items',
                        'value' => number_format($itemsKpis['uniqueItemsSold']),
                        'accent' => 'text-cyan-500',
                    ],
                ];
            @endphp

            @foreach ($iKpis as $k)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-400 mb-1">{{ $k['label'] }}</p>
                    <p class="text-xl font-['Bricolage_Grotesque'] font-black {{ $k['accent'] }}">{{ $k['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top
                    Selling Items (Qty)</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-items-top"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Profit
                    Margin by Item</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-items-margin"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            @php
                $itemTables = [
                    [
                        'key' => 'top_selling',
                        'title' => 'Top Selling',
                        'accent' => 'text-emerald-500',
                        'cols' => [
                            ['f' => 'qty', 'l' => 'Qty'],
                            ['f' => 'revenue', 'l' => 'Revenue', 'p' => '₱', 'dec' => 2],
                        ],
                    ],
                    [
                        'key' => 'slow_moving',
                        'title' => 'Slow Moving',
                        'accent' => 'text-orange-500',
                        'cols' => [
                            ['f' => 'qty', 'l' => 'Qty'],
                            ['f' => 'revenue', 'l' => 'Revenue', 'p' => '₱', 'dec' => 2],
                        ],
                    ],
                    [
                        'key' => 'most_profitable',
                        'title' => 'Most Profitable',
                        'accent' => 'text-yellow-500',
                        'cols' => [
                            ['f' => 'margin', 'l' => 'Margin', 's' => '%', 'dec' => 1],
                            ['f' => 'profit', 'l' => 'Profit', 'p' => '₱', 'dec' => 2],
                        ],
                    ],
                ];
            @endphp

            @foreach ($itemTables as $tbl)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <h3 class="text-sm font-['Bricolage_Grotesque'] font-black {{ $tbl['accent'] }} mb-4">{{ $tbl['title'] }}
                    </h3>

                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                                <th class="text-left py-2 pr-2">Item</th>
                                @foreach ($tbl['cols'] as $col)
                                    <th class="text-right py-2 pr-1">{{ $col['l'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($itemsReport[$tbl['key']] as $row)
                                <tr
                                    class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td
                                        class="py-2 pr-2 font-['Bricolage_Grotesque'] text-gray-700 dark:text-gray-300 truncate max-w-[140px] text-xs">
                                        {{ $row['name'] }}</td>
                                    @foreach ($tbl['cols'] as $col)
                                        <td class="py-2 pr-1 text-right {{ $tbl['accent'] }} font-mono text-xs font-black">
                                            {{ ($col['p'] ?? '') . number_format($row[$col['f']], $col['dec'] ?? 0) . ($col['s'] ?? '') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <script type="application/json" id="report-chart-data-items">
                {!! json_encode([
            'tab' => 'items',
            'itemsCharts' => $itemsCharts,
        ]) !!}
            </script>
    @endif

    {{-- ═══════════════════════════════════════════════════════
    FINANCIAL
    ═══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'financial')
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            @php
                $fKpis = [
                    [
                        'label' => 'Total Paid',
                        'value' => '₱' . number_format($finKpis['totalPaid'], 2),
                        'accent' => 'text-emerald-500',
                    ],
                    [
                        'label' => 'Transactions',
                        'value' => number_format($finKpis['totalPayments']),
                        'accent' => 'text-blue-500',
                    ],
                    [
                        'label' => 'Cancelled/Refunded',
                        'value' => number_format($finKpis['cancelledTxnCount']),
                        'accent' => 'text-red-500',
                    ],
                    [
                        'label' => 'Total Discounts',
                        'value' => '₱' . number_format($finKpis['totalDiscount'], 2),
                        'accent' => 'text-orange-500',
                    ],
                    [
                        'label' => 'Discounted Orders',
                        'value' => number_format($finKpis['discountedOrders']),
                        'accent' => 'text-yellow-500',
                    ],
                    [
                        'label' => 'VAT Collected',
                        'value' => '₱' . number_format($finKpis['vatTotal'], 2),
                        'accent' => 'text-purple-500',
                    ],
                    [
                        'label' => 'Service VAT',
                        'value' => '₱' . number_format($finKpis['serviceVatTotal'], 2),
                        'accent' => 'text-cyan-500',
                    ],
                    [
                        'label' => 'Cancelled Amount',
                        'value' => '₱' . number_format($finKpis['cancelledTxnAmount'], 2),
                        'accent' => 'text-red-400',
                    ],
                ];
            @endphp

            @foreach ($fKpis as $k)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-['Bricolage_Grotesque'] text-gray-500 dark:text-gray-400 mb-1">{{ $k['label'] }}</p>
                    <p class="text-lg font-['Bricolage_Grotesque'] font-black {{ $k['accent'] }}">{{ $k['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Payment
                    Mix</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-fin-paymix"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Discounts
                    by Type</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-fin-discounts"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">VAT
                    Monthly Trend</h3>
                <div class="relative h-48" wire:ignore>
                    <canvas id="chart-fin-vat"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Promotions
                    &amp; Vouchers</h3>
                <div class="relative h-48" wire:ignore>
                    <canvas id="chart-fin-promos"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Payment
                    Method Breakdown</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left py-2 pr-3">Method</th>
                            <th class="text-right py-2 pr-3">Txns</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finReport['payMix'] as $row)
                            <tr
                                class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td
                                    class="py-2 pr-3 font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 uppercase text-xs">
                                    {{ $row->method }}</td>
                                <td class="py-2 pr-3 text-right font-mono text-gray-500 dark:text-gray-400 text-xs">
                                    {{ number_format($row->txn_count) }}</td>
                                <td class="py-2 text-right font-mono font-black text-emerald-600 dark:text-emerald-400 text-xs">
                                    ₱{{ number_format($row->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Discount
                    Usage by Type</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left py-2 pr-3">Type</th>
                            <th class="text-right py-2 pr-3">Orders</th>
                            <th class="text-right py-2">Total Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finReport['discountByType'] as $row)
                            <tr
                                class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td
                                    class="py-2 pr-3 font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 uppercase text-xs">
                                    {{ $row->type }}</td>
                                <td class="py-2 pr-3 text-right font-mono text-gray-500 dark:text-gray-400 text-xs">
                                    {{ number_format($row->orders_count) }}</td>
                                <td class="py-2 text-right font-mono font-black text-orange-500 text-xs">
                                    ₱{{ number_format($row->total_discount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No discounts.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm mb-4">
            <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-red-500 mb-4">Recent Cancelled / Refunded
                Transactions</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[600px]">
                    <thead>
                        <tr
                            class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left py-2 pr-3">ID</th>
                            <th class="text-left py-2 pr-3">Order</th>
                            <th class="text-left py-2 pr-3">Method</th>
                            <th class="text-right py-2 pr-3">Amount</th>
                            <th class="text-left py-2 pr-3">Status</th>
                            <th class="text-left py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finReport['cancelledTxns'] as $row)
                            <tr
                                class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="py-2 pr-3 font-mono text-xs text-gray-400 dark:text-gray-500">#{{ $row->id }}</td>
                                <td class="py-2 pr-3 font-mono text-xs text-gray-400 dark:text-gray-500">#{{ $row->order_id }}
                                </td>
                                <td
                                    class="py-2 pr-3 text-xs font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 uppercase">
                                    {{ $row->payment_method }}</td>
                                <td class="py-2 pr-3 text-right font-mono font-black text-red-500 text-xs">
                                    ₱{{ number_format($row->amount, 2) }}</td>
                                <td class="py-2 pr-3">
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full font-['Bricolage_Grotesque'] font-black
                                                {{ $row->status === 'refunded' ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400' }}">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td class="py-2 text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $row->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-xs text-gray-400 dark:text-gray-500">No cancelled
                                    transactions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script type="application/json" id="report-chart-data-financial">
                {!! json_encode([
            'tab' => 'financial',
            'finCharts' => $finCharts,
        ]) !!}
            </script>
    @endif

    {{-- ═══════════════════════════════════════════════════════
    INVENTORY USAGE BY DAY
    ═══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'inventory_usage')
        <div class="flex flex-wrap gap-3 items-center mb-4">
            <div class="flex items-center gap-2">
                <label
                    class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-500 dark:text-gray-400">Category</label>
                <select wire:model.live="categoryId"
                    class="text-xs font-['Bricolage_Grotesque'] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label
                    class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-500 dark:text-gray-400">Window</label>
                <select wire:model.live="daysWindow"
                    class="text-xs font-['Bricolage_Grotesque'] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    @foreach ([7 => '7 days', 14 => '14 days', 30 => '30 days', 60 => '60 days', 90 => '90 days'] as $v => $l)
                        <option value="{{ $v }}" {{ $daysWindow == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-500 dark:text-gray-400">Per
                    page</label>
                <select wire:model.live="perPage"
                    class="text-xs font-['Bricolage_Grotesque'] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    @foreach ([5, 10, 25, 50] as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if (count($topForChart))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm mb-4">
                <h3 class="text-sm font-['Bricolage_Grotesque'] font-black text-gray-700 dark:text-gray-200 mb-4">Top 8 — Usage
                    by Inventory</h3>
                <div class="relative h-60" wire:ignore>
                    <canvas id="chart-usage-top"></canvas>
                </div>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-x-auto mb-4">
            <table class="w-full text-sm min-w-[820px]">
                <thead>
                    <tr
                        class="text-xs font-['Bricolage_Grotesque'] font-black text-gray-400 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                        <th class="text-left py-3 px-4">Inventory Item</th>
                        <th class="text-left py-3 px-3">Category</th>
                        @foreach ($daysOrder as $day)
                            <th class="text-center py-3 px-2">{{ substr($day, 0, 3) }}</th>
                        @endforeach
                        <th class="text-right py-3 px-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories->items() as $inv)
                        @php $maxVal = $inv->usage_max_day; @endphp
                        <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/20">
                            <td class="py-3 px-4">
                                <p class="font-['Bricolage_Grotesque'] font-black text-gray-800 dark:text-gray-200 text-sm">
                                    {{ $inv->name }}</p>
                                @if (!empty($inv->most_used_days))
                                    <p class="text-xs text-yellow-500 font-['Bricolage_Grotesque'] mt-0.5">Peak:
                                        {{ implode(', ', $inv->most_used_days) }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-xs font-['Bricolage_Grotesque'] text-gray-400 dark:text-gray-500">
                                {{ $inv->category->name ?? '—' }}</td>

                            @foreach ($daysOrder as $day)
                                @php
                                    $val = $inv->usage_days[$day] ?? 0;
                                    $intensity = $maxVal > 0 ? $val / $maxVal : 0;
                                    $cell =
                                        $intensity > 0.75
                                        ? 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-300'
                                        : ($intensity > 0.5
                                            ? 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400'
                                            : ($intensity > 0.25
                                                ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-500 dark:text-orange-400'
                                                : ($intensity > 0
                                                    ? 'bg-gray-50 dark:bg-gray-700/40 text-gray-500 dark:text-gray-400'
                                                    : 'text-gray-300 dark:text-gray-600')));
                                @endphp

                                <td class="py-3 px-2 text-center">
                                    @if ($val > 0)
                                        <span
                                            class="inline-block px-1.5 py-0.5 rounded text-xs font-mono font-black {{ $cell }}">{{ number_format($val, 1) }}</span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="py-3 px-4 text-right font-mono font-black text-yellow-600 dark:text-yellow-400 text-sm">
                                {{ number_format($inv->usage_total, 1) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($daysOrder) + 3 }}"
                                class="py-8 text-center text-gray-400 dark:text-gray-500 text-sm font-['Bricolage_Grotesque']">
                                No inventory data for this window.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">{{ $inventories->links() }}</div>

        @if (count($topForChart))
            <script type="application/json" id="report-chart-data-inventory-usage">
                        {!! json_encode([
                    'tab' => 'inventory_usage',
                    'usageChart' => [
                        'labels' => $topForChart->pluck('name')->values(),
                        'values' => $topForChart->pluck('usage_total')->values(),
                    ],
                ]) !!}
                    </script>
        @endif
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        (function () {
            let charts = {};
            let lastPayload = null;

            const PAL = [
                'rgba(234,179,8,0.85)',
                'rgba(16,185,129,0.85)',
                'rgba(59,130,246,0.85)',
                'rgba(168,85,247,0.85)',
                'rgba(249,115,22,0.85)',
                'rgba(6,182,212,0.85)',
                'rgba(239,68,68,0.85)',
                'rgba(132,204,22,0.85)'
            ];

            function isDark() {
                return document.documentElement.classList.contains('dark');
            }

            // ─── theme() ────────────────────────────────────────────────────────────
            // tick / font bumped to near-white in dark mode for legibility.
            // No more half-transparent slate grays that vanish on dark backgrounds.
            function theme() {
                const d = isDark();
                return {
                    grid: d ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)',
                    tick: d ? '#ffffff' : '#374151',   // ← pure white in dark mode
                    font: d ? '#ffffff' : '#374151',   // ← pure white in dark mode
                    ttBg: d ? '#1f2937' : '#ffffff',
                    ttBdr: d ? '#374151' : '#e5e7eb',
                    ttT: d ? '#ffffff' : '#111827',   // ← tooltip title white too
                    ttB: d ? '#ffffff' : '#374151',   // ← tooltip body white too
                    bdr: d ? '#1f2937' : '#ffffff'
                };
            }

            function destroyChart(id) {
                if (charts[id]) { charts[id].destroy(); delete charts[id]; }
            }

            function destroyMissingCharts() {
                Object.keys(charts).forEach(id => {
                    if (!document.getElementById(id)) destroyChart(id);
                });
            }

            // ─── baseOpts() ─────────────────────────────────────────────────────────
            // NO callback here — callbacks on numeric axes return undefined and
            // silently hide all ticks. Truncation is added per chart type only.
            function baseOpts(showLegend = false) {
                const t = theme();
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: showLegend,
                            labels: {
                                color: t.font,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 12,
                                font: { size: 11, family: 'Bricolage Grotesque' },
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: t.ttBg,
                            borderColor: t.ttBdr,
                            borderWidth: 1,
                            titleColor: t.ttT,
                            bodyColor: t.ttB,
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: t.tick,
                                font: { size: 11, family: 'Bricolage Grotesque' },
                                maxRotation: 40,
                                minRotation: 0
                                // ← no callback here; added only in bar()
                            },
                            grid: { color: t.grid },
                            border: { color: t.grid }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: t.tick,
                                font: { size: 11, family: 'Bricolage Grotesque' },
                                precision: 0
                                // ← no callback here; added only in hbar()
                            },
                            grid: { color: t.grid },
                            border: { color: t.grid }
                        }
                    }
                };
            }

            // ─── bar() ──────────────────────────────────────────────────────────────
            // x-axis carries TEXT labels → safe to truncate here.
            // y-axis carries NUMBERS    → never touch its callback.
            function bar(id, labels, data, color) {
                const el = document.getElementById(id);
                if (!el) return;
                destroyChart(id);

                const opts = baseOpts(false);

                // Truncate long item names on the category (x) axis only
                opts.scales.x.ticks.callback = function (value) {
                    const label = this.getLabelForValue(value);
                    return label && label.length > 13 ? label.slice(0, 11) + '…' : label;
                };

                charts[id] = new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: labels || [],
                        datasets: [{ data: data || [], backgroundColor: color || PAL[0], borderRadius: 5 }]
                    },
                    options: opts
                });
            }

            // ─── hbar() ─────────────────────────────────────────────────────────────
            // After indexAxis:'y' the axes SWAP:
            //   y-axis → category labels (TEXT) → truncate here
            //   x-axis → numeric values         → precision:0, NO callback
            function hbar(id, labels, data, color) {
                const el = document.getElementById(id);
                if (!el) return;
                destroyChart(id);

                const opts = baseOpts(false);
                opts.indexAxis = 'y';

                // x-axis is now the VALUE axis — whole numbers, no truncation
                opts.scales.x.ticks.precision = 0;
                opts.scales.x.ticks.maxRotation = 0;
                delete opts.scales.x.ticks.callback; // safety: remove any inherited callback

                // y-axis is now the LABEL axis — safe to truncate
                opts.scales.y.ticks.callback = function (value) {
                    const label = this.getLabelForValue(value);
                    return label && label.length > 15 ? label.slice(0, 13) + '…' : label;
                };

                charts[id] = new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: labels || [],
                        datasets: [{ data: data || [], backgroundColor: color || PAL, borderRadius: 5 }]
                    },
                    options: opts
                });
            }

            // ─── line() ─────────────────────────────────────────────────────────────
            function line(id, labels, data, color) {
                const el = document.getElementById(id);
                if (!el) return;
                destroyChart(id);

                const c = color || PAL[0];
                charts[id] = new Chart(el, {
                    type: 'line',
                    data: {
                        labels: labels || [],
                        datasets: [{
                            data: data || [],
                            borderColor: c,
                            backgroundColor: c.replace(/[\d.]+\)$/, '0.12)'),
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3
                        }]
                    },
                    options: baseOpts(false)
                });
            }

            // ─── donut() ────────────────────────────────────────────────────────────
            function donut(id, labels, data) {
                const el = document.getElementById(id);
                if (!el) return;
                destroyChart(id);

                const t = theme();
                charts[id] = new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: labels || [],
                        datasets: [{
                            data: data || [],
                            backgroundColor: PAL,
                            borderWidth: 2,
                            borderColor: t.bdr
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: t.font,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 14,
                                    font: { size: 11, family: 'Bricolage Grotesque' },
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                backgroundColor: t.ttBg,
                                borderColor: t.ttBdr,
                                borderWidth: 1,
                                titleColor: t.ttT,
                                bodyColor: t.ttB,
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: ctx => ' ' + ctx.label + ': ' + Number(ctx.parsed).toLocaleString()
                                }
                            }
                        }
                    }
                });
            }

            // ─── multiline() ────────────────────────────────────────────────────────
            function multiline(id, labels, sets) {
                const el = document.getElementById(id);
                if (!el) return;
                destroyChart(id);

                charts[id] = new Chart(el, {
                    type: 'line',
                    data: {
                        labels: labels || [],
                        datasets: (sets || []).map((s, i) => {
                            const c = PAL[i % PAL.length];
                            return {
                                label: s.label,
                                data: s.values || [],
                                borderColor: c,
                                backgroundColor: c.replace('0.85)', '0.12)'),
                                fill: false,
                                tension: 0.4,
                                pointRadius: 3
                            };
                        })
                    },
                    options: baseOpts(true)
                });
            }

            // ─── renderCharts() ─────────────────────────────────────────────────────
            function renderCharts(data) {
                if (!data || !data.tab || typeof Chart === 'undefined') return;
                lastPayload = data;
                destroyMissingCharts();

                if (data.tab === 'dashboard') {
                    bar('chart-top-revenue', data.top?.labels, data.top?.revenue, 'rgba(16,185,129,0.8)');
                    bar('chart-top-qty', data.top?.labels, data.top?.quantity, 'rgba(59,130,246,0.8)');
                    hbar('chart-inv-usage', data.inventory_usage?.labels, data.inventory_usage?.values, 'rgba(168,85,247,0.8)');

                    if (data.paymix) donut('chart-paymix', data.paymix.labels, data.paymix.values);
                    if (data.sales) line('chart-sales-ot', data.sales.labels, data.sales.values, 'rgba(16,185,129,1)');
                    if (data.paid) line('chart-paid-ot', data.paid.labels, data.paid.values, 'rgba(234,179,8,1)');
                    if (data.orders) line('chart-orders-ot', data.orders.labels, data.orders.values, 'rgba(59,130,246,1)');
                }

                if (data.tab === 'inventory') {
                    hbar('chart-inv-consumed', data.consumed?.labels, data.consumed?.values, 'rgba(234,179,8,0.8)');
                }

                if (data.tab === 'items') {
                    bar('chart-items-top', data.itemsCharts?.top_selling?.labels, data.itemsCharts?.top_selling?.values, 'rgba(16,185,129,0.8)');
                    bar('chart-items-margin', data.itemsCharts?.most_profitable_margin?.labels, data.itemsCharts?.most_profitable_margin?.values, 'rgba(168,85,247,0.8)');
                }

                if (data.tab === 'financial') {
                    donut('chart-fin-paymix', data.finCharts?.paymix?.labels, data.finCharts?.paymix?.values);
                    donut('chart-fin-discounts', data.finCharts?.discounts?.labels, data.finCharts?.discounts?.values);

                    multiline('chart-fin-vat', data.finCharts?.vat?.labels, [
                        { label: 'VAT', values: data.finCharts?.vat?.total_vat || [] },
                        { label: 'Service VAT', values: data.finCharts?.vat?.service_vat || [] }
                    ]);

                    multiline('chart-fin-promos', data.finCharts?.promos?.labels, [
                        { label: 'Discount', values: data.finCharts?.promos?.discount_values || [] },
                        { label: 'Gross', values: data.finCharts?.promos?.gross_values || [] }
                    ]);
                }

                if (data.tab === 'inventory_usage') {
                    hbar('chart-usage-top', data.usageChart?.labels, data.usageChart?.values, 'rgba(234,179,8,0.8)');
                }
            }

            // ─── renderFromDom() ────────────────────────────────────────────────────
            function renderFromDom() {
                const ids = [
                    'report-chart-data-dashboard',
                    'report-chart-data-inventory',
                    'report-chart-data-items',
                    'report-chart-data-financial',
                    'report-chart-data-inventory-usage'
                ];

                destroyMissingCharts();

                for (const id of ids) {
                    const el = document.getElementById(id);
                    if (el) {
                        try { renderCharts(JSON.parse(el.textContent)); return; }
                        catch (e) { console.error('Invalid chart JSON in', id, e); }
                    }
                }
            }

            // ─── boot ───────────────────────────────────────────────────────────────
            function bootCharts() {
                renderFromDom();
                setTimeout(renderFromDom, 100); // second pass for Livewire hydration

                if (window.Livewire && typeof Livewire.hook === 'function') {
                    Livewire.hook('morph.updated', () => setTimeout(renderFromDom, 50));
                }
            }

            document.addEventListener('DOMContentLoaded', bootCharts);
            document.addEventListener('livewire:initialized', bootCharts);

            // Re-render on dark/light mode toggle
            new MutationObserver(() => {
                if (lastPayload) renderCharts(lastPayload);
            }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        })();
    </script>
@endpush