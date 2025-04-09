<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_name'  => 'required|string|max:255',
            'teacher_id'  => 'required|exists:teachers,id',
            'schedule'    => 'required|string'
        ];
    }
}
