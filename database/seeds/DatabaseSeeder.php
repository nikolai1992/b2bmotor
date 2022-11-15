<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       // $this->call(\RolesSeeder::class);
        $this->call(\UsersSeeder::class);
        $this->call(\CurrenciesSeeder::class);
    }
}
