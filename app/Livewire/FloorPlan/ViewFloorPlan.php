<?php

namespace App\Livewire\FloorPlan;

use App\Models\FloorPlan;
use App\Models\Table;
use App\Models\TableReservation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Services\SimulationLogger;

#[Layout('layouts.app')]
#[Title('Dining')]
class ViewFloorPlan extends Component
{
    public $floorplan;
    public $mode;
    public $combineMode = false;
    public $selectedTableIds = [];
    public $showCombineConfirm = false;
    public $table_name;
    public $table_capacity;
    public $table_shape;
    public $tables = [];
    public $currentTable;
    public $currentOrder;
    public $currentTableLabel = null;
    public $showSideBar = false;
    public $orders = [];
    public $tab;
    public $showReservationModal = false;
    public $name;
    public $dateTime;
    public $reservations = [];
    public $switchModal = false;
    public $tableToSwitch;
    public $showDeleteConfirm = false;
    public $deleteTableId;
    public $table_shape_data;

    public function mount()
    {
        $this->floorplan = FloorPlan::firstOrCreate([
            'name' => 'Base'
        ]);

        $this->table_shape_data = collect([
            (object)[
                'id' => 'square',
                'name' => 'Square'
            ],
            (object)[
                'id' => 'circle',
                'name' => 'Circle'
            ]
        ]);

        $this->tables = $this->floorplan->tables;
        $this->tab = Auth::user()->canany(['manage table', 'add reservation']) ? 'table' : 'orders';
        $this->mode = Auth::user()->can('create floorplan') ? 'editor' : 'view';
    }

    #[On('tableClicked')]
    public function tableClicked($id)
    {
        if ($this->combineMode) {
            $this->toggleSelect($id);
        } else {
            $this->toggleTable($id);
        }
    }

    #[On('toggleTable')]
    public function toggleTable($id)
    {
        $this->reset(['orders', 'tab']);
        $this->tab = Auth::user()->canany(['manage table', 'add reservation']) ? 'table' : 'orders';

        $table = Table::with([
            'orders',
            'combinedItems.combinedTable.tables'
        ])->where([
            'floor_plan_id' => $this->floorplan->id,
            'id' => $id
        ])->first();

        if (!$table) {
            return;
        }

        $combinedItem = $table->combinedItems->first();

        if ($combinedItem && $combinedItem->combinedTable) {
            $this->currentTableLabel = 'Combined: ' . $combinedItem->combinedTable->tables->pluck('name')->join(', ');
        } else {
            $this->currentTableLabel = $table->name;
        }

        if ($this->currentTable) {
            if ($this->currentTable->id == $table->id) {
                $this->showSideBar = false;
                $this->currentTable = null;
                $this->currentTableLabel = null;
            } else {
                $this->currentTable = $table;
                $this->showSideBar = true;
                $this->loadOrders();
                $this->loadReservations();
                $this->dispatch(
                    'refreshKonva',
                    $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
                    $this->selectedTableIds
                );
            }
        } else {
            $this->showSideBar = true;
            $this->currentTable = $table;
            $this->loadOrders();
            $this->loadReservations();
            $this->dispatch(
                'refreshKonva',
                $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
                $this->selectedTableIds
            );
        }
    }

