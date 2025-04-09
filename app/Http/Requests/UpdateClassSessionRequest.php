<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id'     => 'sometimes|required|exists:classes,id',
            'session_date' => 'sometimes|required|date',
            'start_time'   => 'sometimes|required|date_format:H:i',
            'end_time'     => 'sometimes|required|date_format:H:i|after:start_time',
        ];
    }
}
