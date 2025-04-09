<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentClassService;
use App\Http\Requests\StoreStudentClassRequest;
use App\Http\Requests\UpdateStudentClassRequest;

class StudentClassController extends Controller
{
    protected StudentClassService $studentClassService;

    public function __construct(StudentClassService $studentClassService)
    {
        $this->studentClassService = $studentClassService;
    }

    public function index()
    {
        $studentClasses = $this->studentClassService->index();

        return response()->json($studentClasses);
    }

    public function show($id)
    {
        $studentClass = $this->studentClassService->show($id);

        return response()->json($studentClass);
    }

    public function store(StoreStudentClassRequest $request)
    {
        $studentClass = $this->studentClassService->store($request->validated());

        return response()->json([
            'message' => 'Thêm học sinh vào lớp thành công',
            'student_class' => $studentClass
        ], 201);
    }

    public function update(UpdateStudentClassRequest $request, $id)
    {
        $studentClass = $this->studentClassService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật học sinh trong lớp thành công',
            'student_class' => $studentClass
        ]);
    }

    public function destroy($id)
    {
        $this->studentClassService->destroy($id);

        return response()->json([
            'message' => 'Xóa học sinh khỏi lớp thành công'
        ]);
    }
}
