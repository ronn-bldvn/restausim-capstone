<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\InventoryTransaction;
use App\Models\ItemOrder;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filter    = $request->get('filter', 'today');
        $year      = (int) $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $dateRange = $this->getDateRange($filter, $year, $startDate, $endDate);

        $analytics = $this->buildAnalytics($dateRange);
        $charts    = $this->buildCharts($dateRange, $filter);

        $showWeekCharts = in_array($filter, ['week', 'month', 'year']);

        return view('analytics.index', compact(
            'analytics',
            'filter',
            'startDate',
            'endDate',
            'charts',
            'showWeekCharts'
        ));
    }

    /**
     * AJAX endpoint – called by updateFilters() in the Blade view.
     *
     * GET /reports/analytics/data
     *   ?filter=today|yesterday|week|month|year|custom
     *   &year=2024
     *   &start_date=2024-01-01   (only for custom)
     *   &end_date=2024-12-31     (only for custom)
     */
    public function data(Request $request): JsonResponse
    {
        $filter    = $request->get('filter', 'week');
        $year      = (int) $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $dateRange      = $this->getDateRange($filter, $year, $startDate, $endDate);
        $analytics      = $this->buildAnalytics($dateRange);
        $charts         = $this->buildCharts($dateRange, $filter);
        $showWeekCharts = in_array($filter, ['week', 'month', 'year']);
        $periodLabel    = $this->buildPeriodLabel($filter, $year, $dateRange);

        return response()->json([
            'analytics'        => $analytics,
            'charts'           => $charts,
            'show_week_charts' => $showWeekCharts,
            'period_label'     => $periodLabel,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Shared builders (used by both index() and data())
    // ─────────────────────────────────────────────────────────────────────────

    private function buildAnalytics(array $dateRange): array
    {
        $analytics = [
            'total_orders'              => $this->getTotalOrders($dateRange),
            'average_order_value'       => $this->getAverageOrderValue($dateRange),
            'gross_sales'               => $this->getGrossSalesFromOrders($dateRange),
            'paid_sales'                => $this->getPaidSalesFromPayments($dateRange),
            'total_items_sold'          => $this->getTotalItemsSold($dateRange),
            'cost_of_goods'             => $this->getCostOfGoods($dateRange),
            'most_common_ingredient'    => $this->getMostCommonIngredient($dateRange),
            'most_used_inventory'       => $this->getMostUsedInventory($dateRange),
            'top_selling_item_quantity' => $this->getTopSellingItemByQuantity($dateRange),
            'top_selling_item_revenue'  => $this->getTopSellingItemByRevenue($dateRange),
            'most_profitable_item'      => $this->getMostProfitableItem($dateRange),

        ];

        $salesBase = ($analytics['paid_sales'] ?? 0) > 0
            ? $analytics['paid_sales']
            : $analytics['gross_sales'];

        $analytics['net_profit']    = $salesBase - ($analytics['cost_of_goods'] ?? 0);
        $analytics['profit_margin'] = $salesBase > 0
            ? ($analytics['net_profit'] / $salesBase) * 100
            : 0;

        return $analytics;
    }

    private function buildCharts(array $dateRange, string $filter): array
    {
        $charts = [
            'top' => $this->getTopItemsCharts($dateRange),
            'inventory_usage' => $this->getMostUsedInventoryChart($dateRange),
        ];

        if (in_array($filter, ['week', 'month', 'year'])) {
            $charts['sales']  = $this->getSalesOverTime($dateRange);
            $charts['paid']   = $this->getPaidOverTime($dateRange);
            $charts['orders'] = $this->getOrdersOverTime($dateRange);
            $charts['paymix'] = $this->getPaymentMix($dateRange);
        }

        return $charts;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Date range resolution
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * @param  string      $filter
     * @param  int         $year      – used by 'month' and 'year' filters
     * @param  string|null $startDate – used by 'custom' filter
     * @param  string|null $endDate   – used by 'custom' filter
     */
    private function getDateRange(string $filter, ?int $year = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $year = $year ?? now()->year;

        switch ($filter) {
            case 'today':
                return ['start' => Carbon::today(), 'end' => Carbon::tomorrow()];

            case 'yesterday':
                return ['start' => Carbon::yesterday(), 'end' => Carbon::today()];

            case 'week':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end'   => Carbon::now()->endOfWeek(),
                ];

            case 'month':
                // Respect the selected year
                $month = Carbon::now()->month;
                return [
                    'start' => Carbon::create($year, $month, 1)->startOfMonth(),
                    'end'   => Carbon::create($year, $month, 1)->endOfMonth(),
                ];

            case 'year':
                return [
                    'start' => Carbon::create($year, 1,  1)->startOfYear(),
                    'end'   => Carbon::create($year, 12, 31)->endOfYear(),
                ];

            case 'custom':
                return [
                    'start' => $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::today(),
                    'end'   => $endDate   ? Carbon::parse($endDate)->endOfDay()     : Carbon::tomorrow(),
                ];

            default:
                return ['start' => Carbon::today(), 'end' => Carbon::tomorrow()];
        }
    }

    private function buildPeriodLabel(string $filter, int $year, array $dateRange): string
    {
        return match ($filter) {
            'today'     => 'Today – ' . now()->format('M d, Y'),
            'yesterday' => 'Yesterday – ' . Carbon::yesterday()->format('M d, Y'),
            'week'      => 'This Week: '
                . $dateRange['start']->format('M d')
                . ' – '
                . $dateRange['end']->format('M d, Y'),
            'month'     => $dateRange['start']->format('F Y'),
            'year'      => 'Year ' . $year,
            'custom'    => $dateRange['start']->format('M d, Y')
                . ' – '
                . $dateRange['end']->format('M d, Y'),
            default     => '',
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Query helpers (unchanged from your original)
    // ─────────────────────────────────────────────────────────────────────────

    private function getTotalOrders($dateRange)
    {
        return Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->count();
    }

    private function getAverageOrderValue($dateRange)
    {
        return Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->avg('total_amount') ?? 0;
    }

    private function getTotalItemsSold($dateRange)
    {
        return ItemOrder::whereHas('order', function ($query) use ($dateRange) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->where('status', 'completed');
        })
            ->where('status', 'completed')
            ->sum('quantity_ordered');
    }

    private function getCostOfGoods($dateRange)
    {
        return InventoryTransaction::whereHas('order', function ($query) use ($dateRange) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->where('status', 'completed');
        })
            ->where('type', 'out')
            ->sum(DB::raw('quantity * unit_cost')) ?? 0;
    }

    private function getMostCommonIngredient($dateRange)
    {
        $mostUsed = InventoryTransaction::select('inventory_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', function ($query) use ($dateRange) {
                $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->where('status', 'completed');
            })
            ->where('type', 'out')
            ->groupBy('inventory_id')
            ->orderByDesc('total_quantity')
            ->with('inventory.ingredient')
            ->first();

        if ($mostUsed && $mostUsed->inventory && $mostUsed->inventory->ingredient) {
            return [
                'name'     => $mostUsed->inventory->ingredient->name,
                'quantity' => (float) $mostUsed->total_quantity,
            ];
        }

        return ['name' => 'N/A', 'quantity' => 0];
    }

    private function getTopSellingItemByQuantity($dateRange)
    {
        $top = ItemOrder::query()
            ->join('orders',     'item_orders.order_id',     '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status',      'completed')
            ->where('item_orders.status', 'completed')          //  served = actually delivered
            ->selectRaw('menu_items.name as name, SUM(item_orders.quantity_ordered) as total_qty')
            ->groupBy('menu_items.id', 'menu_items.name')    //  group by PK to avoid duplicate-name collisions
            ->orderByDesc('total_qty')
            ->first();

        return $top
            ? ['name' => $top->name, 'quantity' => (int) $top->total_qty]
            : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getTopSellingItemByRevenue($dateRange)
    {
        $top = ItemOrder::query()
            ->join('orders',     'item_orders.order_id',     '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status',      'completed')
            ->where('item_orders.status', 'completed')          //  consistent with getTotalItemsSold
            ->selectRaw("
                menu_items.name as name,
                SUM(
                    COALESCE(item_orders.final_unit_price,
                             item_orders.quantity_ordered * item_orders.price_at_sale)
                ) as total_revenue
            ")
            ->groupBy('menu_items.id', 'menu_items.name')    //  group by PK
            ->orderByDesc('total_revenue')
            ->first();

        return $top
            ? ['name' => $top->name, 'revenue' => (float) $top->total_revenue]
            : ['name' => 'N/A', 'revenue' => 0];
    }

    private function getMostProfitableItem($dateRange)
    {
        $top = ItemOrder::query()
            ->join('orders',     'item_orders.order_id',     '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status',      'completed')
            ->where('item_orders.status', 'completed')          //  consistent with getTotalItemsSold
            ->selectRaw("
                menu_items.name as name,
                SUM(
                    COALESCE(item_orders.final_unit_price,
                             item_orders.quantity_ordered * item_orders.price_at_sale)
                    -
                    (item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))
                ) as total_profit
            ")
            ->groupBy('menu_items.id', 'menu_items.name')    //  group by PK
            ->orderByDesc('total_profit')
            ->first();

        return $top
            ? ['name' => $top->name, 'profit' => (float) $top->total_profit]
            : ['name' => 'N/A', 'profit' => 0];
    }

    private function getTopItemsCharts($dateRange)
    {
        $rows = ItemOrder::query()
            ->join('orders',     'item_orders.order_id',     '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status',      'completed')
            ->where('item_orders.status', 'completed')          //  consistent with getTotalItemsSold
            ->selectRaw("
                menu_items.id as menu_item_id,
                menu_items.name as name,
                SUM(item_orders.quantity_ordered) as qty,
                SUM(
                    COALESCE(item_orders.final_unit_price,
                             item_orders.quantity_ordered * item_orders.price_at_sale)
                ) as revenue
            ")
            ->groupBy('menu_items.id', 'menu_items.name')    //  group by PK
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        return [
            'labels'   => $rows->pluck('name')->toArray(),
            'quantity' => $rows->pluck('qty')->map(fn($v) => (int) $v)->toArray(),
            'revenue'  => $rows->pluck('revenue')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    private function getGrossSalesFromOrders($dateRange)
    {
        return Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->sum('total_amount') ?? 0;
    }

    private function getPaidSalesFromPayments($dateRange)
    {
        return DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->whereBetween('payments.paid_at', [$dateRange['start'], $dateRange['end']])
            ->where('payments.status', 'completed')
            ->sum('payments.amount') ?? 0;
    }

    private function getSalesOverTime($dateRange)
    {
        $rows = Order::query()
            ->selectRaw("DATE(created_at) as d, SUM(total_amount) as total")
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->groupBy('d')->orderBy('d')
            ->get();

        return [
            'labels' => $rows->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'values' => $rows->pluck('total')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    private function getPaidOverTime($dateRange)
    {
        $rows = DB::table('payments')
            ->selectRaw("DATE(paid_at) as d, SUM(amount) as total")
            ->whereBetween('paid_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->groupBy('d')->orderBy('d')
            ->get();

        return [
            'labels' => collect($rows)->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'values' => collect($rows)->pluck('total')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    private function getOrdersOverTime($dateRange)
    {
        $rows = Order::query()
            ->selectRaw("DATE(created_at) as d, COUNT(*) as total")
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->groupBy('d')->orderBy('d')
            ->get();

        return [
            'labels' => $rows->pluck('d')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'values' => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    private function getPaymentMix($dateRange)
    {
        $rows = DB::table('payments')
            ->selectRaw("payment_method as method, SUM(amount) as total")
            ->whereBetween('paid_at', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->groupBy('method')
            ->get();

        return [
            'labels' => collect($rows)->pluck('method')->map(fn($m) => strtoupper($m))->toArray(),
            'values' => collect($rows)->pluck('total')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    private function getMostUsedInventory($dateRange)
    {
        $row = InventoryTransaction::query()
            ->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.id as inventory_id, inventories.name as name, SUM(inventory_transactions.quantity) as total_used')
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->first();

        return $row
            ? ['name' => $row->name, 'quantity' => (float) $row->total_used]
            : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getMostUsedInventoryChart($dateRange)
    {
        $rows = InventoryTransaction::query()
            ->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.id, inventories.name, SUM(inventory_transactions.quantity) as total_used')
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'values' => $rows->pluck('total_used')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Inventory Reports Page
    // ─────────────────────────────────────────────────────────────────────────

    public function inventory()
    {
        $report = [
            'total_ingredients_used' => $this->getTotalIngredientsUsedAllTime(),
            'most_consumed'          => $this->getMostConsumedIngredientAllTime(),
            'below_par'              => $this->getBelowParItems(),
            'out_of_stock'           => $this->getOutOfStockItems(),
            'top_consumed_chart'     => $this->getTopConsumedIngredientsChartAllTime(),
            'ingredient_breakdown' => $this->getIngredientUsageBreakdownAllTime(),
        ];

        return view('analytics.inventory-report', compact('report'));
    }

    private function getIngredientUsageBreakdownAllTime(): array
    {
        $rows = InventoryTransaction::query()
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw('
            inventories.id,
            inventories.name,
            SUM(inventory_transactions.quantity) as total_used
        ')
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->get();

        return $rows->map(fn($r) => [
            'id'        => (int) $r->id,
            'name'      => $r->name,
            'total_used' => (float) $r->total_used,
        ])->toArray();
    }


    private function getTotalIngredientsUsedAllTime(): float
    {
        return (float) InventoryTransaction::where('type', 'out')
            ->sum('quantity');
    }

    private function getMostConsumedIngredientAllTime(): array
    {
        $row = InventoryTransaction::query()
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.name as name, SUM(inventory_transactions.quantity) as total_used')
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->first();

        return $row
            ? ['name' => $row->name, 'quantity' => (float) $row->total_used]
            : ['name' => 'N/A', 'quantity' => 0];
    }

    private function getTopConsumedIngredientsChartAllTime(): array
    {
        $rows = InventoryTransaction::query()
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw('inventories.name as name, SUM(inventory_transactions.quantity) as total_used')
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'values' => $rows->pluck('total_used')->map(fn($v) => (float) $v)->toArray(),
        ];
    }


    public function inventoryData(Request $request): JsonResponse
    {
        $filter    = $request->get('filter', 'today');
        $year      = (int) $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $dateRange   = $this->getDateRange($filter, $year, $startDate, $endDate);
        $report      = $this->buildInventoryReport($dateRange);
        $periodLabel = $this->buildPeriodLabel($filter, $year, $dateRange);

        return response()->json([
            'report'       => $report,
            'period_label' => $periodLabel,
        ]);
    }

    private function buildInventoryReport(array $dateRange): array
    {
        return [
            'total_ingredients_used' => $this->getTotalIngredientsUsed($dateRange),
            'most_consumed'          => $this->getMostConsumedIngredient($dateRange),
            'below_par'              => $this->getBelowParItems(),
            'out_of_stock'           => $this->getOutOfStockItems(),
            'top_consumed_chart'     => $this->getTopConsumedIngredientsChart($dateRange),
        ];
    }

    /**
     * Total ingredients used within date range:
     * sums ALL inventory_transactions "out" where linked order is completed
     */
    private function getTotalIngredientsUsed(array $dateRange): float
    {
        $total = InventoryTransaction::query()
            ->whereHas('order', function ($q) use ($dateRange) {
                $q->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->where('status', 'completed');
            })
            ->where('type', 'out')
            ->sum('quantity');

        return (float) ($total ?? 0);
    }

    /**
     * Most consumed ingredient within date range
     * returns: ['name' => ..., 'quantity' => ...]
     */
    private function getMostConsumedIngredient(array $dateRange): array
    {
        $row = InventoryTransaction::query()
            ->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw("
            inventories.id as inventory_id,
            inventories.name as ingredient_name,
            SUM(inventory_transactions.quantity) as total_used
        ")
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->first();

        return $row
            ? ['name' => $row->ingredient_name, 'quantity' => (float) $row->total_used]
            : ['name' => 'N/A', 'quantity' => 0];
    }


    /**
     * Items below par level (does NOT depend on date range)
     * Assumes inventories.quantity and inventories.par_level exist.
     */
    private function getBelowParItems(): array
    {
        $paginator = Inventory::query()
            ->whereNotNull('par_level')
            ->where('par_level', '>', 0)
            ->whereColumn('quantity_on_hand', '<', 'par_level')
            ->select(['id', 'name', 'quantity_on_hand', 'par_level'])
            ->orderByRaw('(par_level - quantity_on_hand) DESC')
            ->paginate(9); // 9 per page

        // Transform the items but keep pagination meta
        $items = $paginator->getCollection()->map(fn($r) => [
            'id'        => (int) $r->id,
            'name'      => (string) $r->name,
            'quantity'  => (float) $r->quantity_on_hand,
            'par_level' => (float) $r->par_level,
            'gap'       => (float) ($r->par_level - $r->quantity_on_hand),
        ]);

        $paginator->setCollection($items);

        return [
            'count'      => $paginator->total(),   //  total below-par items
            'items'      => $items->toArray(),     // current page items
            'pagination' => $paginator,            //  for Blade ->links()
        ];
    }


    /**
     * Out of stock items (does NOT depend on date range)
     * Assumes inventories.quantity exists.
     */
    private function getOutOfStockItems(): array
    {
        $items = Inventory::query()
            ->where('quantity_on_hand', '<=', 0)
            ->select([
                'id',
                'name',
                'quantity_on_hand',
                'par_level',
            ])
            ->orderBy('name')
            ->limit(50)
            ->get();

        return [
            'count' => $items->count(),
            'items' => $items->map(fn($r) => [
                'id'        => (int) $r->id,
                'name'      => (string) $r->name,
                'quantity'  => (float) $r->quantity_on_hand,
                'par_level' => (float) ($r->par_level ?? 0),
            ])->toArray(),
        ];
    }


    /**
     * Top consumed ingredients chart (within date range)
     */
    private function getTopConsumedIngredientsChart(array $dateRange): array
    {
        $rows = InventoryTransaction::query()
            ->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw("
            inventories.name as name,
            SUM(inventory_transactions.quantity) as total_used
        ")
            ->groupBy('inventories.id', 'inventories.name')
            ->orderByDesc('total_used')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'values' => $rows->pluck('total_used')->map(fn($v) => (float) $v)->toArray(),
        ];
    }


    public function itemsReport()
    {
        $report = [
            'top_selling'     => $this->getTopSellingItemsAllTime(10),
            'slow_moving'     => $this->getSlowMovingItemsAllTime(10),
            'most_profitable' => $this->getMostProfitableItemsByMarginAllTime(10),
        ];

        $kpis   = $this->getItemsKpisAllTime();
        $charts = $this->getItemsChartsAllTime($report);

        return view('analytics.items-report', compact('report', 'kpis', 'charts'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // KPI (ALL-TIME)
    // ─────────────────────────────────────────────────────────────────────────
    private function getItemsKpisAllTime(): array
    {
        $totalItemsSold = (int) ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->sum('item_orders.quantity_ordered');

        $totalRevenue = (float) (ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->selectRaw("SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) as revenue")
            ->value('revenue') ?? 0);

        $totalCogs = (float) (ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->selectRaw("SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0)) as cogs")
            ->value('cogs') ?? 0);

        $totalProfit = $totalRevenue - $totalCogs;

        $avgMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        $uniqueItemsSold = (int) ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->distinct('item_orders.menu_item_id')
            ->count('item_orders.menu_item_id');

        return [
            'total_items_sold'  => $totalItemsSold,
            'total_revenue'     => $totalRevenue,
            'total_profit'      => $totalProfit,
            'avg_profit_margin' => $avgMargin,
            'unique_items_sold' => $uniqueItemsSold,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Report queries (ALL-TIME)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Top Selling Items (ALL-TIME) by quantity
     */
    private function getTopSellingItemsAllTime(int $limit = 10): array
    {
        $rows = ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->selectRaw("
                menu_items.id as id,
                menu_items.name as name,
                SUM(item_orders.quantity_ordered) as qty,
                SUM(
                    COALESCE(item_orders.final_unit_price,
                             item_orders.quantity_ordered * item_orders.price_at_sale)
                ) as revenue
            ")
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('qty')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'id'      => (int) $r->id,
            'name'    => (string) $r->name,
            'qty'     => (int) $r->qty,
            'revenue' => (float) $r->revenue,
        ])->toArray();
    }

    /**
     * Slow Moving Items (ALL-TIME) — lowest qty sold, includes 0 sold
     */
    private function getSlowMovingItemsAllTime(int $limit = 10): array
    {
        $rows = DB::table('menu_items')
            ->leftJoin('item_orders', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'item_orders.order_id')
                    ->where('orders.status', 'completed');
            })
            ->where(function ($q) {
                // keep items with no sales OR completed served item_orders
                $q->whereNull('item_orders.id')
                    ->orWhere('item_orders.status', 'completed');
            })
            ->selectRaw("
                menu_items.id as id,
                menu_items.name as name,
                COALESCE(SUM(CASE WHEN orders.id IS NULL THEN 0 ELSE item_orders.quantity_ordered END), 0) as qty,
                COALESCE(SUM(
                    CASE WHEN orders.id IS NULL THEN 0
                    ELSE COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)
                    END
                ), 0) as revenue
            ")
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderBy('qty', 'asc')
            ->orderBy('revenue', 'asc')
            ->limit($limit)
            ->get();

        return collect($rows)->map(fn($r) => [
            'id'      => (int) $r->id,
            'name'    => (string) $r->name,
            'qty'     => (int) $r->qty,
            'revenue' => (float) $r->revenue,
        ])->toArray();
    }

    /**
     * Most Profitable Items (ALL-TIME) by PROFIT MARGIN
     * margin = profit / revenue * 100
     * profit uses menu_items.cost as unit cost
     */
    private function getMostProfitableItemsByMarginAllTime(int $limit = 10): array
    {
        $rows = ItemOrder::query()
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')
            ->where('item_orders.status', 'completed')
            ->selectRaw("
                menu_items.id as id,
                menu_items.name as name,
                SUM(item_orders.quantity_ordered) as qty,
                SUM(
                    COALESCE(item_orders.final_unit_price,
                             item_orders.quantity_ordered * item_orders.price_at_sale)
                ) as revenue,
                SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0)) as cogs,
                (
                    SUM(
                        COALESCE(item_orders.final_unit_price,
                                 item_orders.quantity_ordered * item_orders.price_at_sale)
                    )
                    -
                    SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))
                ) as profit,
                CASE
                    WHEN SUM(
                        COALESCE(item_orders.final_unit_price,
                                 item_orders.quantity_ordered * item_orders.price_at_sale)
                    ) > 0
                    THEN (
                        (
                            SUM(
                                COALESCE(item_orders.final_unit_price,
                                         item_orders.quantity_ordered * item_orders.price_at_sale)
                            )
                            -
                            SUM(item_orders.quantity_ordered * COALESCE(menu_items.cost, 0))
                        )
                        /
                        SUM(
                            COALESCE(item_orders.final_unit_price,
                                     item_orders.quantity_ordered * item_orders.price_at_sale)
                        )
                    ) * 100
                    ELSE 0
                END as margin
            ")
            ->groupBy('menu_items.id', 'menu_items.name')
            ->havingRaw('SUM(COALESCE(item_orders.final_unit_price, item_orders.quantity_ordered * item_orders.price_at_sale)) > 0')
            ->orderByDesc('margin')
            ->orderByDesc('profit')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'id'     => (int) $r->id,
            'name'   => (string) $r->name,
            'qty'    => (int) $r->qty,
            'revenue' => (float) $r->revenue,
            'cogs'   => (float) $r->cogs,
            'profit' => (float) $r->profit,
            'margin' => (float) $r->margin,
        ])->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Charts (ALL-TIME)
    // ─────────────────────────────────────────────────────────────────────────
    private function getItemsChartsAllTime(array $report): array
    {
        $top  = collect($report['top_selling'] ?? []);
        $slow = collect($report['slow_moving'] ?? []);
        $prof = collect($report['most_profitable'] ?? []);

        return [
            'top_selling' => [
                'labels' => $top->pluck('name')->toArray(),
                'values' => $top->pluck('qty')->map(fn($v) => (int) $v)->toArray(),
            ],
            'slow_moving' => [
                'labels' => $slow->pluck('name')->toArray(),
                'values' => $slow->pluck('qty')->map(fn($v) => (int) $v)->toArray(),
            ],
            'most_profitable_margin' => [
                'labels' => $prof->pluck('name')->toArray(),
                'values' => $prof->pluck('margin')->map(fn($v) => (float) $v)->toArray(),
            ],
            'most_profitable_profit' => [
                'labels' => $prof->pluck('name')->toArray(),
                'values' => $prof->pluck('profit')->map(fn($v) => (float) $v)->toArray(),
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Payments / Discounts / VAT / Promotions Report (ALL-TIME, no filtering)
    // ─────────────────────────────────────────────────────────────────────────────
    public function paymentsReports()
    {
        $kpis   = $this->paymentsReportsKpisAllTime();
        $report = $this->paymentsReportsTablesAllTime();
        $charts = $this->paymentsReportsChartsAllTime();

        return view('analytics.financial-report', compact('kpis', 'report', 'charts'));
    }

    private function paymentsReportsKpisAllTime(): array
    {
        // Paid payments (completed)
        $totalPaid = (float) (DB::table('payments')
            ->where('status', 'completed')
            ->sum('amount') ?? 0);

        $totalPayments = (int) DB::table('payments')
            ->where('status', 'completed')
            ->count();

        // "Cancelled" == refunded/failed payments (based on your payments table)
        $cancelledTxnCount = (int) DB::table('payments')
            ->whereIn('status', ['failed', 'refunded'])
            ->count();

        $cancelledTxnAmount = (float) (DB::table('payments')
            ->whereIn('status', ['failed', 'refunded'])
            ->sum('amount') ?? 0);

        // Discounts (based on orders table)
        $discountedOrders = (int) DB::table('orders')
            ->where('total_discount_amount', '>', 0)
            ->count();

        $totalDiscount = (float) (DB::table('orders')
            ->where('total_discount_amount', '>', 0)
            ->sum('total_discount_amount') ?? 0);

        // VAT (based on orders table)
        $vatTotal = (float) (DB::table('orders')->sum('total_vat_amount') ?? 0);
        $serviceVatTotal = (float) (DB::table('orders')->sum('service_charge_vat_amount') ?? 0);

        return [
            'total_paid'           => $totalPaid,
            'total_payments'       => $totalPayments,
            'cancelled_txn_count'  => $cancelledTxnCount,
            'cancelled_txn_amount' => $cancelledTxnAmount,
            'discounted_orders'    => $discountedOrders,
            'total_discount'       => $totalDiscount,
            'vat_total'            => $vatTotal,
            'service_vat_total'    => $serviceVatTotal,
        ];
    }

    private function paymentsReportsTablesAllTime(): array
    {
        // Payment Method Breakdown
        $payMix = DB::table('payments')
            ->selectRaw("payment_method as method, COUNT(*) as txn_count, SUM(amount) as total")
            ->where('status', 'completed')
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();

        // Cancelled/Refunded Transactions list
        $cancelledTxns = DB::table('payments')
            ->select('id', 'order_id', 'payment_method', 'amount', 'status', 'paid_at', 'created_at')
            ->whereIn('status', ['failed', 'refunded'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Discount Usage by Type (senior/pwd/promo/voucher/manual)
        $discountByType = DB::table('orders')
            ->selectRaw("
            discount_type as type,
            COUNT(*) as orders_count,
            SUM(total_discount_amount) as total_discount
        ")
            ->where('total_discount_amount', '>', 0)
            ->groupBy('discount_type')            //  group by real column
            ->orderByDesc(DB::raw('SUM(total_discount_amount)')) //  safe order
            ->get();


        // Top discounted orders (optional table)
        $topDiscountedOrders = DB::table('orders')
            ->select('id', 'discount_type', 'total_discount_amount', 'order_discount_percentage', 'order_discount_amount', 'total_amount', 'created_at')
            ->where('total_discount_amount', '>', 0)
            ->orderByDesc('total_discount_amount')
            ->limit(20)
            ->get();

        // VAT Monthly
        $vatMonthly = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_vat_amount) as vat_total, SUM(service_charge_vat_amount) as service_vat_total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->limit(24)
            ->get();

        // Promotion Reports (promo + voucher)
        $promoPerf = DB::table('orders')
            ->selectRaw("
            discount_type as type,
            COUNT(*) as uses,
            SUM(total_discount_amount) as total_discount,
            SUM(total_amount) as gross_amount
        ")
            ->whereIn('discount_type', ['promo', 'voucher'])
            ->groupBy('discount_type')            //
            ->orderByDesc(DB::raw('SUM(total_amount)')) //
            ->get();

        return [
            'pay_mix'              => $payMix,
            'cancelled_txns'       => $cancelledTxns,
            'discount_by_type'     => $discountByType,
            'top_discounted_orders' => $topDiscountedOrders,
            'vat_monthly'          => $vatMonthly,
            'promo_perf'           => $promoPerf,
        ];
    }

    private function paymentsReportsChartsAllTime(): array
    {
        // Payment Mix chart
        $mix = DB::table('payments')
            ->selectRaw("payment_method as method, SUM(amount) as total")
            ->where('status', 'completed')
            ->groupBy('payment_method')           //  real column
            ->orderByDesc(DB::raw('SUM(amount)')) //  raw expression
            ->get();

        $paymix = [
            'labels' => collect($mix)->pluck('method')->map(fn($m) => strtoupper((string)$m))->toArray(),
            'values' => collect($mix)->pluck('total')->map(fn($v) => (float)$v)->toArray(),
        ];

        // Cancelled/Refunded monthly (count + amount)
        $cancelMonthly = DB::table('payments')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as cnt, SUM(amount) as amt")
            ->whereIn('status', ['failed', 'refunded'])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')")) //  real expression
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')")) //
            ->limit(24)
            ->get();

        $cancelChart = [
            'labels' => collect($cancelMonthly)->pluck('ym')->toArray(),
            'count'  => collect($cancelMonthly)->pluck('cnt')->map(fn($v) => (int)$v)->toArray(),
            'amount' => collect($cancelMonthly)->pluck('amt')->map(fn($v) => (float)$v)->toArray(),
        ];

        // Discounts by type chart
        $disc = DB::table('orders')
            ->selectRaw("discount_type as type, SUM(total_discount_amount) as total_discount")
            ->where('total_discount_amount', '>', 0)
            ->groupBy('discount_type')                            //  real column
            ->orderByDesc(DB::raw('SUM(total_discount_amount)'))  // raw expression
            ->get();

        $discounts = [
            'labels' => collect($disc)->pluck('type')->map(fn($t) => strtoupper((string)$t))->toArray(),
            'values' => collect($disc)->pluck('total_discount')->map(fn($v) => (float)$v)->toArray(),
        ];

        // VAT monthly chart
        $vatRows = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_vat_amount) as vat_total, SUM(service_charge_vat_amount) as service_vat_total")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')")) // real expression
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')")) //
            ->limit(24)
            ->get();

        $vat = [
            'labels'      => collect($vatRows)->pluck('ym')->toArray(),
            'total_vat'   => collect($vatRows)->pluck('vat_total')->map(fn($v) => (float)$v)->toArray(),
            'service_vat' => collect($vatRows)->pluck('service_vat_total')->map(fn($v) => (float)$v)->toArray(),
        ];

        // Promo/Voucher chart
        $promo = DB::table('orders')
            ->selectRaw("discount_type as type, SUM(total_discount_amount) as total_discount, SUM(total_amount) as gross")
            ->whereIn('discount_type', ['promo', 'voucher'])
            ->groupBy('discount_type')                   // real column
            ->orderByDesc(DB::raw('SUM(total_amount)'))  // raw expression
            ->get();

        $promos = [
            'labels'          => collect($promo)->pluck('type')->map(fn($t) => strtoupper((string)$t))->toArray(),
            'discount_values' => collect($promo)->pluck('total_discount')->map(fn($v) => (float)$v)->toArray(),
            'gross_values'    => collect($promo)->pluck('gross')->map(fn($v) => (float)$v)->toArray(),
        ];

        return [
            'paymix'    => $paymix,
            'cancelled' => $cancelChart,
            'discounts' => $discounts,
            'vat'       => $vat,
            'promos'    => $promos,
        ];
    }

    /**
     * Most Used Inventory Per Category report
     * Shows usage patterns by day of week
     */
    public function MostUsedInventoryPerCategory(Request $request): \Illuminate\View\View
    {
        $categoryId = $request->get('category'); // null = All
        $perPage    = (int) ($request->get('per_page', 10));
        $daysWindow = (int) ($request->get('days', 30));

        $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dateExpr = "COALESCE(orders.created_at, inventory_transactions.created_at)";

        $invQuery = Inventory::query()
            ->select('inventories.id', 'inventories.name', 'inventories.inventory_category_id')
            ->with('category:id,name')
            // Subquery to get total usage per inventory within the time window
            ->leftJoinSub(
                InventoryTransaction::query()
                    ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
                    ->where('inventory_transactions.type', 'out')
                    ->whereRaw("$dateExpr >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$daysWindow])
                    ->selectRaw("
                inventory_transactions.inventory_id as inv_id,
                SUM(inventory_transactions.quantity) as usage_sum
            ")
                    ->groupBy('inventory_transactions.inventory_id'),
                'usage_totals',
                'usage_totals.inv_id',
                '=',
                'inventories.id'
            )
            ->addSelect(DB::raw('COALESCE(usage_totals.usage_sum, 0) as precomputed_total'))
            ->orderByDesc('precomputed_total'); // highest usage first

        if (!empty($categoryId)) {
            $invQuery->where('inventories.inventory_category_id', $categoryId);
        }

        $inventories = $invQuery->paginate($perPage)->withQueryString();

        // Tabs
        $categories = InventoryCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Only fetch usage for inventories ON THIS PAGE
        $pageIds = $inventories->getCollection()->pluck('id')->values();

        $rows = InventoryTransaction::query()
            ->where('inventory_transactions.type', 'out')
            ->whereIn('inventory_transactions.inventory_id', $pageIds)
            ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
            ->whereRaw("$dateExpr >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$daysWindow])
            ->selectRaw("
                inventory_transactions.inventory_id as inventory_id,
                DAYNAME($dateExpr) as dow_name,
                SUM(inventory_transactions.quantity) as total_used
            ")
            ->groupBy('inventory_id', 'dow_name')
            ->get();

        // Build usage map: [inventory_id => ['Monday'=>.., ...]]
        $usageMap = [];
        foreach ($rows as $r) {
            $usageMap[$r->inventory_id][$r->dow_name] = (float) $r->total_used;
        }

        // Attach usage to each inventory in the page
        $inventories->getCollection()->transform(function ($inv) use ($daysOrder, $usageMap) {
            $days = array_fill_keys($daysOrder, 0.0);

            foreach (($usageMap[$inv->id] ?? []) as $dayName => $val) {
                if (\array_key_exists($dayName, $days)) {
                    $days[$dayName] = (float) $val;
                }
            }

            $total = array_sum($days);
            $max   = max($days);

            $mostUsed = $max > 0
                ? collect($days)->filter(fn($v) => $v == $max)->keys()->values()->all()
                : [];

            // Add extra fields to the model instance (for blade)
            $inv->usage_days      = $days;
            $inv->usage_total     = $total;
            $inv->usage_max_day   = $max;
            $inv->most_used_days  = $mostUsed;

            return $inv;
        });

        $topForChart = collect($inventories->items())
            ->sortByDesc(fn($i) => $i->usage_total ?? 0)
            ->take(8)
            ->values();

        return view('analytics.inventory-usage', [
            'categories'     => $categories,
            'activeCategory' => $categoryId,
            'daysWindow'     => $daysWindow,
            'perPage'        => $perPage,
            'days'           => $daysOrder,
            'inventories'    => $inventories,
            'topForChart'    => $topForChart,
        ]);
    }

    /**
     * Get all inventory categories
     */
    private function getCategories()
    {
        return InventoryCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get inventory usage by day of week
     */
    private function getInventoryUsageByDay(int $daysWindow, ?string $categoryId, string $dateExpr)
    {
        $usageQuery = InventoryTransaction::query()
            ->join('inventories', 'inventories.id', '=', 'inventory_transactions.inventory_id')
            ->leftJoin('orders', 'orders.id', '=', 'inventory_transactions.order_id')
            ->where('inventory_transactions.type', 'out')
            ->whereRaw("$dateExpr >= DATE_SUB(NOW(), INTERVAL ? DAY)", [$daysWindow]);

        if (!empty($categoryId)) {
            $usageQuery->where('inventories.inventory_category_id', $categoryId);
        }

        return $usageQuery
            ->selectRaw("
                inventory_transactions.inventory_id as inventory_id,
                DAYNAME($dateExpr) as dow_name,
                SUM(inventory_transactions.quantity) as total_used
            ")
            ->groupBy('inventory_id', 'dow_name')
            ->get();
    }

    /**
     * Get inventory names keyed by ID
     */
    private function getInventoriesMap($rows)
    {
        $inventoryIds = $rows->pluck('inventory_id')->unique()->values();

        return Inventory::query()
            ->whereIn('id', $inventoryIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');
    }

    /**
     * Build inventory day data structure
     */
    private function buildInventoryDayData($rows, $inventories, array $daysOrder): array
    {
        $byInventory = [];

        foreach ($rows as $r) {
            $inv = $inventories[$r->inventory_id] ?? null;
            if (!$inv) {
                continue;
            }

            if (!isset($byInventory[$r->inventory_id])) {
                $byInventory[$r->inventory_id] = [
                    'id'    => $inv->id,
                    'name'  => $inv->name,
                    'total' => 0,
                    'days'  => array_fill_keys($daysOrder, 0.0),
                ];
            }

            $dayName = $r->dow_name;
            if (isset($byInventory[$r->inventory_id]['days'][$dayName])) {
                $byInventory[$r->inventory_id]['days'][$dayName] += (float) $r->total_used;
                $byInventory[$r->inventory_id]['total'] += (float) $r->total_used;
            }
        }

        return $byInventory;
    }

    /**
     * Format inventory usage data with calculated fields
     */
    private function formatInventoryUsageData(array $byInventory)
    {
        return collect($byInventory)->map(function (array $inv): array {
            $max = max($inv['days']);
            $inv['most_used_days'] = $max > 0
                ? collect($inv['days'])->filter(fn($v) => $v == $max)->keys()->values()->all()
                : [];
            $inv['max_day_value'] = $max;
            return $inv;
        })->sortByDesc('total')->values();
    }
    
    public function exportSales(Request $request)
    {
        $filter    = $request->get('filter', 'today');
        $year      = (int) ($request->get('year') ?: now()->year);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
    
        // FIX: pass year parameter
        $dateRange = $this->getDateRange($filter, $year, $startDate, $endDate);
    
        $rows = DB::table('item_orders')
            ->join('orders', 'item_orders.order_id', '=', 'orders.id')
            ->join('menu_items', 'item_orders.menu_item_id', '=', 'menu_items.id')
            ->leftJoin('payments', function ($j) {
                $j->on('payments.order_id', '=', 'orders.id')
                  ->where('orders.payment_status', 'paid');
            })
            ->leftJoin('users', 'payments.cashier_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->whereIn('item_orders.status', ['completed', 'served'])
            ->selectRaw("
                orders.id as sale_id,
                COALESCE(orders.paid_at, orders.created_at) as sale_date,
                orders.type as order_type,
                orders.table_id,
                users.name as cashier,
                menu_items.name as item_name,
                item_orders.quantity_ordered as qty,
                item_orders.price_at_sale as unit_price,
                item_orders.discount_type,
                item_orders.discount_amount,
    
                -- FIX: compute line net
                (item_orders.quantity_ordered * item_orders.price_at_sale 
                    - COALESCE(item_orders.discount_amount,0)) as line_net,
    
                orders.subtotal_amount,
                orders.total_discount_amount,
                orders.service_charge_amount,
                orders.total_vat_amount,
                orders.total_amount,
                payments.payment_method,
                payments.status as payment_status
            ")
            ->orderByDesc('sale_date')
            ->get();
    
        $filename = "restaurant_sales_{$filter}_" . now()->format('Ymd_His') . ".csv";
    
        return response()->streamDownload(function () use ($rows) {
    
            $out = fopen('php://output', 'w');
    
            // UTF-8 BOM for Excel
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
    
            fputcsv($out, [
                'Sale ID',
                'Sale Date',
                'Order Type',
                'Table ID',
                'Cashier',
                'Item',
                'Qty',
                'Unit Price',
                'Item Discount Type',
                'Item Discount Amount',
                'Line Net Amount',
                'Order Subtotal',
                'Order Discount',
                'Service Charge',
                'VAT',
                'Grand Total',
                'Payment Method',
                'Payment Status'
            ]);
    
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->sale_id,
                    date('Y-m-d H:i:s', strtotime($r->sale_date)),
                    strtoupper($r->order_type ?? ''),
                    $r->table_id,
                    $r->cashier ?? '',
                    $r->item_name,
                    $r->qty,
                    number_format($r->unit_price, 2, '.', ''),
                    $r->discount_type ?? '',
                    number_format($r->discount_amount ?? 0, 2, '.', ''),
                    number_format($r->line_net ?? 0, 2, '.', ''),
                    number_format($r->subtotal_amount ?? 0, 2, '.', ''),
                    number_format($r->total_discount_amount ?? 0, 2, '.', ''),
                    number_format($r->service_charge_amount ?? 0, 2, '.', ''),
                    number_format($r->total_vat_amount ?? 0, 2, '.', ''),
                    number_format($r->total_amount ?? 0, 2, '.', ''),
                    strtoupper($r->payment_method ?? ''),
                    strtoupper($r->payment_status ?? ''),
                ]);
            }
    
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8'
        ]);
    }
    
    public function exportInventory(Request $request)
    {
        $filter    = $request->get('filter', 'today');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
    
        $dateRange = $this->getDateRange($filter, $startDate, $endDate);
    
        $rows = DB::table('inventory_transactions')
            ->join('orders', 'inventory_transactions.order_id', '=', 'orders.id')
            ->join('inventories', 'inventory_transactions.inventory_id', '=', 'inventories.id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->where('orders.status', 'completed')
            ->where('inventory_transactions.type', 'out')
            ->selectRaw("
                inventory_transactions.id as txn_id,
                inventory_transactions.created_at as txn_date,
                orders.id as order_id,
                inventories.name as inventory_item,
                inventory_transactions.quantity,
                inventories.unit_cost,
                (inventory_transactions.quantity * inventories.unit_cost) as total_cost
            ")
            ->orderByDesc('txn_date')
            ->get();
    
        $filename = "inventory_usage_{$filter}_" . now()->format('Ymd_His') . ".csv";
    
        return response()->streamDownload(function () use ($rows) {
    
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
    
            fputcsv($out, [
                'Transaction ID',
                'Transaction Date',
                'Order ID',
                'Inventory Item',
                'Quantity Used',
                'Unit Cost',
                'Total Cost',
            ]);
    
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->txn_id,
                    date('Y-m-d H:i:s', strtotime($r->txn_date)),
                    $r->order_id,
                    $r->inventory_item,
                    $r->quantity,
                    number_format($r->unit_cost ?? 0, 2, '.', ''),
                    number_format($r->total_cost ?? 0, 2, '.', ''),
                ]);
            }
    
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8'
        ]);
    }
}
