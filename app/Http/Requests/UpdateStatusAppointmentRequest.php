<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'note' => 'nullable|string|max:500'
        ];
    }
    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái hẹn là bắt buộc.',
            'status.in' => 'Trạng thái hẹn không hợp lệ.',
            'note.string' => 'Ghi chú phải là chuỗi ký tự.',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ];
    }
}
