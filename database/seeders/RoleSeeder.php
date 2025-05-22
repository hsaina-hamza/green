<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'name_ar' => 'مشرف',
                'guard_name' => 'web'
            ],
            [
                'name' => 'worker',
                'name_ar' => 'موظف',
                'guard_name' => 'web'
            ],
            [
                'name' => 'user',
                'name_ar' => 'مستخدم',
                'guard_name' => 'web'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
