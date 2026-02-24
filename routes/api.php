<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Menu\MenuController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\AuthorizationGroup\AuthorizationGroupController;
use App\Http\Controllers\Api\Organization\DivisionController;
use App\Http\Controllers\Api\Organization\DepartmentController;
use App\Http\Controllers\Api\Employe\EmployeController;
use App\Http\Controllers\Api\Setup\ShiftController;
use App\Http\Controllers\Api\Setup\LeaveTypeController;
use App\Http\Controllers\Api\Setup\WorkScheduleController;
use App\Http\Controllers\Api\Setup\HolidayCalendarController;


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

    //Organization
    Route::get('/divisions/list', [DivisionController::class, 'index']);
    Route::post('/divisions/add', [DivisionController::class, 'store']);
    Route::post('/divisions/update/{id}', [DivisionController::class, 'update']);
    Route::delete('/divisions/delete/{id}', [DivisionController::class, 'destroy']);
    
    Route::get('/departments/list', [DepartmentController::class, 'index']);
    Route::post('/departments/add', [DepartmentController::class, 'store']);
    Route::post('/departments/update/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/delete/{id}', [DepartmentController::class, 'destroy']);
    
    //employes
    Route::get('/employees/list', [EmployeController::class, 'index']);
    Route::post('/employees/add', [EmployeController::class, 'store']);
    Route::post('/employees/update/{id}', [EmployeController::class, 'update']);
    Route::delete('/employees/delete/{id}', [EmployeController::class, 'destroy']);

    //setup
    Route::get('/setup/shifts/list', [ShiftController::class, 'index']);
    Route::post('/setup/shifts/add', [ShiftController::class, 'store']);
    Route::post('/setup/shifts/update/{id}', [ShiftController::class, 'update']);
    Route::delete('/setup/shifts/delete/{id}', [ShiftController::class, 'destroy']);

    Route::get('/setup/work-schedules/list', [WorkScheduleController::class, 'index']);
    Route::post('/setup/work-schedules/add', [WorkScheduleController::class, 'store']);
    Route::post('/setup/work-schedules/update/{id}', [WorkScheduleController::class, 'update']);
    Route::delete('/setup/work-schedules/delete/{id}', [WorkScheduleController::class, 'destroy']);

    // Add other protected routes here
    // Route::apiResource('users', UserController::class);
    // Route::apiResource('employees', EmployeeController::class);
});