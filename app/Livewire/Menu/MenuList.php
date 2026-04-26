<?php

namespace App\Livewire\Menu;

use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Services\SimulationLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Menu Management')]
class MenuList extends Component
{
    use WithPagination;

    public $category = '';
    public $showDeleteModal = false;
    public $deleteId = null;

    public function switchTabs($id)
    {
        $this->category = $id !== '' ? $id : '';
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

    public function deleteMenu()
    {
        DB::beginTransaction();

        try {
            $menu = MenuItem::with([
                'ingredients',
                'customizations',
            ])->findOrFail($this->deleteId);

            SimulationLogger::log(
                action: 'delete.menu.item',
                roleName: 'manager',
                subject: $menu,
                properties: [
                    'menu_item_id' => $menu->id,
                    'menu_item_name' => $menu->name,
                ]
            );

            // Delete related records first
            $menu->ingredients()->delete();
            $menu->customizations()->delete();

            // If you also have discounts or other related records, delete them too
            if (method_exists($menu, 'discounts')) {
                $menu->discounts()->detach();
            }

            if (method_exists($menu, 'menuItemDiscounts')) {
                $menu->menuItemDiscounts()->delete();
            }

            $menu->delete();

            DB::commit();

            $this->dispatch('activityLogged');
            $this->dispatch('toast', message: 'Menu item deleted successfully!', type: 'success');

            $this->closeDeleteModal();
        } catch (Exception $e) {
            DB::rollBack();

            $this->dispatch('toast', message: 'Failed to delete menu item: ' . $e->getMessage(), type: 'error');
            report($e);
        }
    }

    public function render()
    {
        $active = $this->category;
        $categories = MenuItemCategory::all();

        $menuItems = MenuItem::with(['category', 'ingredients', 'customizations'])
            ->when($this->category, function ($query) {
                $query->where('menu_item_category_id', $this->category);
            })
            ->paginate(10);

        return view('livewire.menu.menu-list')
            ->with(compact('active', 'categories', 'menuItems'))
            ->layoutData([
                'headerTitle' => 'Menu Management',
            ]);
    }
}