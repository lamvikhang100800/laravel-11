<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

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
            new Middleware('check.jwt.session'),
            new Middleware('check.role.and.permisson:Blog,create-posts',only: ['index'])
        ];
    }
    public function index(){
        return response()->json(['message' => 'Staff Index']);
    }

    public function create(){
        return response()->json(['message' => 'Staff Create']);
    }
}
