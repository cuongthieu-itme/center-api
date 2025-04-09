<?php

namespace App\Services;

use App\Interfaces\TeacherRepositoryInterface;

class TeacherService
{
    protected $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function index()
    {
        return $this->teacherRepository->index();
    }

    public function show($id)
    {
        return $this->teacherRepository->show($id);
    }

    public function store($data)
    {
        return $this->teacherRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->teacherRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->teacherRepository->destroy($id);
    }
}
