<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database users table.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Another Test User',
            'email' => 'another_test@example.com',
            'password' => 'password',
        ]);
    }
}
