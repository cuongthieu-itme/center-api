<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassModelController;
use App\Http\Controllers\Api\ClassSessionController;
use App\Http\Controllers\Api\StudentClassController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UploadFileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/save-attendance', [AttendanceController::class, 'saveAttendance']);
Route::get('/export-attandance', [AttendanceController::class, 'exportAttendance']);
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('verify-auth', [AuthController::class, 'verifyAuth']);
    });
});

// Các route cần xác thực người dùng trước
Route::middleware('auth:sanctum')->group(function () {

    // Admin có thể truy cập và quản lý tất cả các tài nguyên
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('students', StudentController::class);
        Route::apiResource('teachers', TeacherController::class);
        Route::apiResource('classes', ClassModelController::class);
        Route::apiResource('student-classes', StudentClassController::class);
        Route::apiResource('class-sessions', ClassSessionController::class);
        Route::apiResource('attendance', AttendanceController::class);

        Route::get('teacher/{teacherId}/classes', [ClassModelController::class, 'getClassesByTeacher']);
        Route::get('teacher/{id}/students', [TeacherController::class, 'getStudents']);
        Route::get('students/{studentId}/classes', [StudentClassController::class, 'getClassesByStudentId']);
        Route::get('students/{studentId}/attendance', [AttendanceController::class, 'getStudentAttendance']);
    });

    // Teacher có thể quản lý lớp học và điểm danh của lớp mình
    Route::middleware('role:teacher')->group(function () {
        Route::get('classes/{id}/students', [ClassModelController::class, 'getStudentsByClass']);
        Route::get('students/{id}/attendance-history', [StudentController::class, 'getAttendanceHistory']);
        Route::get('classes/{id}/sessions', [ClassModelController::class, 'getClassSessions']);
        Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
    });

    // Student có thể xem thông tin cá nhân và điểm danh của mình
    Route::middleware('role:student')->group(function () {
        Route::get('students/{id}/attendance-history', [StudentController::class, 'getAttendanceHistory']);
        // Add this route to your existing routes
        Route::post('/students/change-password', [StudentController::class, 'changePassword']);
    });

    // Upload file
    Route::post('/upload', [UploadFileController::class, 'upload']);
});
