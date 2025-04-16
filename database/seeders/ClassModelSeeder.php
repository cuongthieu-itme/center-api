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
            'teacher_id' => $teachers[0]->id,
            'schedule' => 'Thứ 2, Thứ 4 (18:00 - 20:00)'
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp ngữ pháp',
            'teacher_id' => $teachers[1]->id,
            'schedule' => 'Thứ 3, Thứ 5 (17:30 - 19:30)'
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp giao tiếp',
            'teacher_id' => $teachers[2]->id,
            'schedule' => 'Thứ 4, Thứ 6 (19:00 - 21:00)'
        ]);
        
        ClassModel::create([
            'class_name' => 'Lớp tổng hợp',
            'teacher_id' => $teachers[3]->id,
            'schedule' => 'Thứ 7 (8:00 - 11:30)'
        ]);
    }
} 