<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('students');

        return [
            'full_name'   => 'sometimes|required|string|max:255',
            'dob'         => 'sometimes|required|date',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'sometimes|required|email|unique:students,email,' . $studentId,
            'address'     => 'nullable|string|max:255',
            'avatar_url'  => 'nullable|string',
            'user_id'     => 'nullable|exists:users,id',
        ];
    }
}
