<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
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
            new Middleware('check.jwt.session', except: ['getRolesAndPermisson']),
        ];
    }

    public function addUserToRole(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $role->getUsers()->syncWithoutDetaching([
            $user->id => ['model_type' => User::class],
        ]);

        return response()->json(['message' => 'User added to role successfully.']);
    }

    public function addPermissionToRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::find($request->role_id);
        $permission = Permission::find($request->permission_id);

        $role->getPermissions()->syncWithoutDetaching($permission);

        return response()->json(['message' => 'Permission added to role successfully.']);
    }

    public function addPermissionToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $permission = Permission::find($request->permission_id);

        $user->getPermission()->syncWithoutDetaching([
            $permission->id => ['model_type' => User::class],
        ]);

        return response()->json(['message' => 'Permission added to user successfully.']);
    }
    public function removeUserFromRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $role->getUsers()->detach($user->id);

        return response()->json(['message' => 'User removed from role successfully.']);
    }
    public function removePermissionFromRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::find($request->role_id);
        $permission = Permission::find($request->permission_id);

        $role->getPermissions()->detach($permission->id);

        return response()->json(['message' => 'Permission removed from role successfully.']);
    }
    public function removePermissionFromUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $permission = Permission::find($request->permission_id);

        $user->getPermission()->detach($permission->id);

        return response()->json(['message' => 'Permission removed from user successfully.']);
    }
    public function getRolesAndPermisson(Request $request)
    {
        $result = [];
        $role = Role::all();
        $permission = Permission::all();

        $result = [
            'role' =>   $role,
            'permission' =>  $permission
        ];

        return  response()->json($result, 200);
    }
}
