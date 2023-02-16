<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    protected $crud = ['Read', 'Create', 'Update', 'Delete'];
    protected $modules = [
        'Article',
        'Permission',
        'Role',
        'User',
        'Article Category',
        'Article',
    ];

    public function run(): void
    {
        $inputPermissions = [];
        $now = now();

        foreach ($this->crud as $r) {
            foreach ($this->modules as $module) {
                $inputPermissions[] = ['name' => $r . ' ' . $module, 'created_at' => $now];
            }
        }
        Permission::insert($inputPermissions);

        $allPermission = Permission::query()
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $dataRole = Role::create(['name' => 'Admin']);
        $dataRole->permissions()->attach($allPermission);
    }
}
