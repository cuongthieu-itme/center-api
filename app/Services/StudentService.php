<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use App\Repositories\StudentClassRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    protected $studentRepository;
    protected $studentClassRepository;

    public function __construct(
        StudentRepository $studentRepository,
        StudentClassRepository $studentClassRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->studentClassRepository = $studentClassRepository;
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
}
