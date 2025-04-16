<?php

namespace App\Services;

use App\Interfaces\StudentRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Validator;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
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
}
