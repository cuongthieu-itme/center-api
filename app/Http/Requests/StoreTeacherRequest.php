<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'      => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'required|email|unique:teachers,email',
            'specialization' => 'nullable|string|max:255',
            'avatar_url'     => 'nullable|string',
            'user_id'        => 'nullable|exists:users,id',
        ];
    }
}
