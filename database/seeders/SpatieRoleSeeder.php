<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $workerRole = Role::create(['name' => 'worker']);
        $userRole = Role::create(['name' => 'user']);

        // Get all users and assign roles based on their current role column
        User::all()->each(function ($user) {
            if ($user->role === 'admin') {
                $user->assignRole('admin');
            } elseif ($user->role === 'worker') {
                $user->assignRole('worker');
            } else {
                $user->assignRole('user');
            }
        });
    }
}
