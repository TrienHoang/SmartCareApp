<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:4|max:30|regex:/^[a-zA-Z0-9_]+$/|unique:users,username',
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:6|max:32',
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
            'username.unique'   => 'Tên đăng nhập đã tồn tại',

            'fullname.required' => 'Vui lòng nhập họ tên',
            'fullname.string' => 'Họ tên phải là chuỗi ký tự',
            'fullname.max' => 'Họ tên không được vượt quá 100 ký tự',

            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'email.max' => 'Email không được vượt quá 100 ký tự',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 32 ký tự',
        ];
    }
}
