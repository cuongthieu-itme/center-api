<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\ClassSessionRepository;
use App\Repositories\AttendanceRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    protected $studentRepository;
    protected $studentClassRepository;
    protected $classSessionRepository;
    protected $attendanceRepository;

    public function __construct(
        StudentRepository $studentRepository,
        StudentClassRepository $studentClassRepository,
        ClassSessionRepository $classSessionRepository,
        AttendanceRepository $attendanceRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->studentClassRepository = $studentClassRepository;
        $this->classSessionRepository = $classSessionRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    public function index($perPage = 20, $page = 1)
    {
        return $this->studentRepository->index($perPage, $page);
    }

    public function show($id)
    {
        return $this->studentRepository->show($id);
    }

    public function store($data)
    {
        return $this->studentRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->studentRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->studentRepository->destroy($id);
    }

    public function getAttendanceHistory($studentId)
    {
        return $this->studentRepository->getAttendanceHistory($studentId);
    }

    public function uploadFile($file)
    {
        $validator = Validator::make(['file' => $file], [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            throw new Exception('Ảnh không hợp lệ');
        }

        $fileName = 'avatar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('avatars', $fileName, 'public');

        return $path;
    }

    /**
     * Change student's password
     *
     * @param int $userID
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     * @throws Exception
     */
    public function changePassword($userID, $currentPassword, $newPassword)
    {
        return $this->studentRepository->changePassword($userID, $currentPassword, $newPassword);
    }

    /**
     * Get classes for a student
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentClasses($studentId)
    {
        return $this->studentClassRepository->getClassesByStudentId($studentId);
    }

    /**
     * Get schedule for a student
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentSchedule($studentId)
    {
        // Get all classes the student is enrolled in
        $studentClasses = $this->studentClassRepository->getClassesByStudentId($studentId);
        
        // Get class IDs
        $classIds = [];
        foreach ($studentClasses as $class) {
            $classIds[] = $class->class_id;
        }
        
        // Filter parameters
        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);
        $fromDate = request()->get('from_date');
        $toDate = request()->get('to_date');
        
        // Query to get class sessions for these classes
        $query = $this->classSessionRepository->getSessionsByClassIds($classIds, $fromDate, $toDate);
        
        return $query;
    }

    /**
     * Get attendance records for a student
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentAttendance($studentId)
    {
        return $this->attendanceRepository->getStudentAttendance($studentId);
    }
}
