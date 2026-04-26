<x-layout-analyticsv1>
    <div class="p-4 lg:p-6 space-y-6">
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('reports.inventoryUsage', ['days' => request('days', 30), 'per_page' => request('per_page', 10)]) }}"
                class="px-4 py-2 rounded-xl text-sm font-extrabold border
              {{ empty($activeCategory) ? 'bg-black text-white' : 'bg-white text-black border-black/10' }}">
                All
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('reports.inventoryUsage', ['category' => $cat->id, 'days' => request('days', 30), 'per_page' => request('per_page', 10)]) }}"
                    class="px-4 py-2 rounded-xl text-sm font-extrabold border
                      {{ (string) $activeCategory === (string) $cat->id ? 'bg-black text-white' : 'bg-white text-black border-black/10' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-extrabold">Inventory Usage (last {{ $daysWindow }} days)</h2>

            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm font-semibold text-black/70">Days:</label>
                <input name="days" value="{{ request('days', 30) }}"
                    class="w-24 rounded-lg border border-black/10 px-3 py-2 text-sm" />
                <button class="rounded-lg bg-black text-white px-4 py-2 text-sm font-bold">
                    Apply
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow p-5 border border-black/10 overflow-auto">
            <div class="flex items-center justify-between mb-4">
                <div class="font-extrabold">Inventory Usage (last {{ $daysWindow }} days)</div>
                <div class="text-xs font-bold text-black/50">
                    Showing {{ $inventories->count() }} of {{ $inventories->total() }}
                </div>
            </div>

            <table class="min-w-[1100px] w-full text-sm">
                <thead class="text-black/60">
                    <tr class="border-b">
                        <th class="text-left py-3 pr-4 font-extrabold">Inventory</th>
                        <th class="text-left py-3 pr-4 font-extrabold">Category</th>
                        <th class="text-right py-3 px-3 font-extrabold">Total Used</th>
                        <th class="text-left py-3 px-3 font-extrabold">Most Used Day(s)</th>
                        @foreach($days as $d)
                            <th class="text-right py-3 px-3 font-extrabold">{{ substr($d, 0, 3) }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @forelse($inventories as $inv)
                        <tr class="border-b last:border-b-0">
                            <td class="py-3 pr-4 font-bold text-black">{{ $inv->name }}</td>

                            <td class="py-3 pr-4 text-black/70">
                                {{ $inv->category->name ?? '—' }}
                            </td>

                            <td class="py-3 px-3 text-right font-extrabold">
                                {{ number_format($inv->usage_total ?? 0, 2) }}
                            </td>

                            <td class="py-3 px-3">
                                @if(!empty($inv->most_used_days))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($inv->most_used_days as $md)
                                            <span class="px-2.5 py-1 rounded-full bg-black text-white text-xs font-bold">
                                                {{ $md }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-black/40">No usage</span>
                                @endif
                            </td>

                            @foreach($days as $d)
                                @php $val = (float) (($inv->usage_days[$d] ?? 0)); @endphp
                                <td
                                    class="py-3 px-3 text-right {{ ($val > 0 && $val == ($inv->usage_max_day ?? 0)) ? 'font-extrabold' : 'text-black/70' }}">
                                    {{ number_format($val, 2) }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + count($days) }}" class="py-8 text-center text-black/50 font-bold">
                                No inventories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $inventories->links() }}
            </div>
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl shadow p-5 border border-black/10">
            <div class="flex items-center justify-between mb-3">
                <div class="font-extrabold">Top inventories usage by day</div>
                <div class="text-xs font-bold text-black/50">Top {{ $topForChart->count() }}</div>
            </div>
            <div class="h-96">
                <canvas id="usageByDayChart"></canvas>
            </div>
        </div>

    </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const days = @json($days);

            const top = @json($topForChart->map(function ($inv) {
                return [
                    'name' => $inv->name,
                    'days' => $inv->usage_days,
                ];
            }));

            const datasets = top.map(inv => ({
                label: inv.name,
                data: days.map(d => Number(inv.days[d] ?? 0)),
                // let Chart.js pick colors (no manual colors)
            }));

            const ctx = document.getElementById('usageByDayChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days.map(d => d.substring(0, 3)),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
        })();
    </script>
</x-layout-analyticsv1>
