<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher');

        return [
            'full_name'      => 'sometimes|required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'sometimes|required|email|unique:teachers,email,' . $teacherId,
            'specialization' => 'nullable|string|max:255',
            'avatar_url'     => 'nullable|string',
            'user_id'        => 'nullable|exists:users,id',
        ];
    }
}
