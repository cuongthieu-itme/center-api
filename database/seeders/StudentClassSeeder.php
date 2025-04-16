<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class StudentClassSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy thông tin học sinh và lớp học
        $students = Student::all();
        $classes = ClassModel::all();
        
        // Tạo liên kết học sinh - lớp học
        // Học sinh 1 tham gia lớp 1, 3, 4
        StudentClass::create([
            'student_id' => $students[0]->id,
            'class_id' => $classes[0]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[0]->id,
            'class_id' => $classes[2]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[0]->id,
            'class_id' => $classes[3]->id
        ]);
        
        // Học sinh 2 tham gia lớp 1, 2
        StudentClass::create([
            'student_id' => $students[1]->id,
            'class_id' => $classes[0]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[1]->id,
            'class_id' => $classes[1]->id
        ]);
        
        // Học sinh 3 tham gia lớp 2, 4
        StudentClass::create([
            'student_id' => $students[2]->id,
            'class_id' => $classes[1]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[2]->id,
            'class_id' => $classes[3]->id
        ]);
        
        // Học sinh 4 tham gia lớp 3, 4
        StudentClass::create([
            'student_id' => $students[3]->id,
            'class_id' => $classes[2]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[3]->id,
            'class_id' => $classes[3]->id
        ]);
        
        // Học sinh 5 tham gia lớp 2, 3
        StudentClass::create([
            'student_id' => $students[4]->id,
            'class_id' => $classes[1]->id
        ]);
        
        StudentClass::create([
            'student_id' => $students[4]->id,
            'class_id' => $classes[2]->id
        ]);
    }
} 