<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\MenuItem;
use App\Models\Ingredient;
use App\Models\MenuItemCustomization;
use Illuminate\Database\Seeder;

class MenuItemIngredientsBackfillSeeder extends Seeder
{
    public function run(): void
    {
        $invPools = $this->buildInventoryPools();

        $targets = MenuItem::with('category', 'ingredients')
            ->whereDoesntHave('ingredients')
            ->get();

        foreach ($targets as $item) {
            $categoryName = $item->category?->name ?? '';

            $recipe = $this->simulateRecipe($categoryName, $invPools);

            // Attach ingredients
            foreach ($recipe['components'] as $comp) {
                Ingredient::create([
                    'menu_item_id' => $item->id,
                    'inventory_id' => $comp['inventory']->id,
                    'quantity_used' => $comp['qty'],
                    'unit_of_measurement_id' => $comp['inventory']->inventory_unit_id,
                ]);
            }

            // Add-ons and a removal option
            $this->createAddOns($item, $invPools);
            $this->createRemovals($item);
        }
    }

    private function buildInventoryPools(): array
    {
        $get = fn(string $name) => Inventory::whereHas('category', fn($q) => $q->where('name', $name))->get();

        return [
            'Meat/Poultry' => $get('Meat/Poultry'),
            'Dairy/Eggs' => $get('Dairy/Eggs'),
            'Produce' => $get('Produce'),
            'Dry Goods/Grains' => $get('Dry Goods/Grains'),
            'Condiments/Sauces' => $get('Condiments/Sauces'),
            'Spices/Seasonings' => $get('Spices/Seasonings'),
            'Beverages' => $get('Beverages'),
        ];
    }

    private function simulateRecipe(string $menuCategory, array $invPools): array
    {
        $ingredients = $this->chooseIngredientPool($menuCategory, $invPools);
        if (empty($ingredients)) {
            $fallback = Inventory::inRandomOrder()->take(rand(2, 5))->get();
            $ingredients = $fallback->all();
        }

        $count = rand(2, 5);
        $selected = collect($ingredients)->shuffle()->take($count);

        $components = [];
        foreach ($selected as $inv) {
            $qty = $this->simulatedQtyForInventory($inv, $menuCategory);
            $components[] = ['inventory' => $inv, 'qty' => $qty];
        }
        return ['components' => $components];
    }

    private function chooseIngredientPool(string $menuCategory, array $invPools): array
    {
        return match ($menuCategory) {
            'Starters/Appetizers' => array_merge(
                $invPools['Produce']->all(),
                $invPools['Dairy/Eggs']->all(),
                $invPools['Condiments/Sauces']->all(),
                $invPools['Spices/Seasonings']->all()
            ),
            'Soups & Salads' => array_merge(
                $invPools['Produce']->all(),
                $invPools['Dairy/Eggs']->all(),
                $invPools['Spices/Seasonings']->all()
            ),
            'Main Courses' => array_merge(
                $invPools['Meat/Poultry']->all(),
                $invPools['Produce']->all(),
                $invPools['Condiments/Sauces']->all(),
                $invPools['Spices/Seasonings']->all()
            ),
            'Pasta & Risotto' => array_merge(
                $invPools['Dry Goods/Grains']->all(),
                $invPools['Dairy/Eggs']->all(),
                $invPools['Produce']->all(),
                $invPools['Condiments/Sauces']->all()
            ),
            'Sides / Accompaniments' => array_merge(
                $invPools['Produce']->all(),
                $invPools['Dry Goods/Grains']->all(),
                $invPools['Condiments/Sauces']->all()
            ),
            'Desserts' => array_merge(
                $invPools['Dairy/Eggs']->all(),
                $invPools['Produce']->all(),
                $invPools['Dry Goods/Grains']->all()
            ),
            'Beverages' => array_merge(
                $invPools['Beverages']->all(),
                $invPools['Produce']->all()
            ),
            default => array_merge(
                $invPools['Produce']->all(),
                $invPools['Condiments/Sauces']->all(),
                $invPools['Spices/Seasonings']->all()
            ),
        };
    }

    private function simulatedQtyForInventory(Inventory $inv, string $menuCategory): float
    {
        $cat = $inv->category?->name;
        if ($menuCategory === 'Beverages') {
            return $this->randomFloat(0.2, 0.45);
        }
        if ($cat === 'Meat/Poultry') {
            return $this->randomFloat(0.12, 0.35);
        }
        if ($cat === 'Dry Goods/Grains') {
            return $this->randomFloat(0.06, 0.25);
        }
        if ($cat === 'Dairy/Eggs') {
            return $this->randomFloat(0.04, 0.2);
        }
        if ($cat === 'Condiments/Sauces') {
            return $this->randomFloat(0.01, 0.06);
        }
        if ($cat === 'Spices/Seasonings') {
            return $this->randomFloat(0.002, 0.01);
        }
        return $this->randomFloat(0.03, 0.2);
    }

    private function createAddonsPool(array $invPools): array
    {
        return array_merge(
            $invPools['Condiments/Sauces']->all(),
            $invPools['Spices/Seasonings']->all(),
            $invPools['Dairy/Eggs']->all(),
            $invPools['Produce']->all()
        );
    }

    private function createAddOns(MenuItem $item, array $invPools): void
    {
        $pool = $this->createAddonsPool($invPools);
        if (empty($pool)) {
            return;
        }
        $addons = rand(1, 3);
        $choices = collect($pool)->shuffle()->take($addons);
        foreach ($choices as $inv) {
            $qty = $this->addonQtyForInventory($inv);
            $base = $inv->unit_cost * $qty;
            $price = round($base * $this->randomFloat(1.3, 1.8), 2);
            MenuItemCustomization::create([
                'menu_item_id' => $item->id,
                'ingredient_id' => null,
                'inventory_id' => $inv->id,
                'name' => $inv->name,
                'quantity_used' => $qty,
                'price' => $price,
                'cost' => round($base, 2),
                'is_vat_exempt' => false,
                'unit_of_measurement_id' => $inv->inventory_unit_id,
                'action' => 'add',
            ]);
        }
    }

    private function createRemovals(MenuItem $item): void
    {
        $ings = $item->ingredients()->get();
        if ($ings->isEmpty()) {
            return;
        }
        $pick = $ings->random(1);
        foreach ($pick as $ing) {
            MenuItemCustomization::create([
                'menu_item_id' => $item->id,
                'ingredient_id' => $ing->id,
                'inventory_id' => null,
                'name' => null,
                'quantity_used' => 0,
                'price' => 0,
                'cost' => 0,
                'is_vat_exempt' => false,
                'unit_of_measurement_id' => $ing->unit_of_measurement_id,
                'action' => 'remove',
            ]);
        }
    }

    private function addonQtyForInventory(Inventory $inv): float
    {
        $cat = $inv->category?->name;
        if ($cat === 'Spices/Seasonings') {
            return $this->randomFloat(0.001, 0.006);
        }
        if ($cat === 'Condiments/Sauces') {
            return $this->randomFloat(0.01, 0.06);
        }
        if ($cat === 'Dairy/Eggs') {
            return $this->randomFloat(0.02, 0.08);
        }
        if ($cat === 'Produce') {
            return $this->randomFloat(0.02, 0.1);
        }
        return $this->randomFloat(0.01, 0.05);
    }

    private function randomFloat(float $min, float $max): float
    {
        return $min + lcg_value() * (abs($max - $min));
    }
}
