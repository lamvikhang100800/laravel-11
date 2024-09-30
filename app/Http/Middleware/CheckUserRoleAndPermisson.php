<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class CheckUserRoleAndPermisson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $role , $permission = null): Response
    {   
        try {

            $user = JWTAuth::parseToken()->authenticate();
            $hasRole = $user->getRoles()->where('name', $role)->first();
           
            if ($hasRole) {
               
                $roleModel = Role::where('name', $role)->first();

                if ($permission && !$roleModel->getPermissions()->where('name', $permission)->exists()) {
                    return response()->json(['message' => 'Permission Denied'], 403); 
                }

                return $next($request); 
            } else {
                return response()->json(['message' => 'Role Denied'], 403); 
            }

        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
