<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRoleAndPermisson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            $hasRoleAdmin = $user->roles()->where('name', 'root-admin')->first();

            if ($hasRoleAdmin) {
                return $next($request);
            }

            $hasPermissions = UserRole::where('user_id', $user->id)
            ->where('p.name',$permission)
            ->from('tbl_user_has_roles as ur')
            ->join('tbl_role_has_permissions as rp', 'ur.role_id', '=', 'rp.role_id')
            ->join('tbl_permissions as p', 'rp.permission_id', '=', 'p.id')
            ->select('p.name')
            ->first();

            if($hasPermissions){
                return $next($request);
            }

            return response()->json(['message' => 'Unauthorized'], 403);

        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

}
