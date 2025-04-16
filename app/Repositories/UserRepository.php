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
            // Start a database transaction
            \DB::beginTransaction();
            
            // Create the user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'] ?? 'user',
            ]);
            
            // If role is teacher, create a teacher record
            if ($user->role === 'teacher') {
                $user->teacher()->create([
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'user_id' => $user->id
                ]);
            }
            
            // If role is student, create a student record
            if ($user->role === 'student') {
                $user->student()->create([
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'user_id' => $user->id
                ]);
            }
            
            // Commit the transaction
            \DB::commit();
            
            return $user;
        } catch (QueryException $e) {
            // Rollback the transaction
            \DB::rollBack();
            logger()->error('Lỗi DB khi tạo người dùng: ' . $e->getMessage());
            throw new Exception('Tạo người dùng thất bại. Vui lòng thử lại sau.');
        } catch (Exception $e) {
            // Rollback the transaction
            \DB::rollBack();
            logger()->error('Lỗi khi tạo người dùng: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            // Start a database transaction
            \DB::beginTransaction();
            
            $user = User::findOrFail($id);
            $oldRole = $user->role;

            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = $data['name'];
            if (isset($data['email'])) $updateData['email'] = $data['email'];
            if (isset($data['role'])) $updateData['role'] = $data['role'];
            if (isset($data['password'])) $updateData['password'] = bcrypt($data['password']);

            $user->update($updateData);
            
            // If role has changed, handle the associated records
            if (isset($data['role']) && $oldRole !== $data['role']) {
                // If old role was teacher or student, we might need to delete the old record
                // (or you could keep it, based on your business logic)
                
                // If new role is teacher, create teacher record
                if ($data['role'] === 'teacher') {
                    // Check if teacher record already exists
                    if (!$user->teacher) {
                        $user->teacher()->create([
                            'full_name' => $user->name,
                            'email' => $user->email,
                            'user_id' => $user->id
                        ]);
                    }
                }
                
                // If new role is student, create student record
                if ($data['role'] === 'student') {
                    // Check if student record already exists
                    if (!$user->student) {
                        $user->student()->create([
                            'full_name' => $user->name,
                            'email' => $user->email,
                            'user_id' => $user->id
                        ]);
                    }
                }
            }
            
            // Update related teacher or student record information if needed
            if (isset($data['name']) || isset($data['email'])) {
                if ($user->role === 'teacher' && $user->teacher) {
                    $teacherUpdate = [];
                    if (isset($data['name'])) $teacherUpdate['full_name'] = $data['name'];
                    if (isset($data['email'])) $teacherUpdate['email'] = $data['email'];
                    
                    if (!empty($teacherUpdate)) {
                        $user->teacher->update($teacherUpdate);
                    }
                } elseif ($user->role === 'student' && $user->student) {
                    $studentUpdate = [];
                    if (isset($data['name'])) $studentUpdate['full_name'] = $data['name'];
                    if (isset($data['email'])) $studentUpdate['email'] = $data['email'];
                    
                    if (!empty($studentUpdate)) {
                        $user->student->update($studentUpdate);
                    }
                }
            }
            
            // Commit the transaction
            \DB::commit();
            
            return $user;
        } catch (Exception $e) {
            // Rollback the transaction
            \DB::rollBack();
            logger()->error('Lỗi khi cập nhật người dùng: ' . $e->getMessage());
            throw new Exception('Cập nhật người dùng thất bại.');
        }
    }

    public function destroy($id)
    {
        try {
            // Start a database transaction
            \DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Delete associated teacher or student record if exists
            if ($user->role === 'teacher' && $user->teacher) {
                $user->teacher->delete();
            } elseif ($user->role === 'student' && $user->student) {
                $user->student->delete();
            }
            
            $user->delete();
            
            // Commit the transaction
            \DB::commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback the transaction
            \DB::rollBack();
            logger()->error('Lỗi khi xóa người dùng: ' . $e->getMessage());
            throw new Exception('Xóa người dùng thất bại.');
        }
    }
}
