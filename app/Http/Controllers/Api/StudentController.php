<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\ChangePasswordRequest;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Change student's password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $result = $this->studentService->changePassword(
                $request->user_id,
                $request->current_password,
                $request->new_password
            );

            return response()->json([
                'message' => 'Đổi mật khẩu thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get classes for the currently logged-in student
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyClasses()
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;
        
        if (!$studentId) {
            return response()->json([
                'message' => 'Học viên không tồn tại'
            ], 404);
        }
        
        $classes = $this->studentService->getStudentClasses($studentId);
        
        return response()->json([
            'classes' => $classes
        ]);
    }

    /**
     * Get schedule for the currently logged-in student
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMySchedule()
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;
        
        if (!$studentId) {
            return response()->json([
                'message' => 'Học viên không tồn tại'
            ], 404);
        }
        
        try {
            $schedule = $this->studentService->getStudentSchedule($studentId);
            
            return response()->json([
                'schedule' => $schedule
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
