<?php

namespace App\Livewire\Layout;

use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\SimulationSession;

class RecentActivity extends Component
{
    public $logs;
    public $limit = 5;
    public $roleName = null;
    public $totalLogs = 0;

    protected $listeners = [
        'activityLogged' => 'refreshLogs',
        'simulationRoleChanged' => 'setRoleAndRefresh',
    ];

    public function mount()
    {
        $this->logs = collect();
    
        $sessionId = session('simulation_session_id');
    
        if ($sessionId) {
            $session = SimulationSession::find($sessionId);
            $this->roleName = $this->normalizeRole($session?->role_name);
        }
    
        if (!$this->roleName) {
            $this->totalLogs = 0;
            return;
        }
    
        $this->refreshLogs();
    }
    public function setRoleAndRefresh($roleName = null)
    {
        $this->roleName = $this->normalizeRole($roleName ?? session('simulation_role'));
        $this->limit = 5;
        $this->refreshLogs();
    }

    public function loadMore()
    {
        $this->limit += 10;
        $this->loadLogs();
    }

    public function refreshLogs()
    {
        if (!$this->roleName) {
            $this->logs = collect();
            $this->totalLogs = 0;
            return;
        }

        $this->totalLogs = $this->baseQuery()->count();
        $this->loadLogs();
    }

    public function loadLogs()
    {
        $this->logs = $this->baseQuery()
            ->latest()
            ->take($this->limit)
            ->get();
    }

    private function normalizeRole($role): ?string
    {
        if (!$role) {
            return null;
        }
    
        $role = strtolower(trim($role));
        $role = str_replace(['_', '-'], ' ', $role);
        $role = preg_replace('/\s+/', ' ', $role);
    
        return $role;
    }
    
    private function roleAliases(?string $role): array
    {
        if (!$role) {
            return [];
        }
    
        return match ($role) {
            'manager' => ['manager'],
            'waiter' => ['waiter'],
            'kitchen staff' => ['kitchen staff', 'kitchen_staff', 'kitchen-staff', 'kitchen'],
            'front desk' => ['front desk', 'front_desk', 'front-desk', 'host'],
            'cashier' => ['cashier'],
            'host' => ['host', 'front desk', 'front_desk', 'front-desk'],
            default => [$role],
        };
    }
    
    private function baseQuery()
    {
        if (!$this->roleName) {
            return ActivityLog::query()->whereRaw('1 = 0');
        }
    
        $roles = $this->roleAliases($this->roleName);
    
        return ActivityLog::query()
            ->where(function ($query) use ($roles) {
                foreach ($roles as $index => $role) {
                    $normalized = $this->normalizeRole($role);
    
                    if ($index === 0) {
                        $query->whereRaw(
                            "LOWER(REPLACE(REPLACE(role_name, '_', ' '), '-', ' ')) = ?",
                            [$normalized]
                        );
                    } else {
                        $query->orWhereRaw(
                            "LOWER(REPLACE(REPLACE(role_name, '_', ' '), '-', ' ')) = ?",
                            [$normalized]
                        );
                    }
                }
            });
    }

