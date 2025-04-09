<?php

namespace App\Services;

use App\Interfaces\AuthRepositoryInterface;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register($data)
    {
        return $this->authRepository->register($data);
    }

    public function login($credentials)
    {
        return $this->authRepository->login($credentials);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function me()
    {
        return $this->authRepository->me();
    }
}
