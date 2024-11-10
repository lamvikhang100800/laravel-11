<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorizationController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\UserController;




Route::prefix('auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('check.jwt.session');
    Route::post('/refresh',  [AuthController::class, 'refresh'])->middleware('check.jwt.session');
    Route::post('/me', [AuthController::class, 'me'])->middleware('check.jwt.session');

});

Route::prefix('authoriz')->group(function(){

    Route::get('/role-and-permisson', [AuthorizationController::class, 'getRolesAndPermissions']);
    Route::post('/role-detail', [AuthorizationController::class, 'getRoleDetail']);
    Route::post('/check-permission', [AuthorizationController::class, 'checkPermission']);
    Route::post('/add-role', [AuthorizationController::class, 'addRole']);
    Route::post('/add-permission', [AuthorizationController::class, 'addPermission']);
    Route::post('/update-role-and-permission', [AuthorizationController::class, 'updateRoleAndPemission']);

});



Route::prefix('user')->group(function(){
    Route::post('/index', [UserController::class, 'index']);
    Route::post('/create', [UserController::class, 'create']);
    Route::put('/update', [UserController::class, 'update']);
    
});


Route::prefix('staff')->group(function(){
    Route::post('/index', [StaffController::class, 'index']);
    Route::post('/create', [StaffController::class, 'create']);
    Route::get('/test', [StaffController::class, 'test']);

    
});


