<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id'     => 'required|exists:classes,id',
            'session_date' => 'required|date',
            'start_time'   => 'required|date',
            'end_time'     => 'required|date|after:start_time',
        ];
    }
}
