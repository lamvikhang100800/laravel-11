<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Events\NotificationEvent;
use App\Events\TestEvent;

class StaffController extends Controller implements HasMiddleware
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
            // new Middleware('check.jwt.session'),
            // new Middleware('check.role.and.permisson:user-modules,update-user')
        ];
    }
    public function index(Request $request){
        $data = $request->validate([
            'name'=>['required','string']
        ]);
        return response()->json(['message' => 'Staff Index']);
    }

    public function create(){
        return response()->json(['message' => 'Staff Create']);
    }
    public function test(){
        
        $data = (object)[
            'type' => 'success',
            'message' => 'Operation was successful!',
            'url' => 'https://example.com'
        ];

        event(new NotificationEvent( $data));
        return response()->json(['message' => 'Notification sent']);
    }
}
