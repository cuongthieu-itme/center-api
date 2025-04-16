<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Exception;
use Illuminate\Support\Facades\Request;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index()
    {
        $perPage = request()->get('per_page', 20);
        $page = request()->get('page', 1);
        $students = $this->studentService->index($perPage, $page);

        return response()->json($students);
    }

    public function show($id)
    {
        $student = $this->studentService->show($id);

        return response()->json($student);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = $this->studentService->store($request->validated());

        return response()->json([
            'message' => 'Tạo học viên thành công',
            'student' => $student
        ], 201);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = $this->studentService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật học viên thành công',
            'student' => $student
        ]);
    }

    public function destroy($id)
    {
        $this->studentService->destroy($id);

        return response()->json([
            'message' => 'Xóa học viên thành công'
        ]);
    }

    public function getAttendanceHistory($id)
    {
        $history = $this->studentService->getAttendanceHistory($id);
        return response()->json([
            'student_id' => $id,
            'attendance_history' => $history
        ]);
    }
}
