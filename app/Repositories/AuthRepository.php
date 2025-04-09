<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Exception;

class AuthRepository implements AuthRepositoryInterface
{
    public function register($data)
    {
        try {
            return User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        } catch (QueryException $e) {
            logger()->error('Database error during registration: ' . $e->getMessage());
            throw new Exception('Failed to register user. Please try again later.');
        } catch (Exception $e) {
            logger()->error('Error during registration: ' . $e->getMessage());
            throw $e;
        }
    }

    public function login($credentials)
    {
        try {
            if (!Auth::attempt($credentials)) {
                return false;
            }

            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        } catch (Exception $e) {
            logger()->error('Login error: ' . $e->getMessage());
            throw new Exception('Login failed. Please try again.');
        }
    }

    public function logout()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->delete();
        } catch (Exception $e) {
            logger()->error('Logout error: ' . $e->getMessage());
            throw new Exception('Logout failed. Please try again.');
        }
    }

    public function me()
    {
        try {
            /** @var User $user */
            return Auth::user();
        } catch (Exception $e) {
            logger()->error('Profile fetch error: ' . $e->getMessage());
            throw new Exception('Failed to retrieve user profile.');
        }
    }
}
