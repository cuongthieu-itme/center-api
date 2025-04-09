<?php

namespace App\Services;

use App\Interfaces\ClassModelRepositoryInterface;

class ClassModelService
{
    protected $classRepository;

    public function __construct(ClassModelRepositoryInterface $classRepository)
    {
        $this->classRepository = $classRepository;
    }

    public function index()
    {
        return $this->classRepository->index();
    }

    public function show($id)
    {
        return $this->classRepository->show($id);
    }

    public function store($data)
    {
        return $this->classRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->classRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->classRepository->destroy($id);
    }

    public function getStudents($classId)
    {
        return $this->classRepository->getStudents($classId);
    }

    public function getSessions($classId)
    {
        return $this->classRepository->getSessions($classId);
    }

    public function getClassesByTeacher($teacherId)
    {
        return $this->classRepository->getClassesByTeacher($teacherId);
    }
}
