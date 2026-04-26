<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Services\SimulationLogger;
use Livewire\Attributes\Title;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Inventory Management')]
class InventoryList extends Component
{
    use WithPagination;
    public $category;
    public $showDeleteModal = false;
    public $deleteId;
    public $search = '';          // ✅ add
    protected $queryString = [    // ✅ optional: keeps search in URL
        'category' => ['except' => ''],
        'search'   => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
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
    public function deleteInventory()
    {
        DB::beginTransaction();
    
        try {
            $inventory = Inventory::findOrFail($this->deleteId);
    
            $inventoryData = [
                'name' => $inventory->name,
                'code' => $inventory->code,
                'inventory_category_id' => $inventory->inventory_category_id,
            ];
    
            $inventory->delete();
    
            SimulationLogger::log(
                'inventory.deleted',
                'manager',
                $inventory,
                [
                    'name' => $inventory->name,
                    'code' => $inventory->code,
                    'category_id' => $inventory->inventory_category_id
                ]
            );
    
            DB::commit();
    
            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Inventory item deleted successfully!', type: 'success');
    
            $this->showDeleteModal = false;
            $this->deleteId = null;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
    
            $this->dispatch('toast', message: 'Failed to delete inventory item.', type: 'error');
        }
    }
    public function render()
    {
        $active     = $this->category;
        $categories = InventoryCategory::all();

        $inventories = Inventory::with(['category', 'inventoryUnit', 'costUnit'])
            ->when(
                $this->category,
                fn($q) =>
                $q->where('inventory_category_id', $this->category)
            )
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';

                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', $s)
                        ->orWhere('code', 'like', $s);
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $headerTitle = $this->category
            ? 'Inventory - ' . optional($categories->firstWhere('id', $this->category))->name
            : 'Inventory Management';

        return view('livewire.inventory.inventory-list')
            ->with(compact('inventories', 'categories', 'active'))
            ->layoutData(['headerTitle' => $headerTitle]);
    }
}