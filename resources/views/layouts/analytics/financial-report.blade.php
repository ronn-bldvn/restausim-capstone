<x-layout-analyticsv1>
    <div class="min-h-screen bg-[#f4f4f4]">
        <div class="sticky top-0 z-30 bg-[#BDBDBD] px-4 lg:px-6 py-3">
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black leading-tight">
                    Payments / Discounts / VAT / Promotions
                </h2>
                <div class="text-xs sm:text-sm font-bold text-black/70">
                    All-time summary
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- KPI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="relative bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-emerald-600 text-2xl"></i>
                    </div>
                    <div class="mt-12 text-right">
                        <p class="text-xl md:text-2xl font-extrabold leading-tight">
                            ₱{{ number_format($kpis['total_paid'] ?? 0, 2) }}
                        </p>
                        <p class="text-sm font-semibold tracking-wide text-white/90">Total Paid (Payments Completed)</p>
                    </div>
                </div>

                <div class="relative bg-white rounded-2xl p-6 shadow-md border border-black/10">
                    <div class="absolute top-4 left-4 w-12 h-12 bg-black/5 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-black text-2xl"></i>
                    </div>
                    <div class="mt-12 text-right">
                        <p class="text-xl md:text-2xl font-extrabold leading-tight text-black">
                            {{ number_format($kpis['total_payments'] ?? 0) }}
                        </p>
                        <p class="text-sm font-semibold tracking-wide text-black/60">Completed Payment Transactions</p>
                    </div>
                </div>

                <div class="relative bg-white rounded-2xl p-6 shadow-md border border-black/10">
                    <div class="absolute top-4 left-4 w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-ban text-red-600 text-2xl"></i>
                    </div>
                    <div class="mt-12 text-right">
                        <p class="text-xl md:text-2xl font-extrabold leading-tight text-black">
                            {{ number_format($kpis['cancelled_txn_count'] ?? 0) }}
                        </p>
                        <p class="text-sm font-semibold tracking-wide text-black/60">Failed / Refunded Payments</p>
                        <p class="text-xs font-bold text-black/50 mt-1">
                            Amount: ₱{{ number_format($kpis['cancelled_txn_amount'] ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <div class="relative bg-white rounded-2xl p-6 shadow-md border border-black/10">
                    <div class="absolute top-4 left-4 w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-percent text-indigo-600 text-2xl"></i>
                    </div>
                    <div class="mt-12 text-right">
                        <p class="text-xl md:text-2xl font-extrabold leading-tight text-black">
                            ₱{{ number_format($kpis['total_discount'] ?? 0, 2) }}
                        </p>
                        <p class="text-sm font-semibold tracking-wide text-black/60">Total Discounts (Orders)</p>
                        <p class="text-xs font-bold text-black/50 mt-1">
                            Discounted Orders: {{ number_format($kpis['discounted_orders'] ?? 0) }}
                        </p>
                    </div>
                </div>

            </div>
            
            

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                {{-- Payment Method Breakdown --}}
                <div class="bg-white rounded-2xl shadow p-5 border border-black/10">
                    <div class="text-sm font-extrabold text-black uppercase">Payment Method Breakdown</div>
                    <div class="mt-4 h-80"><canvas id="paymixChart"></canvas></div>

                    <div class="mt-5 overflow-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-black/60">
                                <tr>
                                    <th class="text-left font-extrabold py-2">Method</th>
                                    <th class="text-right font-extrabold py-2">Transactions</th>
                                    <th class="text-right font-extrabold py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                            @foreach(($report['pay_mix'] ?? []) as $r)
                                <tr>
                                    <td class="py-2 font-bold text-black">{{ strtoupper($r->method ?? 'N/A') }}</td>
                                    <td class="py-2 text-right">{{ number_format($r->txn_count ?? 0) }}</td>
                                    <td class="py-2 text-right font-extrabold">₱{{ number_format($r->total ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Failed/Refunded Payments --}}
                <div class="bg-white rounded-2xl shadow p-5 border border-black/10">
                    <div class="text-sm font-extrabold text-black uppercase">Failed / Refunded Payments</div>
                    <div class="mt-4 h-80"><canvas id="cancelledChart"></canvas></div>

                    <div class="mt-5 overflow-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-black/60">
                                <tr>
                                    <th class="text-left font-extrabold py-2">Payment #</th>
                                    <th class="text-left font-extrabold py-2">Order #</th>
                                    <th class="text-left font-extrabold py-2">Method</th>
                                    <th class="text-right font-extrabold py-2">Amount</th>
                                    <th class="text-left font-extrabold py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                            @foreach(($report['cancelled_txns'] ?? []) as $p)
                                <tr>
                                    <td class="py-2 font-bold text-black">#{{ $p->id }}</td>
                                    <td class="py-2 text-black/70">#{{ $p->order_id }}</td>
                                    <td class="py-2 text-black/70">{{ strtoupper($p->payment_method) }}</td>
                                    <td class="py-2 text-right font-extrabold">₱{{ number_format($p->amount ?? 0, 2) }}</td>
                                    <td class="py-2 font-bold text-black/70">{{ strtoupper($p->status) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-xs text-black/40 mt-3">Showing latest 20 failed/refunded payments.</div>
                    </div>
                </div>

                {{-- Discount Usage --}}
                <div class="bg-white rounded-2xl shadow p-5 border border-black/10">
                    <div class="text-sm font-extrabold text-black uppercase">Discount Usage (by Type)</div>
                
                    {{-- Breakdown badges --}}
                    <div class="mt-4 flex flex-wrap gap-3">
                        @php
                            $discountColors = [
                                'senior'  => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'dot' => 'bg-blue-500',   'text' => 'text-blue-700'],
                                'pwd'     => ['bg' => 'bg-purple-50',  'border' => 'border-purple-200',  'dot' => 'bg-purple-500',  'text' => 'text-purple-700'],
                                'promo'   => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                                'voucher' => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500',   'text' => 'text-amber-700'],
                                'manual'  => ['bg' => 'bg-rose-50',    'border' => 'border-rose-200',    'dot' => 'bg-rose-500',    'text' => 'text-rose-700'],
                            ];
                            $defaultColor = ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'dot' => 'bg-gray-400', 'text' => 'text-gray-700'];
                        @endphp
                
                        @forelse(($report['discount_by_type'] ?? []) as $r)
                            @php
                                $key   = strtolower($r->type ?? 'other');
                                $color = $discountColors[$key] ?? $defaultColor;
                            @endphp
                            <div class="flex items-center gap-3 {{ $color['bg'] }} {{ $color['border'] }} border rounded-xl px-4 py-3 min-w-[160px]">
                                <span class="w-3 h-3 rounded-full {{ $color['dot'] }} flex-shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold {{ $color['text'] }} uppercase tracking-wide">
                                        {{ ucfirst($r->type ?? 'None') }} Discount
                                    </p>
                                    <p class="text-xl font-extrabold text-black leading-tight">
                                        {{ number_format($r->orders_count ?? 0) }}
                                        <span class="text-xs font-semibold text-black/50">orders</span>
                                    </p>
                                    <p class="text-xs font-bold text-black/60 mt-0.5">
                                        ₱{{ number_format($r->total_discount ?? 0, 2) }} total
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-black/40 font-bold">No discount data available.</p>
                        @endforelse
                    </div>
                
                    <div class="mt-5 h-72"><canvas id="discountChart"></canvas></div>
                
                    <div class="mt-5 overflow-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-black/60">
                                <tr class="border-b">
                                    <th class="text-left font-extrabold py-2">Type</th>
                                    <th class="text-right font-extrabold py-2">Orders</th>
                                    <th class="text-right font-extrabold py-2">Avg. Discount</th>
                                    <th class="text-right font-extrabold py-2">Total Discount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                            @foreach(($report['discount_by_type'] ?? []) as $r)
                                @php
                                    $avg = ($r->orders_count ?? 0) > 0
                                        ? ($r->total_discount / $r->orders_count)
                                        : 0;
                                @endphp
                                <tr>
                                    <td class="py-2 font-bold text-black">{{ strtoupper($r->type ?? 'NONE') }}</td>
                                    <td class="py-2 text-right">{{ number_format($r->orders_count ?? 0) }}</td>
                                    <td class="py-2 text-right text-black/70">₱{{ number_format($avg, 2) }}</td>
                                    <td class="py-2 text-right font-extrabold">₱{{ number_format($r->total_discount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- VAT Report --}}
                <div class="bg-white rounded-2xl shadow p-5 border border-black/10">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-extrabold text-black uppercase">VAT Report</div>
                        <div class="text-xs font-bold text-black/50">
                            VAT Total: ₱{{ number_format($kpis['vat_total'] ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="mt-4 h-80"><canvas id="vatChart"></canvas></div>
                    <div class="text-xs text-black/40 mt-3">
                        Includes Total VAT and Service Charge VAT.
                    </div>
                </div>

            </div>

            {{-- Promotion Reports --}}
            <div class="bg-white rounded-2xl shadow p-5 border border-black/10 mt-6">
                <div class="text-sm font-extrabold text-black uppercase">Promotion Reports (PROMO / VOUCHER)</div>
                <div class="mt-4 h-96"><canvas id="promoChart"></canvas></div>

                <div class="mt-5 overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-black/60">
                            <tr>
                                <th class="text-left font-extrabold py-2">Type</th>
                                <th class="text-right font-extrabold py-2">Uses</th>
                                <th class="text-right font-extrabold py-2">Total Discount</th>
                                <th class="text-right font-extrabold py-2">Gross Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                        @foreach(($report['promo_perf'] ?? []) as $r)
                            <tr>
                                <td class="py-2 font-bold text-black">{{ strtoupper($r->type) }}</td>
                                <td class="py-2 text-right">{{ number_format($r->uses ?? 0) }}</td>
                                <td class="py-2 text-right font-extrabold">₱{{ number_format($r->total_discount ?? 0, 2) }}</td>
                                <td class="py-2 text-right font-extrabold">₱{{ number_format($r->gross_amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = {!! json_encode($charts ?? []) !!};
        const safe = (x) => Array.isArray(x) ? x : [];

        // Paymix
        const paymixColors = [
            '#10b981','#3b82f6','#f59e0b','#ef4444',
            '#8b5cf6','#06b6d4','#ec4899','#84cc16'
        ];
        
        new Chart(document.getElementById('paymixChart'), {
            type: 'doughnut',
            data: {
                labels: safe(charts.paymix?.labels),
                datasets: [{
                    data: safe(charts.paymix?.values),
                    backgroundColor: paymixColors.slice(0, safe(charts.paymix?.labels).length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ₱${Number(ctx.raw).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`
                        }
                    }
                }
            }
        });

        // Failed/Refunded monthly
        new Chart(document.getElementById('cancelledChart'), {
            type: 'bar',
            data: {
                labels: safe(charts.cancelled?.labels),
                datasets: [
                    { label:'Count', data: safe(charts.cancelled?.count) },
                    { label:'Amount', data: safe(charts.cancelled?.amount) },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } }
        });

        // Discounts by type
        new Chart(document.getElementById('discountChart'), {
            type: 'bar',
            data: { labels: safe(charts.discounts?.labels), datasets: [{ label:'Total Discount', data: safe(charts.discounts?.values) }] },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } }
        });

        // VAT monthly (2 lines)
        new Chart(document.getElementById('vatChart'), {
            type: 'line',
            data: {
                labels: safe(charts.vat?.labels),
                datasets: [
                    { label:'Total VAT', data: safe(charts.vat?.total_vat), tension:0.25 },
                    { label:'Service VAT', data: safe(charts.vat?.service_vat), tension:0.25 },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } }
        });

        // Promo/Voucher (discount + gross)
        new Chart(document.getElementById('promoChart'), {
            type: 'bar',
            data: {
                labels: safe(charts.promos?.labels),
                datasets: [
                    { label:'Total Discount', data: safe(charts.promos?.discount_values) },
                    { label:'Gross Amount', data: safe(charts.promos?.gross_values) },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } }
        });
    </script>
</x-layout-analyticsv1>
