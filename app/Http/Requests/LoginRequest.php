<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|min:4|max:30|regex:/^[a-zA-Z0-9_]+$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:32',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.string' => 'Tên đăng nhập phải là chuỗi ký tự',
            'username.min' => 'Tên đăng nhập phải có ít nhất 4 ký tự',
            'username.max' => 'Tên đăng nhập không được vượt quá 30 ký tự',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 32 ký tự',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Nếu CÓ bất kỳ lỗi nào => không làm gì cả
            if ($validator->errors()->any()) {
                return;
            }

            $credentials = $this->only('username', 'password');

            // Nếu đăng nhập sai → thêm lỗi vào 'auth'
            if (!Auth::attempt($credentials)) {
                $validator->errors()->add('auth', 'Sai tên đăng nhập hoặc mật khẩu');
            }
        });
    }
}
