<?php

namespace App\Repositories;

use App\Models\ClassSession;
use Illuminate\Database\QueryException;
use Exception;

class ClassSessionRepository
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

    public function getByClassIDsAndDateTime(array $classIDs, string $dateNow, mixed $time_in)
    {
        $classSessions = ClassSession::whereIn('class_id', $classIDs)->where('session_date', $dateNow)->where('start_time', '<=', $time_in)->where('end_time', '>=', $time_in)->first();
        return $classSessions;
    }

    /**
     * Get sessions by class IDs with optional date filters
     *
     * @param array $classIds
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSessionsByClassIds(array $classIds, $fromDate = null, $toDate = null)
    {
        try {
            $perPage = request()->get('per_page', 10);
            $page = request()->get('page', 1);

            $query = ClassSession::whereIn('class_id', $classIds)
                ->with('classModel');
            
            // Apply date filters if provided
            if ($fromDate) {
                $query->where('session_date', '>=', $fromDate);
            }
            
            if ($toDate) {
                $query->where('session_date', '<=', $toDate);
            }
            
            // Order by date and time
            $query->orderBy('session_date', 'asc')
                  ->orderBy('start_time', 'asc');
            
            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy lịch học: ' . $e->getMessage());
            throw new Exception('Không thể lấy lịch học.');
        }
    }
}
