<?php

namespace App\Repositories;

use App\Models\ClassModel;
use Illuminate\Database\QueryException;
use Exception;

class ClassModelRepository
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);

            $query = ClassModel::with(['teacher', 'teacher.user']);

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
                    if (in_array($column, (new ClassModel())->getFillable())) {
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
            logger()->error('Lỗi khi lấy danh sách lớp học: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách lớp học.');
        }
    }

    public function show($id)
    {
        try {
            return ClassModel::with(['teacher', 'teacher.user'])->findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm lớp học với ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy lớp học.');
        }
    }

    public function store($data)
    {
        try {
            return ClassModel::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo lớp học: ' . $e->getMessage());
            throw new Exception('Tạo lớp học thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo lớp học: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $class->update($data);
            return $class;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật lớp học ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật lớp học thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật lớp học ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $class->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa lớp học ID $id: " . $e->getMessage());
            throw new Exception('Xóa lớp học thất bại.');
        }
    }

    public function getStudentsByClass($classId)
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);
            
            $class = ClassModel::findOrFail($classId);
            
            // Get student query builder to apply pagination
            $studentsQuery = $class->students();
            
            // Get all request parameters for filtering
            $params = request()->all();
            
            // Apply filters if needed (similar to index method)
            foreach ($params as $key => $value) {
                // Skip pagination parameters
                if (in_array($key, ['per_page', 'page'])) {
                    continue;
                }
                
                // Check if the column exists in the pivot table or the student model
                if (!empty($value)) {
                    // For string fields, use LIKE for partial matching
                    if (is_string($value) && !is_numeric($value)) {
                        $studentsQuery->where($key, 'LIKE', '%' . $value . '%');
                    } else {
                        // For other fields, use exact matching
                        $studentsQuery->where($key, $value);
                    }
                }
            }
            
            return $studentsQuery->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy danh sách học sinh của lớp học ID $classId: " . $e->getMessage());
            throw new Exception('Không thể lấy danh sách học sinh của lớp học.');
        }
    }

    public function getSessions($classId)
    {
        try {
            $class = ClassModel::with('classSessions')->findOrFail($classId);
            return $class->classSessions()->orderBy('session_date')->get();
        } catch (\Exception $e) {
            logger()->error("Lỗi khi lấy buổi học của lớp $classId: " . $e->getMessage());
            throw new \Exception('Không thể lấy danh sách buổi học.');
        }
    }

    public function getClassesByTeacher($teacherId)
    {
        try {
            return ClassModel::where('teacher_id', $teacherId)
                ->with('teacher')
                ->get();
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy lớp học của giáo viên ID $teacherId: " . $e->getMessage());
            throw new Exception('Không thể lấy lớp học của giáo viên.');
        }
    }
}
