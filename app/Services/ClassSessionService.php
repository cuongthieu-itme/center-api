<?php

namespace App\Services;

use App\Repositories\ClassSessionRepository;

class ClassSessionService
{
    protected $classSessionRepository;

    public function __construct(ClassSessionRepository $classSessionRepository)
    {
        $this->classSessionRepository = $classSessionRepository;
    }

    public function index()
    {
        return $this->classSessionRepository->index();
    }

    public function show($id)
    {
        return $this->classSessionRepository->show($id);
    }

    public function store($data)
    {
        return $this->classSessionRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->classSessionRepository->update($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->classSessionRepository->destroy($id);
    }
}
