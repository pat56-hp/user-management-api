<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'nom' => 'Test User 1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1'),
            'role' => 'admin',
            'actif' => true,
        ]); */

        User::factory()->create([
            'nom' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password2'),
            'role' => 'admin',
            'actif' => true,
        ]);
    }
}
