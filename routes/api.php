<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V2\VideoController;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V2\AuthController;
use App\Http\Controllers\V1\StatsController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\PermissionController;
use App\Http\Controllers\V2\EnrollmentController;
use App\Http\Controllers\V2\MentorController;
use App\Http\Controllers\V2\StudentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Routes d'authentification
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/update-profile', [AuthController::class, 'updateProfile']);

    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/tags', TagController::class);
    Route::apiResource('/courses', CourseController::class);
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::post('/video', [VideoController::class, 'store']);
    Route::post('/videos/{id}', [VideoController::class, 'update']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

    Route::prefix('V1')->group(function () {
        Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
        Route::get('/courses/{id}/enrollments', [EnrollmentController::class, 'listEnrollments']);
    });

    Route::prefix('V2')->middleware(['auth:api', 'role:admin|mentor'])->group(function () {
        Route::put('/enrollments/{id}', [EnrollmentController::class, 'updateEnrollment']);
        Route::delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment']);
    });

    Route::prefix('V1')->group(function () {
        Route::get('/stats/courses', [StatsController::class, 'getCourseStats']);
        Route::get('/stats/categories', [StatsController::class, 'getCategoryStats']);
        Route::get('/stats/tags', [StatsController::class, 'getTagStats']);
    });

    Route::prefix('V1')->group(function () {
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/permissions', PermissionController::class);
        Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole']);
        Route::delete('/users/{userId}/remove-role', [UserController::class, 'removeRole']);
        Route::post('/users/{userId}/assign-permission', [UserController::class, 'assignPermission']);
        Route::delete('/users/{userId}/remove-permission', [UserController::class, 'removePermission']);
        Route::put('/roles/{id}/sync-permissions', [RoleController::class, 'syncPermissions']);
        Route::put('/users/{userId}/sync-roles', [UserController::class, 'syncRoles']);
    });
});


Route::prefix('V1')->group(function () {
    // Mentor
    Route::get('/mentors/{id}/courses', [MentorController::class, 'getCourses']);
    Route::get('/mentors/{id}/students', [MentorController::class, 'getStudents']);
    Route::get('/mentors/{id}/performance', [MentorController::class, 'getPerformance']);
    // Student
    Route::get('/students/{id}/courses', [StudentController::class, 'getCourses']);
    Route::get('/students/{id}/progress', [StudentController::class, 'getProgress']);
    Route::get('/students/{id}/badges', [StudentController::class, 'getBadges']);
});