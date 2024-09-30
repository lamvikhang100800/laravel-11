<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Session as UserSession;
use Illuminate\Support\Facades\Session;

class CheckJwtAndSession
{
    public function handle($request, Closure $next): Response
    {   
        //JWT
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token absent'], 401);
        }
        
        //Session

        $session = UserSession::where('user_id', $user->id)->first();
        if ($session && ($session->ip_address !== $request->ip() || $session->user_agent !== $request->userAgent())) {
            return response()->json(['error' => 'Session information mismatch.'], 403);
        }

        $request->merge(['user' => $user]);
        return $next($request);
    }
}
