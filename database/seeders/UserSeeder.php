<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Center',
            'email' => 'admin_center@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Teacher Center',
            'email' => 'teacher_center@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'User Center',
            'email' => 'user_center@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'student',
        ]);

        // 5 người dùng mới với tên tiếng Việt
        User::create([
            'name' => 'Nguyễn Văn An',
            'email' => 'nguyenvanan@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'teacher',
        ]);
        
        User::create([
            'name' => 'Trần Thị Bình',
            'email' => 'tranthbinh@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'teacher',
        ]);
        
        User::create([
            'name' => 'Lê Văn Cường',
            'email' => 'levancuong@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'student',
        ]);
        
        User::create([
            'name' => 'Phạm Thị Dung',
            'email' => 'phamthidung@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'student',
        ]);
        
        User::create([
            'name' => 'Hoàng Văn Em',
            'email' => 'hoangvanem@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'student',
        ]);
    }
}
