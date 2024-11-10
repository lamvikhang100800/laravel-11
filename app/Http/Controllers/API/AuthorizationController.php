<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class AuthorizationController extends Controller implements HasMiddleware
{
    public $user;

    public function __construct()
    {
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            $this->user = null;
        }
    }
    public static function middleware(): array
    {
        return [
            new Middleware('check.jwt.session', except: ['getRolesAndPermissions']),
        ];
    }
    public function getRolesAndPermissions(Request $request)
    {

        $roles = Role::all()->select('id','name','description');
        $permissions = Permission::all()->select('id','name','description');
        $users = User::all()->select('id','name');

        return response()->json([
            'role' => $roles,
            'permissions' => $permissions,
            'users'=>$users
        ], 200);
    }

    public function getRoleDetail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::find($request->role_id);
        $roleHasPermissions = RolePermission::where('role_id', $role->id)
            ->from('tbl_role_has_permissions as rp')
            ->join('tbl_permissions as p', 'rp.permission_id', '=', 'p.id')
            ->select('p.id', 'p.name','p.description')
            ->get();
        $userHasRole = UserRole::where('role_id', $role->id)
            ->from('tbl_user_has_roles as ur')
            ->join('users as u', 'ur.user_id', '=', 'u.id')
            ->select('u.id', 'u.name')
            ->get();

        return response()->json(([
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'description'=>$role->description,
                'permissions' => $roleHasPermissions,
                'user' => $userHasRole
            ]
        ]), 200);
    }
    public function checkPermission()
    {
        $user_id = $this->user->id;

        $result = UserRole::where('user_id', $user_id)
            ->from('tbl_user_has_roles as ur')
            ->join('tbl_role_has_permissions as rp', 'ur.role_id', '=', 'rp.role_id')
            ->join('tbl_permissions as p', 'rp.permission_id', '=', 'p.id')
            ->select('p.name')
            ->get();

        return response()->json(([
            'permissions' =>  $result
        ]), 200);
    }
    public function addRole(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Role::updateOrCreate(
            ['name' => $request->input('name')],
            [
            'description' => $request->input('description'),
            'status'=>'ACT'
            ],
        );

        return response()->json(['message' => 'Create Role Successfully!'], 200);
    }

    public function addPermission(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Permission::updateOrCreate(
            ['name' => $request->input('name')],
            [
            'description' => $request->input('description'),
            'status'=>'ACT'
            ],
        );

        return response()->json(['message' => 'Create Permission Successfully!'], 200);
    }

    public function updateRoleAndPemission(Request $request)
    {   
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'selectedPermissionsIds' => 'required|array',
            'selectedUsersIds' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::find($request->input('role_id'));
        $role->update([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        RolePermission::where('role_id',$request->input('role_id'))->delete();

        $arrayPermissions = $request->input('selectedPermissionsIds');
        foreach ($arrayPermissions as $permissionId) {
            RolePermission::create([
                'role_id' => $request->input('role_id'),
                'permission_id' => $permissionId
            ]);
        }

        UserRole::where('role_id', $request->input('role_id'))->delete();
        $arrayUsers = $request->input('selectedUsersIds');
        foreach ($arrayUsers as $userId) {
            UserRole::create([
                'role_id' => $request->input('role_id'),
                'user_id' => $userId
            ]);
            
            if($userId != 1){
                $data = (object) [
                    'type' => 'info',
                    'message' => 'Your account has been updated with new permissions.',
                    'url' => ''
                ];
                event(new NotificationEvent($data, $userId));
            }
            
        }
        return response()->json(['message' => 'Role and permissions updated successfully!'], 200);

    }
}
