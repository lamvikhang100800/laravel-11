<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorizationController;
use App\Http\Controllers\API\StaffController;



Route::prefix('auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('check.jwt.session');
    Route::post('/refresh',  [AuthController::class, 'refresh'])->middleware('check.jwt.session');
    Route::post('/me', [AuthController::class, 'me'])->middleware('check.jwt.session');

});

Route::prefix('authoriz')->group(function(){
    Route::post('/add-user-role', [AuthorizationController::class, 'addUserToRole']);
    Route::post('/add-permission-role', [AuthorizationController::class, 'addPermissionToRole']);
    Route::post('/add-permission-user', [AuthorizationController::class, 'addPermissionToUser']);

    Route::post('/remove-user-role', [AuthorizationController::class, 'removeUserFromRole']);
    Route::post('/remove-permission-role', [AuthorizationController::class, 'removePermissionFromRole']);
    Route::post('/remove-permission-user', [AuthorizationController::class, 'removePermissionFromUser']);

    Route::get('/permissions', [AuthorizationController::class, 'getUserPermissions']);
    Route::get('/role-and-permisson', [AuthorizationController::class, 'getRolesAndPermisson']);

});

Route::prefix('staff')->group(function(){
    Route::post('/index', [StaffController::class, 'index']);
    Route::post('/create', [StaffController::class, 'create']);
    
});