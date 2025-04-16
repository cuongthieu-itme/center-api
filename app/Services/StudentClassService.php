<?php

namespace App\Services;

use App\Interfaces\StudentClassRepositoryInterface;

class StudentClassService
{
    protected $studentClassRepository;

    public function __construct(StudentClassRepositoryInterface $studentClassRepository)
    {
        $this->studentClassRepository = $studentClassRepository;
    }

    public function index()
    {
        return $this->studentClassRepository->index();
    }

    public function show($id)
    {
        return $this->studentClassRepository->show($id);
    }

    public function store($data)
    {
        return $this->studentClassRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->studentClassRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->studentClassRepository->destroy($id);
    }

    public function getClassesByStudentId($studentId)
    {
        return $this->studentClassRepository->getClassesByStudentId($studentId);
    }
}
