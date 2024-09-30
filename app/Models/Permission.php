<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'guard_name'];

    public function getRoles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function getUsers()
    {
        return $this->belongsToMany(User::class, 'model_has_permissions','permissions_id', 'model_id');
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
