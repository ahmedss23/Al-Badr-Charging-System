<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'active users', 'guard_name' => 'admin']);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'super admin', 'guard_name' => 'admin']);
    }
}
