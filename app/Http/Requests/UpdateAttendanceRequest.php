<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'     => 'sometimes|required|exists:students,id',
            'session_id'     => 'sometimes|required|exists:class_sessions,id',
            'status'         => 'sometimes|required|in:present,absent,late',
            'check_in_time'  => 'nullable|date',
            'check_out_time' => 'nullable|date|after_or_equal:check_in_time',
        ];
    }
}
