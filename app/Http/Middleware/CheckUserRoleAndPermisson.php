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
    public function handle(Request $request, Closure $next, $module, $permission = null): Response
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            $hasRoleAdmin = $user->roles()->where('name', 'root-admin')->first();

            if ($hasRoleAdmin) {
                return $next($request);
            }

            $modules = $user->getModuleAndPermisson();
            $moduleExists = false;
            $permissionExists = false;

            foreach ($modules as $item) {
                if ($item['module'] === $module) {
                    $moduleExists = true;

                    if ($permission) {
                        foreach ($item['permissions'] as $perm) {
                            if ($perm->name === $permission) {
                                $permissionExists = true;
                                break;
                            }
                        }
                    }
                    break; 
                }
            }

            
            if (!$moduleExists) {
                return response()->json(['message' => 'Module not found'], 404);
            }

           
            if ($permission && !$permissionExists) {
                return response()->json(['message' => 'Permission denied'], 403);
            }

            return $next($request);
            
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

}