    public function formatLog(ActivityLog $log): string
    {
        $p = $log->properties ?? [];
        $get = fn($k, $default = null) => data_get($p, $k, $default);

        return match ($log->action) {
            // TABLE
            'table.created' => 'Created table "' . $get('name') . '" (cap ' . $get('capacity', '?') . ')',
            'table.deleted' => 'Deleted table "' . $get('name') . '"',
            'table.cleaned' => 'Cleaned table "' . $get('name') . '"',
            'table.combined' => 'Combined tables [' . implode(', ', (array) $get('table_ids', [])) . ']',
            'table.uncombined' => 'Uncombined tables [' . implode(', ', (array) $get('table_ids', [])) . ']',

            // INVENTORY
            'inventory.created' => 'Created inventory "' . $get('name', 'Unnamed inventory') . '"',
            'inventory.updated' => 'Updated inventory "' . $get('name', 'Unnamed inventory') . '"',
            'inventory.deleted' => 'Deleted inventory "' . $get('name', 'Unnamed inventory') . '"',
            'inventory.restocked' => 'Restocked ' . $get('items_restocked', 0) . ' item(s)',

            // MENU
            'create.menu' => 'Created menu item #' . $get('menu_item_id'),
            'create.menu.item' => 'Created menu item "' . $get('menu_item_name') . '" (#' . $get('menu_item_id') . ')',
            'menu.updated' => 'Updated menu item "' . $get('menu_item_name') . '" (#' . $get('menu_item_id') . ')',
            'menu.deleted' => 'Deleted menu item "' . $get('menu_item_name') . '" (#' . $get('menu_item_id') . ')',

            // ORDER
            'order_saved' => 'Saved order #' . $get('order_id') . ' (' . $get('table_name', 'table ' . $get('table_id')) . ')',
            'order.switched_table' => 'Switched order #' . $get('order_id') . ' from ' . $get('from_table_id') . ' to ' . $get('to_table_id'),

            // KITCHEN
            'order.started' => 'Started order (' .
    ($get('table') ?? ($get('table_id') ? 'Table ' . $get('table_id') : 'Unknown table'))
    . ')' .
    ($get('item_name') ? ' - ' . $get('item_name') : ''),
            'order.completed' => 'Completed order (' . $get('table', 'Unknown table') . ')' . ($get('item_name', null) ? ' - ' . $get('item_name') : ''),

            // CASHIER
            'order_discount_applied' => 'Applied discount to order #' . $get('order_id'),
            'payment_completed' => 'Payment completed for order #' . $get('order_id') . ' (' . $get('table_name') . ') - ' . $get('payment_type', 'payment'),

            // DISCOUNTS
            'discount.created',
            'created discount' => 'Created discount "' . $get('name', $get('discount_name', 'Unnamed discount')) . '"',

            'discount.updated',
            'updated discount' => 'Updated discount "' . $get('name', $get('discount_name', 'Unnamed discount')) . '"',

            'discount.deleted',
            'deleted discount' => 'Deleted discount "' . $get('name', $get('discount_name', 'Unnamed discount')) . '"',

            default => Str::headline(str_replace(['.', '_'], ' ', $log->action)),
        };
    }

    private function fmtMoney($val): ?string
    {
        if ($val === null || $val === '') {
            return null;
        }

        return '₱' . number_format((float) $val, 2);
    }

    private function joinParts(array $parts): string
    {
        return collect($parts)
            ->filter(fn($x) => filled($x))
            ->implode(' • ');
    }

