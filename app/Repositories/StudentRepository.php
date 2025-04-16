<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Exception;

class StudentRepository implements StudentRepositoryInterface
{
    public function index($perPage = 20, $page = 1)
    {
        try {
            $perPage = request()->get('per_page', $perPage);
            $page = request()->get('page', $page);
            
            $query = Student::query();
            
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
                    if (in_array($column, (new Student())->getFillable())) {
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
            logger()->error('Lỗi khi lấy danh sách học viên: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách học viên.');
        }
    }

    public function show($id)
    {
        try {
            return Student::findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm học viên với ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy học viên.');
        }
    }

    public function store($data)
    {
        try {
            return Student::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo học viên: ' . $e->getMessage());
            throw new Exception('Tạo học viên thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo học viên: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $student = Student::findOrFail($id);
            $student->update($data);
            return $student;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật học viên ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật học viên thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật học viên ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa học viên ID $id: " . $e->getMessage());
            throw new Exception('Xóa học viên thất bại.');
        }
    }

    public function getAttendanceHistory($studentId)
    {
        try {
            $attendanceHistory = Attendance::with(['classSession', 'classSession.class'])
                ->where('student_id', $studentId)
                ->get();

            return $attendanceHistory;
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy lịch sử điểm danh của học sinh ID $studentId: " . $e->getMessage());
            throw new Exception('Không thể lấy lịch sử điểm danh.');
        }
    }
}
