<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');

            $filename = time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('uploads', $filename, 'public');

            return response()->json([
                'message' => 'Tải file thành công!',
                'path' => Storage::url($path)
            ], 200);
        }

        return response()->json([
            'message' => 'Không có file hoặc file không hợp lệ.',
        ], 400);
    }
}
