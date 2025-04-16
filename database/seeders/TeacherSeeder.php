<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user có role là teacher
        $teacherUser1 = User::where('email', 'teacher_center@gmail.com')->first();
        $teacherUser2 = User::where('email', 'nguyenvanan@gmail.com')->first();
        $teacherUser3 = User::where('email', 'tranthbinh@gmail.com')->first();
        
        // Tạo 5 giáo viên với dữ liệu tiếng Việt
        Teacher::create([
            'full_name' => 'Trần Văn Hiệu',
            'phone' => '0981234567',
            'email' => 'tranvanhieu@gmail.com',
            'specialization' => 'Từ vựng',
            'avatar_url' => 'avatars/teacher1.jpg',
            'user_id' => $teacherUser1->id
        ]);
        
        Teacher::create([
            'full_name' => 'Nguyễn Văn An',
            'phone' => '0982345678',
            'email' => 'nguyenvanan@gmail.com',
            'specialization' => 'Ngữ pháp',
            'avatar_url' => 'avatars/teacher2.jpg',
            'user_id' => $teacherUser2->id
        ]);
        
        Teacher::create([
            'full_name' => 'Trần Thị Bình',
            'phone' => '0983456789',
            'email' => 'tranthbinh@gmail.com',
            'specialization' => 'Kỹ năng giao tiếp',
            'avatar_url' => 'avatars/teacher3.jpg',
            'user_id' => $teacherUser3->id
        ]);
        
        Teacher::create([
            'full_name' => 'Lê Minh Đức',
            'phone' => '0984567890',
            'email' => 'leminhduc@gmail.com',
            'specialization' => 'Kỹ năng tổng hợp',
            'avatar_url' => 'avatars/teacher4.jpg',
            'user_id' => $teacherUser1->id
        ]);
        
        Teacher::create([
            'full_name' => 'Phạm Thanh Hà',
            'phone' => '0985678901',
            'email' => 'phamthanhha@gmail.com',
            'specialization' => 'Luyện thi',
            'avatar_url' => 'avatars/teacher5.jpg',
            'user_id' => $teacherUser2->id
        ]);
    }
} 