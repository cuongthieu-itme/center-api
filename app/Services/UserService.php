<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($perPage = 20, $page = 1)
    {
        return $this->userRepository->index($perPage, $page);
    }

    public function show($id)
    {
        return $this->userRepository->show($id);
    }

    public function store($data)
    {
        return $this->userRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->userRepository->destroy($id);
    }
}
