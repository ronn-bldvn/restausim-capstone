<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\UnitOfMeasurement;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

#[Layout('layouts.app')]
#[Title('Inventory Management')]
class InventoryRestock extends Component
{
    public $category;
    public $showSelectModal = false;
    public $selectedInventories = [];
    public function openSelectModal()
    {
        $this->showSelectModal = true;
    }
    public function closeSelectModal()
    {
        $this->showSelectModal = false;
    }
    public function switchTabs($id)
    {
        if ($id !== '') {
            $this->category = $id;
        } else {
            $this->category = '';
        }
    }
    public function addInventory($id)
    {

        $match = array_search($id, array_column($this->selectedInventories, 'id'));

        if ($match === false) {
            $inventoryData = Inventory::with(['inventoryUnit', 'costUnit'])->findOrFail($id);
            $this->selectedInventories[] =
                [
                    'id' => $inventoryData->id,
                    'image' => $inventoryData->image,
                    'name' => $inventoryData->name,
                    'cost_per_unit' => $inventoryData->cost_per_unit,
                    'quantity' => $inventoryData->quantity_on_hand,
                    'unit_of_measurement' => $inventoryData->inventoryUnit->symbol,
                    'unit_category' => $inventoryData->inventoryUnit->category,
                    'addQuantity' => '',
                    'addUnitOfMeasurement' => $inventoryData->inventoryUnit->symbol,
                ];
        } else {
            $this->selectedInventories = array_values(array_filter($this->selectedInventories, fn($item) => $item['id'] !== $id));
        }
    }
    public function restock()
    {
        $validated = $this->validate([
            'selectedInventories.*.id' => 'required|exists:inventories,id',
            'selectedInventories.*.addQuantity' => 'required|numeric|gt:0',
            'selectedInventories.*.unit_of_measurement' => 'required|exists:unit_of_measurements,symbol',
            'selectedInventories.*.unit_category' => 'required|exists:unit_of_measurements,category',
            'selectedInventories.*.addUnitOfMeasurement' => 'required|exists:unit_of_measurements,symbol'
        ]);
    
        DB::beginTransaction();
    
        try {
            foreach ($validated['selectedInventories'] as $inventoryItem) {
                if ($inventoryItem['unit_category'] === 'weight') {
                    $restockQuantity = new Mass($inventoryItem['addQuantity'], $inventoryItem['addUnitOfMeasurement']);
                    $finalQuantity = $restockQuantity->toUnit($inventoryItem['unit_of_measurement']);
                } elseif ($inventoryItem['unit_category'] === 'volume') {
                    $restockQuantity = new Volume($inventoryItem['addQuantity'], $inventoryItem['addUnitOfMeasurement']);
                    $finalQuantity = $restockQuantity->toUnit($inventoryItem['unit_of_measurement']);
                } else {
                    $finalQuantity = $inventoryItem['addQuantity'];
                }
    
                $inventory = Inventory::findOrFail($inventoryItem['id']);
                $inventory->increment('quantity_on_hand', $finalQuantity);
                $inventory->refresh();
    
                SimulationLogger::log(
                    action: 'inventory.restocked',
                    roleName: 'manager',
                    subject: $inventory,
                    properties: [
                        'name' => $inventory->name,
                        'added_quantity_input' => $inventoryItem['addQuantity'],
                        'added_unit_input' => $inventoryItem['addUnitOfMeasurement'],
                        'converted_quantity_added' => $finalQuantity,
                        'stock_unit' => $inventoryItem['unit_of_measurement'],
                        'new_quantity_on_hand' => $inventory->quantity_on_hand,
                    ]
                );
            }
    
            DB::commit();
    
            $this->dispatch('activityLogged');
            $this->reset(['selectedInventories']);
            $this->dispatch('toast', message: 'Inventory item restocked successfully!', type: 'success');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('toast', message: 'Something went wrong. Please try again.', type: 'error');
        }
    }
    public function render()
    {
        $active = $this->category;
        $units = UnitOfMeasurement::all()->groupBy('category');
        $categories = InventoryCategory::all();
        $selectedInventories = $this->selectedInventories;
        $inventories = $this->category ?
            Inventory::with(['category', 'inventoryUnit', 'costUnit'])
            ->where('inventory_category_id', $this->category)
            ->get()
            :
            Inventory::with(['category', 'inventoryUnit', 'costUnit'])
            ->get();

        return view('livewire.inventory.inventory-restock')
            ->with(compact('inventories', 'categories', 'selectedInventories', 'active', 'units'))
            ->layoutData([
                'headerTitle' => 'Restock Inventory Item',
            ]);
    }
}
