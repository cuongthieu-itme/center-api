<?php

namespace App\Repositories;

use App\Interfaces\ClassSessionRepositoryInterface;
use App\Models\ClassSession;
use Illuminate\Database\QueryException;
use Exception;

class ClassSessionRepository implements ClassSessionRepositoryInterface
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 20);
            return ClassSession::paginate($perPage);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy danh sách buổi học: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách buổi học.');
        }
    }

    public function show($id)
    {
        try {
            return ClassSession::findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm buổi học ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy buổi học.');
        }
    }

    public function store($data)
    {
        try {
            return ClassSession::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo buổi học: ' . $e->getMessage());
            throw new Exception('Tạo buổi học thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo buổi học: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $classSession = ClassSession::findOrFail($id);
            $classSession->update($data);
            return $classSession;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật buổi học ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật buổi học thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật buổi học ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $classSession = ClassSession::findOrFail($id);
            $classSession->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa buổi học ID $id: " . $e->getMessage());
            throw new Exception('Xóa buổi học thất bại.');
        }
    }
}
