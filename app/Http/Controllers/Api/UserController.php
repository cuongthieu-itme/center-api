<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $page = $request->query('page', 1);
        $users = $this->userService->index($perPage, $page);

        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->userService->show($id);

        return response()->json($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return response()->json([
            'message' => 'Tạo người dùng thành công',
            'user' => $user
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật người dùng thành công',
            'user' => $user
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->userService->destroy($id);

        return response()->json([
            'message' => 'Xóa người dùng thành công'
        ], Response::HTTP_OK);
    }
}
