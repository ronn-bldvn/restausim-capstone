<?php

namespace App\Livewire\Discount;

use App\Models\Discount;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DiscountIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $deleteId;
    public $showItemsModal = false;
    public $discountItems = [];

    public function openItemsModal($id)
    {
        $this->showItemsModal = true;
        $this->discountItems = [];

        $discount = Discount::with(['menu_item_discounts'])->findOrFail($id);

        foreach ($discount->menu_item_discounts as $item) {
            $this->discountItems[] = $item;
        }
    }

    public function closeItemsModal()
    {
        $this->reset(['showItemsModal', 'discountItems']);
    }

    public function openDeleteModal($id)
    {
        $this->showDeleteModal = true;
        $this->deleteId = $id;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function deleteDiscount()
    {
        DB::beginTransaction();

        try {
            $discount = Discount::findOrFail($this->deleteId);

            SimulationLogger::log(
                action: 'deleted discount',
                roleName: 'manager',
                subject: $discount,
                properties: [
                    'discount_id' => $discount->id,
                    'discount_name' => $discount->name ?? null,
                ]
            );

            $discount->delete();

            DB::commit();

            $this->closeDeleteModal();

            $this->dispatch(
                'toast',
                message: 'Discount deleted successfully!',
                type: 'success'
            );

        } catch (Exception $e) {

            DB::rollBack();

            SimulationLogger::log(
                action: 'failed to delete discount',
                roleName: 'manager',
                subject: null,
                properties: [
                    'discount_id' => $this->deleteId,
                    'error' => $e->getMessage(),
                ]
            );

            $this->dispatch(
                'toast',
                message: 'Failed to delete discount!',
                type: 'error'
            );
        }
    }

    public function render()
    {
        $discounts = Discount::paginate(10);

        return view('livewire.discount.discount-index')
            ->with(compact('discounts'));
    }
}