    public function toggleCombineMode()
    {
        $this->combineMode = !$this->combineMode;

        if (!$this->combineMode) {
            $this->reset(['selectedTableIds', 'showCombineConfirm']);
        }

        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function toggleSelect($id)
    {
        if (in_array($id, $this->selectedTableIds)) {
            $this->selectedTableIds = array_values(array_diff($this->selectedTableIds, [$id]));
        } else {
            $this->selectedTableIds[] = $id;
        }

        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function confirmCombine()
    {
        $this->showCombineConfirm = true;
    }

    public function cancelCombine()
    {
        $this->showCombineConfirm = false;
    }

    public function combineSelected()
    {
        $this->validate([
            'selectedTableIds' => 'required|array|min:2'
        ], [
            'selectedTableIds.min' => 'Select at least two available tables.',
        ]);

        $tables = Table::with('combinedItems', 'orders')
            ->whereIn('id', $this->selectedTableIds)
            ->get();

        if ($tables->isEmpty()) {
            $this->addError('combine', 'No tables selected.');
            return;
        }

        $floorplanId = $tables->first()->floor_plan_id;

        foreach ($tables as $t) {
            if ($t->floor_plan_id !== $floorplanId) {
                $this->addError('combine', 'Tables must be from the same floorplan.');
                return;
            }

            if ($t->status !== 'available') {
                $this->addError('combine', 'Only available tables can be combined.');
                return;
            }

            if ($t->combinedItems()->exists()) {
                $this->addError('combine', 'One or more tables are already combined.');
                return;
            }
        }

        $totalCapacity = (int) $tables->sum('capacity');

        DB::beginTransaction();

        try {
            $combined = \App\Models\CombinedTable::create([
                'floor_plan_id' => $floorplanId,
                'total_capacity' => $totalCapacity,
                'status' => 'available',
            ]);

            foreach ($tables as $t) {
                $combined->items()->create([
                    'table_id' => $t->id,
                ]);
            }

            DB::commit();

            SimulationLogger::log(
                action: 'table.combined',
                roleName: 'waiter',
                subject: $combined,
                properties: [
                    'floor_plan_id' => $floorplanId,
                    'table_ids' => $tables->pluck('id')->values(),
                    'table_names' => $tables->pluck('name')->values(),
                    'total_capacity' => $totalCapacity,
                ]
            );

            $this->toggleCombineMode();
            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Combined tables successfully!', type: 'success');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
        }

        $this->reset(['selectedTableIds', 'showCombineConfirm']);
        $this->floorplan->load(['tables.combinedItems.combinedTable.tables']);
        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function uncombineCurrent()
    {
        if (!$this->currentTable) {
            return;
        }

        $item = $this->currentTable->combinedItems()->with('combinedTable')->first();

        if (!$item) {
            return;
        }

        DB::beginTransaction();

        try {
            $combined = $item->combinedTable;
            $combinedId = $combined->id;
            $tableIds = $combined->items()->pluck('table_id');

            $combined->items()->delete();
            $combined->delete();

            DB::commit();

            SimulationLogger::log(
                action: 'table.uncombined',
                roleName: 'waiter',
                subject: $combined,
                properties: [
                    'combined_table_id' => $combinedId,
                    'table_ids' => $tableIds->values(),
                ]
            );

            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Uncombined tables successfully!', type: 'success');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('combine', 'Failed to uncombine tables.');
            return;
        }

        $this->toggleTable($this->currentTable->id);
        $this->floorplan->load(['tables.combinedItems.combinedTable.tables']);
        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function deleteSelected()
    {
        $this->validate([
            'selectedTableIds' => 'required|array|min:1'
        ], [
            'selectedTableIds.min' => 'Select at least one table.',
        ]);

        $tables = Table::with('combinedItems', 'orders')
            ->whereIn('id', $this->selectedTableIds)
            ->get();

        if ($tables->isEmpty()) {
            $this->addError('combine', 'No tables selected.');
            return;
        }

        $floorplanId = $tables->first()->floor_plan_id;

        foreach ($tables as $t) {
            if ($t->floor_plan_id !== $floorplanId) {
                $this->addError('combine', 'Tables must be from the same floorplan.');
                return;
            }

            if ($t->status !== 'available') {
                $this->addError('combine', 'Only available tables can be deleted.');
                return;
            }

            if ($t->combinedItems()->exists()) {
                $this->addError('combine', 'One or more tables are already combined.');
                return;
            }
        }

        DB::beginTransaction();

        try {
            foreach ($tables as $table) {
                SimulationLogger::log(
                    action: 'table.deleted',
                    roleName: 'manager',
                    subject: $table,
                    properties: [
                        'floor_plan_id' => $floorplanId,
                        'table_ids' => $tables->pluck('id')->values(),
                        'table_names' => $tables->pluck('name')->values(),
                    ]
                );

                $table->delete();
            }

            DB::commit();

            $this->toggleCombineMode();
            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Deleted tables successfully!', type: 'success');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
        }

        $this->reset(['selectedTableIds', 'showCombineConfirm']);
        $this->floorplan->load(['tables.combinedItems.combinedTable.tables']);
        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function switchTabs($tab)
    {
        $this->tab = $tab;
    }

    public function loadOrders()
    {
        $this->orders = [];
        $this->currentOrder = null;

        if (!$this->currentTable) {
            return;
        }

        $combinedItem = $this->currentTable->combinedItems()->with([
            'combinedTable.tables',
            'combinedTable.orders.items.item',
            'combinedTable.orders.items.customizations.customization.inventory',
            'combinedTable.orders.items.customizations.customization.ingredient.inventory',
        ])->first();

        if ($combinedItem && $combinedItem->combinedTable) {
            $combinedTable = $combinedItem->combinedTable;

            $order = $combinedTable->orders()
                ->with([
                    'items.item',
                    'items.customizations.customization.inventory',
                    'items.customizations.customization.ingredient.inventory',
                ])
                ->where('status', '!=', 'completed')
                ->where('payment_status', '!=', 'paid')
                ->first();

            if ($order) {
                foreach ($order->items as $item) {
                    if ($item->status !== 'cancelled') {
                        $customizations = [];

                        foreach ($item->customizations as $customization) {
                            if ($item->id === $customization->item_order_id) {
                                $customizations[] = [
                                    'id' => $customization->customization->id,
                                    'name' => $customization->customization->inventory->name
                                        ?? 'No ' . $customization->customization->ingredient->inventory->name,
                                    'quantity' => $customization->quantity_ordered ?? 1,
                                    'price' => $customization->customization->price,
                                ];
                            }
                        }

                        $this->orders[] = [
                            'uid' => $item->id,
                            'menu_item_id' => $item->menu_item_id,
                            'menu_name' => $item->item->name,
                            'customizations' => $customizations,
                            'quantity' => $item->quantity_ordered,
                            'base_price' => $item->price_at_sale,
                            'price' => $item->line_gross_amount ?? $item->total_item_amount ?? 0,
                            'status' => $item->status,
                        ];
                    }
                }

                $this->currentOrder = $order;
            }

            return;
        }

        $table = $this->currentTable->load([
            'orders.items.item',
            'orders.items.customizations.customization.inventory',
            'orders.items.customizations.customization.ingredient.inventory',
        ]);

        foreach ($table->orders as $order) {
            if ($order->status != 'completed' && $order->payment_status !== 'paid') {
                foreach ($order->items as $item) {
                    if ($item->status !== 'cancelled') {
                        $customizations = [];

                        foreach ($item->customizations as $customization) {
                            if ($item->id === $customization->item_order_id) {
                                $customizations[] = [
                                    'id' => $customization->customization->id,
                                    'name' => $customization->customization->inventory->name
                                        ?? 'No ' . $customization->customization->ingredient->inventory->name,
                                    'quantity' => $customization->quantity_ordered ?? 1,
                                    'price' => $customization->customization->price,
                                ];
                            }
                        }

                        $this->orders[] = [
                            'uid' => $item->id,
                            'menu_item_id' => $item->menu_item_id,
                            'menu_name' => $item->item->name,
                            'customizations' => $customizations,
                            'quantity' => $item->quantity_ordered,
                            'base_price' => $item->price_at_sale,
                            'price' => $item->line_gross_amount ?? $item->total_item_amount ?? 0,
                            'status' => $item->status,
                        ];
                    }
                }
            }
        }

        $this->currentOrder = $table->orders->where('status', 'preparing')->first()
            ?? $table->orders->firstWhere('payment_status', '!=', 'paid');
    }

    public function loadReservations()
    {
        if (!$this->currentTable) {
            return;
        }

        $reservations = TableReservation::withoutGlobalScopes()
            ->where('table_id', $this->currentTable->id)
            ->get();

        $this->reservations = $reservations->map(function ($reservation) {
            return [
                'name' => $reservation->reservee_name,
                'time' => Carbon::parse($reservation->reservation_time)->format('F j, Y g:i A'),
            ];
        })->toArray();
    }

    public function cleanTable()
    {
        $this->currentTable->update(['status' => 'available']);

        SimulationLogger::log(
            action: 'table.cleaned',
            roleName: 'waiter',
            subject: $this->currentTable,
            properties: [
                'table_id' => $this->currentTable->id,
                'name' => $this->currentTable->name,
            ]
        );

        $this->dispatch('activityLogged');
        $this->dispatch('toast', message: 'Table cleaned successfully!', type: 'success');

        $this->floorplan->load(['tables']);
        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function openSwitchModal()
    {
        $this->switchModal = true;
    }

    public function closeSwitchModal()
    {
        $this->switchModal = false;
    }

    public function switchTable()
    {
        DB::beginTransaction();

        try {
            $this->currentTable->update([
                'status' => 'available',
            ]);

            $table = $this->tables->find($this->tableToSwitch);

            $table->update([
                'status' => 'occupied',
            ]);

            $this->currentOrder->update([
                'table_id' => $table->id,
            ]);

            DB::commit();

            SimulationLogger::log(
                action: 'order.switched_table',
                roleName: 'waiter',
                subject: $this->currentOrder,
                properties: [
                    'order_id' => $this->currentOrder->id,
                    'from_table_id' => $this->currentTable->id,
                    'to_table_id' => $table->id,
                ]
            );

            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Switch tables successfully!', type: 'success');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }

        $this->toggleTable($this->tableToSwitch);

        $this->reset(['tableToSwitch', 'switchModal']);

        $this->dispatch(
            'refreshKonva',
            $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
            $this->selectedTableIds
        );
    }

    public function openReservationModal()
    {
        $this->showReservationModal = true;
    }

    public function closeReservationModal()
    {
        $this->showReservationModal = false;
        $this->resetErrorBag();
    }

    public function saveReservation()
    {
        $validated = $this->validate([
            'name' => 'required',
            'dateTime' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now'
        ], [
            '*.required' => 'This field is required',
            'dateTime.date_format' => 'Invalid date format',
            'dateTime.after_or_equal' => 'Date and Time must be in the future',
        ]);

        DB::beginTransaction();

        try {
            $reservation = TableReservation::create([
                'table_id' => $this->currentTable->id,
                'reservee_name' => $validated['name'],
                'reservation_time' => $validated['dateTime'],
            ]);

            SimulationLogger::log(
                action: 'reservation.created',
                roleName: 'front desk',
                subject: $reservation,
                properties: [
                    'table_id' => $this->currentTable->id,
                    'reservee_name' => $reservation->reservee_name,
                    'reservation_time' => $reservation->reservation_time,
                ]
            );

            DB::commit();

            $this->showReservationModal = false;
            $this->loadReservations();
            $this->resetErrorBag();
            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Saved reservation successfully!', type: 'success');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function render()
    {
        if ($this->currentTable) {
            $this->loadReservations();
        }

        $floorplan = $this->floorplan->load(['tables.combinedItems.combinedTable.tables']);
        $currentTable = $this->currentTable;

        $ordersStatus = null;

        if ($currentTable) {
            $ordersStatus = $this->currentTable->checkIfOrdersAreComplete();
        }

        return view('livewire.floor-plan.view-floor-plan')
            ->with(compact('floorplan', 'currentTable', 'ordersStatus'))
            ->layoutData([
                'headerTitle' => 'Dining',
            ]);
    }

    #[On('updateTable')]
    public function updateTable($id, $x, $y, $rotation, $width = null, $height = null)
    {
        if ($this->mode === 'editor') {
            $this->floorplan->tables()->where('id', $id)->update([
                'x' => round($x),
                'y' => round($y),
                'rotation' => round($rotation),
                'width' => $width,
                'height' => $height
            ]);
        }
    }

    public function addTable()
    {
        $validated = $this->validate([
            'table_name' => 'required|unique:tables,name,NULL,id,floor_plan_id,' . $this->floorplan->id,
            'table_capacity' => 'required||numeric|gte:1|lte:6',
            'table_shape' => 'required',
        ], [
            '*.required' => 'This field is required',
            'table_name.unique' => 'Table name must be unique',
            'table_capacity.gte' => 'Must be greater than 1',
            'table_capacity.lte' => 'Must be greater than 6',
        ]);

        DB::beginTransaction();

        try {
            $table = Table::create([
                'floor_plan_id' => $this->floorplan->id,
                'name' => $validated['table_name'],
                'capacity' => $validated['table_capacity'],
                'shape' => $validated['table_shape'],
            ]);

            SimulationLogger::log(
                action: 'table.created',
                roleName: 'manager',
                subject: $table,
                properties: [
                    'floor_plan_id' => $this->floorplan->id,
                    'name' => $table->name,
                    'capacity' => $table->capacity,
                    'shape' => $table->shape,
                ]
            );

            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Added new table successfully!', type: 'success');
            $this->floorplan->load('tables');

            $this->reset(['table_name', 'table_capacity', 'table_shape']);
            $this->dispatch(
                'refreshKonva',
                $this->floorplan->tables->load(['combinedItems', 'combinedItems.combinedTable', 'combinedItems.combinedTable.tables']),
                $this->selectedTableIds
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}