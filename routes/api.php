<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseDetailController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\StudentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =======================================================
// 🔓 1. PUBLIC ROUTES (No login required)
// =======================================================
Route::post('/login', [AuthController::class, 'userLogin']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::prefix("course")->group(function () {
    Route::get("/", [CourseController::class, "index"]);
    Route::get("/search", [CourseController::class, "search"]);
    Route::get("/{id}", [CourseController::class, "show"]);
});

Route::prefix("schedule")->group(function () {
    Route::get("/", [ScheduleController::class, "index"]);
    Route::get("/search", [ScheduleController::class, "search"]);
    Route::get("/{id}", [ScheduleController::class, "show"]);
});

Route::prefix("instructor")->group(function () {
    Route::get("/", [InstructorController::class, "index"]);
    Route::get("/search", [InstructorController::class, "search"]);
    Route::get("/{id}", [InstructorController::class, "show"]);
});

Route::prefix("courseDetail")->group(function () {
    Route::get("/", [CourseDetailController::class, "index"]);
    Route::get("/{id}", [CourseDetailController::class, "show"]);
});

Route::prefix("employee")->group(function () {
    Route::get("/", [EmployeeController::class, "index"]);
    Route::get("/{id}", [EmployeeController::class, "show"]);
});

Route::prefix("student")->group(function () {
    Route::get("/", [StudentController::class, "index"]);
    Route::get("/", [StudentController::class, "search"]);
});

// =======================================================
// 🔒 2. USER ROUTES (Must have Sanctum token, ANY role)
// =======================================================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::middleware('checkAdmin')->get('/users', function () {
        // You can return User::all(), or paginate it if you have a lot!
        return response()->json([
            'data' => User::all()
        ]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Regular users CAN read these, but only admins can edit them (in admin.php)
    Route::prefix("enrollment")->group(function () {
        Route::get("/", [EnrollmentController::class, "index"]);
        Route::get("/search", [EnrollmentController::class, "search"]);
        Route::get("/{id}", [EnrollmentController::class, "show"]);
    });

    Route::prefix("student")->group(function () {
        Route::get("/{id}", [StudentController::class, "show"]);
    });
});
