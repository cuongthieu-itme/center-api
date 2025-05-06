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
    
    public function viewFile($filename)
    {
        $path = 'uploads/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'message' => 'File không tồn tại.'
            ], 404);
        }
        
        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path);
        
        return response($file, 200)->header('Content-Type', $type);
    }
}