    public function formatDetails(ActivityLog $log): string
    {
        $p = $log->properties ?? [];
        $get = fn($k, $default = null) => data_get($p, $k, $default);

        return match ($log->action) {
            // TABLE
            'table.created' => $this->joinParts([
                $get('floor_plan_id') ? 'Floor plan #' . $get('floor_plan_id') : null,
                $get('shape') ? 'Shape: ' . Str::headline($get('shape')) : null,
            ]),

            'table.deleted' => $this->joinParts([
                $get('id') ? 'Table ID: ' . $get('id') : null,
            ]),

            'table.cleaned' => $this->joinParts([
                $get('table_id') ? 'Table ID: ' . $get('table_id') : null,
            ]),

            'table.combined' => $this->joinParts([
                $get('floor_plan_id') ? 'Floor plan #' . $get('floor_plan_id') : null,
                $get('combined_table_id') ? 'Combined #' . $get('combined_table_id') : null,
                $get('table_names') ? 'Tables: ' . implode(', ', (array) $get('table_names')) : (
                    $get('table_ids') ? 'Table IDs: ' . implode(', ', (array) $get('table_ids')) : null
                ),
            ]),

            'table.uncombined' => $this->joinParts([
                $get('combined_table_id') ? 'Combined #' . $get('combined_table_id') : null,
                $get('table_names') ? 'Tables: ' . implode(', ', (array) $get('table_names')) : (
                    $get('table_ids') ? 'Table IDs: ' . implode(', ', (array) $get('table_ids')) : null
                ),
            ]),

            // INVENTORY
            'inventory.created' => $this->joinParts([
                $get('inventory_category') ? 'Category: ' . $get('inventory_category') : null,
                $get('unit') ? 'Unit: ' . $get('unit') : null,
                $get('quantity') !== null ? 'Qty: ' . $get('quantity') : null,
            ]),

            'inventory.updated' => $this->joinParts([
                $get('changes') ? 'Changed: ' . implode(', ', array_map(
                    fn($k, $v) => Str::headline($k) . ': ' . $v,
                    array_keys((array) $get('changes')),
                    array_values((array) $get('changes'))
                )) : null,
            ]),

            'inventory.deleted' => $this->joinParts([
                $get('inventory_category') ? 'Category: ' . $get('inventory_category') : null,
            ]),

            'inventory.restocked' => $this->joinParts([
                $get('items_restocked') !== null ? 'Items restocked: ' . $get('items_restocked') : null,
                $get('restock_batch') ? 'Batch: ' . $get('restock_batch') : null,
            ]),

            // MENU
            'create.menu' => $this->joinParts([
                $get('menu_item_name') ? 'Name: ' . $get('menu_item_name') : null,
                $get('price') !== null ? 'Price: ' . $this->fmtMoney($get('price')) : null,
                $get('category') ? 'Category: ' . $get('category') : null,
            ]),

            'create.menu.item' => $this->joinParts([
                $get('price') !== null ? 'Price: ' . $this->fmtMoney($get('price')) : null,
                $get('category') ? 'Category: ' . $get('category') : null,
            ]),

            'menu.updated' => $this->joinParts([
                $get('old_name') && $get('menu_item_name')
                    ? 'Renamed: ' . $get('old_name') . ' → ' . $get('menu_item_name')
                    : null,
                $get('old_price') !== null && $get('price') !== null
                    ? 'Price: ' . $this->fmtMoney($get('old_price')) . ' → ' . $this->fmtMoney($get('price'))
                    : null,
            ]),

            'menu.deleted' => $this->joinParts([
                $get('menu_item_name') ? 'Name: ' . $get('menu_item_name') : null,
            ]),

            // ORDER
            'order_saved' => $this->joinParts([
                $get('table_name') ? 'Table: ' . $get('table_name') : ($get('table_id') ? 'Table ID: ' . $get('table_id') : null),
                $get('items_count') ? 'Items: ' . $get('items_count') : null,
                $get('total') !== null ? 'Total: ' . $this->fmtMoney($get('total')) : null,
            ]),

            'order.switched_table' => $this->joinParts([
                $get('from_table_name') && $get('to_table_name')
                    ? 'From ' . $get('from_table_name') . ' → ' . $get('to_table_name')
                    : ($get('from_table_id') && $get('to_table_id')
                        ? 'From #' . $get('from_table_id') . ' → #' . $get('to_table_id')
                        : null),
            ]),

            // KITCHEN
            'order.started' => $this->joinParts([
                $get('table')
                    ? 'Table: ' . $get('table')
                    : ($get('table_id') ? 'Table: ' . $get('table_id') : null),
                $get('item_name') ? 'Item: ' . $get('item_name') : null,
            ]),

            'order.completed' => $this->joinParts([
                $get('table') ? 'Table: ' . $get('table') : null,
                $get('item_name') ? 'Item: ' . $get('item_name') : null,
            ]),

            // CASHIER
            'order_discount_applied' => $this->joinParts([
                $get('table_name') ? 'Table: ' . $get('table_name') : null,
                $get('discount_name') ? 'Discount: ' . $get('discount_name') : null,
                $get('discount_amount') !== null ? 'Less: ' . $this->fmtMoney($get('discount_amount')) : null,
                $get('new_total') !== null ? 'New total: ' . $this->fmtMoney($get('new_total')) : null,
            ]),

            'payment_completed' => $this->joinParts([
                $get('table_name') ? 'Table: ' . $get('table_name') : null,
                $get('payment_type') ? 'Type: ' . Str::upper($get('payment_type')) : null,
                $get('grand_total') !== null ? 'Total: ' . $this->fmtMoney($get('grand_total')) : null,
                $get('paid_amount') !== null ? 'Paid: ' . $this->fmtMoney($get('paid_amount')) : null,
                $get('change') !== null ? 'Change: ' . $this->fmtMoney($get('change')) : null,
                $get('split_count') ? 'Splits: ' . $get('split_count') : null,
            ]),

            // DISCOUNTS
            'discount.created',
            'created discount' => $this->joinParts([
                $get('type') ? 'Type: ' . $get('type') : null,
                $get('rate') !== null ? 'Rate: ' . $get('rate') : null,
            ]),

            'discount.updated',
            'updated discount' => $this->joinParts([
                $get('changes') ? 'Changed: ' . implode(', ', array_keys((array) $get('changes'))) : null,
            ]),

            'discount.deleted',
            'deleted discount' => $this->joinParts([
                $get('id') ? 'Discount ID: ' . $get('id') : ($get('discount_id') ? 'Discount ID: ' . $get('discount_id') : null),
            ]),

            default => '',
        };
    }

    public function render()
    {
        $sessionRole = session('simulation_role');
        $normalizedRole = $this->roleName;
    
        $query = $this->baseQuery();
        $matchedLogs = $query->count();
    
        $dbRoles = ActivityLog::select('role_name')
            ->distinct()
            ->pluck('role_name')
            ->toArray();
    
        return view('livewire.layout.recent-activity', [
            'debugSessionRole' => $sessionRole,
            'debugNormalizedRole' => $normalizedRole,
            'debugMatchedLogs' => $matchedLogs,
            'debugDbRoles' => $dbRoles,
        ]);
    }
}