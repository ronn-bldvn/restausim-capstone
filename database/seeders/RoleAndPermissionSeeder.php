<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'create inventory']);
        Permission::create(['name' => 'edit inventory']);
        Permission::create(['name' => 'view inventory']);
        Permission::create(['name' => 'delete inventory']);
        Permission::create(['name' => 'restock inventory']);

        Permission::create(['name' => 'create menu']);
        Permission::create(['name' => 'edit menu']);
        Permission::create(['name' => 'view menu']);
        Permission::create(['name' => 'delete menu']);

        Permission::create(['name' => 'view floorplan']);
        Permission::create(['name' => 'create floorplan']);

        Permission::create(['name' => 'view table']);
        Permission::create(['name' => 'manage table']);

        Permission::create(['name' => 'take orders']);

        Permission::create(['name' => 'view kitchen orders']);
        Permission::create(['name' => 'manage kitchen orders']);

        Permission::create(['name' => 'apply discount']);
        Permission::create(['name' => 'process payment']);

        $cashier = Role::create(['name' => 'cashier']);

        $cashier->givePermissionTo([
            'view table',
            'apply discount',
            'process payment',
        ]);

        $waiter = Role::create(['name' => 'waiter']);

        $waiter->givePermissionTo([
            'view table', 
            'manage table', 
            'take orders', 
        ]);

        $kitchenStaff = Role::create(['name' => 'kitchen staff']);

        $kitchenStaff->givePermissionTo([
            'create menu',
            'edit menu',
            'view menu',
            'delete menu',

            'view kitchen orders',
            'manage kitchen orders',
        ]);

        $host = Role::create(['name' => 'host']);

        $host->givePermissionTo([
            'view table',
            'manage table',
        ]);

        $manager = Role::create(['name' => 'manager']);

        $manager->givePermissionTo([
            'create inventory',
            'edit inventory',
            'view inventory',
            'delete inventory',

            'create menu',
            'edit menu',
            'view menu',
            'delete menu',

            'view floorplan',
            'create floorplan',
        ]);
    }
}
