<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user có role là student
        $studentUser1 = User::where('email', 'user_center@gmail.com')->first();
        $studentUser2 = User::where('email', 'levancuong@gmail.com')->first();
        $studentUser3 = User::where('email', 'phamthidung@gmail.com')->first();
        $studentUser4 = User::where('email', 'hoangvanem@gmail.com')->first();
        
        // Tạo 5 học sinh với dữ liệu tiếng Việt
        Student::create([
            'full_name' => 'Nguyễn Văn Học',
            'dob' => '2000-05-15',
            'phone' => '0971234567',
            'email' => 'user_center@gmail.com',
            'address' => 'Số 123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh',
            'avatar_url' => 'avatars/student1.jpg',
            'user_id' => $studentUser1->id
        ]);
        
        Student::create([
            'full_name' => 'Lê Văn Cường',
            'dob' => '2001-07-22',
            'phone' => '0972345678',
            'email' => 'levancuong@gmail.com',
            'address' => 'Số 45 Đường Nguyễn Huệ, Quận 2, TP. Hồ Chí Minh',
            'avatar_url' => 'avatars/student2.jpg',
            'user_id' => $studentUser2->id
        ]);
        
        Student::create([
            'full_name' => 'Phạm Thị Dung',
            'dob' => '2002-03-10',
            'phone' => '0973456789',
            'email' => 'phamthidung@gmail.com',
            'address' => 'Số 67 Đường Trần Hưng Đạo, Quận 5, TP. Hồ Chí Minh',
            'avatar_url' => 'avatars/student3.jpg',
            'user_id' => $studentUser3->id
        ]);
        
        Student::create([
            'full_name' => 'Hoàng Văn Em',
            'dob' => '2001-11-30',
            'phone' => '0974567890',
            'email' => 'hoangvanem@gmail.com',
            'address' => 'Số 89 Đường Lý Tự Trọng, Quận 3, TP. Hồ Chí Minh',
            'avatar_url' => 'avatars/student4.jpg',
            'user_id' => $studentUser4->id
        ]);
        
        Student::create([
            'full_name' => 'Trịnh Thị Lan',
            'dob' => '2000-09-20',
            'phone' => '0975678901',
            'email' => 'trinhthilan@gmail.com',
            'address' => 'Số 111 Đường Phan Đình Phùng, Quận Phú Nhuận, TP. Hồ Chí Minh',
            'avatar_url' => 'avatars/student5.jpg',
            'user_id' => $studentUser1->id
        ]);
    }
} 