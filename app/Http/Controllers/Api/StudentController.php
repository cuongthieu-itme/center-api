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
        $students = $this->studentService->index();

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

    public function uploadFile(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'Không có file ảnh để upload'], 400);
        }

        $file = $request->file('file');

        try {
            $path = $this->studentService->uploadFile($file);

            return response()->json([
                'message' => 'Upload file thành công',
                'file_path' => $path,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
