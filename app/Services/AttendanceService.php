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

    public function exportAttendance($classId, $teacherID)
    {
        $attendances = $this->attendanceRepository->getAttendanceDataExport($classId, $teacherID)->toArray();

        // Format dates to dd-mm-yyyy and prepare data for export
        $formattedData = [];
        foreach ($attendances as $attendance) {
            // Format created_at and updated_at dates
            $createdAt = isset($attendance['created_at']) ?
                Carbon::parse($attendance['created_at'])->format('d-m-Y') : null;
            $updatedAt = isset($attendance['updated_at']) ?
                Carbon::parse($attendance['updated_at'])->format('d-m-Y') : null;

            // Format dob
            $dob = isset($attendance['dob']) ?
                Carbon::parse($attendance['dob'])->format('d-m-Y') : null;

            // Format session_date
            $sessionDate = isset($attendance['session_date']) ?
                Carbon::parse($attendance['session_date'])->format('d-m-Y') : null;

            // Update the attendance record with formatted dates
            $attendance['created_at'] = $createdAt;
            $attendance['updated_at'] = $updatedAt;
            $attendance['dob'] = $dob;
            $attendance['session_date'] = $sessionDate;

            $formattedData[] = $attendance;
        }
        return $formattedData;
    }

    public function saveAttendence(mixed $id, mixed $time_in)
    {
        $student = $this->studentRepo->find($id);
        if (!isset($student)){
            return "Không tìm thấy sinh viên";
        }

        $time = Carbon::createFromTimestamp($time_in)->timezone('Asia/Ho_Chi_Minh')->format("H:i:s");
        $dateNow = Carbon::createFromTimestamp($time_in)->timezone('Asia/Ho_Chi_Minh')->format("Y-m-d");

        $classOfStudent = $this->studentClassRepo->getStudentId($student->id)->toArray();
        $classIDs = array_column($classOfStudent, 'class_id');

        $classSession = $this->classSessionRepo->getByClassIDsAndDateTime($classIDs, $dateNow, $time);
        if (!isset($classSession)){
            return "Không tìm thấy buổi học";
        }

        // Check if attendance record already exists for this student and session
        $existingAttendance = Attendance::where('student_id', $student->id)
            ->where('session_id', $classSession->id)
            ->first();

        if ($existingAttendance) {
            // If check_out_time is already set, return message
            if ($existingAttendance->check_out_time) {
                return "Đã checkout";
            }

            // If check_in_time exists but check_out_time is null, update check_out_time
            $existingAttendance->check_out_time = $time;
            $existingAttendance->save();
            return $existingAttendance;
        }

        // Create new attendance record if none exists
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
