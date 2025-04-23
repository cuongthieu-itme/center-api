<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;

class AttendanceService
{
    protected $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function index()
    {
        return $this->attendanceRepository->index();
    }

    public function show($id)
    {
        return $this->attendanceRepository->show($id);
    }

    public function store($data)
    {
        return $this->attendanceRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->attendanceRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->attendanceRepository->destroy($id);
    }

    public function bulkStore($data)
    {
        return $this->attendanceRepository->bulkStore($data);
    }

    public function getStudentAttendance($studentId)
    {
        return $this->attendanceRepository->getStudentAttendance($studentId);
    }
}
