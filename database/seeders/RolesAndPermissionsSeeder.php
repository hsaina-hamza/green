<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_reports',
            'manage_schedules',
            'view_statistics',
            'manage_locations',
            
            // Worker permissions
            'manage_assigned_reports',
            'view_schedules',
            'update_report_status',
            
            // User permissions
            'create_reports',
            'view_own_reports',
            'add_comments'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage_users',
            'manage_reports',
            'manage_schedules',
            'view_statistics',
            'manage_locations',
            'create_reports',
            'view_own_reports',
            'add_comments'
        ]);

        $workerRole = Role::create(['name' => 'worker']);
        $workerRole->givePermissionTo([
            'manage_assigned_reports',
            'view_schedules',
            'update_report_status',
            'view_own_reports',
            'add_comments'
        ]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'create_reports',
            'view_own_reports',
            'add_comments'
        ]);
    }
}
