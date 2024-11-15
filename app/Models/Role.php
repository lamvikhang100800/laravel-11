<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends Model
{   
    protected $table = 'tbl_roles';
    protected $fillable = ['name', 'description','status'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tbl_user_has_roles', 'role_id', 'user_id')
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
