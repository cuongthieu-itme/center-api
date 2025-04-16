<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function index($perPage = 20, $page = 1)
    {
        try {
            $query = User::query();
            
            // Get all request parameters
            $params = request()->all();
            
            // Filter by fields
            foreach ($params as $key => $value) {
                // Skip pagination parameters
                if (in_array($key, ['per_page', 'page'])) {
                    continue;
                }
                
                // Apply filters based on field type
                if (!empty($value)) {
                    $column = $key;
                    
                    // Check if column exists in the table
                    if (in_array($column, (new User())->getFillable())) {
                        // For string fields, use LIKE for partial matching
                        if (is_string($value) && !is_numeric($value)) {
                            $query->where($column, 'LIKE', '%' . $value . '%');
                        } else {
                            // For other fields, use exact matching
                            $query->where($column, $value);
                        }
                    }
                }
            }
            
            // Load appropriate relationship based on role
            $query->with(['teacher', 'student']);
            
            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy danh sách người dùng: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách người dùng.');
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Load teacher or student relationships based on role
            if ($user->role === 'teacher') {
                $user->load('teacher');
            } elseif ($user->role === 'student') {
                $user->load('student');
            }
            
            return $user;
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy thông tin người dùng: ' . $e->getMessage());
            throw new Exception('Không thể tìm thấy người dùng với ID: ' . $id);
        }
    }

    public function store($data)
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'] ?? 'user',
            ]);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo người dùng: ' . $e->getMessage());
            throw new Exception('Tạo người dùng thất bại. Vui lòng thử lại sau.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo người dùng: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $user = User::findOrFail($id);

            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = $data['name'];
            if (isset($data['email'])) $updateData['email'] = $data['email'];
            if (isset($data['role'])) $updateData['role'] = $data['role'];
            if (isset($data['password'])) $updateData['password'] = bcrypt($data['password']);

            $user->update($updateData);
            return $user;
        } catch (Exception $e) {
            logger()->error('Lỗi khi cập nhật người dùng: ' . $e->getMessage());
            throw new Exception('Cập nhật người dùng thất bại.');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return true;
        } catch (Exception $e) {
            logger()->error('Lỗi khi xóa người dùng: ' . $e->getMessage());
            throw new Exception('Xóa người dùng thất bại.');
        }
    }
}
