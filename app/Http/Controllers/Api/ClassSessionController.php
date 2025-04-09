<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClassSessionService;
use App\Http\Requests\StoreClassSessionRequest;
use App\Http\Requests\UpdateClassSessionRequest;

class ClassSessionController extends Controller
{
    protected ClassSessionService $classSessionService;

    public function __construct(ClassSessionService $classSessionService)
    {
        $this->classSessionService = $classSessionService;
    }

    public function index()
    {
        return response()->json($this->classSessionService->index());
    }

    public function show($id)
    {
        return response()->json($this->classSessionService->show($id));
    }

    public function store(StoreClassSessionRequest $request)
    {
        $session = $this->classSessionService->store($request->validated());

        return response()->json([
            'message' => 'Tạo buổi học thành công',
            'class_session' => $session,
        ], 201);
    }

    public function update(UpdateClassSessionRequest $request, $id)
    {
        $session = $this->classSessionService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật buổi học thành công',
            'class_session' => $session,
        ]);
    }

    public function destroy($id)
    {
        $this->classSessionService->destroy($id);

        return response()->json([
            'message' => 'Xóa buổi học thành công',
        ]);
    }
}
