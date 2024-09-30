<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'create-posts', 'guard_name' => 'api'],
            ['name' => 'edit-posts', 'guard_name' => 'api'],
            ['name' => 'delete-posts', 'guard_name' => 'api'],
            ['name' => 'view-posts', 'guard_name' => 'api'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $roles = [
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'editor', 'guard_name' => 'api'],
            ['name' => 'viewer', 'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
