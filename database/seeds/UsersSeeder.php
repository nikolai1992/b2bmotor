<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {

        $admin = factory(\App\User::class)->create([
            'email' => 'admin@turumburum.com',
            'password' => bcrypt('testpass'),
        ]);
        $admin->roles()->attach(1);

        $manager = factory(\App\User::class)->create([
            'email' => 'manager@turumburum.com',
            'password' => bcrypt('testpass'),
        ]);
        $manager->roles()->attach(2);

        $client = factory(\App\User::class)->create([
            'email' => 'client@turumburum.com',
            'password' => bcrypt('testpass'),
        ]);
        $client->roles()->attach(3);

    }
}
