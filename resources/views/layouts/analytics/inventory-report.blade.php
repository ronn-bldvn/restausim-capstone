<x-layout-analyticsv1>
    <div id="inventory-reports-wrapper" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">

        {{-- Sticky Header --}}
        <div class="sticky top-0 z-30 bg-[#BDBDBD]/95 backdrop-blur border-b border-black/10 px-4 lg:px-6 py-3">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-col">
                    <h2
                        class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black leading-tight">
                        Inventory Reports (All Time)
                    </h2>
                    <div id="periodLabel" class="text-xs lg:text-sm font-semibold text-black/70">
                        {{ $periodLabel ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-4 lg:px-6 py-6 space-y-6">

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500 uppercase">Most Consumed Ingredient</div>
                    <div class="text-xl font-extrabold mt-1" id="kpiMostName">
                        {{ $report['most_consumed']['name'] ?? 'N/A' }}
                    </div>
                    <div class="text-3xl font-black text-green-600 mt-2" id="kpiMostQty">
                        {{ number_format($report['most_consumed']['quantity'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500 uppercase">Items Below Par</div>
                    <div class="text-xl font-extrabold mt-1" id="kpiMostName">Total:</div>
                    <div class="text-3xl font-black text-green-600 mt-2" id="kpiMostQty">
                        {{ $report['below_par']['count'] ?? 0 }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500 uppercase">Out of Stock</div>
                    <div class="text-xl font-extrabold mt-1" id="kpiOutOfStock">Total:</div>
                    <div class="text-3xl font-black text-green-600 mt-2" id="">
                        {{ $report['out_of_stock']['count'] ?? 0 }}
                    </div>
                </div>

            </div>

            {{-- Chart (single card) --}}
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                <h2
                    class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black mb-4 sm:mb-10 text-center uppercase">
                    Top Consumed Ingredients
                </h2>
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                    <canvas id="topConsumedChart" height="420"></canvas>
                </div>
            </div>

            {{-- Below Par (paginated) --}}
            <div class="flex items-center gap-2.5">
                <div>
                    <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black text-center uppercase">
                        Items Below Par Level
                    </h2>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">

                    {{-- Count badge --}}
                    @php $count = $report['below_par']['count'] ?? 0; @endphp
                    <div id="belowParCount"
                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
            {{ $count > 0 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200' }}">
                        @if($count > 0)
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        @endif
                        {{ $count }} {{ Str::plural('item', $count) }}
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-auto" id="belowParTable">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th
                                    class="text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Ingredient</th>
                                <th
                                    class="text-right text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-4">
                                    On Hand</th>
                                <th
                                    class="text-right text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Par Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse(($report['below_par']['items'] ?? []) as $r)
                                @php
                                    $qty = $r['quantity'];
                                    $par = $r['par_level'];
                                    $pct = $par > 0 ? min(100, ($qty / $par) * 100) : 0;
                                    $deficit = $par - $qty;

                                    // Severity thresholds
                                    if ($pct <= 25) {
                                        $dot = 'bg-red-500';
                                        $bar = 'bg-red-400';
                                        $badge = 'bg-red-50 text-red-600 border-red-200';
                                        $text = 'text-red-600';
                                    } elseif ($pct <= 60) {
                                        $dot = 'bg-amber-400';
                                        $bar = 'bg-amber-400';
                                        $badge = 'bg-amber-50 text-amber-600 border-amber-200';
                                        $text = 'text-amber-600';
                                    } else {
                                        $dot = 'bg-yellow-400';
                                        $bar = 'bg-yellow-300';
                                        $badge = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                        $text = 'text-yellow-700';
                                    }
                                @endphp
                                <tr class="group hover:bg-slate-50 transition-colors duration-100">
                                    {{-- Ingredient name --}}
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $dot }}"></span>
                                            <span class="font-semibold text-slate-800">{{ $r['name'] }}</span>
                                        </div>
                                        {{-- Progress bar --}}
                                        {{-- <div class="mt-1.5 ml-[18px] h-1 w-40 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $bar }} transition-all duration-500"
                                                style="width: {{ number_format($pct, 1) }}%"></div>
                                        </div> --}}
                                    </td>

                                    {{-- Qty --}}
                                    <td class="py-3 px-4 text-right align-middle">
                                        <span class="font-bold {{ $text }}">{{ number_format($qty, 2) }}</span>
                                    </td>

                                    {{-- Par level + deficit badge --}}
                                    <td class="py-3 px-5 text-right align-middle">
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="font-semibold text-slate-600">{{ number_format($par, 2) }}</span>
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $badge }}">
                                                −{{ number_format($deficit, 2) }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-500">All items are well stocked</p>
                                            <p class="text-xs text-slate-400">Nothing is below par level</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($report['below_par']['pagination']))
                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                        {{ $report['below_par']['pagination']->links() }}
                    </div>
                @endif

            </div>

            {{-- ===================== OUT OF STOCK ===================== --}}
            <div class="flex items-center gap-2.5">
                <div>
                    <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black text-center uppercase">
                        Out of Stock Items
                    </h2>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">


                    @php $outCount = $report['out_of_stock']['count'] ?? 0; @endphp
                    <div id="outOfStockCount"
                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $outCount > 0 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200' }}">
                        @if($outCount > 0)
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        @endif
                        {{ $outCount }} {{ Str::plural('item', $outCount) }}
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-auto" id="outOfStockTable">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th
                                    class="text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Ingredient</th>
                                <th
                                    class="text-right text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-4">
                                    Qty</th>
                                <th
                                    class="text-right text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Par Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse(($report['out_of_stock']['items'] ?? []) as $r)
                                <tr class="group hover:bg-red-50/40 transition-colors duration-100">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-2.5">
                                            {{-- Empty stock bar --}}
                                            <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                                            <span class="font-semibold text-slate-800">{{ $r['name'] }}</span>
                                        </div>
                                        {{-- Full empty bar --}}
                                        {{-- <div class="mt-1.5 ml-[18px] h-1 w-40 bg-red-100 rounded-full overflow-hidden">
                                            <div class="h-full w-0 rounded-full bg-red-400"></div>
                                        </div> --}}
                                    </td>
                                    <td class="py-3 px-4 text-right align-middle">
                                        <span class="font-bold text-red-600">{{ number_format($r['quantity'], 2) }}</span>
                                    </td>
                                    <td class="py-3 px-5 text-right align-middle">
                                        <div class="flex flex-col items-end gap-1">
                                            <span
                                                class="font-semibold text-slate-600">{{ number_format($r['par_level'], 2) }}</span>
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border bg-red-50 text-red-600 border-red-200">
                                                EMPTY
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-500">Nothing is out of stock</p>
                                            <p class="text-xs text-slate-400">All ingredients have available quantity</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>


            {{-- ===================== INGREDIENT USAGE BREAKDOWN ===================== --}}
            <div class="flex items-center gap-2.5">
                <div>
                    <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black text-center uppercase">
                        Ingredient Usage Breakdown
                    </h2>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">All
                        Time</span>
                </div>

                {{-- Table with max height scroll --}}
                <div class="overflow-auto max-h-96">
                    @php
                        $usageItems = $report['ingredient_breakdown'] ?? [];
                        $maxUsage = collect($usageItems)->max('total_used') ?? 1;
                    @endphp
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th
                                    class="text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Ingredient</th>
                                <th
                                    class="text-right text-[11px] font-semibold text-slate-400 uppercase tracking-wider py-2.5 px-5">
                                    Total Used</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($usageItems as $index => $row)
                                @php
                                    $pct = $maxUsage > 0 ? ($row['total_used'] / $maxUsage) * 100 : 0;

                                    // Top items get a distinct accent
                                    $barColor = match (true) {
                                        $index === 0 => 'bg-violet-500',
                                        $index === 1 => 'bg-violet-400',
                                        $index === 2 => 'bg-violet-300',
                                        default => 'bg-slate-300',
                                    };
                                    $rankColor = match (true) {
                                        $index === 0 => 'text-violet-600 font-bold',
                                        $index === 1 => 'text-violet-500 font-semibold',
                                        $index === 2 => 'text-violet-400 font-semibold',
                                        default => 'text-slate-400',
                                    };
                                @endphp
                                <tr class="group hover:bg-slate-50 transition-colors duration-100">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-2.5">
                                            {{-- Rank number --}}
                                            <span class="text-[11px] w-5 text-right flex-shrink-0 {{ $rankColor }}">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <span
                                                    class="font-semibold text-slate-800 truncate block">{{ $row['name'] }}</span>
                                                {{-- Usage bar --}}
                                                {{-- <div class="mt-1 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full {{ $barColor }} transition-all duration-500"
                                                        style="width: {{ number_format($pct, 1) }}%"></div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-5 text-right align-middle">
                                        <span class="font-bold {{ $index < 3 ? 'text-violet-600' : 'text-slate-600' }}">
                                            {{ number_format($row['total_used'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-500">No usage data found</p>
                                            <p class="text-xs text-slate-400">Usage will appear once orders are processed
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    <script>
        let topConsumedChartInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('topConsumedChart');
            if (!el) return;

            const labels = @json($report['top_consumed_chart']['labels'] ?? []);
            const values = @json($report['top_consumed_chart']['values'] ?? []);

            initChart(labels, values);
        });

        function initChart(labels, values) {
            const el = document.getElementById('topConsumedChart');
            if (!el) return;

            if (topConsumedChartInstance) {
                topConsumedChartInstance.destroy();
                topConsumedChartInstance = null;
            }

            topConsumedChartInstance = new Chart(el.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total Used',
                        data: values,
                        borderWidth: 1,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const v = Number(ctx.raw || 0).toLocaleString(undefined, {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return ` ${v}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { maxRotation: 0, autoSkip: true }
                        },
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // safer lookup (your snippet referenced periodFilterEl but didn't define it)
        const periodFilterEl = document.getElementById('period_filter');

        async function updateInventoryFilters() {
            const filter = periodFilterEl ? periodFilterEl.value : 'today';
            const start_date = document.getElementById('start_date')?.value;
            const end_date = document.getElementById('end_date')?.value;

            const params = new URLSearchParams();
            params.set('filter', filter);

            if (filter === 'custom') {
                if (start_date) params.set('start_date', start_date);
                if (end_date) params.set('end_date', end_date);
            }

            const url = `{{ route('reports.inventory.data') }}?${params.toString()}`;

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                document.getElementById('periodLabel').textContent = data.period_label || '';

                // KPIs
                document.getElementById('kpiTotalUsed').textContent =
                    Number(data.report.total_ingredients_used || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('kpiMostName').textContent = data.report.most_consumed?.name || 'N/A';
                document.getElementById('kpiMostQty').textContent =
                    Number(data.report.most_consumed?.quantity || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('kpiBelowPar').textContent = data.report.below_par?.count ?? 0;
                document.getElementById('kpiOutOfStock').textContent = data.report.out_of_stock?.count ?? 0;

                document.getElementById('belowParCount').textContent = data.report.below_par?.count ?? 0;
                document.getElementById('outOfStockCount').textContent = data.report.out_of_stock?.count ?? 0;

                renderTable('belowParTable', data.report.below_par?.items || []);
                renderTable('outOfStockTable', data.report.out_of_stock?.items || []);

                initChart(
                    data.report.top_consumed_chart?.labels || [],
                    data.report.top_consumed_chart?.values || []
                );

            } catch (e) {
                console.error(e);
                alert('Failed to load inventory report data.');
            }
        }

        function renderTable(tbodyId, rows) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="py-4 text-center text-black/60 font-semibold">No data.</td>
                    </tr>
                `;
                return;
            }

            rows.forEach(r => {
                const qty = Number(r.quantity || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const par = Number(r.par_level || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                tbody.innerHTML += `
                    <tr class="hover:bg-black/5">
                        <td class="py-2 px-3 font-bold text-black">${escapeHtml(r.name || 'N/A')}</td>
                        <td class="py-2 px-3 text-right font-semibold">${qty}</td>
                        <td class="py-2 px-3 text-right font-semibold">${par}</td>
                    </tr>
                `;
            });
        }

        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, s => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[s]));
        }
    </script>
</x-layout-analyticsv1>
