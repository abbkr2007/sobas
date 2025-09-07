<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'update']);
        Permission::create(['name' => 'create']);

        // Create roles and assign created permissions
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super admin']);

        // Assign permissions to admin
        $adminRole->givePermissionTo(['edit', 'delete', 'update', 'create']);

        // Assign all permissions to super admin
        $superAdminRole->givePermissionTo(Permission::all());
    }
}



