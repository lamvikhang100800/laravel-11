<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\User ;


class UserController extends Controller implements HasMiddleware
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
            new Middleware('check.jwt.session'),
            new Middleware('check.role.and.permisson:user-module'),
            new Middleware('check.role.and.permisson:user-module,read-user',only: ['index'])

        ];
    }

    public function index(){
        $users = User::all();

        return response()->json($users , 200);
    }
}
