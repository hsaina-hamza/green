<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );
        $user->assignRole('user');

        // Create test admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('admin');

        // Create test worker
        $worker = User::firstOrCreate(
            ['email' => 'worker@example.com'],
            [
                'name' => 'Test Worker',
                'password' => Hash::make('password123'),
            ]
        );
        $worker->assignRole('worker');
    }
}
