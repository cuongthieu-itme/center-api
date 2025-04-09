<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClassModelService;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;

class ClassModelController extends Controller
{
    protected ClassModelService $classService;

    public function __construct(ClassModelService $classService)
    {
        $this->classService = $classService;
    }

    public function index()
    {
        $classes = $this->classService->index();

        return response()->json($classes);
    }

    public function show($id)
    {
        $class = $this->classService->show($id);

        return response()->json($class);
    }

    public function store(StoreClassRequest $request)
    {
        $class = $this->classService->store($request->validated());

        return response()->json([
            'message' => 'Tạo lớp học thành công',
            'class' => $class
        ], 201);
    }

    public function update(UpdateClassRequest $request, $id)
    {
        $class = $this->classService->update($id, $request->validated());

        return response()->json([
            'message' => 'Cập nhật lớp học thành công',
            'class' => $class
        ]);
    }

    public function destroy($id)
    {
        $this->classService->destroy($id);

        return response()->json([
            'message' => 'Xóa lớp học thành công'
        ]);
    }
}
