<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize()
    {
        // Chỉ cho phép nếu người dùng có quyền (có thể kiểm soát bằng middleware/policy)
        return auth()->check(); // Hoặc dùng policy cụ thể nếu cần
    }

    public function rules()
    {
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'specialization' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'room_id' => 'required|exists:rooms,id',
            'biography' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => '🧑 Vui lòng chọn người dùng.',
            'specialization.required' => '💼 Vui lòng nhập chuyên môn.',
            'department_id.required' => '🏥 Vui lòng chọn phòng ban.',
            'room_id.required' => '🏨 Vui lòng chọn phòng khám.',
        ];
    }
}
