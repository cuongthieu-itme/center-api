<?php

namespace App\Services;

use App\Interfaces\StudentRepositoryInterface;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function index()
    {
        return $this->studentRepository->index();
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
}
