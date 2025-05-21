<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin permission first
        $permission = Permission::firstOrCreate(['name' => 'admin']);

        // Create roles
        $roles = ['admin', 'worker', 'user'];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            // Give admin role all permissions
            if ($roleName === 'admin') {
                $role->givePermissionTo($permission);
            }
        }
    }
}
