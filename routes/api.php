<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Menu\MenuController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\AuthorizationGroup\AuthorizationGroupController;

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

    //groups
    Route::get('/groups/list', [AuthorizationGroupController::class, 'index']);
    Route::post('/groups/add', [AuthorizationGroupController::class, 'store']);
    Route::post('/groups/update/{id}', [AuthorizationGroupController::class, 'update']);
    Route::delete('/groups/delete/{id}', [AuthorizationGroupController::class, 'destroy']);


    
    // Add other protected routes here
    // Route::apiResource('users', UserController::class);
    // Route::apiResource('employees', EmployeeController::class);
});