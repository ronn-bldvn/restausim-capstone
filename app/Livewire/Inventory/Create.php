<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\UnitOfMeasurement;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;
use Illuminate\Validation\ValidationException;
use App\Services\SimulationLogger;

#[Layout('layouts.app')]
#[Title('Inventory Management')]
class Create extends Component
{
    use WithFileUploads;

    public $storedUnits;
    public $unitCategory;

    public $inventoryDataToSave = [
        'name' => '',
        'image' => '',
        'opening_quantity' => '',
        'opening_quantity_unit_id' => '',
        'cost' => '',
        'cost_unit_id' => '',
        'par_level' => '',
        'inventory_category_id' => '',
        'inventory_unit_id' => '',
    ];

    public function mount()
    {
        $this->storedUnits = UnitOfMeasurement::all();
    }

    public function updatedInventoryDataToSaveInventoryUnitId($value)
    {
        foreach ($this->storedUnits as $unit) {
            if ($unit->id === (int) $value) {
                $this->unitCategory = $unit->category;
                break;
            }
        }

        $this->inventoryDataToSave['opening_quantity_unit_id'] = '';
        $this->inventoryDataToSave['cost_unit_id'] = '';
    }

    public function store()
    {
        $validated = $this->validate([
            'inventoryDataToSave.name' => 'required|string',
            'inventoryDataToSave.image' => 'required|image',
            'inventoryDataToSave.opening_quantity' => 'required|numeric|gt:0',
            'inventoryDataToSave.opening_quantity_unit_id' => 'required|exists:unit_of_measurements,id',
            'inventoryDataToSave.cost' => 'required|numeric|gt:0',
            'inventoryDataToSave.cost_unit_id' => 'required|exists:unit_of_measurements,id',
            'inventoryDataToSave.par_level' => 'required|numeric|gt:0',
            'inventoryDataToSave.inventory_category_id' => 'required|exists:inventory_categories,id',
            'inventoryDataToSave.inventory_unit_id' => 'required|exists:unit_of_measurements,id',
        ]);

        DB::beginTransaction();

        try {
            $imagePath = $validated['inventoryDataToSave']['image']->store('inventory_images', 'public');

            $inventoryUnit = $this->storedUnits->find($validated['inventoryDataToSave']['inventory_unit_id']);
            $openingUnit = $this->storedUnits->find($validated['inventoryDataToSave']['opening_quantity_unit_id']);

            if (!$inventoryUnit || !$openingUnit) {
                throw new Exception('Invalid unit selected.');
            }

            if ($inventoryUnit->category !== $openingUnit->category) {
                throw new Exception('Opening quantity unit must match the inventory unit category.');
            }

            if ($inventoryUnit->category === 'weight') {
                $openingQuantity = new Mass(
                    $validated['inventoryDataToSave']['opening_quantity'],
                    $openingUnit->symbol
                );
                $finalOpeningQuantity = $openingQuantity->toUnit($inventoryUnit->symbol);
            } elseif ($inventoryUnit->category === 'volume') {
                $openingQuantity = new Volume(
                    $validated['inventoryDataToSave']['opening_quantity'],
                    $openingUnit->symbol
                );
                $finalOpeningQuantity = $openingQuantity->toUnit($inventoryUnit->symbol);
            } elseif ($inventoryUnit->category === 'count') {
                $finalOpeningQuantity = $validated['inventoryDataToSave']['opening_quantity'];
            } else {
                throw new Exception('Unsupported unit category.');
            }

            $inventory = Inventory::create([
                'name' => $validated['inventoryDataToSave']['name'],
                'image' => $imagePath,
                'opening_quantity' => $finalOpeningQuantity,
                'quantity_on_hand' => $finalOpeningQuantity,
                'unit_cost' => $validated['inventoryDataToSave']['cost'],
                'cost_unit_id' => $validated['inventoryDataToSave']['cost_unit_id'],
                'par_level' => $validated['inventoryDataToSave']['par_level'],
                'inventory_category_id' => $validated['inventoryDataToSave']['inventory_category_id'],
                'inventory_unit_id' => $validated['inventoryDataToSave']['inventory_unit_id'],
            ]);

            SimulationLogger::log(
                'inventory.created',
                'manager',
                $inventory,
                [
                    'name' => $inventory->name,
                    'inventory_category_id' => $inventory->inventory_category_id,
                    'opening_quantity' => $inventory->opening_quantity,
                    'quantity_on_hand' => $inventory->quantity_on_hand,
                    'unit_cost' => $inventory->unit_cost,
                ]
            );

            DB::commit();

            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Inventory item created successfully!', type: 'success');

            $this->reset('inventoryDataToSave', 'unitCategory');

            $this->inventoryDataToSave = [
                'name' => '',
                'image' => '',
                'opening_quantity' => '',
                'opening_quantity_unit_id' => '',
                'cost' => '',
                'cost_unit_id' => '',
                'par_level' => '',
                'inventory_category_id' => '',
                'inventory_unit_id' => '',
            ];
        } catch (ValidationException $e) {
            DB::rollBack();

            $first = collect($e->validator->errors()->all())->first() ?? 'Validation error.';
            $this->dispatch('toast', message: $first, type: 'error');

            throw $e;
        } catch (Exception $e) {
            DB::rollBack();

            if (!empty($imagePath ?? null)) {
                Storage::disk('public')->delete($imagePath);
            }

            report($e);
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $filteredUnits = $this->unitCategory
            ? UnitOfMeasurement::where('category', $this->unitCategory)->get()
            : UnitOfMeasurement::all();

        $units = UnitOfMeasurement::all()->groupBy('category');
        $categories = InventoryCategory::all();

        return view('livewire.inventory.create')
            ->with(compact('units', 'filteredUnits', 'categories'))
            ->layoutData([
                'headerTitle' => 'Create Inventory Item',
            ]);
    }
}