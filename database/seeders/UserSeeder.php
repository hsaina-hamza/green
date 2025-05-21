<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone_number' => '966500000000',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Create worker user
        $worker = User::updateOrCreate(
            ['email' => 'worker@example.com'],
            [
                'name' => 'Worker User',
                'password' => Hash::make('password'),
                'phone_number' => '966500000001',
                'is_active' => true,
            ]
        );
        $worker->assignRole('worker');

        // Create regular user
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'phone_number' => '966500000002',
                'is_active' => true,
            ]
        );
        $user->assignRole('user');
    }
}
