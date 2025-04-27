<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classId = $this->route('classes');

        return [
            'class_name'  => 'sometimes|required|string|max:255',
            'teacher_id'  => 'sometimes|required|exists:teachers,id',
        ];
    }
}
