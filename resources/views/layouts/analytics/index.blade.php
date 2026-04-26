<x-layout-analyticsv1>
    <div class="" id="analytics-wrapper">

        {{-- ─── Sticky Header / Filters ─── --}}
        <div class="sticky top-0 z-30 bg-[#BDBDBD] px-4 lg:px-6 py-3 lg:h-auto">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                <h2 class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black leading-tight">
                    Dashboard
                </h2>

                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:gap-6">
                    <div class="font-['Bricolage_Grotesque'] font-extrabold text-sm lg:text-base lg:mr-2">
                        Filters:
                    </div>

                    {{-- Period filter --}}
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <label for="period_filter" class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">
                            Period:
                        </label>
                        <div class="relative w-full lg:w-40">
                            <select id="period_filter" onchange="updateFilters()" class="w-full appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer py-1 px-4 pr-8">
                                <option value="today" selected>Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Custom date range (hidden by default) --}}
                    <div id="custom_range_fields" class="hidden flex flex-col gap-2 lg:flex-row lg:items-center lg:gap-3">
                        <div class="flex items-center gap-2">
                            <label class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">From:</label>
                            <input type="date" id="start_date" onchange="updateFilters()"
                                class="rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 py-1 px-4 text-sm cursor-pointer">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">To:</label>
                            <input type="date" id="end_date" onchange="updateFilters()"
                                class="rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 py-1 px-4 text-sm cursor-pointer">
                        </div>
                    </div>

                    {{-- Loading spinner --}}
                    <div id="filter_spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="analytics-content" class="px-4 sm:px-6 lg:px-8 transition-opacity duration-200">

                <div class="py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        
                        <!-- Title and Period -->
                        <div>
                            <h2 class="text-xl sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-gray-800">
                                Total Sales
                            </h2>
                            <p id="period_label" class="text-sm text-gray-500">
                                {{ $periodLabel ?? '' }}
                            </p>
                        </div>
                
                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button onclick="downloadInventory()"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg shadow">
                                <i class="fa-solid fa-file-csv mr-1"></i>
                                Export Inventory
                            </button>
                
                            <button onclick="downloadSales()"
                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-4 py-2 rounded-lg shadow">
                                <i class="fa-solid fa-file-csv mr-1"></i>
                                Export Sales
                            </button>
                        </div>
                
                    </div>
                </div>

                {{-- KPI Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">

                    <div class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-money-bill-wave text-green-600 text-2xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <p class="text-xl md:text-2xl font-extrabold leading-tight" id="kpi_income">
                                ₱{{ number_format($analytics['paid_sales'], 2) }}
                            </p>
                            <p class="text-sm font-semibold tracking-wide text-white/90">Gross Sales</p>
                        </div>
                    </div>

                    <div class="relative bg-white rounded-2xl p-6 shadow-md">
                        <div class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <div id="kpi_cogs" class="text-2xl font-extrabold mt-1 text-green-500">
                                ₱{{ number_format($analytics['cost_of_goods'] ?? 0, 2) }}
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Total Cost of Goods</p>
                        </div>
                    </div>

                    <div class="relative bg-white rounded-2xl p-6 shadow-md">
                        <div class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-arrow-trend-up text-white text-xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <div id="kpi_profit" class="text-2xl font-extrabold mt-1 {{ $analytics['net_profit'] >= 0 ? 'text-green-500' : 'text-red-600' }}">
                                ₱{{ number_format($analytics['net_profit'], 2) }}
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Net Profit</p>
                        </div>
                    </div>

                    <div class="relative bg-white rounded-2xl p-6 shadow-md">
                        <div class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-percent text-white text-xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <div id="kpi_margin" class="text-2xl font-extrabold mt-1 {{ $analytics['net_profit'] >= 0 ? 'text-green-500' : 'text-red-600' }}">
                                {{ number_format($analytics['profit_margin'] ?? 0, 1) }}%
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Profit Margin</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Top Selling Items Chart --}}
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black mb-6 sm:mb-10 text-center uppercase">
                    Highest Revenue Items
                </h2>
                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                    <div class="h-80"><canvas id="topItemsChart"></canvas></div>
                </div>
            </div>

            {{-- Top Performer Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mt-6">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500">TOP SELLER</div>
                    <div class="text-xl font-extrabold mt-1" id="top_seller_name">{{ $analytics['top_selling_item_quantity']['name'] }}</div>
                    <div class="text-3xl font-black text-blue-600 mt-2" id="top_seller_qty">
                        {{ number_format($analytics['top_selling_item_quantity']['quantity']) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500">TOP EARNER</div>
                    <div class="text-xl font-extrabold mt-1" id="top_earner_name">{{ $analytics['top_selling_item_revenue']['name'] }}</div>
                    <div class="text-3xl font-black text-emerald-600 mt-2" id="top_earner_revenue">
                        ₱{{ number_format($analytics['top_selling_item_revenue']['revenue'], 2) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500">MOST PROFITABLE</div>
                    <div class="text-xl font-extrabold mt-1" id="top_profit_name">
                        {{ $analytics['most_profitable_item']['name'] }}
                    </div>
                    <div class="text-3xl font-black text-purple-600 mt-2" id="top_profit_value">
                        ₱{{ number_format($analytics['most_profitable_item']['profit'], 2) }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-xs font-extrabold text-gray-500">MOST USED INVENTORY</div>
                    <div class="text-xl font-extrabold mt-1" id="kpi_inv_name">
                        {{ $analytics['most_used_inventory']['name'] ?? 'N/A' }}
                    </div>
                    <div class="text-3xl font-extrabold text-green-600 mt-2" id="kpi_inv_qty">
                        {{ number_format($analytics['most_used_inventory']['quantity'] ?? 0, 2) }}
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black mb-6 sm:mb-10 text-center uppercase">
                    Most Used Inventories
                </h2>

                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                    <div class="h-80"><canvas id="inventoryUsageChart"></canvas></div>
                </div>
            </div>

            {{-- Week Charts --}}
            <div id="week_charts_section" class="{{ $showWeekCharts ? '' : 'hidden' }} mt-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-extrabold text-sm">Sales (Orders)</div>
                            <div class="text-xs text-gray-500">₱ per day</div>
                        </div>
                        <div class="h-72"><canvas id="salesChart"></canvas></div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-extrabold text-sm">Paid Sales (Payments)</div>
                            <div class="text-xs text-gray-500">₱ per day</div>
                        </div>
                        <div class="h-72"><canvas id="paidChart"></canvas></div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-extrabold text-sm">Orders Over Time</div>
                            <div class="text-xs text-gray-500">count/day</div>
                        </div>
                        <div class="h-72"><canvas id="ordersChart"></canvas></div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-extrabold text-sm">Payment Mix</div>
                            <div class="text-xs text-gray-500">cash/debit/credit</div>
                        </div>
                        <div class="h-72"><canvas id="paymixChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div id="week_charts_placeholder" class="{{ $showWeekCharts ? 'hidden mt-6 bg-white rounded-2xl border border-gray-200 shadow-sm p-5' : '' }} ">

            </div>

        </div>
    </div>

    {{-- ─── JavaScript ─── --}}
    <script>
    // ── Chart instances ──────────────────────────────────────────────────────
    let topItemsChartInst = null;
    let salesChartInst    = null;
    let paidChartInst     = null;
    let ordersChartInst   = null;
    let paymixChartInst   = null;
    let inventoryUsageChartInst = null;

    // ── Sync filter UI visibility based on selected period ───────────────────
    function syncFilterVisibility() {
        const period   = document.getElementById('period_filter').value;
        const isCustom = period === 'custom';
    
        document.getElementById('custom_range_fields').classList.toggle('hidden', !isCustom);
    }

    // ── Build URLSearchParams from current filter state ──────────────────────
    function buildParams() {
        const period = document.getElementById('period_filter').value;
        const params = new URLSearchParams({ filter: period });
    
        if (period === 'custom') {
            const start = document.getElementById('start_date').value;
            const end   = document.getElementById('end_date').value;
            if (start) params.set('start_date', start);
            if (end)   params.set('end_date', end);
        }
    
        return params;
    }

    // ── Destroy all chart instances before re-rendering ──────────────────────
    function destroyCharts() {
        [topItemsChartInst, salesChartInst, paidChartInst, ordersChartInst, paymixChartInst]
            .forEach(c => c && c.destroy());
        topItemsChartInst = salesChartInst = paidChartInst = ordersChartInst = paymixChartInst = null;

        [topItemsChartInst, inventoryUsageChartInst, salesChartInst, paidChartInst, ordersChartInst, paymixChartInst]
            .forEach(c => c && c.destroy());
        topItemsChartInst = inventoryUsageChartInst = salesChartInst = paidChartInst = ordersChartInst = paymixChartInst = null;

    }

    // ── Render / re-render charts from data ──────────────────────────────────
    function renderCharts(charts, showWeekCharts) {
        destroyCharts();

        topItemsChartInst = new Chart(document.getElementById('topItemsChart'), {
            type: 'bar',
            data: {
                labels: charts.top.labels,
                datasets: [
                    { label: 'Revenue (₱)', data: charts.top.revenue, backgroundColor: 'rgba(59,130,246,0.8)', yAxisID: 'y' },
                    { label: 'Quantity',    data: charts.top.quantity, backgroundColor: 'rgba(236,72,153,0.8)',  yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y:  { type: 'linear', position: 'left',  beginAtZero: true, title: { display: true, text: 'Revenue (₱)' } },
                    y1: { type: 'linear', position: 'right', beginAtZero: true, title: { display: true, text: 'Quantity Sold' }, grid: { drawOnChartArea: false } }
                }
            }
        });

        inventoryUsageChartInst = new Chart(document.getElementById('inventoryUsageChart'), {
            type: 'bar',
            data: {
                labels: charts.inventory_usage.labels,
                datasets: [
                    { label: 'Total Used', data: charts.inventory_usage.values }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });


        if (!showWeekCharts) return;

        const makeLine = (elId, labels, values, label) => new Chart(document.getElementById(elId), {
            type: 'line',
            data: { labels, datasets: [{ label, data: values, tension: 0.35 }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });

        salesChartInst  = makeLine('salesChart',  charts.sales.labels,  charts.sales.values,  'Sales (Orders)');
        paidChartInst   = makeLine('paidChart',   charts.paid.labels,   charts.paid.values,   'Paid Sales (Payments)');

        ordersChartInst = new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: { labels: charts.orders.labels, datasets: [{ label: 'Orders', data: charts.orders.values, tension: 0.35 }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        paymixChartInst = new Chart(document.getElementById('paymixChart'), {
            type: 'doughnut',
            data: { labels: charts.paymix.labels, datasets: [{ label: 'Payments', data: charts.paymix.values }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    // ── Update DOM from JSON response ─────────────────────────────────────────
    function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
}

function applyData(data) {
    const a = data.analytics;
    const c = data.charts;

    setText('period_label', data.period_label ?? '');

    setText('kpi_income', '₱' + formatMoney(a.paid_sales));
    setText('kpi_orders', formatNumber(a.total_orders));

    // ✅ Most Used Inventory KPI (dynamic)
    setText('kpi_inv_name', a.most_used_inventory?.name ?? 'N/A');
    setText('kpi_inv_qty',  formatNumber(a.most_used_inventory?.quantity ?? 0, 2));

    // ✅ COGS
    setText('kpi_cogs', '₱' + Number(a.cost_of_goods ?? 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));

    // ✅ Profit & Margin
    const profitEl = document.getElementById('kpi_profit');
    const marginEl = document.getElementById('kpi_margin');

    if (profitEl) profitEl.textContent = '₱' + formatMoney(a.net_profit ?? 0);
    if (marginEl) marginEl.textContent = formatNumber(a.profit_margin ?? 0, 1) + '%';

    [profitEl, marginEl].forEach(el => {
        if (!el) return;
        el.classList.toggle('text-green-500', (a.net_profit ?? 0) >= 0);
        el.classList.toggle('text-red-600',   (a.net_profit ?? 0) < 0);
    });

    // Top performers
    setText('top_seller_name',   a.top_selling_item_quantity?.name ?? 'N/A');
    setText('top_seller_qty',    formatNumber(a.top_selling_item_quantity?.quantity ?? 0));
    setText('top_earner_name',   a.top_selling_item_revenue?.name ?? 'N/A');
    setText('top_earner_revenue','₱' + formatMoney(a.top_selling_item_revenue?.revenue ?? 0));
    setText('top_profit_name',   a.most_profitable_item?.name ?? 'N/A');
    setText('top_profit_value',  '₱' + formatMoney(a.most_profitable_item?.profit ?? 0));

    const showWeek = data.show_week_charts;
    document.getElementById('week_charts_section')?.classList.toggle('hidden', !showWeek);
    document.getElementById('week_charts_placeholder')?.classList.toggle('hidden', showWeek);

    renderCharts(c, showWeek);
}


    // ── Number helpers ────────────────────────────────────────────────────────
    function formatMoney(val) {
        return parseFloat(val).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function formatNumber(val, decimals = 0) {
        return parseFloat(val).toLocaleString('en-PH', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }

    // ── Main AJAX fetch ───────────────────────────────────────────────────────
    let fetchController = null;

    async function updateFilters() {
        syncFilterVisibility();   // ← update UI first, then fetch

        if (fetchController) fetchController.abort();
        fetchController = new AbortController();

        const spinner = document.getElementById('filter_spinner');
        const content = document.getElementById('analytics-content');
        spinner.classList.remove('hidden');
        content.style.opacity = '0.4';

        try {
            const params   = buildParams();
            const response = await fetch(`{{ route('reportingAnalytics.data') }}?${params}`, {
                signal:  fetchController.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            applyData(data);

        } catch (err) {
            if (err.name !== 'AbortError') {
                console.error('Filter fetch failed:', err);
            }
        } finally {
            spinner.classList.add('hidden');
            content.style.opacity = '1';
        }
    }

    // ── On page load: sync UI then render initial charts from SSR data ────────
    syncFilterVisibility();
    (function initCharts() {
        const charts         = @json($charts);
        const showWeekCharts = @json($showWeekCharts);
        renderCharts(charts, showWeekCharts);
    })();
    
    function downloadInventory() {
    const params = buildParams();
    window.location.href = `{{ route('analytics.export.inventory') }}?${params}`;
}

function downloadSales() {
    const params = buildParams();
    window.location.href = `{{ route('analytics.export.sales') }}?${params}`;
}
    </script>
</x-layout-analyticsv1>
