<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{

    public static function addUserToRole($user, $role)
    {
        return $role->users()->attach($user);
    }

    public static function addPermissionToRole($role, $permission)
    {
        return $role->permissions()->attach($permission);
    }

    public static function addUserToPermission($user, $permission)
    {
        return $permission->users()->attach($user);
    }

    public static function removeUserFromRole($user, $role)
    {
        return $role->users()->detach($user);
    }

    public static function removePermissionFromRole($role, $permission)
    {
        return $role->permissions()->detach($permission);
    }

    public static function removeUserFromPermission($user, $permission)
    {
        return $permission->users()->detach($user);
    }
}
