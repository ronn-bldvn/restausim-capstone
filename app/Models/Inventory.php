<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;
use App\Models\UnitOfMeasurement;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class Inventory extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'name',
        'image',
        'opening_quantity',
        'quantity_on_hand',
        'unit_cost',
        'par_level',
        'inventory_category_id',
        'inventory_unit_id',
        'cost_unit_id',
    ];

    protected $appends = [
        'cost_per_unit'
    ];

    public function getCostPerUnitAttribute(){
        return $this->unit_cost . '/' . $this->costUnit->symbol;
    }

    public function computeCostPerUnit(string $baseUnit, string $unitCategory){
        if($unitCategory === 'weight'){
            $mass = new Mass(1, $this->costUnit->symbol);
            $baseQuantity = $mass->toUnit($baseUnit);
        }
        elseif($unitCategory === 'volume'){
            $mass = new Volume(1, $this->costUnit->symbol);
            $baseQuantity = $mass->toUnit($baseUnit);
        }
        elseif($unitCategory === 'count'){
            return $this->unit_cost;
        }

        // dd($this->unit_cost, $baseQuantity, $this->unit_cost/$baseQuantity);

        return (int) $this->unit_cost / $baseQuantity;
        // return round((int) $this->unit_cost / $baseQuantity, 2);
    }

    public function deductQuantityOnHand($quantity, $symbol, $category){
        if($category === 'weight'){
            $qtyToDeduct = new Mass($quantity, $symbol);
            $finalQtyToDeduct = $qtyToDeduct->toUnit($this->inventoryUnit->symbol);
        }
        elseif($category === 'volume'){
            $qtyToDeduct = new Volume($quantity, $symbol);
            $finalQtyToDeduct = $qtyToDeduct->toUnit($this->inventoryUnit->symbol);
        }
        elseif($category === 'count'){
            $finalQtyToDeduct = $quantity;
        }

        if($this->quantity_on_hand < $finalQtyToDeduct){
            throw new \DomainException('Insufficient stock');
        }

        $this->decrement('quantity_on_hand', $finalQtyToDeduct);
    }

    // RELATIONSHIPS

    public function category(){
        return $this->belongsTo(
            InventoryCategory::class,
            'inventory_category_id',
            'id'
        );
    }

    public function inventoryUnit(){
        return $this->belongsTo(
            UnitOfMeasurement::class,
            'inventory_unit_id',
            'id'
        );
    }
    public function costUnit(){
        return $this->belongsTo(
            UnitOfMeasurement::class,
            'cost_unit_id',
            'id'
        );
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // public function unitOfMeasurement()
    // {
    //     return $this->belongsTo(UnitOfMeasurement::class);
    // }

}
