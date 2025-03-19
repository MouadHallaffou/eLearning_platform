<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V2\AuthController;
use App\Http\Controllers\V1\StatsController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\PermissionController;
use App\Http\Controllers\V2\EnrollmentController;

// Route pour obtenir l'utilisateur authentifié 
Route::get('/user', function (Request $request) {
    return $request->user();  
})->middleware('auth:api');  

Route::apiResource('/category', CategoryController::class);
Route::apiResource('/tag', TagController::class);
Route::apiResource('/course', CourseController::class);


Route::prefix('V1')->group(function () {
    Route::get('/stats/courses', [StatsController::class, 'getCourseStats']);
    Route::get('/stats/categories', [StatsController::class, 'getCategoryStats']);
    Route::get('/stats/tags', [StatsController::class, 'getTagStats']);
});


Route::prefix('V1')->group(function () {
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll'])->middleware('auth:api');
    Route::get('/courses/{id}/enrollments', [EnrollmentController::class, 'listEnrollments'])->middleware('auth:api');
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);  
    Route::post('/login', [AuthController::class, 'login']); 

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']); 
        Route::post('/refresh', [AuthController::class, 'refresh']); 
        Route::get('/profile', [AuthController::class, 'profile']);  
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);  
    });
});


Route::prefix('V2')->group(function () {
    Route::put('/enrollments/{id}', [EnrollmentController::class, 'updateEnrollment'])->middleware('auth:api');
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment'])->middleware('auth:api');
});


Route::prefix('V1')->group(function () {
    // Routes pour les rôles
    Route::apiResource('roles', RoleController::class);

    // Routes pour les permissions
    Route::apiResource('permissions', PermissionController::class);

    // Routes pour assigner des rôles et permissions aux utilisateurs
    Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole']);
    Route::delete('/users/{userId}/remove-role', [UserController::class, 'removeRole']);
    Route::post('/users/{userId}/assign-permission', [UserController::class, 'assignPermission']);
    Route::delete('/users/{userId}/remove-permission', [UserController::class, 'removePermission']);

    // Routes pour synchroniser les rôles et permissions
    Route::put('/roles/{id}/sync-permissions', [RoleController::class, 'syncPermissions']);
    Route::put('/users/{userId}/sync-roles', [UserController::class, 'syncRoles']);
});