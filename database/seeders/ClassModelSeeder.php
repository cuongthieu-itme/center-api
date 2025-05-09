<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassModelSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy thông tin giáo viên
        $teachers = Teacher::all();
        
        // Tạo 4 lớp học với dữ liệu tiếng Việt
        ClassModel::create([
            'class_name' => 'Lớp từ vựng',
            'teacher_id' => $teachers[0]->id
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp ngữ pháp',
            'teacher_id' => $teachers[1]->id
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp giao tiếp',
            'teacher_id' => $teachers[2]->id
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp tổng hợp',
            'teacher_id' => $teachers[3]->id
        ]);
    }
} 