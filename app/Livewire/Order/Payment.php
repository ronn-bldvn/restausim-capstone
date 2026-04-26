<?php

namespace App\Livewire\Order;

use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Payment as ModelsPayment;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Payment extends Component
{
    public $order;
    public $orders;
    public $subtotal;
    public $total_discount;
    public $total_vat;
    public $amount_due;
    public $currentTab = 'cash';
    public $payment_method = 'cash';
    public $paymentData = [
        [
            'method' => 'cash',
            'type' => 'cash',
            'amount' => 0,
            'recieved_amount' => 0,
            'change' => 0,
        ]
    ];
    public $totalPayment = 0;
    public $isSplitBill = false;
    public $showSelectModal = false;
    public $paymentIndex;
    public $splitData = [];
    public $showReceiptModal = false;
    public $receipt = [];
    public $splitReceipts = [];
    public $currentReceiptIndex = 0;
    // CASH
    public function mount(Order $order)
{
    $this->order = $order->load([
        'table',
        'combinedTable.tables',
        'items.item',
        'items.customizations.customization.inventory',
        'items.customizations.customization.ingredient.inventory',
    ]);

    $this->amount_due = (float) $this->order->total_amount;
    $this->total_discount = (float) $this->order->total_discount_amount;
    $this->total_vat = (float) $this->order->total_vat_amount;

    $this->subtotal = (float) $this->order->subtotal_amount;

    // Fallback if subtotal_amount in DB is still zero
    if ($this->subtotal <= 0) {
        $this->subtotal = $this->amount_due + $this->total_discount;
    }

    $this->totalPayment = $this->amount_due;
    $this->paymentData[0]['amount'] = $this->amount_due;

    $this->orders = [];

    foreach ($this->order->items as $item) {
        if ($item->status === 'completed') {
            $customizations = [];

            foreach ($item->customizations as $customization) {
                if ($item->id === $customization->item_order_id) {
                    $customizations[] = [
                        'id' => $customization->customization->id,
                        'name' => $customization->customization->inventory->name
                            ?? 'No ' . $customization->customization->ingredient->inventory->name,
                        'quantity' => $customization->customization->quantity_used,
                        'price' => $customization->customization->price,
                        'type' => $customization->customization->action,
                    ];
                }
            }

            $this->orders[] = [
                'uid' => $item->id,
                'menu_item_id' => $item->menu_item_id,
                'menu_name' => $item->item->name,
                'customizations' => $customizations,
                'quantity' => $item->quantity_ordered,
                'discount_amount' => $item->discount_amount,
                'price_at_sale' => $item->price_at_sale,
                'total_item_amount' => $item->total_item_amount,
            ];
        }
    }
}
    public function switchTab($value)
    {
        $this->currentTab = $value;
        $this->paymentData = [];
        if ($value === 'cash') {
            $this->paymentData[] = [
                'method' => 'cash',
                'type' => 'cash',
                'amount' => $this->amount_due,
                'recieved_amount' => 0,
                'change' => 0,
            ];
            $this->totalPayment = $this->amount_due;
            $this->payment_method = $value;
        } elseif ($value === 'debit') {
            $this->paymentData[] = [
                'method' => 'card',
                'type' => 'card',
                'card_type' => 'visa',
                'payment_method' => 'credit',
                'bank' => 'bdo',
                'auth_code' => null,
                'ref_number' => null,
                'amount' => $this->amount_due,
            ];
            $this->totalPayment = $this->amount_due;
            $this->payment_method = 'card';
        } elseif ($value === 'split') {
            $this->totalPayment = 0;
            $this->payment_method = $value;
        }
    }
    public function addCashAmount($value)
    {
        $this->paymentData[0]['recieved_amount'] += (int) $value;
        $this->paymentData[0]['change'] = round((int) $this->paymentData[0]['recieved_amount'] - $this->amount_due, 2);
        if ($this->paymentData[0]['change'] <= 0) {
            $this->paymentData[0]['change'] = 0;
        }
    }
    public function clearCashAmount()
    {
        $this->paymentData[0]['recieved_amount'] = 0;
        $this->paymentData[0]['change'] = 0;
    }
    public function updatedPaymentData($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === "recieved_amount") {
            if ($this->paymentData[$index]['method'] === 'cash') {
                $this->paymentData[0]['change'] = round((int) $this->paymentData[0]['recieved_amount'] - $this->amount_due, 2);
                if ($this->paymentData[0]['change'] <= 0) {
                    $this->paymentData[0]['change'] = 0;
                }
            } elseif ($this->paymentData[$index]['method'] === 'split') {
                $this->paymentData[$index]['change'] = round((int) $this->paymentData[$index]['recieved_amount'] - (int) $this->paymentData[$index]['amount'], 2);
                if ($this->paymentData[$index]['change'] <= 0) {
                    $this->paymentData[$index]['change'] = 0;
                }
            }
        }

        if ($field === 'amount' && ($value !== null && $value !== '')) {
            $oldPayment = $this->totalPayment;
            $this->calculateTotalPayment();
            if ($this->totalPayment > $this->amount_due) {
                $excess_payment = $this->totalPayment - $this->amount_due;
                $this->totalPayment = $this->amount_due;
                $this->paymentData[$index]['amount'] -= $excess_payment;
                $this->paymentData[$index]['amount'] = round($this->paymentData[$index]['amount'], 2);
            }
        }
    }
    // SPLIT
    public function addSplitPayment($type)
    {
        if ($type === 'cash') {
            $this->paymentData[] = [
                'method' => 'split',
                'type' => 'cash',
                'amount' => 0,
                'recieved_amount' => 0,
                'change' => 0,
            ];
        } elseif ($type === 'debit') {
            $this->paymentData[] = [
                'method' => 'split',
                'type' => 'card',
                'card_type' => 'visa',
                'payment_method' => 'credit',
                'bank' => 'bdo',
                'auth_code' => null,
                'ref_number' => null,
                'amount' => 0,
            ];
        }

        $this->splitData[] = [];

        // dd($this->paymentData, $this->splitData);
    }
    public function removeSplitPayment($index)
    {
        unset($this->paymentData[$index]);
        unset($this->splitData[$index]);
        $this->paymentData = array_values($this->paymentData);
        $this->splitData = array_values($this->splitData);
    }
    public function updatedIsSplitBill($value) {}
    public function openSelectModal($index)
    {
        $this->showSelectModal = true;
        $this->paymentIndex = $index;
    }
    public function closeSelectModal()
    {
        $this->reset('showSelectModal');
    }
    public function updatedSplitData($value, $key)
    {
        [$paymentIndex, $orderIndex] = explode('.', $key);
        // dd($this->paymentIndex, (int) $paymentIndex);
        // if($value !== ''){
        //     foreach($this->splitData as $index => $split){
        //         $item_order = $this->order->items->find($value);
        //         if($index !== (int) $paymentIndex && in_array($value, $split)){
        //             dd('match');
        //         }
        //         else{
        //             $this->paymentData[$paymentIndex]['amount'] =+ $item_order->line_gross_amount;
        //         }
        //     }
        // }
        // else{
        //     foreach($this->splitData as $index => $split){
        //         $item_order = $this->order->items->find($split);
        //         dd($item_order);
        //         $this->paymentData[$paymentIndex]['amount'] =+ $item_order->line_gross_amount;
        //     }
        // }
        $this->paymentData[$paymentIndex]['amount'] = 0;
        foreach ($this->splitData[$paymentIndex] as $index => $split) {
            $item_order = $this->order->items()->find($split);
            // dd($split);
            $this->paymentData[$paymentIndex]['amount'] += $item_order->line_gross_amount;
        }

        $this->calculateTotalPayment();
    }
    public function calculateTotalPayment()
    {
        $this->totalPayment = 0;
        foreach ($this->paymentData as $payment) {
            $this->totalPayment += $payment['amount'];
        }
    }
    public function finishPayment()
    {
        // dd($this->paymentData);
        // dd($this->paymentData, $this->splitData);

        // fix validation for split payments
        if ($this->totalPayment == $this->amount_due) {
            $validated = $this->validate([
                'paymentData.*.type' => 'required',
                'paymentData.*.amount' => 'required',
                // CASH
                'paymentData.*.recieved_amount' => 'nullable',
                // CARD
                'paymentData.*.card_type' => 'nullable',
                'paymentData.*.payment_method' => 'nullable',
                'paymentData.*.bank' => 'nullable',
                'paymentData.*.auth_code' => 'nullable',
                'paymentData.*.ref_number' => 'nullable',
            ]);

            // dd($this->payment_method);

            DB::beginTransaction();
            try {
                if ($this->payment_method === 'cash') {
                    $payment = ModelsPayment::create([
                        'order_id' => $this->order->id,
                        'cashier_id' => Auth::id(),
                        'payment_method' => 'cash',
                        'amount' => $validated['paymentData'][0]['amount'],
                        'cash_recieved' => $validated['paymentData'][0]['recieved_amount'],
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);

                    foreach ($this->order->items as $item) {
                        $itemOrderData[$item->id] = [
                            'quantity' => $item->quantity_ordered
                        ];
                    }

                    $payment->item_orders()->sync($itemOrderData);
                } elseif ($this->payment_method === 'card') {
                    $payment = ModelsPayment::create([
                        'order_id' => $this->order->id,
                        'cashier_id' => Auth::id(),
                        'payment_method' => $validated['paymentData'][0]['payment_method'],
                        'amount' => $validated['paymentData'][0]['amount'],
                        'card_type' => $validated['paymentData'][0]['card_type'],
                        'bank' => $validated['paymentData'][0]['bank'],
                        'authorization_code' => $validated['paymentData'][0]['auth_code'],
                        'reference_number' => $validated['paymentData'][0]['ref_number'],
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);

                    foreach ($this->order->items as $item) {
                        $itemOrderData[$item->id] = [
                            'quantity' => $item->quantity_ordered
                        ];
                    }

                    $payment->item_orders()->sync($itemOrderData);
                } elseif ($this->payment_method === 'split') {
                    foreach ($validated['paymentData'] as $index => $payment) {
                        if ($payment['type'] === 'cash') {
                            $payment = ModelsPayment::create([
                                'order_id' => $this->order->id,
                                'cashier_id' => Auth::id(),
                                'payment_method' => 'cash',
                                'amount' => $payment['amount'],
                                'cash_recieved' => $payment['recieved_amount'],
                                'status' => 'completed',
                                'paid_at' => now(),
                            ]);
                        } elseif ($payment['type'] === 'card') {
                            $payment = ModelsPayment::create([
                                'order_id' => $this->order->id,
                                'cashier_id' => Auth::id(),
                                'payment_method' => $payment['payment_method'],
                                'amount' => $payment['amount'],
                                'card_type' => $payment['card_type'],
                                'bank' => $payment['bank'],
                                'authorization_code' => $payment['auth_code'],
                                'reference_number' => $payment['ref_number'],
                                'status' => 'completed',
                                'paid_at' => now(),
                            ]);
                        }

                        $syncData = [];
                        foreach ($this->splitData[$index] as $split) {
                            $item_order_data = ItemOrder::find($split);

                            $syncData[$item_order_data->id] = ['quantity' => $item_order_data->quantity_ordered];
                        }

                        $payment->item_orders()->sync($syncData);
                    }
                }
                // foreach($validated['paymentData'] as $payment){
                //     if($payment['method'] === 'cash'){

                //     }
                //     elseif($payment['method'] === 'card'){

                //     }
                //     elseif($payment['method'] === 'split'){

                //     }
                // }

                if ($this->order->checkPayment()) {
                    $this->order->update([
                        'status' => 'completed',
                        'payment_status' => 'paid',
                    ]);
                
                    $singleTable = $this->order->table()->first();
                    if ($singleTable) {
                        $singleTable->update(['status' => 'dirty']);
                    }
                
                    $combinedTable = $this->order->combinedTable()->with('tables')->first();
                    if ($combinedTable) {
                        $combinedTable->update(['status' => 'dirty']);
                
                        foreach ($combinedTable->tables as $table) {
                            $table->update(['status' => 'dirty']);
                        }
                    }
                }

                // dd('successful?');
                SimulationLogger::log(
                    action: 'payment_completed',
                    roleName: 'cashier',
                    subject: $this->order,
                    properties: [
                        'order_id' => $this->order->id,
                        'table_name' => optional($this->order->table)->name
                            ?? optional($this->order->table)->table_code,
                        'payment_type' => $this->payment_method,
                        'total_due' => $this->amount_due,
                        'total_paid' => $this->totalPayment,
                        'performed_by' => Auth::id(),
                        'cashier_name' => Auth::user()?->name,
                        'role' => Auth::user()?->getRoleNames()?->first(),

                        'payment_breakdown' => collect($validated['paymentData'])->map(function ($p) {
                            return [
                                'type' => $p['type'],
                                'method' => $p['payment_method'] ?? 'cash',
                                'amount' => $p['amount'],
                                'received' => $p['recieved_amount'] ?? null,
                                'change' => isset($p['recieved_amount'])
                                    ? max(0, ($p['recieved_amount'] - $p['amount']))
                                    : 0,
                                'card_type' => $p['card_type'] ?? null,
                                'bank' => $p['bank'] ?? null,
                            ];
                        })->toArray(),
                    ]
                );
                $this->dispatch('activityLogged');
                DB::commit();
                if ($this->payment_method === 'split') {
                    $this->splitReceipts = [];
                    foreach ($validated['paymentData'] as $i => $p) {
                        $this->splitReceipts[] = [
                            'title' => 'OFFICIAL RECEIPT (SIMULATION)',
                            'restaurant' => 'Sample Restaurant',
                            'address' => '123 Demo Street',
                            'order_number' => $this->order->id,
                            'order_type' => $this->order->type ?? 'dine-in',
                            'table_name' => optional($this->order->table)->name ?? optional($this->order->table)->table_code,
                            'cashier_name' => Auth::user()->name ?? 'Cashier',
                            'datetime' => now()->toDateTimeString(),
                            'items' => $this->orders,
                            'subtotal' => $this->subtotal,
                            'discount' => $this->total_discount,
                            'grand_total' => $this->amount_due,
                            'assigned_amount' => $p['amount'],
                            'payment_method' => $p['type'] === 'cash' ? 'cash' : ($p['payment_method'] ?? 'card'),
                            'amount_paid' => $p['type'] === 'cash' ? ($p['recieved_amount'] ?? 0) : $p['amount'],
                            'change' => $p['type'] === 'cash' ? max(0, ($p['recieved_amount'] ?? 0) - $p['amount']) : 0,
                            'split_index' => $i + 1,
                            'split_total' => count($validated['paymentData']),
                        ];
                    }
                    $this->currentReceiptIndex = 0;
                    $this->showReceiptModal = true;
                } else {
                    $pm = $this->payment_method === 'cash' ? 'cash' : ($this->payment_method === 'card' ? ($validated['paymentData'][0]['payment_method'] ?? 'card') : 'other');
                    $paid = $this->payment_method === 'cash' ? ($validated['paymentData'][0]['recieved_amount'] ?? 0) : ($validated['paymentData'][0]['amount'] ?? 0);
                    $chg = $this->payment_method === 'cash' ? max(0, $paid - $this->amount_due) : 0;
                    $this->receipt = [
                        'title' => 'OFFICIAL RECEIPT (SIMULATION)',
                        'restaurant' => 'Sample Restaurant',
                        'address' => '123 Demo Street',
                        'order_number' => $this->order->id,
                        'order_type' => $this->order->type ?? 'dine-in',
                        'table_name' => optional($this->order->table)->name ?? optional($this->order->table)->table_code,
                        'cashier_name' => Auth::user()->name ?? 'Cashier',
                        'datetime' => now()->toDateTimeString(),
                        'items' => $this->orders,
                        'subtotal' => $this->subtotal,
                        'discount' => $this->total_discount,
                        'grand_total' => $this->amount_due,
                        'payment_method' => $pm,
                        'amount_paid' => $paid,
                        'change' => $chg,
                    ];
                    $this->showReceiptModal = true;
                }
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
            }
        }
    }
    public function setReceiptIndex($index)
    {
        $this->currentReceiptIndex = $index;
    }
    public function nextReceipt()
    {
        if (($this->currentReceiptIndex + 1) < count($this->splitReceipts)) {
            $this->currentReceiptIndex++;
        }
    }
    public function closeReceipt()
    {
        $this->showReceiptModal = false;
        $this->redirectRoute('floorplan.index');
    }
    public function render()
    {
        return view('livewire.order.payment');
    }
}

