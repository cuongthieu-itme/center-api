<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'     => 'required|exists:students,id',
            'session_id'     => 'required|exists:class_sessions,id',
            'status'         => 'required|in:present,absent,late',
            'check_in_time'  => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after_or_equal:check_in_time',
        ];
    }
}
