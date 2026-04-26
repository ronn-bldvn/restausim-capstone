<?php

namespace App\Livewire\Order;

use App\Models\Discount;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Services\SimulationLogger;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $order;
    public $itemOrder;
    public $orders = [];
    public $showDiscountModal = false;

    public $itemDiscount = [
        'vat_amount' => 0,
        'discount_type' => 'none',
        'discount_percentage' => 0,
        'discount_amount' => 0,
        'discount_id' => null,
        'vat_exempt_due_to_discount' => false,
        'final_unit_price' => 0,
        'line_gross_amount' => 0,
        'notes' => null,
    ];

    public $orderDiscount = [
        'discount_percentage' => 0,
        'discount_amount' => 0,
        'discount_type' => 'none',
    ];

    public $itemOrderDiscount = [];
    public $vat_sales = 0;
    public $vat_exempt_sales = 0;
    public $total_vat = 0;
    public $total_discount = 0;
    public $amount_due = 0;
    public $activeTab = 'discount';
    public $service_charge = 0;
    public $service_charge_amount = 0;
    public $service_charge_vat_amount = 0;

    public $discountSelection;
    public $orderDiscountSelection;
    public $orderDiscountType = '';

    public function mount(Order $order)
    {
        $this->order = $order;

        $this->orderDiscount['discount_percentage'] = (float) ($this->order->order_discount_percentage ?? 0);
        $this->orderDiscount['discount_amount'] = (float) ($this->order->order_discount_amount ?? 0);
        $this->orderDiscount['discount_type'] = $this->order->discount_type ?? 'none';

        $this->total_discount = (float) ($this->order->total_discount_amount ?? 0);
        $this->total_vat = (float) ($this->order->total_vat_amount ?? 0);
        $this->amount_due = (float) ($this->order->total_amount ?? 0);
        $this->service_charge = (float) ($this->order->service_charge_rate ?? 0);
        $this->service_charge_amount = (float) ($this->order->service_charge_amount ?? 0);
        $this->service_charge_vat_amount = (float) ($this->order->service_charge_vat_amount ?? 0);

        $this->orderDiscountSelection = Discount::where('type', 'order')->get();

        $this->loadOrders();
        $this->calculateOrderTotal();
    }

    public function render()
    {
        return view('livewire.order.checkout');
    }

    public function changeActiveTab($tabName)
    {
        $this->activeTab = $tabName;
    }

    public function loadOrders()
    {
        $this->orders = [];

        $this->order->load('items.customizations.customization.inventory', 'items.customizations.customization.ingredient.inventory', 'items.item');

        foreach ($this->order->items as $item) {
            if ($item->status !== 'completed') {
                continue;
            }

            $customizations = [];

            foreach ($item->customizations as $customization) {
                $customizations[] = [
                    'id' => $customization->customization->id,
                    'name' => $customization->customization->inventory->name
                        ?? 'No ' . ($customization->customization->ingredient->inventory->name ?? ''),
                    'quantity' => $customization->customization->quantity_used,
                    'price' => $customization->customization->price,
                    'type' => $customization->customization->action,
                ];
            }

            $computedLineGross = (float) $item->final_unit_price * (float) $item->quantity_ordered;

            $this->orders[] = [
                'uid' => $item->id,
                'menu_item_id' => $item->menu_item_id,
                'menu_name' => $item->item->name ?? 'Unknown Item',
                'customizations' => $customizations,
                'quantity' => $item->quantity_ordered,
                'discount_amount' => $item->discount_amount,
                'price_at_sale' => $item->price_at_sale,
                'line_gross_amount' => $computedLineGross,
            ];
        }
    }

    public function calculateOrderTotal()
    {
        $this->order->refresh();
        $this->order->load('items');

        $this->vat_sales = 0;
        $this->vat_exempt_sales = 0;
        $this->total_vat = 0;
        $this->total_discount = 0;
        $this->amount_due = 0;
        $this->itemOrderDiscount = [];

        foreach ($this->order->items as $item) {
            if ($item->status !== 'completed') {
                continue;
            }

            $qty = (float) $item->quantity_ordered;
            $finalUnitPrice = (float) $item->final_unit_price;
            $discountAmount = (float) ($item->discount_amount ?? 0);
            $lineTotal = $finalUnitPrice * $qty;

            $this->total_discount += $discountAmount;
            $this->amount_due += $lineTotal;

            if ((bool) $item->vat_exempt_due_to_discount) {
                $this->vat_exempt_sales += $lineTotal;
            } else {
                $vatRate = ((float) $item->vat_rate) / 100;
                $netSale = $vatRate > 0 ? $lineTotal / (1 + $vatRate) : $lineTotal;
                $vatAmount = $lineTotal - $netSale;

                $this->vat_sales += $netSale;
                $this->total_vat += $vatAmount;
            }
        }

        if ((float) $this->orderDiscount['discount_percentage'] > 0) {
            $this->calculateOrderDiscount((float) $this->orderDiscount['discount_percentage'], 'percentage', true);
        } elseif ((float) $this->orderDiscount['discount_amount'] > 0) {
            $this->calculateOrderDiscount((float) $this->orderDiscount['discount_amount'], 'flat', true);
        }

        if ((float) $this->service_charge > 0) {
            $this->applyServiceCharge((float) $this->service_charge);
        }

        $this->vat_sales = round($this->vat_sales, 2);
        $this->vat_exempt_sales = round($this->vat_exempt_sales, 2);
        $this->total_vat = round($this->total_vat, 2);
        $this->total_discount = round($this->total_discount, 2);
        $this->amount_due = round($this->amount_due, 2);
    }

    public function openDiscountModal($index)
    {
        $this->itemOrder = ItemOrder::with(['item', 'item_order_discounts'])->find($this->orders[$index]['uid']);

        if (!$this->itemOrder) {
            $this->dispatch('toast', type: 'error', message: 'Item order not found.');
            return;
        }

        $this->showDiscountModal = true;
        $this->discountSelection = $this->itemOrder->item->menu_item_discounts ?? collect();

        $existingDiscountId = optional($this->itemOrder->item_order_discounts->first())->id;
        $baseLineTotal = (float) $this->itemOrder->final_unit_price * (float) $this->itemOrder->quantity_ordered;
        $vatRate = ((float) $this->itemOrder->vat_rate) / 100;

        $netSale = ((bool) $this->itemOrder->vat_exempt_due_to_discount || $vatRate <= 0)
            ? $baseLineTotal
            : $baseLineTotal / (1 + $vatRate);

        $vatAmount = ((bool) $this->itemOrder->vat_exempt_due_to_discount || $vatRate <= 0)
            ? 0
            : $baseLineTotal - $netSale;

        $this->itemDiscount['discount_type'] = $this->itemOrder->discount_type === 'promo'
            ? $existingDiscountId
            : ($this->itemOrder->discount_type ?? 'none');

        $this->itemDiscount['discount_id'] = $existingDiscountId;
        $this->itemDiscount['discount_percentage'] = (float) ($this->itemOrder->discount_percentage ?? 0);
        $this->itemDiscount['discount_amount'] = (float) ($this->itemOrder->discount_amount ?? 0);
        $this->itemDiscount['vat_exempt_due_to_discount'] = (bool) ($this->itemOrder->vat_exempt_due_to_discount ?? false);
        $this->itemDiscount['final_unit_price'] = (float) ($this->itemOrder->final_unit_price ?? 0);
        $this->itemDiscount['line_gross_amount'] = round($baseLineTotal, 2);
        $this->itemDiscount['vat_amount'] = round($vatAmount, 2);
        $this->itemDiscount['notes'] = $this->itemOrder->notes;
    }

    public function closeDiscountModal()
    {
        $this->showDiscountModal = false;
        $this->itemOrder = null;
        $this->itemDiscount = [
            'vat_amount' => 0,
            'discount_type' => 'none',
            'discount_percentage' => 0,
            'discount_amount' => 0,
            'discount_id' => null,
            'vat_exempt_due_to_discount' => false,
            'final_unit_price' => 0,
            'line_gross_amount' => 0,
            'notes' => null,
        ];
    }

    public function updatedItemDiscountDiscountPercentage($value)
    {
        if ($value !== null && $value !== '' && $this->itemOrder) {
            $this->itemDiscount['discount_percentage'] = max(0, min(100, (float) $value));
            $this->calculateItemDiscount();
        }
    }

    public function updatedItemDiscountDiscountAmount($value)
    {
        if ($value !== null && $value !== '' && $this->itemOrder) {
            $value = max(0, (float) $value);
            $basePrice = (float) $this->itemOrder->price_at_sale;

            $this->itemDiscount['discount_percentage'] = $basePrice > 0
                ? min(100, round(($value / $basePrice) * 100, 2))
                : 0;

            $this->calculateItemDiscount();
        }
    }

    public function updatedItemDiscountDiscountType($value)
    {
        if (!$this->itemOrder) {
            return;
        }

        if ($value === '') {
            $this->itemDiscount['discount_type'] = 'none';
            $this->itemDiscount['discount_id'] = null;
            $this->resetDiscountValues();
            return;
        }

        if ($value === 'custom') {
            $this->itemDiscount['discount_type'] = 'custom';
            $this->itemDiscount['discount_id'] = null;
            $this->resetDiscountValues();
            return;
        }

        $discount = Discount::find($value);

        if (!$discount) {
            $this->dispatch('toast', type: 'error', message: 'Discount not found.');
            return;
        }

        $this->itemDiscount['discount_type'] = 'promo';
        $this->itemDiscount['discount_id'] = $discount->id;
        $this->itemDiscount['vat_exempt_due_to_discount'] = (bool) $discount->is_vat_exempt;

        if ($discount->discount_type === 'percentage') {
            $this->itemDiscount['discount_percentage'] = (float) $discount->discount_value;
        } else {
            $basePrice = (float) $this->itemOrder->price_at_sale;
            $this->itemDiscount['discount_percentage'] = $basePrice > 0
                ? min(100, round((((float) $discount->discount_value) / $basePrice) * 100, 2))
                : 0;
        }

        $this->calculateItemDiscount();
    }

    public function calculateItemDiscount()
    {
        if (!$this->itemOrder) {
            return;
        }

        $baseUnitPrice = (float) $this->itemOrder->price_at_sale;
        $qty = (float) $this->itemOrder->quantity_ordered;
        $vatRate = ((float) $this->itemOrder->vat_rate) / 100;
        $percentage = max(0, min(100, (float) $this->itemDiscount['discount_percentage']));

        $discountAmount = round($baseUnitPrice * ($percentage / 100), 2);
        $finalUnitPrice = max(0, round($baseUnitPrice - $discountAmount, 2));
        $lineGrossAmount = round($finalUnitPrice * $qty, 2);

        if ((bool) $this->itemDiscount['vat_exempt_due_to_discount']) {
            $vatAmount = 0;
        } else {
            $netSale = $vatRate > 0 ? $lineGrossAmount / (1 + $vatRate) : $lineGrossAmount;
            $vatAmount = $lineGrossAmount - $netSale;
        }

        $this->itemDiscount['discount_percentage'] = round($percentage, 2);
        $this->itemDiscount['discount_amount'] = round($discountAmount, 2);
        $this->itemDiscount['final_unit_price'] = $finalUnitPrice;
        $this->itemDiscount['line_gross_amount'] = $lineGrossAmount;
        $this->itemDiscount['vat_amount'] = round($vatAmount, 2);
    }

    public function resetDiscountValues()
    {
        if (!$this->itemOrder) {
            return;
        }

        $qty = (float) $this->itemOrder->quantity_ordered;
        $baseUnitPrice = (float) $this->itemOrder->price_at_sale;
        $lineGrossAmount = round($baseUnitPrice * $qty, 2);
        $vatRate = ((float) $this->itemOrder->vat_rate) / 100;
        $netSale = $vatRate > 0 ? $lineGrossAmount / (1 + $vatRate) : $lineGrossAmount;
        $vatAmount = $lineGrossAmount - $netSale;

        $this->itemDiscount['discount_percentage'] = 0;
        $this->itemDiscount['discount_amount'] = 0;
        $this->itemDiscount['vat_exempt_due_to_discount'] = false;
        $this->itemDiscount['final_unit_price'] = round($baseUnitPrice, 2);
        $this->itemDiscount['line_gross_amount'] = round($lineGrossAmount, 2);
        $this->itemDiscount['vat_amount'] = round($vatAmount, 2);
    }

    public function resetIfEmpty()
    {
        if (
            $this->itemDiscount['discount_percentage'] === '' ||
            $this->itemDiscount['discount_amount'] === ''
        ) {
            $this->resetDiscountValues();
        }
    }

    public function saveItemDiscount()
    {
        $validated = $this->validate([
            'itemDiscount.discount_type' => 'required',
            'itemDiscount.discount_percentage' => 'required|numeric|min:0|max:100',
            'itemDiscount.discount_amount' => 'required|numeric|min:0',
            'itemDiscount.final_unit_price' => 'required|numeric|min:0',
            'itemDiscount.notes' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $itemOrder = ItemOrder::findOrFail($this->itemOrder->id);

            $itemOrder->discount_type = $validated['itemDiscount']['discount_type'];
            $itemOrder->discount_percentage = round($validated['itemDiscount']['discount_percentage'], 2);
            $itemOrder->discount_amount = round($validated['itemDiscount']['discount_amount'], 2);
            $itemOrder->vat_exempt_due_to_discount = $this->itemDiscount['vat_exempt_due_to_discount'] ? 1 : 0;
            $itemOrder->final_unit_price = round($validated['itemDiscount']['final_unit_price'], 2);
            $itemOrder->notes = $validated['itemDiscount']['notes'] ?? null;
            $itemOrder->save();

            if (
                $validated['itemDiscount']['discount_type'] !== 'none' &&
                $validated['itemDiscount']['discount_type'] !== 'custom' &&
                !empty($this->itemDiscount['discount_id'])
            ) {
                $itemOrder->item_order_discounts()->sync([$this->itemDiscount['discount_id']]);
            } else {
                $itemOrder->item_order_discounts()->sync([]);
            }

            // Optional: comment this out if logger causes issues
            // SimulationLogger::log(
            //     action: 'item_discount_updated',
            //     subject: $itemOrder,
            //     properties: [
            //         'item_order_id' => $itemOrder->id,
            //     ]
            // );

            DB::commit();

            $this->order->refresh();
            $this->loadOrders();
            $this->calculateOrderTotal();
            $this->closeDiscountModal();

            $this->dispatch('toast', message: 'Item discount saved!', type: 'success');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function percentDiscountClick($value)
    {
        $this->orderDiscount['discount_percentage'] = (float) $value;
        $this->orderDiscount['discount_amount'] = 0;
        $this->orderDiscount['discount_type'] = 'manual';

        $this->calculateOrderDiscount((float) $value, 'percentage', true);
    }

    public function updatedOrderDiscountDiscountPercentage($value)
    {
        if ($value !== null && $value !== '') {
            $this->orderDiscount['discount_percentage'] = (float) $value;
            $this->orderDiscount['discount_amount'] = 0;
            $this->orderDiscount['discount_type'] = 'manual';

            $this->calculateOrderDiscount((float) $value, 'percentage', true);
        }
    }

    public function updatedOrderDiscountDiscountAmount($value)
    {
        if ($value !== null && $value !== '') {
            $this->orderDiscount['discount_amount'] = (float) $value;
            $this->orderDiscount['discount_percentage'] = 0;
            $this->orderDiscount['discount_type'] = 'manual';

            $this->calculateOrderDiscount((float) $value, 'flat', true);
        }
    }

    public function updatedOrderDiscountType($value)
    {
        $this->orderDiscountType = $value;

        if ($value === '') {
            $this->reseetOrderDiscountValues();
            $this->orderDiscount['discount_type'] = 'none';
            return;
        }

        if ($value === 'custom') {
            $this->reseetOrderDiscountValues();
            $this->orderDiscount['discount_type'] = 'manual';
            return;
        }

        $orderDiscount = Discount::find($value);

        if (!$orderDiscount) {
            $this->dispatch('toast', type: 'error', message: 'Discount not found.');
            return;
        }

        $vatExempt = (bool) $orderDiscount->is_vat_exempt;
        $this->orderDiscount['discount_type'] = 'promo';

        if ($orderDiscount->discount_type === 'percentage') {
            $this->orderDiscount['discount_percentage'] = (float) $orderDiscount->discount_value;
            $this->orderDiscount['discount_amount'] = 0;
            $this->calculateOrderDiscount((float) $this->orderDiscount['discount_percentage'], 'percentage', $vatExempt);
        } else {
            $this->orderDiscount['discount_amount'] = (float) $orderDiscount->discount_value;
            $this->orderDiscount['discount_percentage'] = 0;
            $this->calculateOrderDiscount((float) $orderDiscount->discount_value, 'flat', $vatExempt);
        }
    }

    public function calculateOrderDiscount($value, $type, $vatExempt)
    {
        $this->itemOrderDiscount = [];
        $this->vat_sales = 0;
        $this->vat_exempt_sales = 0;
        $this->total_vat = 0;
        $this->total_discount = 0;
        $this->amount_due = 0;

        $this->order->refresh();
        $this->order->load('items');

        foreach ($this->order->items as $item) {
            if ($item->status !== 'completed') {
                continue;
            }

            $qty = (float) $item->quantity_ordered;
            $baseUnitPrice = (float) $item->final_unit_price;
            $lineTotal = $baseUnitPrice * $qty;

            if ($item->discount_type === 'none') {
                $discountAmount = $type === 'percentage'
                    ? round($baseUnitPrice * ((float) $value / 100), 2)
                    : round((float) $value, 2);

                if ($discountAmount > $baseUnitPrice) {
                    $discountAmount = $baseUnitPrice;
                }

                $finalUnitPrice = max(0, round($baseUnitPrice - $discountAmount, 2));
                $finalLineTotal = round($finalUnitPrice * $qty, 2);
                $vatRate = ((float) $item->vat_rate) / 100;

                if ($vatExempt) {
                    $vatAmount = 0;
                    $this->vat_exempt_sales += $finalLineTotal;
                } else {
                    $netSale = $vatRate > 0 ? $finalLineTotal / (1 + $vatRate) : $finalLineTotal;
                    $vatAmount = $finalLineTotal - $netSale;
                    $this->vat_sales += $netSale;
                }

                $this->itemOrderDiscount[] = [
                    'id' => $item->id,
                    'discount_amount' => round($discountAmount, 2),
                    'final_unit_price' => round($finalUnitPrice, 2),
                    'vat_exempt_due_to_discount' => $vatExempt ? 1 : 0,
                ];

                $this->total_discount += $discountAmount;
                $this->total_vat += $vatAmount;
                $this->amount_due += $finalLineTotal;
            } else {
                $discountAmount = (float) ($item->discount_amount ?? 0);
                $finalLineTotal = (float) $item->final_unit_price * $qty;
                $vatRate = ((float) $item->vat_rate) / 100;

                if ((bool) $item->vat_exempt_due_to_discount) {
                    $this->vat_exempt_sales += $finalLineTotal;
                } else {
                    $netSale = $vatRate > 0 ? $finalLineTotal / (1 + $vatRate) : $finalLineTotal;
                    $vatAmount = $finalLineTotal - $netSale;
                    $this->vat_sales += $netSale;
                    $this->total_vat += $vatAmount;
                }

                $this->total_discount += $discountAmount;
                $this->amount_due += $finalLineTotal;
            }
        }

        if ((float) $this->service_charge > 0) {
            $this->applyServiceCharge((float) $this->service_charge);
        }

        $this->vat_sales = round($this->vat_sales, 2);
        $this->vat_exempt_sales = round($this->vat_exempt_sales, 2);
        $this->total_vat = round($this->total_vat, 2);
        $this->total_discount = round($this->total_discount, 2);
        $this->amount_due = round($this->amount_due, 2);
    }

    public function applyServiceCharge($value)
    {
        $baseVatSales = (float) $this->vat_sales;
        $serviceChargeRate = ((float) $value) / 100;
        $serviceCharge = $baseVatSales * $serviceChargeRate;
        $serviceChargeVat = $serviceCharge * 0.12;

        $this->service_charge = (float) $value;
        $this->service_charge_amount = round($serviceCharge, 2);
        $this->service_charge_vat_amount = round($serviceChargeVat, 2);

        $this->total_vat += $this->service_charge_vat_amount;
        $this->amount_due += $this->service_charge_amount + $this->service_charge_vat_amount;

        $this->total_vat = round($this->total_vat, 2);
        $this->amount_due = round($this->amount_due, 2);
    }

    public function reseetOrderDiscountValues()
    {
        $this->orderDiscount['discount_percentage'] = (float) ($this->order->order_discount_percentage ?? 0);
        $this->orderDiscount['discount_amount'] = (float) ($this->order->order_discount_amount ?? 0);
        $this->orderDiscount['discount_type'] = $this->order->discount_type ?? 'none';
        $this->orderDiscountType = '';
        $this->calculateOrderTotal();
    }

    public function updatedServiceCharge($value)
    {
        $this->calculateOrderTotal();

        if ((float) $value > 0) {
            $this->applyServiceCharge((float) $value);
        }
    }

    public function applyOrderDiscountType($type)
    {
        if ($type === 'clear') {
            $this->orderDiscount['discount_percentage'] = 0;
            $this->orderDiscount['discount_amount'] = 0;
            $this->orderDiscount['discount_type'] = 'none';
            $this->orderDiscountType = '';
            $this->service_charge_amount = 0;
            $this->service_charge_vat_amount = 0;
            $this->calculateOrderTotal();
        }
    }

    public function applyOrderLevelDiscount()
        {
            $validated = $this->validate([
                'orderDiscount.discount_percentage' => 'required|numeric|min:0',
                'orderDiscount.discount_amount' => 'required|numeric|min:0',
                'orderDiscount.discount_type' => 'required',
                'total_vat' => 'required|numeric|min:0',
                'total_discount' => 'required|numeric|min:0',
                'amount_due' => 'required|numeric|min:0',
                'service_charge' => 'required|numeric|min:0',
                'service_charge_amount' => 'required|numeric|min:0',
                'service_charge_vat_amount' => 'required|numeric|min:0',
            ]);
        
            DB::beginTransaction();
        
            try {
                $order = Order::findOrFail($this->order->id);
        
                $subtotal = 0;
                $completedItems = $order->items()->where('status', 'completed')->get();
        
                foreach ($completedItems as $item) {
                    $subtotal += (float) $item->price_at_sale * (float) $item->quantity_ordered;
                }
        
                $order->subtotal_amount = round($subtotal, 2);
                $order->total_discount_amount = round($validated['total_discount'], 2);
                $order->discount_type = $validated['orderDiscount']['discount_type'];
                $order->order_discount_percentage = round($validated['orderDiscount']['discount_percentage'], 2);
                $order->order_discount_amount = round($validated['orderDiscount']['discount_amount'], 2);
                $order->notes = null;
                $order->service_charge_rate = round($validated['service_charge'], 2);
                $order->service_charge_amount = round($validated['service_charge_amount'], 2);
                $order->service_charge_vat_amount = round($validated['service_charge_vat_amount'], 2);
                $order->total_vat_amount = round($validated['total_vat'], 2);
                $order->total_amount = round($validated['amount_due'], 2);
                $order->save();
        
                foreach ($this->itemOrderDiscount as $itemOrderData) {
                    $item = ItemOrder::find($itemOrderData['id']);
        
                    if ($item) {
                        $item->discount_type = 'order';
                        $item->discount_amount = round($itemOrderData['discount_amount'], 2);
                        $item->final_unit_price = round($itemOrderData['final_unit_price'], 2);
                        $item->vat_exempt_due_to_discount = $itemOrderData['vat_exempt_due_to_discount'];
                        $item->save();
                    }
                }
        
                DB::commit();
        
                $this->dispatch('toast', type: 'success', message: 'Order-level discount applied!');
        
                return $this->redirectRoute('order.payment', ['order' => $order->id], navigate: true);
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                $this->dispatch('toast', type: 'error', message: $e->getMessage());
            }
        }
}