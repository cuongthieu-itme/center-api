<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Exception;

class StudentRepository implements StudentRepositoryInterface
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 10);
            return Student::paginate($perPage);
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
