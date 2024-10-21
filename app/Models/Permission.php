<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{   
    protected $table = 'tbl_permissions';
    
    protected $fillable = ['name', 'description','status'];

    public function getRoles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tbl_role_has_permissions', 'permission_id', 'role_id')
                    ->withPivot('module_id')
                    ->withTimestamps();
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
