<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register($data);

    public function login($credentials);

    public function logout();

    public function me();
    
    public function verifyAuth();
}
