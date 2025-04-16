<?php

namespace Database\Seeders;

use App\Models\ClassSession;
use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class ClassSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy thông tin lớp học
        $classes = ClassModel::all();
        
        // Tạo buổi học cho lớp Toán nâng cao
        ClassSession::create([
            'class_id' => $classes[0]->id,
            'session_date' => '2023-11-20',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00'
        ]);
        
        ClassSession::create([
            'class_id' => $classes[0]->id,
            'session_date' => '2023-11-22',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00'
        ]);
        
        // Tạo buổi học cho lớp Vật lý cơ bản
        ClassSession::create([
            'class_id' => $classes[1]->id,
            'session_date' => '2023-11-21',
            'start_time' => '17:30:00',
            'end_time' => '19:30:00'
        ]);
        
        ClassSession::create([
            'class_id' => $classes[1]->id,
            'session_date' => '2023-11-23',
            'start_time' => '17:30:00',
            'end_time' => '19:30:00'
        ]);
        
        // Tạo buổi học cho lớp Hóa học chuyên sâu
        ClassSession::create([
            'class_id' => $classes[2]->id,
            'session_date' => '2023-11-22',
            'start_time' => '19:00:00',
            'end_time' => '21:00:00'
        ]);
    }
} 