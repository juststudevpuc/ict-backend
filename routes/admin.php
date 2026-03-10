<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseDetailController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

// =======================================================
// 🛑 ADMIN MUTATIONS (Prefix: /api/admin/...)
// Automatically protected by auth:sanctum & checkAdmin
// =======================================================

Route::prefix("employee")->group(function () {
    Route::post("/", [EmployeeController::class, "store"]);
    Route::put("/{id}", [EmployeeController::class, "update"]);
    Route::delete("/{id}", [EmployeeController::class, "destroy"]);
});

Route::prefix("course")->group(function () {
    Route::post("/", [CourseController::class, "store"]);
    Route::put("/{id}", [CourseController::class, "update"]);
    Route::delete("/{id}", [CourseController::class, "destroy"]);
});

Route::prefix("schedule")->group(function () {
    Route::post("/", [ScheduleController::class, "store"]);
    Route::put("/{id}", [ScheduleController::class, "update"]);
    Route::delete("/{id}", [ScheduleController::class, "destroy"]);
});

Route::prefix("instructor")->group(function () {
    Route::post("/", [InstructorController::class, "store"]);
    Route::put("/{id}", [InstructorController::class, "update"]);
    Route::delete("/{id}", [InstructorController::class, "destroy"]);
});

Route::prefix("enrollment")->group(function () {
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
    Route::post("/", [CourseDetailController::class, "store"]);
    Route::put("/{id}", [CourseDetailController::class, "update"]);
    Route::delete("/{id}", [CourseDetailController::class, "destroy"]);
});
