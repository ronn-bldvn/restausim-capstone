<?php

namespace App\Livewire\Discount;

use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Models\UnitOfMeasurement;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DiscountEdit extends Component
{
    public $discount;
    public $name;
    public $type;
    public $discount_type;
    public $discount_value;
    public $is_vat_exempt;
    public $show_item_select = false;
    public $category;
    public $showSelectModal = false;
    public $selectedMenuItem = [];
    public $applies_to_data;
    public $discount_type_data;

    public function mount(Discount $discount)
    {
        $this->discount = $discount;
        $this->name = $discount->name;
        $this->type = $discount->type;
        $this->discount_type = $discount->discount_type;
        $this->discount_value = $discount->discount_value;
        $this->is_vat_exempt = $discount->is_vat_exempt ? true : false;

        if ($discount->type === 'menu_item') {
            $this->show_item_select = true;

            foreach ($this->discount->menu_item_discounts as $item) {
                $this->addInventory($item->id);
            }
        }

        $this->applies_to_data = collect([
            (object) [
                'id' => 'order',
                'name' => 'Applies to Whole Order'
            ],
            (object) [
                'id' => 'all_items',
                'name' => 'Applies to All items'
            ],
            (object) [
                'id' => 'menu_item',
                'name' => 'Applies to an item'
            ]
        ]);

        $this->discount_type_data = collect([
            (object) [
                'id' => 'amount',
                'name' => 'Amount (₱)'
            ],
            (object) [
                'id' => 'percentage',
                'name' => 'Percentage (%)'
            ]
        ]);
    }

    public function updatedType($value)
    {
        if ($value === 'menu_item') {
            $this->show_item_select = true;
        } else {
            $this->reset(['show_item_select', 'selectedMenuItem']);
        }
    }

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
        $match = array_search($id, array_column($this->selectedMenuItem, 'id'));

        if ($match === false) {
            $menuItemData = MenuItem::with(['ingredients', 'customizations'])->findOrFail($id);
            $this->selectedMenuItem[] = $menuItemData;
        } else {
            $this->selectedMenuItem = array_values(
                array_filter($this->selectedMenuItem, fn($item) => $item['id'] !== $id)
            );
        }
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'type' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'is_vat_exempt' => 'required|boolean'
        ]);

        DB::beginTransaction();

        try {
            $oldData = [
                'name' => $this->discount->name,
                'type' => $this->discount->type,
                'discount_type' => $this->discount->discount_type,
                'discount_value' => $this->discount->discount_value,
                'is_vat_exempt' => $this->discount->is_vat_exempt,
            ];

            $this->discount->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'is_vat_exempt' => $validated['is_vat_exempt'],
            ]);

            if ($validated['type'] === 'all_items') {
                $ids = MenuItem::pluck('id')->toArray();
                $this->discount->menu_item_discounts()->sync($ids);
            } elseif ($validated['type'] === 'menu_item') {
                $this->discount->menu_item_discounts()->sync(collect($this->selectedMenuItem)->pluck('id')->toArray());
            } else {
                $this->discount->menu_item_discounts()->sync([]);
            }

            DB::commit();

            SimulationLogger::log(
                action: 'updated discount',
                roleName: 'manager',
                subject: $this->discount->fresh(),
                properties: [
                    'old' => $oldData,
                    'new' => [
                        'name' => $validated['name'],
                        'type' => $validated['type'],
                        'discount_type' => $validated['discount_type'],
                        'discount_value' => $validated['discount_value'],
                        'is_vat_exempt' => $validated['is_vat_exempt'],
                        'menu_item_ids' => $validated['type'] === 'menu_item'
                            ? collect($this->selectedMenuItem)->pluck('id')->toArray()
                            : [],
                    ],
                ]
            );

            $this->dispatch('toast', message: 'Discount updated successfully!', type: 'success');
        } catch (Exception $e) {
            DB::rollBack();

            SimulationLogger::log(
                action: 'failed to update discount',
                roleName: 'manager',
                subject: $this->discount,
                properties: [
                    'discount_id' => $this->discount->id,
                    'error' => $e->getMessage(),
                ]
            );

            $this->dispatch('toast', message: 'Failed to update discount!', type: 'error');
        }
    }

    public function render()
    {
        $active = $this->category;
        $units = UnitOfMeasurement::all()->groupBy('category');
        $categories = MenuItemCategory::all();
        $inventories = $this->category
            ? MenuItem::with(['ingredients', 'customizations'])
                ->where('menu_item_category_id', $this->category)
                ->get()
            : MenuItem::with(['ingredients', 'customizations'])->get();

        return view('livewire.discount.discount-edit')
            ->with(compact('inventories', 'categories', 'active', 'units'));
    }
}