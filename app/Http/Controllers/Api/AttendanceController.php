<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        return response()->json($this->attendanceService->index());
    }

    public function show($id)
    {
        return response()->json($this->attendanceService->show($id));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $attendance = $this->attendanceService->store($request->validated());

        return response()->json([
            'message' => 'Điểm danh thành công',
            'attendance' => $attendance,
        ], 201);
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        $attendance = $this->attendanceService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật điểm danh thành công',
            'attendance' => $attendance,
        ]);
    }

    public function destroy($id)
    {
        $this->attendanceService->destroy($id);

        return response()->json([
            'message' => 'Xóa bản ghi điểm danh thành công',
        ]);
    }

    public function bulkStore(Request $request)
    {
        $this->attendanceService->bulkStore($request);
        return response()->json(['message' => 'Điểm danh thành công.']);
    }

    public function getStudentAttendance($studentId)
    {
        $attendance = $this->attendanceService->getStudentAttendance($studentId);

        return response()->json($attendance);
    }

    public function saveAttendance(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'time_in' => 'required',
        ]);
        if (!$validated) {
            return response()->json([
                'message' => 'Validation Error',
            ],422);
        }

        $id = $request->input('id');
        $time_in = $request->input('time_in');

        $result = $this->attendanceService->saveAttendence($id, $time_in);
        return response()->json($result);
    }
}
