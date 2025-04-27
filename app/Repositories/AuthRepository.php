<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Exception;

class AuthRepository
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
            logger()->error('Lỗi DB khi đăng ký: ' . $e->getMessage());
            throw new Exception('Đăng ký thất bại. Vui lòng thử lại sau.');
        } catch (Exception $e) {
            logger()->error('Lỗi khi đăng ký: ' . $e->getMessage());
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
            logger()->error('Lỗi đăng nhập: ' . $e->getMessage());
            throw new Exception('Đăng nhập thất bại. Vui lòng thử lại.');
        }
    }

    public function logout()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->delete();
        } catch (Exception $e) {
            logger()->error('Lỗi đăng xuất: ' . $e->getMessage());
            throw new Exception('Đăng xuất thất bại. Vui lòng thử lại.');
        }
    }

    public function me()
    {
        try {
            /** @var User $user */
            return Auth::user();
        } catch (Exception $e) {
            logger()->error('Lỗi lấy thông tin người dùng: ' . $e->getMessage());
            throw new Exception('Không thể lấy thông tin người dùng.');
        }
    }

    public function verifyAuth()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return [
                    'authenticated' => false,
                    'message' => 'Token không hợp lệ hoặc đã hết hạn',
                ];
            }

            return [
                'authenticated' => true,
                'user' => $user,
            ];
        } catch (Exception $e) {
            logger()->error('Lỗi xác thực token: ' . $e->getMessage());

            return [
                'authenticated' => false,
                'message' => 'Xác thực thất bại. Vui lòng đăng nhập lại.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ];
        }
    }
}
