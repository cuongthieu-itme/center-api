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
            $page = request()->get('page', 1);
            
            $query = ClassSession::query()->with('classModel');
            
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
                    if (in_array($column, (new ClassSession())->getFillable())) {
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
            logger()->error('Lỗi khi lấy danh sách buổi học: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách buổi học.');
        }
    }

    public function show($id)
    {
        try {
            return ClassSession::with('classModel')->findOrFail($id);
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
