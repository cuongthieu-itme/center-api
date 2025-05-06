<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ID học viên là bắt buộc',
            'user_id.exists' => 'Học viên không tồn tại',
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc',
            'current_password.min' => 'Mật khẩu hiện tại phải có ít nhất 6 ký tự',
            'new_password.required' => 'Mật khẩu mới là bắt buộc',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại',
            'confirm_password.required' => 'Xác nhận mật khẩu là bắt buộc',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu mới',
        ];
    }
}
