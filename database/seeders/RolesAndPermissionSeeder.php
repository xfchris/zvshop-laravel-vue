<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('permission.names');
        $roles = config('permission.roles');
        $permissionsInDB = Permission::select('id', 'name')->get()->keyBy('name');

        foreach ($permissions as $permission) {
            if (!$permissionsInDB->get($permission)) {
                Permission::Create(['name' => $permission]);
            }
        }

        $permisionsToDelete = $permissionsInDB->filter(fn ($permissionDB) => (
            empty($permissions[$permissionDB->name])
        ));
        Permission::whereIn('id', $permisionsToDelete->pluck('id'))->delete();

        foreach ($roles as $role) {
            $objRole = Role::firstOrCreate(['name' => $role['name']]);

            if ($role['permissions'] == 'ALL') {
                $objRole->syncPermissions(Permission::all());
            } elseif (is_array($role['permissions'])) {
                $objRole->syncPermissions(array_map(fn ($key) => $permissions[$key], $role['permissions']));
            }
        }
    }
}
