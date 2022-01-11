<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        $permissions = config('permission.names');
        $roles = config('permission.roles');

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $objRole = Role::firstOrCreate(['name' => $role['name']]);
            $objRole->syncPermissions();

            if ($role['permissions'] == 'ALL') {
                $objRole->givePermissionTo(Permission::all());
            } elseif (count($role['permissions'])) {
                $objRole->givePermissionTo(array_map(fn ($key) => $permissions[$key], $role['permissions']));
            }
        }
    }
}
