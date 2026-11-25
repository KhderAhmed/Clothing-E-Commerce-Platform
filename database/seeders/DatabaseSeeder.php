<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//         // User::factory(10)->create();

   \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@site.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'User',
            'email' => 'user@site.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
            $this->call([
    ColorSeeder::class,
    SizeSeeder::class,
]);
    }

}
