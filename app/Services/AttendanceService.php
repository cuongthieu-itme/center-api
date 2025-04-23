<?php

namespace App\Services;

use App\Models\Attendance;
use App\Repositories\AttendanceRepository;
use App\Repositories\ClassSessionRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\StudentRepository;
use Carbon\Carbon;

class AttendanceService
{
    protected $attendanceRepository;
    protected $studentRepo;
    protected $studentClassRepo;
    protected  $classSessionRepo;

    public function __construct(AttendanceRepository $attendanceRepository, StudentRepository $studentRepo, StudentClassRepository $classStudentRepo, ClassSessionRepository $classSessionRepo)
    {
        $this->attendanceRepository = $attendanceRepository;
        $this->studentRepo = $studentRepo;
        $this->studentClassRepo = $classStudentRepo;
        $this->classSessionRepo = $classSessionRepo;
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

    public function saveAttendence(mixed $id, mixed $time_in)
    {
        $student = $this->studentRepo->find($id);

        if (!isset($student)){
            return null;
        }

        $time = Carbon::createFromTimestamp($time_in)->timezone('Asia/Ho_Chi_Minh')->format("H:i:s");
        $dateNow = Carbon::createFromTimestamp($time_in)->timezone('Asia/Ho_Chi_Minh')->format("Y-m-d");

        $classOfStudent = $this->studentClassRepo->getStudentId($student->id)->toArray();
        $classIDs = array_column($classOfStudent, 'class_id');

        $classSession = $this->classSessionRepo->getByClassIDsAndDateTime($classIDs, $dateNow, $time);

        $attendance = new Attendance();
        $attendance->fill([
            'student_id' => $student->id,
            'session_id' => $classSession->id,
            'status' => 'present',
            'check_in_time' => $time,
            'check_out_time' => null,
        ]);
        $attendance->save();
        return $attendance;
    }
}
