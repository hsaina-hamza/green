<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create worker users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Worker User {$i}",
                'email' => "worker{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'worker',
                'email_verified_at' => now(),
            ]);
        }

        // Create regular users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Regular User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        // Create additional users with factory
        User::factory(20)->create();
    }
}
