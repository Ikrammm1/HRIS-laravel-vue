<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Menu\MenuController;
use App\Http\Controllers\Api\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
    Route::post('/menu/authorize-menu', [MenuController::class, 'authorizeMenu']);
    Route::post('/menu/list', [MenuController::class, 'index']);
    Route::post('/menu/add', [MenuController::class, 'store']);
    Route::post('/menu/update', [MenuController::class, 'update']);
    Route::post('/menu/delete', [MenuController::class, 'delete']);
    
    //user
    Route::get('/users/list', [UserController::class, 'index']);
    Route::post('/users/add', [UserController::class, 'store']);
    Route::post('/users/update', [UserController::class, 'update']);
    Route::post('/users/delete', [UserController::class, 'delete']);
    
    // Add other protected routes here
    // Route::apiResource('users', UserController::class);
    // Route::apiResource('employees', EmployeeController::class);
});