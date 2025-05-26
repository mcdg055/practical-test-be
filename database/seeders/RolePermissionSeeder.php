<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create IPAddress',
            'read IPAddress',
            'update IPAddress',
            'update own IPAddress',
            'delete IPAddress',

            'read Activity',
            'view audit dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = Role::firstOrCreate(['name' => 'User']);

        $superAdmin->syncPermissions(Permission::all());

        $user->syncPermissions([
            'create IPAddress',
            'read IPAddress',
            'update own IPAddress',
        ]);
    }
}
