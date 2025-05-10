<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Support\Facades\Auth;
use Exception;

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

    public function getStudents($id)
    {
        $students = $this->teacherService->getStudentsByTeacherId($id);
        
        return response()->json($students);
    }
    
    /**
     * Get all students for the currently logged-in teacher
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyStudents()
    {
        $user = Auth::user();
        $teacherId = $user->teacher->id ?? null;
        
        if (!$teacherId) {
            return response()->json([
                'message' => 'Giáo viên không tồn tại'
            ], 404);
        }
        
        try {
            $students = $this->teacherService->getStudentsByTeacherId($teacherId);
            
            return response()->json([
                'students' => $students
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get classes with schedule for the currently logged-in teacher
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyClassesWithSchedule()
    {
        $user = Auth::user();
        $teacherId = $user->teacher->id ?? null;
        
        if (!$teacherId) {
            return response()->json([
                'message' => 'Giáo viên không tồn tại'
            ], 404);
        }
        
        try {
            $classesWithSchedule = $this->teacherService->getTeacherClassesWithSchedule($teacherId);
            
            // Return the paginator directly
            // Laravel will automatically convert it to the proper JSON format
            return response()->json($classesWithSchedule);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
