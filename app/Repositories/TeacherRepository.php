<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Exception;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);
            
            $query = Teacher::query();
            
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
                    if (in_array($column, (new Teacher())->getFillable())) {
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
            logger()->error('Lỗi khi lấy danh sách giáo viên: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách giáo viên.');
        }
    }

    public function show($id)
    {
        try {
            return Teacher::findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm giáo viên với ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy giáo viên.');
        }
    }

    public function store($data)
    {
        try {
            return Teacher::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo giáo viên: ' . $e->getMessage());
            throw new Exception('Tạo giáo viên thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo giáo viên: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->update($data);
            return $teacher;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật giáo viên ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật giáo viên thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật giáo viên ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa giáo viên ID $id: " . $e->getMessage());
            throw new Exception('Xóa giáo viên thất bại.');
        }
    }

    public function getStudentsByTeacherId($id)
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);
            $params = request()->all();
            
            $teacher = Teacher::findOrFail($id);
            
            // Lấy tất cả lớp học của giáo viên này
            $classes = $teacher->classes;
            
            // Thu thập tất cả học sinh từ các lớp học
            $studentsCollection = collect();
            
            foreach ($classes as $class) {
                $students = $class->students;
                $studentsCollection = $studentsCollection->concat($students);
            }
            
            // Loại bỏ các học sinh trùng lặp
            $studentsCollection = $studentsCollection->unique('id');
            
            // Áp dụng bộ lọc
            foreach ($params as $key => $value) {
                // Bỏ qua tham số phân trang
                if (in_array($key, ['per_page', 'page'])) {
                    continue;
                }
                
                // Áp dụng bộ lọc dựa trên loại trường
                if (!empty($value)) {
                    $studentsCollection = $studentsCollection->filter(function ($student) use ($key, $value) {
                        // Kiểm tra nếu trường tồn tại trong model
                        if (in_array($key, (new \App\Models\Student())->getFillable())) {
                            // Đối với trường chuỗi, sử dụng contains cho tìm kiếm một phần
                            if (is_string($value) && !is_numeric($value)) {
                                return str_contains(strtolower($student->$key), strtolower($value));
                            } else {
                                // Đối với các trường khác, sử dụng so sánh chính xác
                                return $student->$key == $value;
                            }
                        }
                        return true;
                    });
                }
            }
            
            // Phân trang thủ công
            $total = $studentsCollection->count();
            $lastPage = ceil($total / $perPage);
            $items = $studentsCollection->forPage($page, $perPage)->values();
            
            // Tạo đối tượng phân trang thủ công
            $paginatedResult = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            return $paginatedResult;
            
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy danh sách học sinh của giáo viên ID $id: " . $e->getMessage());
            throw new Exception('Không thể lấy danh sách học sinh của giáo viên.');
        }
    }
}
