<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Mail; 
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Session as UserSession;
use App\Models\User;
use App\Models\EmailVerification;

class AuthController extends Controller
{   
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'username' => 'required|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'username' => $request->username,
            'status' => 'PEN', 
        ]);

        $token = Str::random(60);
        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(30),  
        ]);
        
        Mail::to($user->email)->send(new \App\Mail\VerifyEmail($token));

        return response()->json(['message' => 'Registration successful! Please check your email to confirm your account.'], 201);

    }
    public function verifyEmail(Request $request, $token)
    {
        $verification = EmailVerification::where('token', $token)->first();
    
        if (!$verification) {
            return response()->json(['message' => 'Invalid token.'], 404);
        }
    
        if ($verification->expires_at < now()) {
            return response()->json(['message' => 'Token has expired.'], 400);
        }
    
        $user = User::find($verification->user_id);
    
        if (!$user) {
            return response()->json(['message' => 'User does not exist.'], 404);
        }
    
        $user->status = 'ACT';
        $user->email_verified_at = now(); 
        $user->save();
    
        $verification->delete();
    
        return response()->json(['message' => 'Email has been successfully verified!'], 200);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = request(['email', 'password']);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->status !== 'ACT' || !$user->email_verified_at) {
            return response()->json(['error' => 'Account not active or email not verified.'], 403);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
            
        }

        UserSession::where('user_id', $user->id)->delete();

        UserSession::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => json_encode([
                'access_token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]),
            'last_activity' => now()->timestamp,
            'expires_at' => now()->addMinutes(auth('api')->factory()->getTTL()),
        ]);

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {   
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        $newToken = auth('api')->refresh();

        $session = UserSession::where('user_id', $user->id)->first();

        $session->update([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => json_encode([
                'access_token' => $newToken,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]),
            'last_activity' => now()->timestamp,
            'expires_at' => now()->addMinutes(auth('api')->factory()->getTTL()),
        ]);

        auth('api')->invalidate(true);

        return $this->respondWithToken($newToken);
    }

    public function me()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_id' => auth('api')->user()->id,
        ]);
    }

}
