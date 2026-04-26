<?php

namespace App\Livewire\Kitchen;

use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Table;
use App\Services\SimulationLogger;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kitchen Dashboard')]
class KitchenDashboard extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders(): void
    {
        $this->orders = [];

        // Initialize physical tables first
        $tables = Table::orderBy('id')->get();

        foreach ($tables as $table) {
            $this->orders['table_' . $table->id] = [
                'table_id' => $table->id,
                'table_name' => $table->name,
                'floor_plan_id' => $table->floor_plan_id,
                'items' => [],
            ];
        }

        $orders = Order::with([
            'table',
            'combinedTable.tables',
            'items.item',
            'items.customizations.customization.ingredient.inventory',
            'items.customizations.customization.inventory',
        ])->get();

        foreach ($orders as $order) {
            if ($order->table) {
                $groupKey = 'table_' . $order->table->id;

                if (!isset($this->orders[$groupKey])) {
                    $this->orders[$groupKey] = [
                        'table_id' => $order->table->id,
                        'table_name' => $order->table->name,
                        'floor_plan_id' => $order->table->floor_plan_id,
                        'items' => [],
                    ];
                }
            } elseif ($order->combinedTable) {
                $groupKey = 'combined_' . $order->combinedTable->id;

                $combinedNames = $order->combinedTable->tables->pluck('name')->join(' + ');

                if (!isset($this->orders[$groupKey])) {
                    $this->orders[$groupKey] = [
                        'table_id' => $order->combinedTable->id,
                        'table_name' => 'Combined: ' . $combinedNames,
                        'floor_plan_id' => $order->combinedTable->floor_plan_id,
                        'items' => [],
                    ];
                }
            } else {
                continue;
            }

            foreach ($order->items as $item) {
                if (in_array($item->status, ['cancelled', 'completed'])) {
                    continue;
                }

                $customizations = [];

                foreach ($item->customizations as $custom) {
                    if (!$custom->customization) {
                        continue;
                    }

                    $customization = $custom->customization;

                    if ($customization->action === 'remove') {
                        $name = optional(optional($customization->ingredient)->inventory)->name;
                        $displayName = $name ? 'No ' . $name : 'No item';
                    } elseif ($customization->action === 'replace') {
                        $displayName = optional($customization->inventory)->name ?? 'Replacement item';
                    } else {
                        $name = optional($customization->inventory)->name;
                        $displayName = $name ? 'Extra ' . $name : 'Extra item';
                    }

                    $customizations[] = [
                        'id' => $customization->id,
                        'name' => $displayName,
                        'quantity' => $custom->quantity_ordered ?? 1,
                    ];
                }

                $this->orders[$groupKey]['items'][] = [
                    'item_id' => $item->id,
                    'item_name' => optional($item->item)->name ?? 'Unknown Item',
                    'quantity' => $item->quantity_ordered ?? 1,
                    'status' => $item->status ?? 'pending',
                    'customizations' => $customizations,
                    'created_at' => optional($item->created_at)?->toDateTimeString(),
                ];
            }
        }

        $this->normalizeOrders();
    }

    protected function normalizeOrders(): void
    {
        foreach ($this->orders as $groupKey => $tableData) {
            if (!is_array($tableData)) {
                unset($this->orders[$groupKey]);
                continue;
            }

            $this->orders[$groupKey] = [
                'table_id' => $tableData['table_id'] ?? null,
                'table_name' => $tableData['table_name'] ?? 'Unknown Table',
                'floor_plan_id' => $tableData['floor_plan_id'] ?? null,
                'items' => [],
            ];

            $items = $tableData['items'] ?? [];

            if (!is_array($items)) {
                $items = [];
            }

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $this->orders[$groupKey]['items'][] = [
                    'item_id' => $item['item_id'] ?? null,
                    'item_name' => $item['item_name'] ?? 'Unknown Item',
                    'quantity' => $item['quantity'] ?? 1,
                    'status' => $item['status'] ?? 'pending',
                    'customizations' => is_array($item['customizations'] ?? null) ? $item['customizations'] : [],
                    'created_at' => $item['created_at'] ?? null,
                ];
            }
        }
    }

    #[On('echo-private:kitchen-orders,.order.saved')]
    public function addOrder($itemOrder): void
    {
        if (!empty($itemOrder['table_id'])) {
            $groupKey = 'table_' . $itemOrder['table_id'];
            $table = Table::find($itemOrder['table_id']);

            if (!$table) {
                $this->loadOrders();
                return;
            }

            if (!isset($this->orders[$groupKey]) || !is_array($this->orders[$groupKey])) {
                $this->orders[$groupKey] = [
                    'table_id' => $table->id,
                    'table_name' => $table->name,
                    'floor_plan_id' => $table->floor_plan_id,
                    'items' => [],
                ];
            }

            $label = $table->name;
        } elseif (!empty($itemOrder['combined_table_id'])) {
            $groupKey = 'combined_' . $itemOrder['combined_table_id'];

            $combinedName = $itemOrder['combined_table_name'] ?? ('Combined Table #' . $itemOrder['combined_table_id']);

            if (!isset($this->orders[$groupKey]) || !is_array($this->orders[$groupKey])) {
                $this->orders[$groupKey] = [
                    'table_id' => $itemOrder['combined_table_id'],
                    'table_name' => $combinedName,
                    'floor_plan_id' => null,
                    'items' => [],
                ];
            }

            $label = $combinedName;
        } else {
            $this->loadOrders();
            return;
        }

        $newItem = [
            'item_id' => $itemOrder['item_id'] ?? null,
            'item_name' => $itemOrder['item_name'] ?? 'Unknown Item',
            'quantity' => $itemOrder['quantity'] ?? 1,
            'status' => $itemOrder['status'] ?? 'pending',
            'customizations' => is_array($itemOrder['customizations'] ?? null) ? $itemOrder['customizations'] : [],
            'created_at' => $itemOrder['created_at'] ?? now()->toDateTimeString(),
        ];

        $this->orders[$groupKey]['items'][] = $newItem;

        $this->normalizeOrders();

        $this->dispatch('toast', message: "New order received for {$label}", type: 'info');
    }

    public function updateStatus($itemOrderId, $status, $groupKey, $index): void
    {
        try {
            $itemOrder = ItemOrder::with(['item', 'order.table', 'order.combinedTable.tables'])->findOrFail($itemOrderId);

            if (!isset($this->orders[$groupKey]['items'][$index])) {
                return;
            }

            $tableName = $this->orders[$groupKey]['table_name'] ?? 'Unknown Table';

            if ($status === 'pending') {
                $itemOrder->update(['status' => 'preparing']);
                $this->orders[$groupKey]['items'][$index]['status'] = 'preparing';

                $this->dispatch(
                    'toast',
                    message: "Started: {$this->orders[$groupKey]['items'][$index]['item_name']} ({$tableName})",
                    type: 'success'
                );

                SimulationLogger::log(
                    action: 'order.started',
                    roleName: 'kitchen staff',
                    subject: $itemOrder,
                    properties: [
                        'table_group' => $groupKey,
                        'table_name' => $tableName,
                        'item_name' => optional($itemOrder->item)->name,
                    ]
                );
            } elseif ($status === 'preparing') {
                $itemOrder->update(['status' => 'completed']);
                unset($this->orders[$groupKey]['items'][$index]);
                $this->orders[$groupKey]['items'] = array_values($this->orders[$groupKey]['items']);

                $this->dispatch(
                    'toast',
                    message: "Completed order ({$tableName})",
                    type: 'success'
                );

                SimulationLogger::log(
                    action: 'order.completed',
                    roleName: 'kitchen staff',
                    subject: $itemOrder,
                    properties: [
                        'table_group' => $groupKey,
                        'table_name' => $tableName,
                        'item_name' => optional($itemOrder->item)->name,
                    ]
                );
            }

            $this->dispatch('activityLogged');
        } catch (\Exception $e) {
            $this->dispatch(
                'toast',
                message: 'Something went wrong. Please try again.',
                type: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.kitchen.kitchen-dashboard')
            ->layoutData([
                'headerTitle' => 'Kitchen',
            ]);
    }
}