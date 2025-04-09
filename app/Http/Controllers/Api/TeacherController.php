<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index()
    {
        $teachers = $this->teacherService->index();

        return response()->json($teachers);
    }

    public function show($id)
    {
        $teacher = $this->teacherService->show($id);

        return response()->json($teacher);
    }

    public function store(StoreTeacherRequest $request)
    {
        $teacher = $this->teacherService->store($request->validated());

        return response()->json([
            'message' => 'Tạo giáo viên thành công',
            'teacher' => $teacher
        ], 201);
    }

    public function update(UpdateTeacherRequest $request, $id)
    {
        $teacher = $this->teacherService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật giáo viên thành công',
            'teacher' => $teacher
        ]);
    }

    public function destroy($id)
    {
        $this->teacherService->destroy($id);

        return response()->json([
            'message' => 'Xóa giáo viên thành công'
        ]);
    }
}
