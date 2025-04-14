<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request);

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $result = $this->authService->login($credentials);

        if (!$result) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        return response()->json($result);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Đã đăng xuất']);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }
    
    public function verifyAuth()
    {
        $result = $this->authService->verifyAuth();
        
        if (!$result['authenticated']) {
            return response()->json($result, 401);
        }
        
        return response()->json($result);
    }
}
