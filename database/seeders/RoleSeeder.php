<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'user']);

        // Create permissions
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'manage waste reports']);
        Permission::create(['name' => 'view statistics']);

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $employeeRole = Role::findByName('employee');

        $adminRole->givePermissionTo([
            'manage employees',
            'manage waste reports',
            'view statistics'
        ]);

        $employeeRole->givePermissionTo([
            'manage waste reports',
            'view statistics'
        ]);
    }
}
