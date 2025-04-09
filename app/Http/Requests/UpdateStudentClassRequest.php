<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentClassId = $this->route('student_classes');

        return [
            'student_id' => 'sometimes|required|exists:students,id',
            'class_id'   => 'sometimes|required|exists:classes,id',
        ];
    }
}
