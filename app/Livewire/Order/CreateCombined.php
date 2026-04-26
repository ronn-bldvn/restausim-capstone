<?php

namespace App\Livewire\Order;

use App\Models\CombinedTable;
use App\Models\ItemOrder;
use App\Models\ItemOrderCustomization;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Models\MenuItemCustomization;
use App\Models\Order;
use App\Services\InventoryUsageService;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Point of Sale')]
class CreateCombined extends Component
{
    use WithPagination;
    public $order;
    public $category;
    public $combinedTable;
    public $customizations;
    public $showCustomizationModal = false;
    public $showDiscountModal = false;
    public $menu;
    public $ordersToAdd = [];
    public $orders = [];
    public $orderCount = 0;
    public $totalPrice = 0;
    public $menuStock = [];
    public $modalBasePrice = 0.0;
    public $modalAddonsTotal = 0.0;
    public $modalLineTotal = 0.0;
    public function mount(CombinedTable $combinedTable)
    {
        $this->combinedTable = $combinedTable->load('tables');
        $this->customizations = MenuItemCustomization::with(['ingredient', 'inventory', 'unitOfMeasurement'])->get();

        $orders = Order::with(['items'])
            ->where('combined_table_id', $this->combinedTable->id)
            ->where('status', '!=', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->limit(2)
            ->get();
        if ($orders->count() === 1) {
            $this->order = $orders->first();
            foreach ($this->order->items as $item) {
                if ($item->status !== 'cancelled') {
                    $customizations = [];
                    foreach ($item->customizations as $customization) {
                        if ($item->id === $customization->item_order_id) {
                            $customizations[] = [
                                'id' => $customization->customization->id,
                                'name' => $customization->customization->inventory->name ?? 'No ' . $customization->customization->ingredient->inventory->name,
                                'quantity' => $customization->customization->quantity_used,
                                'price' => $customization->customization->price,
                            ];
                        }
                    }
                    $this->orders[] = [
                        'uid' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'menu_name' => $item->item->name,
                        'customizations' => $customizations,
                        'quantity' =>  $item->quantity_ordered,
                        'base_price' => $item->price_at_sale,
                        'price' => $item->total_item_amount,
                        'status' => 'old',
                    ];
                }
            }
        } elseif ($orders->isEmpty()) {
            $this->order = Order::create([
                'combined_table_id' => $this->combinedTable->id,
                'subtotal_amount' => 0,
                'total_discount_amount' => 0,
                'service_charge_rate' => 0,
                'service_charge_amount' => 0,
                'service_charge_vat_amount' => 0,
                'total_vat_amount' => 0,
                'total_amount' => 0,
            ]);
            $this->combinedTable->update(['status' => 'occupied']);
            foreach ($this->combinedTable->tables as $t) {
                $t->update(['status' => 'occupied']);
            }
        } else {
            dd('Error: there are two existing orders for this combined table');
        }
    }
    
    public function updated($name)
    {
        if ($this->showCustomizationModal && Str::startsWith($name, 'ordersToAdd')) {
            $this->recomputeModalTotals();
        }
    }
    
    public function tryOpenItem($id)
    {
        $this->openCustomizationModal($id);
    }
    
    public function switchTabs($id)
    {
        if ($id !== '') {
            $this->category = $id;
        } else {
            $this->category = '';
        }

        $this->resetPage();
    }
    public function openCustomizationModal($id)
    {
        $this->menu = MenuItem::with(['category', 'ingredients', 'customizations'])->findOrFail($id);
        $this->ordersToAdd['menu_item_id'] = $this->menu->id;
        foreach ($this->menu->ingredients as $ingredient) {
            $this->ordersToAdd['ingredients'][$ingredient->id] = 'default';
        }
        foreach ($this->menu->customizations as $customization) {
            if ($customization->action == 'add') {
                $this->ordersToAdd['additionalIngredients'][$customization->id] = 0;
            }
        }
        $this->ordersToAdd['quantity'] = 1;
        $this->showCustomizationModal = true;
        $this->recomputeModalTotals();
    }
    public function closeCustomizationModal()
    {
        $this->reset(['ordersToAdd', 'menu']);
        $this->showCustomizationModal = false;
    }
    public function incrementAdditionalIngredient($index)
    {
        ++$this->ordersToAdd['additionalIngredients'][$index];
        $this->recomputeModalTotals();
    }
    
    public function decrementAdditionalIngredient($index)
    {
        if ($this->ordersToAdd['additionalIngredients'][$index] > 0) {
            --$this->ordersToAdd['additionalIngredients'][$index];
        }
        $this->recomputeModalTotals();
    }
    
    public function incrementOrderQuantity($index = null)
    {
        if ($index !== null) {
            ++$this->orders[$index]['quantity'];
        } else {
            ++$this->ordersToAdd['quantity'];
        }
        $this->recomputeModalTotals();
    }
    
    public function decrementOrderQuantity($index = null)
    {
        if ($index !== null) {
            if ($this->orders[$index]['quantity'] > 1) {
                --$this->orders[$index]['quantity'];
            }
        } else {
            if ($this->ordersToAdd['quantity'] > 1) {
                --$this->ordersToAdd['quantity'];
            }
        }
        $this->recomputeModalTotals();
    }
    public function addToOrders()
    {
        $customizationsArray = [];
        $totalPrice = (int) $this->menu->price;
        foreach ($this->ordersToAdd['ingredients'] as $key => $orderIngredients) {
            if ($orderIngredients !== 'default') {
                foreach ($this->customizations as $custom) {
                    if ($custom->id == $orderIngredients && $custom->action == 'replace') {
                        $customizationsArray[] = [
                            'id' => $orderIngredients,
                            'name' => $custom->inventory->name,
                            'quantity' => 0,
                            'price' => $custom->price,
                        ];
                        $totalPrice += (int) $custom->price;
                    } elseif ($custom->id == $orderIngredients && $custom->action == 'remove') {
                        $customizationsArray[] = [
                            'id' => $orderIngredients,
                            'name' => 'No ' . $custom->ingredient->inventory->name,
                            'quantity' => 0,
                            'price' => $custom->price,
                        ];
                        $totalPrice += (int) $custom->price;
                    }
                }
            }
        }
        if (array_key_exists('additionalIngredients', $this->ordersToAdd)) {
            foreach ($this->ordersToAdd['additionalIngredients'] as $key => $additional) {
                foreach ($this->customizations as $custom) {
                    if ($custom->id == $key && $additional > 0) {
                        $customizationsArray[] = [
                            'id' => $key,
                            'name' => $custom->inventory->name,
                            'quantity' => $additional,
                            'price' => (int) $custom->price,
                        ];
                        $totalPrice += $custom->price * $additional;
                    }
                }
            }
        }

        $computedPrice = $this->ordersToAdd['quantity'] * $totalPrice;

        $this->orders[] = [
            'uid' => (string) Str::uuid(),
            'menu_item_id' => $this->menu->id,
            'menu_name' => $this->menu->name,
            'customizations' => $customizationsArray,
            'quantity' => $this->ordersToAdd['quantity'],
            'base_price' => $totalPrice,
            'price' => $computedPrice,
            'status' => 'new',
        ];

        $this->reset(['ordersToAdd', 'menu']);
        $this->showCustomizationModal = false;
        $this->computeOrderTotal();
        $this->dispatch('orders-updated');
    }
    public function computeOrderTotal()
    {
        $this->reset(['totalPrice']);
        foreach ($this->orders as $order) {
            $this->totalPrice += $order['price'];
        }
    }
    public function saveOrders()
    {
        DB::beginTransaction();
        try {
            $existingIds = [];

            $order = $this->order;

            foreach ($this->orders as $index => $itemOrder) {
                if (!ItemOrder::where('id', $itemOrder['uid'])->exists()) {
                    $vat_rate_percent = 12;
                    $vat_rate = $vat_rate_percent / 100;
                    $vat_removed_amount = ($itemOrder['base_price'] * $vat_rate / (1 + $vat_rate));
                    $ItemOrder = ItemOrder::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $itemOrder['menu_item_id'],
                        'status' => 'pending',
                        'quantity_ordered' => $itemOrder['quantity'],
                        'price_at_sale' => $itemOrder['base_price'],
                        'vat_rate' => $vat_rate_percent,
                        // 'vat_amount' => $vat_removed_amount,
                        'discount_type' => 'none',
                        'discount_percentage' => 0,
                        'discount_amount' => 0,
                        'final_unit_price' => $itemOrder['base_price'],
                    ]);
                    $existingIds[] = $ItemOrder->id;
                    foreach ($itemOrder['customizations'] as $customization) {
                        ItemOrderCustomization::create([
                            'item_order_id' => $ItemOrder->id,
                            'menu_item_customization_id' => $customization['id'],
                            'quantity_ordered' => $customization['quantity'] ?: 1
                        ]);
                    }

                    app(InventoryUsageService::class)
                        ->consumeForItemOrder(
                            $ItemOrder->load([
                                'customizations.customization',
                                'customizations.customization.ingredient'
                            ])
                        );
                    event(new \App\Events\OrderSaved(
                        $ItemOrder->load(
                            'order.table:id,name',
                            'order.combinedTable.tables:id,name',
                            'item:id,name',
                            'customizations:id,item_order_id,menu_item_customization_id,quantity_ordered',
                            'customizations.customization:id,ingredient_id,inventory_id,action',
                            'customizations.customization.ingredient.inventory:id,name',
                            'customizations.customization.inventory:id,name',
                        )
                    ));
                    $this->orders[$index]['status'] = 'old';
                } else {
                    $existingIds[] = $itemOrder['uid'];
                }
            }
            $databaseIds = ItemOrder::where('order_id', $order->id)->pluck('id')->toArray();
            $missingIds = array_diff($databaseIds, $existingIds);

            $itemsToUpdate = ItemOrder::whereIn('id', $missingIds)
                ->where('status', '!=', 'cancelled')
                ->get();
            SimulationLogger::log(
                action: 'combined_order_saved',
                roleName: 'waiter',
                subject: $order,
                properties: [
                    'order_id' => $order->id,
                    'combined_table_id' => $this->combinedTable->id,
                    'combined_tables' => $this->combinedTable->tables->pluck('name')->toArray(),
                    'total_items' => count($this->orders),
                    'total_amount' => $this->totalPrice,
                    'performed_by' => Auth::id(),
                    'role' => Auth::user()?->getRoleNames()?->first(),
                    'items' => collect($this->orders)->map(function ($item) {
                        return [
                            'menu_item_id' => $item['menu_item_id'],
                            'menu_name'    => $item['menu_name'],
                            'quantity'     => $item['quantity'],
                            'price'        => $item['price'],
                            'status'       => $item['status'],
                        ];
                    })->toArray(),
                ]
            );
            $this->dispatch('activityLogged');
            $this->dispatch(
                'toast',
                type: 'success',
                message: 'Order saved successfully!'
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function render()
    {
        $active = $this->category;
        $orderInstance = $this->order;
        $categories = MenuItemCategory::all();
    
        $menuItems = $this->category
            ? MenuItem::with(['category', 'ingredients', 'customizations'])
                ->where('menu_item_category_id', $this->category)
                ->paginate(10)
            : MenuItem::with(['category', 'ingredients', 'customizations'])
                ->paginate(10);
    
        $tableLabel = 'Combined Table: ' . $this->combinedTable->tables->pluck('name')->join(' + ');
    
        return view('livewire.order.create')
            ->with(compact('active', 'categories', 'menuItems', 'orderInstance', 'tableLabel'))
            ->layoutData([
                'headerTitle' => 'Point of Sale',
            ]);
    }
    
    private function recomputeModalTotals(): void
    {
        if (!$this->menu) {
            $this->modalBasePrice = 0;
            $this->modalAddonsTotal = 0;
            $this->modalLineTotal = 0;
            return;
        }
    
        $base = (float) $this->menu->price;
        $addons = 0.0;
    
        if (!empty($this->ordersToAdd['ingredients'])) {
            foreach ($this->ordersToAdd['ingredients'] as $ingredientId => $customId) {
                if ($customId !== 'default') {
                    foreach ($this->menu->customizations as $c) {
                        if ($c->id == $customId) {
                            $addons += (float) $c->price;
                            break;
                        }
                    }
                }
            }
        }
    
        if (!empty($this->ordersToAdd['additionalIngredients'])) {
            foreach ($this->ordersToAdd['additionalIngredients'] as $customId => $qty) {
                if ($qty > 0) {
                    foreach ($this->menu->customizations as $c) {
                        if ($c->id == $customId && $c->action === 'add') {
                            $addons += (float) $c->price * (int) $qty;
                            break;
                        }
                    }
                }
            }
        }
    
        $qty = (int) ($this->ordersToAdd['quantity'] ?? 1);
    
        $this->modalBasePrice = round($base, 2);
        $this->modalAddonsTotal = round($addons, 2);
        $this->modalLineTotal = round(($base + $addons) * $qty, 2);
    }
}
