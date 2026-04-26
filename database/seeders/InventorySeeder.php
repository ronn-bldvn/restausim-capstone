<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $units = $this->ensureUnits();

        $categories = [
            'Meat/Poultry' => [
                'Chicken Breast',
                'Chicken Thighs',
                'Whole Chicken',
                'Ground Beef',
                'Beef Sirloin',
                'Pork Belly',
                'Pork Chops',
                'Bacon',
                'Turkey Breast',
                'Chicken Wings',
            ],
            'Dairy/Eggs' => [
                'Whole Milk',
                'Cheddar Cheese',
                'Mozzarella Cheese',
                'Butter',
                'Yogurt',
                'Heavy Cream',
                'Eggs',
                'Parmesan Cheese',
                'Cream Cheese',
                'Condensed Milk',
            ],
            'Produce' => [
                'Tomatoes',
                'Onions',
                'Garlic',
                'Lettuce',
                'Spinach',
                'Carrots',
                'Bell Peppers',
                'Cucumbers',
                'Potatoes',
                'Lemons',
            ],
            'Dry Goods/Grains' => [
                'Long-Grain Rice',
                'Jasmine Rice',
                'Spaghetti',
                'Elbow Macaroni',
                'All-Purpose Flour',
                'Bread Flour',
                'Granulated Sugar',
                'Brown Sugar',
                'Bread Crumbs',
                'Cornstarch',
            ],
            'Condiments/Sauces' => [
                'Ketchup',
                'Mayonnaise',
                'Mustard',
                'Soy Sauce',
                'Fish Sauce',
                'Oyster Sauce',
                'Hot Sauce',
                'BBQ Sauce',
                'Vinegar',
                'Worcestershire Sauce',
            ],
            'Spices/Seasonings' => [
                'Black Pepper',
                'Sea Salt',
                'Paprika',
                'Oregano',
                'Basil',
                'Cumin',
                'Turmeric',
                'Chili Flakes',
                'Garlic Powder',
                'Onion Powder',
            ],
            'Beverages' => [
                'Bottled Water',
                'Orange Juice',
                'Apple Juice',
                'Cola Syrup',
                'Lemonade Syrup',
                'Iced Tea Mix',
                'Coffee Beans',
                'Black Tea',
                'Green Tea',
                'Soda Water',
            ],
        ];

        $prefixes = [
            'Meat/Poultry' => 'MEA',
            'Dairy/Eggs' => 'DAI',
            'Produce' => 'PRO',
            'Dry Goods/Grains' => 'DRY',
            'Condiments/Sauces' => 'CON',
            'Spices/Seasonings' => 'SPI',
            'Beverages' => 'BEV',
        ];

        $rows = [];
        $now = now();

        foreach ($categories as $categoryName => $items) {
            $categoryId = InventoryCategory::where('name', $categoryName)->value('id');
            if (!$categoryId) {
                continue;
            }

            $unitId = $this->unitForCategory($categoryName, $units);

            foreach ($items as $i => $name) {
                // $code = 'INV-' . $prefixes[$categoryName] . '-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);

                $opening = $this->randDecimal(50, 200, 3);
                $onHand = $this->randDecimal(20, 150, 3);
                $par = $this->randDecimal(30, 100, 3);
                $unitCost = $this->unitCostForCategory($categoryName);

                $rows[] = [
                    'name' => $name,
                    // 'code' => $code,
                    'image' => '',
                    'opening_quantity' => $opening,
                    'quantity_on_hand' => $onHand,
                    'unit_cost' => $unitCost,
                    'par_level' => $par,
                    'inventory_category_id' => $categoryId,
                    'inventory_unit_id' => $unitId,
                    'cost_unit_id' => $unitId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('inventories')->insert($chunk);
        }
    }

    private function ensureUnits(): array
    {
        $kg = UnitOfMeasurement::firstOrCreate(
            ['symbol' => 'kg'],
            [
                'name' => 'Kilogram',
                'category' => 'Weight'
            ]
        );

        $l = UnitOfMeasurement::firstOrCreate(
            ['symbol' => 'L'],
            [
                'name' => 'Liter',
                'category' => 'Volume'
            ]
        );

        $pcs = UnitOfMeasurement::firstOrCreate(
            ['symbol' => 'pcs'],
            [
                'name' => 'Piece',
                'category' => 'Count'
            ]
        );

        return [
            'kg' => $kg->id,
            'l' => $l->id,
            'pcs' => $pcs->id
        ];
    }

    private function unitForCategory(string $category, array $units): int
    {
        if ($category === 'Beverages') {
            return $units['l'];
        }
        if ($category === 'Dairy/Eggs') {
            return $units['pcs'];
        }
        if ($category === 'Condiments/Sauces') {
            return $units['l'];
        }
        return $units['kg'];
    }

    private function unitCostForCategory(string $category): float
    {
        return match ($category) {
            'Meat/Poultry' => $this->randDecimal(150, 500, 2),
            'Dairy/Eggs' => $this->randDecimal(50, 200, 2),
            'Produce' => $this->randDecimal(20, 120, 2),
            'Dry Goods/Grains' => $this->randDecimal(30, 150, 2),
            'Condiments/Sauces' => $this->randDecimal(20, 150, 2),
            'Spices/Seasonings' => $this->randDecimal(10, 250, 2),
            'Beverages' => $this->randDecimal(15, 200, 2),
            default => $this->randDecimal(20, 100, 2),
        };
    }

    private function randDecimal(int $min, int $max, int $precision): float
    {
        $scale = 10 ** $precision;
        return mt_rand($min * $scale, $max * $scale) / $scale;
    }
}
