<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menuCategories = [
            'Starters/Appetizers' => [
                'Crispy Chicken Wings',
                'Mozzarella Sticks',
                'Garlic Butter Shrimp',
                'Loaded Potato Skins',
                'Fried Calamari',
                'Bruschetta',
                'Chicken Tenders',
                'Spinach Artichoke Dip',
                'Nacho Platter',
                'Cheesy Garlic Bread',
            ],
            'Soups & Salads' => [
                'Chicken Noodle Soup',
                'Cream of Mushroom Soup',
                'Tomato Basil Soup',
                'Caesar Salad',
                'Garden Salad',
                'Greek Salad',
                'Chicken Cobb Salad',
                'Tuna Salad',
                'Asian Sesame Salad',
                'Pumpkin Soup',
            ],
            'Main Courses' => [
                'Grilled Chicken Steak',
                'Beef Sirloin Steak',
                'Pork Chop with Gravy',
                'Chicken Teriyaki',
                'Beef Tapa Rice Meal',
                'Crispy Pork Belly',
                'Chicken Alfredo',
                'Roast Chicken Plate',
                'BBQ Pork Ribs',
                'Beef Stroganoff',
            ],
            'Pasta & Risotto' => [
                'Spaghetti Bolognese',
                'Creamy Carbonara',
                'Garlic Shrimp Pasta',
                'Pesto Pasta',
                'Four Cheese Penne',
                'Lasagna al Forno',
                'Mushroom Risotto',
                'Seafood Marinara',
                'Truffle Cream Pasta',
                'Chicken Alfredo Penne',
            ],
            'Sides / Accompaniments' => [
                'Steamed Rice',
                'Garlic Rice',
                'Mashed Potatoes',
                'Buttered Corn',
                'Coleslaw',
                'Steamed Vegetables',
                'French Fries',
                'Onion Rings',
                'Grilled Vegetables',
                'Garlic Butter Rice',
            ],
            'Desserts' => [
                'Chocolate Lava Cake',
                'Cheesecake Slice',
                'Vanilla Panna Cotta',
                'Chocolate Mousse',
                'Apple Pie Slice',
                'Tiramisu',
                'Leche Flan',
                'Banoffee Pie',
                'Brownie à la Mode',
                'Mango Float',
            ],
            'Beverages' => [
                'Iced Tea',
                'Lemonade',
                'Cappuccino',
                'Americano',
                'Hot Chocolate',
                'Bottled Water',
                'Orange Juice',
                'Apple Juice',
                'Soda Water',
                'Green Tea',
            ],
        ];

        $invPools = $this->buildInventoryPools();

        $now = now();
        $rows = [];

        foreach ($menuCategories as $menuCategoryName => $items) {
            $menuCategoryId = MenuItemCategory::where('name', $menuCategoryName)->value('id');
            if (!$menuCategoryId) {
                continue;
            }

            foreach ($items as $name) {
                $description = $this->descriptionFor($name, $menuCategoryName);
                $cost = $this->simulateCost($menuCategoryName, $invPools);
                $markup = $this->randomFloat(2.5, 3.0);
                $price = round($cost * $markup, 2);
                $rows[] = [
                    'name' => $name,
                    'description' => $description,
                    'image' => 'menu/default.jpg',
                    'price' => $price,
                    'cost' => round($cost, 2),
                    'is_vat_exempt' => $this->isVatExempt($menuCategoryName, $name),
                    'menu_item_category_id' => $menuCategoryId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('menu_items')->insert($chunk);
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

    private function simulateCost(string $menuCategory, array $invPools): float
    {
        $ingredients = $this->chooseIngredientPool($menuCategory, $invPools);
        if (empty($ingredients)) {
            return $this->randomFloat(30, 150);
        }

        $count = rand(2, 5);
        $selected = collect($ingredients)->shuffle()->take($count);

        $cost = 0;
        foreach ($selected as $inv) {
            $qty = $this->simulatedQtyForInventory($inv, $menuCategory);
            $cost += $inv->unit_cost * $qty;
        }
        return max(10.0, $cost);
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
            default => [],
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

    private function isVatExempt(string $menuCategory, string $name): bool
    {
        if ($menuCategory === 'Beverages' && stripos($name, 'Water') !== false) {
            return true;
        }
        return false;
    }

    private function descriptionFor(string $name, string $menuCategory): string
    {
        return match ($menuCategory) {
            'Starters/Appetizers' => $name . ' served hot with a house dip.',
            'Soups & Salads' => $name . ' prepared fresh with seasonal ingredients.',
            'Main Courses' => $name . ' crafted with chef’s special blend.',
            'Pasta & Risotto' => $name . ' tossed in a rich, savory sauce.',
            'Sides / Accompaniments' => $name . ' made to pair with any entrée.',
            'Desserts' => $name . ' finished with a delicate sweet touch.',
            'Beverages' => $name . ' served chilled and refreshing.',
            default => $name . ' from our kitchen.',
        };
    }

    private function randomFloat(float $min, float $max): float
    {
        return $min + lcg_value() * (abs($max - $min));
    }
}

