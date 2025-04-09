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
            $perPage = request()->get('per_page', 10);
            return Teacher::paginate($perPage);
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
}
