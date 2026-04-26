{{-- resources/views/analytics/items-report.blade.php --}}

<x-layout-analyticsv1>
    <div class="min-h-screen bg-[#EDEDED]">
        {{-- Header --}}
        <div class="sticky top-0 z-30 bg-[#BDBDBD] px-4 lg:px-6 py-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black leading-tight">
                    Items Report (All-time)
                </h2>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 lg:px-6 py-6 space-y-6">

            {{-- KPI ROW --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="text-xs text-gray-500 font-bold">TOTAL ITEMS SOLD</div>
                    <div class="text-2xl font-extrabold mt-1">
                        {{ number_format($kpis['total_items_sold'] ?? 0) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="text-xs text-gray-500 font-bold">TOTAL REVENUE</div>
                    <div class="text-2xl font-extrabold mt-1">
                        ₱{{ number_format($kpis['total_revenue'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="text-xs text-gray-500 font-bold">TOTAL PROFIT</div>
                    <div class="text-2xl font-extrabold mt-1">
                        ₱{{ number_format($kpis['total_profit'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="text-xs text-gray-500 font-bold">AVG PROFIT MARGIN</div>
                    <div class="text-2xl font-extrabold mt-1">
                        {{ number_format($kpis['avg_profit_margin'] ?? 0, 2) }}%
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="text-xs text-gray-500 font-bold">UNIQUE ITEMS SOLD</div>
                    <div class="text-2xl font-extrabold mt-1">
                        {{ number_format($kpis['unique_items_sold'] ?? 0) }}
                    </div>
                </div>
            </div>

            {{-- CHARTS (4) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-3">
                        Top Selling (Qty)
                    </h3>
                    <div class="h-72">
                        <canvas id="chartTopSelling"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-3">
                        Slow Moving (Qty)
                    </h3>
                    <div class="h-72">
                        <canvas id="chartSlowMoving"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-3">
                        Most Profitable (Margin %)
                    </h3>
                    <div class="h-72">
                        <canvas id="chartMostProfitableMargin"></canvas>
                    </div>

                    <div class="mt-3 text-xs text-gray-500">
                        * Margin = Profit ÷ Revenue. Profit uses <code>menu_items.cost</code>.
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-3">
                        Most Profitable (Profit ₱)
                    </h3>
                    <div class="h-72">
                        <canvas id="chartMostProfitableProfit"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABLES --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Top Selling --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-4">
                        Top Selling Items
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pr-2">Item</th>
                                    <th class="py-2 pr-2 text-right">Qty</th>
                                    <th class="py-2 text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($report['top_selling'] ?? []) as $r)
                                    <tr class="border-t">
                                        <td class="py-2 pr-2 font-semibold">{{ $r['name'] }}</td>
                                        <td class="py-2 pr-2 text-right">{{ $r['qty'] }}</td>
                                        <td class="py-2 text-right">₱{{ number_format($r['revenue'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr class="border-t">
                                        <td colspan="3" class="py-3 text-gray-500">No data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Slow Moving --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-4">
                        Slow Moving Items
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pr-2">Item</th>
                                    <th class="py-2 pr-2 text-right">Qty</th>
                                    <th class="py-2 text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($report['slow_moving'] ?? []) as $r)
                                    <tr class="border-t">
                                        <td class="py-2 pr-2 font-semibold">{{ $r['name'] }}</td>
                                        <td class="py-2 pr-2 text-right">{{ $r['qty'] }}</td>
                                        <td class="py-2 text-right">₱{{ number_format($r['revenue'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr class="border-t">
                                        <td colspan="3" class="py-3 text-gray-500">No data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Most Profitable --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-['Bricolage_Grotesque'] font-extrabold text-lg mb-4">
                        Most Profitable Items (Profit Margin)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pr-2">Item</th>
                                    <th class="py-2 pr-2 text-right">Margin</th>
                                    <th class="py-2 text-right">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($report['most_profitable'] ?? []) as $r)
                                    <tr class="border-t">
                                        <td class="py-2 pr-2 font-semibold">{{ $r['name'] }}</td>
                                        <td class="py-2 pr-2 text-right">{{ number_format($r['margin'], 2) }}%</td>
                                        <td class="py-2 text-right">₱{{ number_format($r['profit'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr class="border-t">
                                        <td colspan="3" class="py-3 text-gray-500">No data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = @json($charts ?? []);

        function makeBarChart(canvasId, labels, values, labelText, isMoney=false) {
            const el = document.getElementById(canvasId);
            if (!el) return;

            new Chart(el, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: labelText,
                        data: values,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const v = Number(ctx.raw || 0);
                                    if (!isMoney) return `${labelText}: ${v.toLocaleString()}`;
                                    return `${labelText}: ₱${v.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        makeBarChart(
            'chartTopSelling',
            charts?.top_selling?.labels || [],
            charts?.top_selling?.values || [],
            'Quantity'
        );

        makeBarChart(
            'chartSlowMoving',
            charts?.slow_moving?.labels || [],
            charts?.slow_moving?.values || [],
            'Quantity'
        );

        makeBarChart(
            'chartMostProfitableMargin',
            charts?.most_profitable_margin?.labels || [],
            charts?.most_profitable_margin?.values || [],
            'Profit Margin (%)'
        );

        makeBarChart(
            'chartMostProfitableProfit',
            charts?.most_profitable_profit?.labels || [],
            charts?.most_profitable_profit?.values || [],
            'Profit (₱)',
            true
        );
    </script>
</x-layout-analyticsv1>
