<?php

namespace App\Repositories;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Models\Attendance;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 10);
            return Attendance::paginate($perPage);
        } catch (Exception $e) {
            logger()->error('Lỗi khi lấy danh sách điểm danh: ' . $e->getMessage());
            throw new Exception('Không thể lấy danh sách điểm danh.');
        }
    }

    public function show($id)
    {
        try {
            return Attendance::findOrFail($id);
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
}
