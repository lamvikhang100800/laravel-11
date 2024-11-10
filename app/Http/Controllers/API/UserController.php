<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User ;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Http\RedirectResponse;



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
            new Middleware('check.role.and.permisson:read-user',only: ['index']),
            new Middleware('check.role.and.permisson:create-user',only: ['create'])


        ];
    }

    public function index()
    {
        $users = User::all();

        return response()->json($users , 200);
    }
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:users,code',
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'The email has already been taken.',
            'code.unique' => 'The code has already been taken.',
            'username.unique' => 'The username has already been taken.',
        ]);
        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        User::create([
            'code'=> $request->input('code'),
            'name'=> $request->input('name'),
            'username'=> $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 'ACT',
        ]);

        return response()->json(['message' => 'Create User Success!'], 201);
    }
    public function update(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id'=>'required|string',
            'code'=>'required|string',
            'name'=>'required|string',
            'username'=>'required|string',
            'email' => 'required|string|email|max:255',
            'address'=>'required|string',
            'phone'=>'required|string',
            'status'=>'required|string'
        ]);
 
        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->name = $request->name;
        $user->code = $request->code;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->save();  

        return response()->json(['message' => 'User updated successfully!'], 200);

    }
}
