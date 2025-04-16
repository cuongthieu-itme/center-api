<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassSession;
use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy thông tin học sinh, buổi học, và đăng ký lớp
        $students = Student::all();
        $classSessions = ClassSession::all();
        $studentClasses = StudentClass::all();
        
        // Tạo điểm danh cho buổi học Toán nâng cao (Học sinh 1 và 2 học Toán nâng cao)
        // Buổi học 1 - Học sinh 1
        Attendance::create([
            'student_id' => $students[0]->id,
            'session_id' => $classSessions[0]->id,
            'status' => 'present',
            'check_in_time' => '17:55:00',
            'check_out_time' => '20:00:00'
        ]);
        
        // Buổi học 1 - Học sinh 2
        Attendance::create([
            'student_id' => $students[1]->id,
            'session_id' => $classSessions[0]->id,
            'status' => 'present',
            'check_in_time' => '18:05:00',
            'check_out_time' => '20:00:00'
        ]);
        
        // Buổi học 2 - Học sinh 1
        Attendance::create([
            'student_id' => $students[0]->id,
            'session_id' => $classSessions[1]->id,
            'status' => 'absent',
            'check_in_time' => null,
            'check_out_time' => null
        ]);
        
        // Buổi học 2 - Học sinh 2
        Attendance::create([
            'student_id' => $students[1]->id,
            'session_id' => $classSessions[1]->id,
            'status' => 'present',
            'check_in_time' => '17:50:00',
            'check_out_time' => '20:00:00'
        ]);
        
        // Tạo điểm danh cho buổi học Vật lý cơ bản (Học sinh 2 và 3 học Vật lý cơ bản)
        // Buổi học 3 - Học sinh 2
        Attendance::create([
            'student_id' => $students[1]->id,
            'session_id' => $classSessions[2]->id,
            'status' => 'present',
            'check_in_time' => '17:25:00',
            'check_out_time' => '19:30:00'
        ]);
    }
} 