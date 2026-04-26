<?php

namespace App\Livewire\ReportingAnalytics;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\InventoryTransaction;
use App\Models\ItemOrder;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['headerTitle' => 'Reports & Analytics'])]
#[Title('Reports & Analytics')]
class ReportingAnalytics extends Component
{
    use WithPagination;


    // ── Active tab ─────────────────────────────────────────────────────────
    public string $activeTab = 'dashboard';

    // ── Shared date filters ────────────────────────────────────────────────
    public string  $filter    = 'today';
    public int     $year;
    public ?string $startDate = null;
    public ?string $endDate   = null;

    // ── Inventory-usage tab extras ─────────────────────────────────────────
    public ?string $categoryId = null;
    public int     $daysWindow = 30;
    public int     $perPage    = 10;

    public function mount(): void
    {
        $this->year = now()->year;
    }

    public function updated(string $property): void
    {
        $triggers = ['filter', 'year', 'startDate', 'endDate', 'activeTab', 'categoryId', 'daysWindow', 'perPage'];
        if (in_array($property, $triggers)) {
            $this->resetPage();
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render(): \Illuminate\View\View
    {
        $dateRange   = $this->getDateRange();
        $periodLabel = $this->buildPeriodLabel($dateRange);
        $years       = range(now()->year, now()->year - 5);

        $tabData = match ($this->activeTab) {
            'dashboard'       => $this->buildDashboardData($dateRange),
            'inventory'       => $this->buildInventoryData($dateRange),
            'items'           => $this->buildItemsData(),
            'financial'       => $this->buildFinancialData(),
            'inventory_usage' => $this->buildInventoryUsageData(),
            default           => $this->buildDashboardData($dateRange),
        };

        return view('livewire.reporting-analytics', array_merge(
            compact('periodLabel', 'years'),
            $tabData
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Tab builders
    // ─────────────────────────────────────────────────────────────────────

    private function buildDashboardData(array $dr): array
    {
        return [
            'analytics' => $this->buildAnalytics($dr),
            'charts'    => $this->buildCharts($dr, $this->filter),
        ];
    }

    private function buildInventoryData(array $dr): array
    {
        return ['invReport' => [
            'total_ingredients_used' => $this->getTotalIngredientsUsed($dr),
            'most_consumed'          => $this->getMostConsumedIngredient($dr),
            'below_par'              => $this->getBelowParItems(),
            'out_of_stock'           => $this->getOutOfStockItems(),
            'top_consumed_chart'     => $this->getTopConsumedIngredientsChart($dr),
            'ingredient_breakdown'   => $this->getIngredientUsageBreakdown($dr),
        ]];
    }

    private function buildItemsData(): array
    {
        $itemsReport = [
            'top_selling'     => $this->getTopSellingItemsAllTime(10),
            'slow_moving'     => $this->getSlowMovingItemsAllTime(10),
            'most_profitable' => $this->getMostProfitableItemsByMarginAllTime(10),
        ];
        return [
            'itemsReport' => $itemsReport,
            'itemsKpis'   => $this->getItemsKpisAllTime(),
            'itemsCharts' => $this->getItemsChartsAllTime($itemsReport),
        ];
    }

    private function buildFinancialData(): array
    {
        return [
            'finKpis'   => $this->paymentsReportsKpisAllTime(),
            'finReport' => $this->paymentsReportsTablesAllTime(),
            'finCharts' => $this->paymentsReportsChartsAllTime(),
        ];
    }

    private function buildInventoryUsageData(): array
    {
        $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dateExpr  = "COALESCE(orders.created_at, inventory_transactions.created_at)";

        $invQuery = Inventory::query()
            ->select('inventories.id', 'inventories.name', 'inventories.inventory_category_id')
            ->with('category:id,name')
            ->leftJoinSub(
                InventoryTransaction::query()
                    ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
                    ->where('inventory_transactions.type', 'out')
                    ->whereRaw("$dateExpr >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$this->daysWindow])
                    ->selectRaw("inventory_transactions.inventory_id as inv_id, SUM(inventory_transactions.quantity) as usage_sum")
                    ->groupBy('inventory_transactions.inventory_id'),
                'usage_totals', 'usage_totals.inv_id', '=', 'inventories.id'
            )
            ->addSelect(DB::raw('COALESCE(usage_totals.usage_sum, 0) as precomputed_total'))
            ->orderByDesc('precomputed_total');

        if (!empty($this->categoryId)) {
            $invQuery->where('inventories.inventory_category_id', $this->categoryId);
        }

        $inventories = $invQuery->paginate($this->perPage)->withQueryString();
        $categories  = InventoryCategory::query()->select('id', 'name')->orderBy('name')->get();
        $pageIds     = $inventories->getCollection()->pluck('id')->values();

        $rows = InventoryTransaction::query()
            ->where('inventory_transactions.type', 'out')
            ->whereIn('inventory_transactions.inventory_id', $pageIds)
            ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
            ->whereRaw("$dateExpr >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$this->daysWindow])
            ->selectRaw("inventory_transactions.inventory_id as inventory_id, DAYNAME($dateExpr) as dow_name, SUM(inventory_transactions.quantity) as total_used")
            ->groupBy('inventory_id', 'dow_name')->get();

        $usageMap = [];
        foreach ($rows as $r) {
            $usageMap[$r->inventory_id][$r->dow_name] = (float) $r->total_used;
        }

        $inventories->getCollection()->transform(function ($inv) use ($daysOrder, $usageMap) {
            $days = array_fill_keys($daysOrder, 0.0);
            foreach (($usageMap[$inv->id] ?? []) as $dayName => $val) {
                if (array_key_exists($dayName, $days)) $days[$dayName] = (float) $val;
            }
            $total = array_sum($days);
            $max   = max($days);
            $inv->usage_days     = $days;
            $inv->usage_total    = $total;
            $inv->usage_max_day  = $max;
            $inv->most_used_days = $max > 0
                ? collect($days)->filter(fn($v) => $v == $max)->keys()->values()->all()
                : [];
            return $inv;
        });

        $topForChart = collect($inventories->items())->sortByDesc(fn($i) => $i->usage_total ?? 0)->take(8)->values();

        return compact('inventories', 'categories', 'topForChart', 'daysOrder');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Date helpers
    // ─────────────────────────────────────────────────────────────────────

    private function getDateRange(): array
    {
        return match ($this->filter) {
            'today'     => ['start' => Carbon::today(),     'end' => Carbon::tomorrow()],
            'yesterday' => ['start' => Carbon::yesterday(), 'end' => Carbon::today()],
            'week'      => ['start' => Carbon::now()->startOfWeek(), 'end' => Carbon::now()->endOfWeek()],
            'month'     => ['start' => Carbon::create($this->year, now()->month, 1)->startOfMonth(), 'end' => Carbon::create($this->year, now()->month, 1)->endOfMonth()],
            'year'      => ['start' => Carbon::create($this->year, 1, 1)->startOfYear(), 'end' => Carbon::create($this->year, 12, 31)->endOfYear()],
            'custom'    => ['start' => $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::today(), 'end' => $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::tomorrow()],
            default     => ['start' => Carbon::today(), 'end' => Carbon::tomorrow()],
        };
    }

    private function buildPeriodLabel(array $dr): string
    {
        return match ($this->filter) {
            'today'     => 'Today — ' . now()->format('M d, Y'),
            'yesterday' => 'Yesterday — ' . Carbon::yesterday()->format('M d, Y'),
            'week'      => 'This Week: ' . $dr['start']->format('M d') . ' – ' . $dr['end']->format('M d, Y'),
            'month'     => $dr['start']->format('F Y'),
            'year'      => 'Year ' . $this->year,
            'custom'    => $dr['start']->format('M d, Y') . ' – ' . $dr['end']->format('M d, Y'),
            default     => '',
        };
    }

    // ─────────────────────────────────────────────────────────────────────
    // Analytics builders
    // ─────────────────────────────────────────────────────────────────────

    private function buildAnalytics(array $dr): array
    {
        $a = [
            'total_orders'              => Order::whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed')->count(),
            'average_order_value'       => (float) (Order::whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed')->avg('total_amount') ?? 0),
            'gross_sales'               => (float) (Order::whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed')->sum('total_amount') ?? 0),
            'paid_sales'                => (float) (DB::table('payments')->join('orders', 'payments.order_id', '=', 'orders.id')->whereBetween('payments.paid_at', [$dr['start'], $dr['end']])->where('payments.status', 'completed')->sum('payments.amount') ?? 0),
            'total_items_sold'          => (int) ItemOrder::whereHas('order', fn($q) => $q->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed'))->where('status', 'completed')->sum('quantity_ordered'),
            'cost_of_goods'             => (float) (InventoryTransaction::whereHas('order', fn($q) => $q->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed'))->where('type', 'out')->sum(DB::raw('quantity * unit_cost')) ?? 0),
            'most_common_ingredient'    => $this->getMostCommonIngredient($dr),
            'most_used_inventory'       => $this->getMostUsedInventory($dr),
            'top_selling_item_quantity' => $this->getTopSellingItemByQuantity($dr),
            'top_selling_item_revenue'  => $this->getTopSellingItemByRevenue($dr),
            'most_profitable_item'      => $this->getMostProfitableItem($dr),
        ];
        $base = ($a['paid_sales'] ?? 0) > 0 ? $a['paid_sales'] : $a['gross_sales'];
        $a['net_profit']    = $base - ($a['cost_of_goods'] ?? 0);
        $a['profit_margin'] = $base > 0 ? ($a['net_profit'] / $base) * 100 : 0;
        return $a;
    }

    private function buildCharts(array $dr, string $filter): array
    {
        $c = [
            'top'             => $this->getTopItemsCharts($dr),
            'inventory_usage' => $this->getMostUsedInventoryChart($dr),
        ];
        if (in_array($filter, ['week', 'month', 'year'])) {
            $c['sales']  = $this->getSalesOverTime($dr);
            $c['paid']   = $this->getPaidOverTime($dr);
            $c['orders'] = $this->getOrdersOverTime($dr);
            $c['paymix'] = $this->getPaymentMix($dr);
        }
        return $c;
    }

    private function getMostCommonIngredient(array $dr): array
    {
        $row = InventoryTransaction::select('inventory_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed'))
            ->where('type', 'out')->groupBy('inventory_id')->orderByDesc('total_quantity')->with('inventory.ingredient')->first();
        if ($row && $row->inventory && $row->inventory->ingredient) {
            return ['name' => $row->inventory->ingredient->name, 'quantity' => (float) $row->total_quantity];
        }
        return ['name' => 'N/A', 'quantity' => 0];
    }

    private function getMostUsedInventory(array $dr): array
    {
        $row = InventoryTransaction::query()->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.id as inventory_id, inventories.name as name, SUM(inventory_transactions.quantity) as total_used')
            ->groupBy('inventories.id', 'inventories.name')->orderByDesc('total_used')->first();
        return $row ? ['name' => $row->name, 'quantity' => (float) $row->total_used] : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getTopSellingItemByQuantity(array $dr): array
    {
        $top = ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw('menu_items.name as name, SUM(item_orders.quantity_ordered) as total_qty')->groupBy('menu_items.id', 'menu_items.name')->orderByDesc('total_qty')->first();
        return $top ? ['name' => $top->name, 'quantity' => (int) $top->total_qty] : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getTopSellingItemByRevenue(array $dr): array
    {
        $top = ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw("menu_items.name as name, SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as total_revenue")
            ->groupBy('menu_items.id', 'menu_items.name')->orderByDesc('total_revenue')->first();
        return $top ? ['name' => $top->name, 'revenue' => (float) $top->total_revenue] : ['name' => 'N/A', 'revenue' => 0];
    }

    private function getMostProfitableItem(array $dr): array
    {
        $top = ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw("menu_items.name as name, SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale) - (item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))) as total_profit")
            ->groupBy('menu_items.id', 'menu_items.name')->orderByDesc('total_profit')->first();
        return $top ? ['name' => $top->name, 'profit' => (float) $top->total_profit] : ['name' => 'N/A', 'profit' => 0];
    }

    private function getTopItemsCharts(array $dr): array
    {
        $rows = ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw("menu_items.id as menu_item_id, menu_items.name as name, SUM(item_orders.quantity_ordered) as qty, SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as revenue")
            ->groupBy('menu_items.id', 'menu_items.name')->orderByDesc('revenue')->limit(8)->get();
        return ['labels' => $rows->pluck('name')->toArray(), 'quantity' => $rows->pluck('qty')->map(fn($v) => (int) $v)->toArray(), 'revenue' => $rows->pluck('revenue')->map(fn($v) => (float) $v)->toArray()];
    }

    private function getMostUsedInventoryChart(array $dr): array
    {
        $rows = InventoryTransaction::query()->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.id, inventories.name, SUM(inventory_transactions.quantity) as total_used')->groupBy('inventories.id', 'inventories.name')->orderByDesc('total_used')->limit(8)->get();
        return ['labels' => $rows->pluck('name')->toArray(), 'values' => $rows->pluck('total_used')->map(fn($v) => (float) $v)->toArray()];
    }

    private function getSalesOverTime(array $dr): array
    {
        $rows = Order::query()->selectRaw("DATE(created_at) as d, SUM(total_amount) as total")->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed')->groupBy('d')->orderBy('d')->get();
        return ['labels' => $rows->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(), 'values' => $rows->pluck('total')->map(fn($v) => (float) $v)->toArray()];
    }

    private function getPaidOverTime(array $dr): array
    {
        $rows = DB::table('payments')->selectRaw("DATE(paid_at) as d, SUM(amount) as total")->whereBetween('paid_at', [$dr['start'], $dr['end']])->where('status', 'completed')->groupBy('d')->orderBy('d')->get();
        return ['labels' => collect($rows)->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(), 'values' => collect($rows)->pluck('total')->map(fn($v) => (float) $v)->toArray()];
    }

    private function getOrdersOverTime(array $dr): array
    {
        $rows = Order::query()->selectRaw("DATE(created_at) as d, COUNT(*) as total")->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed')->groupBy('d')->orderBy('d')->get();
        return ['labels' => $rows->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(), 'values' => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray()];
    }

    private function getPaymentMix(array $dr): array
    {
        $rows = DB::table('payments')->selectRaw("payment_method as method, SUM(amount) as total")->whereBetween('paid_at', [$dr['start'], $dr['end']])->where('status', 'completed')->groupBy('method')->get();
        return ['labels' => collect($rows)->pluck('method')->map(fn($m) => strtoupper($m))->toArray(), 'values' => collect($rows)->pluck('total')->map(fn($v) => (float) $v)->toArray()];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Inventory report
    // ─────────────────────────────────────────────────────────────────────

    private function getTotalIngredientsUsed(array $dr): float
    {
        return (float) (InventoryTransaction::query()->whereHas('order', fn($q) => $q->whereBetween('created_at', [$dr['start'], $dr['end']])->where('status', 'completed'))->where('type', 'out')->sum('quantity') ?? 0);
    }

    private function getMostConsumedIngredient(array $dr): array
    {
        $row = InventoryTransaction::query()->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('inventory_transactions.type', 'out')
            ->selectRaw("inventories.id as inventory_id, inventories.name as ingredient_name, SUM(inventory_transactions.quantity) as total_used")
            ->groupBy('inventories.id', 'inventories.name')->orderByDesc('total_used')->first();
        return $row ? ['name' => $row->ingredient_name, 'quantity' => (float) $row->total_used] : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getIngredientUsageBreakdown(array $dr): array
    {
        return InventoryTransaction::query()->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.id, inventories.name, SUM(inventory_transactions.quantity) as total_used')->groupBy('inventories.id', 'inventories.name')->orderByDesc('total_used')->get()
            ->map(fn($r) => ['id' => (int) $r->id, 'name' => $r->name, 'total_used' => (float) $r->total_used])->toArray();
    }

    private function getTopConsumedIngredientsChart(array $dr): array
    {
        $rows = InventoryTransaction::query()->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dr['start'], $dr['end']])->where('orders.status', 'completed')->where('inventory_transactions.type', 'out')
            ->selectRaw("inventories.name as name, SUM(inventory_transactions.quantity) as total_used")->groupBy('inventories.id', 'inventories.name')->orderByDesc('total_used')->limit(8)->get();
        return ['labels' => $rows->pluck('name')->toArray(), 'values' => $rows->pluck('total_used')->map(fn($v) => (float) $v)->toArray()];
    }

    private function getBelowParItems(): array
    {
        $p = Inventory::query()->whereNotNull('par_level')->where('par_level', '>', 0)->whereColumn('quantity_on_hand', '<', 'par_level')
            ->select(['id', 'name', 'quantity_on_hand', 'par_level'])->orderByRaw('(par_level - quantity_on_hand) DESC')->paginate(9);
        $items = $p->getCollection()->map(fn($r) => ['id' => (int) $r->id, 'name' => (string) $r->name, 'quantity' => (float) $r->quantity_on_hand, 'par_level' => (float) $r->par_level, 'gap' => (float) ($r->par_level - $r->quantity_on_hand)]);
        $p->setCollection($items);
        return ['count' => $p->total(), 'items' => $items->toArray(), 'pagination' => $p];
    }

    private function getOutOfStockItems(): array
    {
        $items = Inventory::query()->where('quantity_on_hand', '<=', 0)->select(['id', 'name', 'quantity_on_hand', 'par_level'])->orderBy('name')->limit(50)->get();
        return ['count' => $items->count(), 'items' => $items->map(fn($r) => ['id' => (int) $r->id, 'name' => (string) $r->name, 'quantity' => (float) $r->quantity_on_hand, 'par_level' => (float) ($r->par_level ?? 0)])->toArray()];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Items report (all-time)
    // ─────────────────────────────────────────────────────────────────────

    private function getItemsKpisAllTime(): array
    {
        $totalItemsSold  = (int) ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->where('orders.status', 'completed')->where('item_orders.status', 'completed')->sum('item_orders.quantity_ordered');
        $totalRevenue    = (float) (ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->where('orders.status', 'completed')->where('item_orders.status', 'completed')->selectRaw("SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as revenue")->value('revenue') ?? 0);
        $totalCogs       = (float) (ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')->where('orders.status', 'completed')->where('item_orders.status', 'completed')->selectRaw("SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0)) as cogs")->value('cogs') ?? 0);
        $totalProfit     = $totalRevenue - $totalCogs;
        $avgMargin       = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
        $uniqueItemsSold = (int) ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->where('orders.status', 'completed')->where('item_orders.status', 'completed')->distinct('item_orders.menu_item_id')->count('item_orders.menu_item_id');
        return compact('totalItemsSold', 'totalRevenue', 'totalProfit', 'avgMargin', 'uniqueItemsSold');
    }

    private function getTopSellingItemsAllTime(int $limit = 10): array
    {
        return ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw("menu_items.id as id, menu_items.name as name, SUM(item_orders.quantity_ordered) as qty, SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as revenue")
            ->groupBy('menu_items.id', 'menu_items.name')->orderByDesc('qty')->limit($limit)->get()
            ->map(fn($r) => ['id' => (int) $r->id, 'name' => (string) $r->name, 'qty' => (int) $r->qty, 'revenue' => (float) $r->revenue])->toArray();
    }

    private function getSlowMovingItemsAllTime(int $limit = 10): array
    {
        $rows = DB::table('menu_items')->leftJoin('item_orders', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->leftJoin('orders', fn($j) => $j->on('orders.id', '=', 'item_orders.order_id')->where('orders.status', 'completed'))
            ->where(fn($q) => $q->whereNull('item_orders.id')->orWhere('item_orders.status', 'completed'))
            ->selectRaw("menu_items.id as id, menu_items.name as name, COALESCE(SUM(CASE WHEN orders.id IS NULL THEN 0 ELSE item_orders.quantity_ordered END), 0) as qty, COALESCE(SUM(CASE WHEN orders.id IS NULL THEN 0 ELSE COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale) END), 0) as revenue")
            ->groupBy('menu_items.id', 'menu_items.name')->orderBy('qty')->orderBy('revenue')->limit($limit)->get();
        return collect($rows)->map(fn($r) => ['id' => (int) $r->id, 'name' => (string) $r->name, 'qty' => (int) $r->qty, 'revenue' => (float) $r->revenue])->toArray();
    }

    private function getMostProfitableItemsByMarginAllTime(int $limit = 10): array
    {
        return ItemOrder::query()->join('orders', 'item_orders.order_id', '=', 'orders.id')->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')->where('item_orders.status', 'completed')
            ->selectRaw("menu_items.id as id, menu_items.name as name, SUM(item_orders.quantity_ordered) as qty, SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as revenue, SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0)) as cogs, (SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) - SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))) as profit, CASE WHEN SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) > 0 THEN ((SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) - SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))) / SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale))) * 100 ELSE 0 END as margin")
            ->groupBy('menu_items.id', 'menu_items.name')->havingRaw('SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) > 0')
            ->orderByDesc('margin')->orderByDesc('profit')->limit($limit)->get()
            ->map(fn($r) => ['id' => (int) $r->id, 'name' => (string) $r->name, 'qty' => (int) $r->qty, 'revenue' => (float) $r->revenue, 'cogs' => (float) $r->cogs, 'profit' => (float) $r->profit, 'margin' => (float) $r->margin])->toArray();
    }

    private function getItemsChartsAllTime(array $report): array
    {
        $top  = collect($report['top_selling'] ?? []);
        $slow = collect($report['slow_moving'] ?? []);
        $prof = collect($report['most_profitable'] ?? []);
        return [
            'top_selling'            => ['labels' => $top->pluck('name')->toArray(),  'values' => $top->pluck('qty')->map(fn($v) => (int) $v)->toArray()],
            'slow_moving'            => ['labels' => $slow->pluck('name')->toArray(), 'values' => $slow->pluck('qty')->map(fn($v) => (int) $v)->toArray()],
            'most_profitable_margin' => ['labels' => $prof->pluck('name')->toArray(), 'values' => $prof->pluck('margin')->map(fn($v) => (float) $v)->toArray()],
            'most_profitable_profit' => ['labels' => $prof->pluck('name')->toArray(), 'values' => $prof->pluck('profit')->map(fn($v) => (float) $v)->toArray()],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Financial (all-time)
    // ─────────────────────────────────────────────────────────────────────

    private function paymentsReportsKpisAllTime(): array
    {
        return [
            'totalPaid'          => (float) (DB::table('payments')->where('status', 'completed')->sum('amount') ?? 0),
            'totalPayments'      => (int) DB::table('payments')->where('status', 'completed')->count(),
            'cancelledTxnCount'  => (int) DB::table('payments')->whereIn('status', ['failed', 'refunded'])->count(),
            'cancelledTxnAmount' => (float) (DB::table('payments')->whereIn('status', ['failed', 'refunded'])->sum('amount') ?? 0),
            'discountedOrders'   => (int) DB::table('orders')->where('total_discount_amount', '>', 0)->count(),
            'totalDiscount'      => (float) (DB::table('orders')->where('total_discount_amount', '>', 0)->sum('total_discount_amount') ?? 0),
            'vatTotal'           => (float) (DB::table('orders')->sum('total_vat_amount') ?? 0),
            'serviceVatTotal'    => (float) (DB::table('orders')->sum('service_charge_vat_amount') ?? 0),
        ];
    }

    private function paymentsReportsTablesAllTime(): array
    {
        return [
            'payMix'              => DB::table('payments')->selectRaw("payment_method as method, COUNT(*) as txn_count, SUM(amount) as total")->where('status', 'completed')->groupBy('method')->orderByDesc('total')->get(),
            'cancelledTxns'       => DB::table('payments')->select('id', 'order_id', 'payment_method', 'amount', 'status', 'paid_at', 'created_at')->whereIn('status', ['failed', 'refunded'])->orderByDesc('created_at')->limit(20)->get(),
            'discountByType'      => DB::table('orders')->selectRaw("discount_type as type, COUNT(*) as orders_count, SUM(total_discount_amount) as total_discount")->where('total_discount_amount', '>', 0)->groupBy('discount_type')->orderByDesc(DB::raw('SUM(total_discount_amount)'))->get(),
            'topDiscountedOrders' => DB::table('orders')->select('id', 'discount_type', 'total_discount_amount', 'order_discount_percentage', 'order_discount_amount', 'total_amount', 'created_at')->where('total_discount_amount', '>', 0)->orderByDesc('total_discount_amount')->limit(20)->get(),
            'vatMonthly'          => DB::table('orders')->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_vat_amount) as vat_total, SUM(service_charge_vat_amount) as service_vat_total")->groupBy('ym')->orderBy('ym')->limit(24)->get(),
            'promoPerf'           => DB::table('orders')->selectRaw("discount_type as type, COUNT(*) as uses, SUM(total_discount_amount) as total_discount, SUM(total_amount) as gross_amount")->whereIn('discount_type', ['promo', 'voucher'])->groupBy('discount_type')->orderByDesc(DB::raw('SUM(total_amount)'))->get(),
        ];
    }

    private function paymentsReportsChartsAllTime(): array
    {
        $mix  = DB::table('payments')->selectRaw("payment_method as method, SUM(amount) as total")->where('status', 'completed')->groupBy('payment_method')->orderByDesc(DB::raw('SUM(amount)'))->get();
        $disc = DB::table('orders')->selectRaw("discount_type as type, SUM(total_discount_amount) as total_discount")->where('total_discount_amount', '>', 0)->groupBy('discount_type')->orderByDesc(DB::raw('SUM(total_discount_amount)'))->get();
        $vR   = DB::table('orders')->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_vat_amount) as vat_total, SUM(service_charge_vat_amount) as service_vat_total")->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->limit(24)->get();
        $cM   = DB::table('payments')->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as cnt, SUM(amount) as amt")->whereIn('status', ['failed', 'refunded'])->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->limit(24)->get();
        $pr   = DB::table('orders')->selectRaw("discount_type as type, SUM(total_discount_amount) as total_discount, SUM(total_amount) as gross")->whereIn('discount_type', ['promo', 'voucher'])->groupBy('discount_type')->orderByDesc(DB::raw('SUM(total_amount)'))->get();
        return [
            'paymix'    => ['labels' => collect($mix)->pluck('method')->map(fn($m) => strtoupper((string) $m))->toArray(), 'values' => collect($mix)->pluck('total')->map(fn($v) => (float) $v)->toArray()],
            'discounts' => ['labels' => collect($disc)->pluck('type')->map(fn($t) => strtoupper((string) $t))->toArray(), 'values' => collect($disc)->pluck('total_discount')->map(fn($v) => (float) $v)->toArray()],
            'vat'       => ['labels' => collect($vR)->pluck('ym')->toArray(), 'total_vat' => collect($vR)->pluck('vat_total')->map(fn($v) => (float) $v)->toArray(), 'service_vat' => collect($vR)->pluck('service_vat_total')->map(fn($v) => (float) $v)->toArray()],
            'cancelled' => ['labels' => collect($cM)->pluck('ym')->toArray(), 'count' => collect($cM)->pluck('cnt')->map(fn($v) => (int) $v)->toArray(), 'amount' => collect($cM)->pluck('amt')->map(fn($v) => (float) $v)->toArray()],
            'promos'    => ['labels' => collect($pr)->pluck('type')->map(fn($t) => strtoupper((string) $t))->toArray(), 'discount_values' => collect($pr)->pluck('total_discount')->map(fn($v) => (float) $v)->toArray(), 'gross_values' => collect($pr)->pluck('gross')->map(fn($v) => (float) $v)->toArray()],
        ];
    }
    
    /**
 * Export a Sales Report CSV covering completed orders & payments.
 * Triggered by: wire:click="exportSalesReport"
 */
public function exportSalesReport(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    $dateRange = $this->getDateRange();
 
    $rows = \Illuminate\Support\Facades\DB::table('orders')
        ->join('payments', 'payments.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
        ->where('orders.status', 'completed')
        ->where('payments.status', 'completed')
        ->select([
            'orders.id as order_id',
            'orders.created_at as order_date',
            'orders.total_amount as gross_amount',
            'orders.total_discount_amount as discount',
            'orders.total_vat_amount as vat',
            'orders.service_charge_vat_amount as service_vat',
            'payments.payment_method as payment_method',
            'payments.amount as paid_amount',
            'payments.paid_at',
        ])
        ->orderBy('orders.created_at')
        ->get();
 
    $filename = 'sales-report-' . now()->format('Y-m-d_H-i') . '.csv';
 
    return response()->streamDownload(function () use ($rows) {
        $handle = fopen('php://output', 'w');
 
        // BOM for Excel UTF-8
        fputs($handle, "\xEF\xBB\xBF");
 
        // Header row
        fputcsv($handle, [
            'Order ID', 'Order Date', 'Gross Amount', 'Discount',
            'VAT', 'Service VAT', 'Payment Method', 'Paid Amount', 'Paid At',
        ]);
 
        foreach ($rows as $row) {
            fputcsv($handle, [
                $row->order_id,
                $row->order_date,
                number_format($row->gross_amount, 2, '.', ''),
                number_format($row->discount ?? 0, 2, '.', ''),
                number_format($row->vat ?? 0, 2, '.', ''),
                number_format($row->service_vat ?? 0, 2, '.', ''),
                $row->payment_method,
                number_format($row->paid_amount, 2, '.', ''),
                $row->paid_at,
            ]);
        }
 
        fclose($handle);
    }, $filename, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
}
 
/**
 * Export an Inventory Report CSV covering all inventory items with
 * current stock, par levels, and usage totals for the selected window.
 * Triggered by: wire:click="exportInventoryReport"
 */
public function exportInventoryReport(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    $rows = \App\Models\Inventory::query()
        ->with('category:id,name')
        ->leftJoinSub(
            \App\Models\InventoryTransaction::query()
                ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
                ->where('inventory_transactions.type', 'out')
                ->whereRaw(
                    "COALESCE(orders.created_at, inventory_transactions.created_at) >= DATE_SUB(NOW(), INTERVAL ? DAY)",
                    [$this->daysWindow]
                )
                ->selectRaw('inventory_transactions.inventory_id as inv_id, SUM(inventory_transactions.quantity) as usage_sum')
                ->groupBy('inventory_transactions.inventory_id'),
            'usage_totals',
            'usage_totals.inv_id', '=', 'inventories.id'
        )
        ->select([
            'inventories.id',
            'inventories.name',
            'inventories.inventory_category_id',
            'inventories.quantity_on_hand',
            'inventories.par_level',
            \Illuminate\Support\Facades\DB::raw('COALESCE(usage_totals.usage_sum, 0) as usage_total'),
        ])
        ->orderBy('inventories.name')
        ->get();
 
    $filename = 'inventory-report-' . now()->format('Y-m-d_H-i') . '.csv';
 
    return response()->streamDownload(function () use ($rows) {
        $handle = fopen('php://output', 'w');
 
        fputs($handle, "\xEF\xBB\xBF");
 
        fputcsv($handle, [
            'ID', 'Name', 'Category', 'Qty on Hand',
            'Par Level', 'Status', "Usage (last {$this->daysWindow}d)",
        ]);
 
        foreach ($rows as $row) {
            $qty = (float) $row->quantity_on_hand;
            $par = (float) ($row->par_level ?? 0);
            $status = $qty <= 0 ? 'Out of Stock'
                    : ($par > 0 && $qty < $par ? 'Below Par' : 'OK');
 
            fputcsv($handle, [
                $row->id,
                $row->name,
                $row->category->name ?? '',
                number_format($qty, 2, '.', ''),
                number_format($par, 2, '.', ''),
                $status,
                number_format((float) $row->usage_total, 2, '.', ''),
            ]);
        }
 
        fclose($handle);
    }, $filename, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
}
}