<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the initial admin user
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => 'Admin@123456', // Will be hashed automatically
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Optional: Create sample users for testing
        if (app()->environment('local')) {
            User::factory()->count(20)->create();
        }
    }
}
