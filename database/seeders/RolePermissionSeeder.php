<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;



class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {  
        

        $roles = [
            ['name' => 'root-admin', 'description' => 'Root Admin','status'=>'ACT'],
            ['name' => 'manager', 'description' => 'Manager','status'=>'ACT'],
            ['name' => 'staff', 'description' => 'Staff','status'=>'ACT']
            
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['name' => 'create-auth', 'description' => 'no','status'=>'ACT'],
            ['name' => 'read-auth', 'description' => 'no','status'=>'ACT'],
            ['name' => 'update-auth', 'description' => 'no','status'=>'ACT'],
            ['name' => 'delete-auth', 'description' => 'no','status'=>'ACT'],
            ['name' => 'create-user', 'description' => 'no','status'=>'ACT'],
            ['name' => 'read-user', 'description' => 'no','status'=>'ACT'],
            ['name' => 'update-user', 'description' => 'no','status'=>'ACT'],
            ['name' => 'delete-user', 'description' => 'no','status'=>'ACT']
            
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }


        $user_has_roles = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 3],
        ];

        foreach ($user_has_roles as  $user_has_role) {
            UserRole::create($user_has_role);
        }

        
        
        $role_has_permissions =[
            ['role_id'=>1,'permission_id'=>1],
            ['role_id'=>1,'permission_id'=>2],
            ['role_id'=>1,'permission_id'=>3],
            ['role_id'=>1,'permission_id'=>4],
            ['role_id'=>2,'permission_id'=>5],
            ['role_id'=>2,'permission_id'=>6],
            ['role_id'=>2,'permission_id'=>7],
            ['role_id'=>2,'permission_id'=>8]
        ];
        foreach ($role_has_permissions as     $role_has_permission) {
            RolePermission::create(  $role_has_permission);
        }

    }
}
