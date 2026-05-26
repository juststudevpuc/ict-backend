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
    Route::get("/search", [StudentController::class, "search"]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::prefix("enrollment")->group(function () {
    //     Route::get("/", [EnrollmentController::class, "index"]);
    //     Route::get("/search", [EnrollmentController::class, "search"]);
    //     Route::get("/{id}", [EnrollmentController::class, "show"]);
    //     Route::post("/", [EnrollmentController::class, "store"]);
    //     Route::put("/{id}", [EnrollmentController::class, "update"]);
    // });

    Route::prefix("student")->group(function () {
        Route::get("/{id}", [StudentController::class, "show"]);
    });
});

Route::middleware(['auth:sanctum', 'checkAdmin'])->prefix('admin')->group(function () {
    Route::get('/users', function () {
        return response()->json([
            'data' => User::all()
        ]);
    });

    Route::prefix("employee")->group(function () {
        Route::post("/", [EmployeeController::class, "store"]);
        Route::put("/{id}", [EmployeeController::class, "update"]);
        Route::delete("/{id}", [EmployeeController::class, "destroy"]);
    });

    Route::prefix("course")->group(function () {
        Route::get("/", [CourseController::class, "index"]);
        Route::get("/{id}", [CourseController::class, "show"]);
        Route::post("/", [CourseController::class, "store"]);
        Route::put("/{id}", [CourseController::class, "update"]);
        Route::delete("/{id}", [CourseController::class, "destroy"]);
    });

    Route::prefix("schedule")->group(function () {
        Route::get("/", [ScheduleController::class, "index"]);
        Route::get("/{id}", [ScheduleController::class, "show"]);
        Route::post("/", [ScheduleController::class, "store"]);
        Route::put("/{id}", [ScheduleController::class, "update"]);
        Route::delete("/{id}", [ScheduleController::class, "destroy"]);
    });

    Route::prefix("instructor")->group(function () {
        Route::get("/", [InstructorController::class, "index"]);
        Route::get("/{id}", [InstructorController::class, "show"]);
        Route::post("/", [InstructorController::class, "store"]);
        Route::put("/{id}", [InstructorController::class, "update"]);
        Route::delete("/{id}", [InstructorController::class, "destroy"]);
    });

    Route::prefix("enrollment")->group(function () {
        Route::get("/", [EnrollmentController::class, "index"]);
        Route::get("/search", [EnrollmentController::class, "search"]);
        Route::get("/{id}", [EnrollmentController::class, "show"]);
        Route::post("/", [EnrollmentController::class, "store"]);
        Route::put("/{id}", [EnrollmentController::class, "update"]);
        Route::delete("/{id}", [EnrollmentController::class, "destroy"]);

    });

    Route::prefix("student")->group(function () {
        Route::post("/", [StudentController::class, "store"]);
        Route::put("/{id}", [StudentController::class, "update"]);
        Route::delete("/{id}", [StudentController::class, "destroy"]);
    });

    Route::prefix("courseDetail")->group(function () {
        Route::get("/", [CourseDetailController::class, "index"]);
        Route::post("/", [CourseDetailController::class, "store"]);
        Route::put("/{id}", [CourseDetailController::class, "update"]);
        Route::delete("/{id}", [CourseDetailController::class, "destroy"]);
    });
});
