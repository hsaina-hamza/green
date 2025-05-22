<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarocUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'مستخدم عادي',
                'email' => 'user@guelmim.ma',
                'password' => Hash::make('password'),
                'phone_number' => '0600000000',
                'is_active' => true,
            ],
            [
                'name' => 'عامل',
                'email' => 'worker@guelmim.ma',
                'password' => Hash::make('password'),
                'phone_number' => '0600000001',
                'is_active' => true,
            ],
            [
                'name' => 'مشرف',
                'email' => 'admin@guelmim.ma',
                'password' => Hash::make('password'),
                'phone_number' => '0600000002',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Assign roles based on email
            if (str_contains($userData['email'], 'admin')) {
                $user->assignRole('admin');
            } elseif (str_contains($userData['email'], 'worker')) {
                $user->assignRole('worker');
            } else {
                $user->assignRole('user');
            }
        }
    }
}
