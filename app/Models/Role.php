<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'guard_name'];

    const ADMIN = 'admin';
    const USER = 'user';
    const MODERATOR = 'moderator';

    public function getPermissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions','role_id', 'permission_id');
    }

    public function getUsers()
    {
        return $this->belongsToMany(User::class, 'model_has_roles' ,'role_id', 'model_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }   
}
