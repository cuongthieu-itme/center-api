<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassModelController;
use App\Http\Controllers\Api\ClassSessionController;
use App\Http\Controllers\Api\StudentClassController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UploadFileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // CRUD full model
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('classes', ClassModelController::class);
    Route::apiResource('student-classes', StudentClassController::class);
    Route::apiResource('class-sessions', ClassSessionController::class);
    Route::apiResource('attendance', AttendanceController::class);

    // Lấy danh sách học sinh theo lớp
    Route::get('classes/{id}/students', [ClassModelController::class, 'getStudentsInClass']);
    // Lấy lịch sử điểm danh của 1 học sinh
    Route::get('students/{id}/attendance-history', [StudentController::class, 'getAttendanceHistory']);
    // Lấy danh sách buổi học của 1 lớp
    Route::get('classes/{id}/sessions', [ClassModelController::class, 'getClassSessions']);
    // Gửi điểm danh hàng loạt
    Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
    // API thống kê (nếu cần phát triển sau)
    // Tất cả lớp của một giáo viên
    Route::get('teacher/{teacherId}/classes', [ClassModelController::class, 'getClassesByTeacher']);
    // Upload file
    Route::post('/upload', [UploadFileController::class, 'upload']);
});
