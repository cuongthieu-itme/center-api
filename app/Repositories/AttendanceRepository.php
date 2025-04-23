<?php

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);

            $query = Attendance::with(['student', 'classSession']);
            $params = request()->all();
            foreach ($params as $key => $value) {

                if (in_array($key, ['per_page', 'page'])) {
                    continue;
                }
                if (!empty($value)) {
                    $column = $key;
                    if (in_array($column, (new Attendance())->getFillable())) {

                        if (is_string($value) && !is_numeric($value)) {
                            $query->where($column, 'LIKE', '%' . $value . '%');
                        } else {

                            $query->where($column, $value);
                        }
                    }
                }
            }
            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy danh sách điểm danh: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách điểm danh.');
        }
    }

    public function show($id)
    {
        try {
            return Attendance::with(['student', 'classSession'])->findOrFail($id);
        } catch (Exception $e) {
            logger()->error("Lỗi khi tìm điểm danh ID $id: " . $e->getMessage());
            throw new Exception('Không tìm thấy bản ghi điểm danh.');
        }
    }

    public function store($data)
    {
        try {
            return Attendance::create($data);
        } catch (QueryException $e) {
            logger()->error('Lỗi DB khi tạo điểm danh: ' . $e->getMessage());
            throw new Exception('Tạo bản ghi điểm danh thất bại.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi tạo điểm danh: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->update($data);
            return $attendance;
        } catch (QueryException $e) {
            logger()->error("Lỗi DB khi cập nhật điểm danh ID $id: " . $e->getMessage());
            throw new Exception('Cập nhật điểm danh thất bại.');
        } catch (Exception $e) {
            logger()->error("Lỗi khi cập nhật điểm danh ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();
            return true;
        } catch (Exception $e) {
            logger()->error("Lỗi khi xóa điểm danh ID $id: " . $e->getMessage());
            throw new Exception('Xóa bản ghi điểm danh thất bại.');
        }
    }

    public function bulkStore(array $data)
    {
        try {
            DB::beginTransaction();

            foreach ($data['attendances'] as $attendance) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendance['student_id'],
                        'session_id' => $data['session_id']
                    ],
                    [
                        'status' => $attendance['status'],
                        'check_in_time' => $attendance['check_in_time'] ?? null,
                        'check_out_time' => $attendance['check_out_time'] ?? null,
                    ]
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Lỗi điểm danh hàng loạt: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getStudentAttendance($studentId)
    {
        try {
            $perPage = request()->get('per_page', 20);
            $page = request()->get('page', 1);

            $query = Attendance::where('student_id', $studentId)
                ->with(['classSession' => function($query) {
                    $query->with('classModel');
                }, 'student']);
            $params = request()->all();
            foreach ($params as $key => $value) {
                if (in_array($key, ['per_page', 'page', 'student_id'])) {
                    continue;
                }
                if (!empty($value)) {
                    $column = $key;
                    if (in_array($column, (new Attendance())->getFillable())) {
                        if (is_string($value) && !is_numeric($value)) {
                            $query->where($column, 'LIKE', '%' . $value . '%');
                        } else {
                            $query->where($column, $value);
                        }
                    }
                    if ($key === 'date_from' && !empty($value)) {
                        $query->whereHas('classSession', function($q) use ($value) {
                            $q->whereDate('session_date', '>=', $value);
                        });
                    }

                    if ($key === 'date_to' && !empty($value)) {
                        $query->whereHas('classSession', function($q) use ($value) {
                            $q->whereDate('session_date', '<=', $value);
                        });
                    }
                    if ($key === 'class_id' && !empty($value)) {
                        $query->whereHas('classSession', function($q) use ($value) {
                            $q->where('class_id', $value);
                        });
                    }
                }
            }

            return $query->paginate($perPage, ['*'], 'page', $page);
        } catch (Exception $e) {
            logger()->error("Lỗi khi lấy thông tin điểm danh của học sinh ID $studentId: " . $e->getMessage());
            throw new Exception('Không thể lấy thông tin điểm danh của học sinh.');
        }
    }
}
