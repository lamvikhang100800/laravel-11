<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'status',
        'branch_id',
        'code',
        'address',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tbl_user_has_roles', 'user_id', 'role_id');
    }

    public function getModuleAndPermisson()
    {   
        $roles = $this->roles()->get();
        $result = [];
        foreach ( $roles as $role){

            $modules = DB::table('tbl_role_has_module')
            ->join('tbl_modules','tbl_modules.id','=','tbl_role_has_module.module_id')
            ->select('tbl_modules.name','tbl_modules.id')
            ->where('role_id',$role->id)
            ->get();

                foreach( $modules as  $module){

                    $permissions = DB::table('tbl_module_has_permission')
                    ->join('tbl_permissions','tbl_permissions.id','=','tbl_module_has_permission.permission_id')
                    ->select('tbl_permissions.name')
                    ->where('module_id' , $module->id)
                    ->get();

                    $result[] = [
                        'module' =>  $module->name,
                        'permissions' => $permissions
                    ];
            
                
                }
            }

        return  $result ;
    }

}
