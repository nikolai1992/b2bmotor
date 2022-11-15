<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'create-manager' => true,
                'update-manager' => true,
                'remove-manager' => true,
                'update-product' => true,
                'update-client' => true,
                'show-all-orders' => true,
                'create-order' => true,
                'update-order' => true,
                'show_catalog' => true
            ]
        ]);

        $manager = Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'permissions' => [
                'update-product' => true,
                'update-client' => true,
                'create-order' => true,
                'update-order' => true,
                'show_catalog' => true
            ]
        ]);

        $client = Role::create([
            'name' => 'Client',
            'slug' => 'client',
            'permissions' => [
                'create-order' => true
            ]
        ]);
    }
}
