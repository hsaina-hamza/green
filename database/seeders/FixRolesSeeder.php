<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class FixRolesSeeder extends Seeder
{
    public function run()
    {
        // Clear user roles
        $users = User::all();
        foreach ($users as $user) {
            $user->roles()->detach();
        }

        // Reassign roles
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $worker = User::where('email', 'worker@example.com')->first();
        if ($worker) {
            $worker->assignRole('employee');
        }

        $user = User::where('email', 'user@example.com')->first();
        if ($user) {
            $user->assignRole('user');
        }
    }
}
