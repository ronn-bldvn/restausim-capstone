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
class DiscountCreate extends Component
{
    public $name;
    public $type;
    public $discount_type;
    public $discount_value;
    public $is_vat_exempt = false;
    public $show_item_select = false;
    public $category;
    public $showSelectModal = false;
    public $selectedMenuItem = [];
    public $applies_to_data;
    public $discount_type_data;

    public function mount()
    {
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

    public function save()
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
            $discount = Discount::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'is_vat_exempt' => $validated['is_vat_exempt'],
            ]);

            if ($validated['type'] === 'all_items') {
                $ids = MenuItem::pluck('id')->toArray();
                $discount->menu_item_discounts()->sync($ids);
            } elseif ($validated['type'] === 'menu_item') {
                $discount->menu_item_discounts()->sync(collect($this->selectedMenuItem)->pluck('id')->toArray());
            }

            DB::commit();

            SimulationLogger::log(
                action: 'created discount',
                roleName: 'manager',
                subject: $discount->fresh(),
                properties: [
                    'discount_id' => $discount->id,
                    'name' => $discount->name,
                    'type' => $discount->type,
                    'discount_type' => $discount->discount_type,
                    'discount_value' => $discount->discount_value,
                    'is_vat_exempt' => $discount->is_vat_exempt,
                    'menu_item_ids' => $validated['type'] === 'menu_item'
                        ? collect($this->selectedMenuItem)->pluck('id')->toArray()
                        : [],
                ]
            );

            $this->reset([
                'name',
                'type',
                'discount_type',
                'discount_value',
                'is_vat_exempt',
                'selectedMenuItem',
                'show_item_select',
                'category'
            ]);

            $this->dispatch('toast', message: 'Discount created successfully!', type: 'success');
        } catch (Exception $e) {
            DB::rollBack();

            SimulationLogger::log(
                action: 'failed to create discount',
                roleName: 'manager',
                subject: null,
                properties: [
                    'name' => $this->name,
                    'type' => $this->type,
                    'discount_type' => $this->discount_type,
                    'discount_value' => $this->discount_value,
                    'is_vat_exempt' => $this->is_vat_exempt,
                    'selected_menu_item_ids' => collect($this->selectedMenuItem)->pluck('id')->toArray(),
                    'error' => $e->getMessage(),
                ]
            );

            $this->dispatch('toast', message: 'Failed to create discount!', type: 'error');
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

        return view('livewire.discount.discount-create')
            ->with(compact('inventories', 'categories', 'active', 'units'));
    }
}