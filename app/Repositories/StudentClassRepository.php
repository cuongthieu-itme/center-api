<?php

namespace App\Repositories;

use App\Interfaces\StudentClassRepositoryInterface;
use App\Models\StudentClass;
use Illuminate\Database\QueryException;
use Exception;

class StudentClassRepository implements StudentClassRepositoryInterface
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);
            
            $query = StudentClass::query();
            
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
                    if (in_array($column, (new StudentClass())->getFillable())) {
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
            
            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy danh sách học sinh trong lớp: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách học sinh trong lớp.');
        }
    }

    public function show($id)
    {
        try {
            return StudentClass::findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm bản ghi student_class với ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy thông tin học sinh trong lớp.');
        }
    }

    public function store($data)
    {
        try {
            return StudentClass::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo bản ghi student_class: ' . $e->getMessage());
            throw new Exception('Thêm học sinh vào lớp thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi thêm học sinh vào lớp: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $studentClass = StudentClass::findOrFail($id);
            $studentClass->update($data);
            return $studentClass;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật student_class ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật bản ghi học sinh trong lớp thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật student_class ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $studentClass = StudentClass::findOrFail($id);
            $studentClass->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa student_class ID $id: " . $e->getMessage());
            throw new Exception('Xóa học sinh khỏi lớp thất bại.');
        }
    }

    public function getClassesByStudentId($studentId)
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);
            
            $query = StudentClass::where('student_id', $studentId)
                ->with(['classModel' => function($query) {
                    $query->with('teacher');
                }]);
            
            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy danh sách lớp học của học sinh ID $studentId: " . $e->getMessage());
            throw new Exception('Không thể lấy danh sách lớp học của học sinh.');
        }
    }
}
