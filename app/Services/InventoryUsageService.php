<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\ItemOrder;
use Illuminate\Support\Facades\DB;

class InventoryUsageService
{
    /**
     * Deduct inventory + create ledger rows for an item order.
     * This assumes ItemOrder has relation: customizations()->with('customization')
     */
    public function consumeForItemOrder(ItemOrder $itemOrder): void
    {
        // 1) Base recipe consumption from ingredients table
        $baseIngredients = Ingredient::where('menu_item_id', $itemOrder->menu_item_id)->get();

        // inventory_id => qty
        $usage = [];

        foreach ($baseIngredients as $ing) {
            $qty = (float) $ing->quantity_used * (int) $itemOrder->quantity_ordered;
            $usage[$ing->inventory_id] = ($usage[$ing->inventory_id] ?? 0) + $qty;
        }

        // 2) Apply customizations (add/remove/replace)
        $itemOrder->loadMissing(['customizations.customization', 'customizations.customization.ingredient']);

        foreach ($itemOrder->customizations as $ioc) {
            $c = $ioc->customization; // MenuItemCustomization
            if (!$c) continue;

            $action = $c->action; // add/remove/replace
            $lineQty = (int) $itemOrder->quantity_ordered;

            // for "add", they can order multiple of the customization (quantity_ordered in item_order_customizations)
            $customQtyMultiplier = (int) ($ioc->quantity_ordered ?: 1);

            // how much this customization consumes per unit
            $cQtyUsed = (float) ($c->quantity_used ?? 0);

            if ($action === 'add') {
                // consumes added inventory
                if ($c->inventory_id) {
                    $qty = $cQtyUsed * $lineQty * $customQtyMultiplier;
                    $usage[$c->inventory_id] = ($usage[$c->inventory_id] ?? 0) + $qty;
                }
            }

            if ($action === 'remove') {
                // remove means subtract the base ingredient consumption (linked via ingredient_id)
                if ($c->ingredient_id) {
                    $base = $baseIngredients->firstWhere('id', $c->ingredient_id);
                    if ($base) {
                        $qty = (float) $base->quantity_used * $lineQty;
                        $usage[$base->inventory_id] = ($usage[$base->inventory_id] ?? 0) - $qty;
                    }
                }
            }

            if ($action === 'replace') {
                // replace = remove base ingredient + add new inventory
                if ($c->ingredient_id) {
                    $base = $baseIngredients->firstWhere('id', $c->ingredient_id);
                    if ($base) {
                        $qty = (float) $base->quantity_used * $lineQty;
                        $usage[$base->inventory_id] = ($usage[$base->inventory_id] ?? 0) - $qty;
                    }
                }
                if ($c->inventory_id) {
                    $qty = $cQtyUsed * $lineQty; // usually not multiplied by customization qty for replace
                    $usage[$c->inventory_id] = ($usage[$c->inventory_id] ?? 0) + $qty;
                }
            }
        }

        // 3) Clean negatives (don’t allow negative usage)
        foreach ($usage as $invId => $qty) {
            if ($qty <= 0) unset($usage[$invId]);
        }

        if (empty($usage)) return;

        // 4) Apply deductions atomically + insert ledger
        DB::transaction(function () use ($usage, $itemOrder) {
            foreach ($usage as $invId => $qtyUsed) {
                /** @var Inventory $inv */
                $inv = Inventory::where('id', $invId)->lockForUpdate()->first();

                // Optional: prevent negative stock
                // if ($inv->quantity_on_hand < $qtyUsed) throw new \Exception("Insufficient stock for {$inv->name}");

                $inv->quantity_on_hand = (float) $inv->quantity_on_hand - (float) $qtyUsed;
                $inv->save();

                InventoryTransaction::create([
                    'inventory_id' => $invId,
                    'type' => 'out',
                    'quantity' => $qtyUsed,
                    'unit_cost' => $inv->unit_cost,
                    'order_id' => $itemOrder->order_id,
                    'item_order_id' => $itemOrder->id,
                ]);
            }
        });
    }

    public function reverseForItemOrder(ItemOrder $itemOrder): void
    {
        // ✅ If already reversed before, stop (prevents double add)
        $alreadyVoided = InventoryTransaction::where('item_order_id', $itemOrder->id)
            ->where('type', 'void')
            ->exists();

        if ($alreadyVoided) {
            return;
        }

        // Get all "out" transactions for this item order
        $outs = InventoryTransaction::where('item_order_id', $itemOrder->id)
            ->where('type', 'out')
            ->get();

        if ($outs->isEmpty()) {
            // nothing was deducted (maybe never saved/confirmed)
            return;
        }

        // Group by inventory_id and sum quantities
        $grouped = $outs->groupBy('inventory_id')->map(fn($rows) => $rows->sum('quantity'));

        DB::transaction(function () use ($grouped, $itemOrder) {
            foreach ($grouped as $inventoryId => $qtyToReturn) {
                /** @var Inventory $inv */
                $inv = Inventory::where('id', $inventoryId)->lockForUpdate()->first();

                // 1️⃣ Add stock back
                $inv->quantity_on_hand = (float) $inv->quantity_on_hand + (float) $qtyToReturn;
                $inv->save();

                // 2️⃣ Log reversal
                InventoryTransaction::create([
                    'inventory_id' => $inventoryId,
                    'type' => 'void', // or 'return'
                    'quantity' => (float) $qtyToReturn,
                    'unit_cost' => $inv->unit_cost,
                    'order_id' => $itemOrder->order_id,
                    'item_order_id' => $itemOrder->id,
                ]);
            }
        });
    }
}
