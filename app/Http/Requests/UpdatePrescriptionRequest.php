<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medical_record_id' => 'required|exists:medical_records,id',
            'prescribed_at' => ['required', 'date', 'before_or_equal:now'],
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.usage_instructions' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'medical_record_id.required' => 'Vui lòng chọn hồ sơ bệnh án.',
            'medical_record_id.exists' => 'Hồ sơ bệnh án không tồn tại.',
            'prescribed_at.required' => 'Vui lòng nhập ngày kê toa.',
            'prescribed_at.date' => 'Ngày kê toa không hợp lệ.',
            'prescribed_at.before_or_equal' => 'Ngày kê toa không được lớn hơn ngày hiện tại.',
            'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'medicines.required' => 'Vui lòng thêm ít nhất một thuốc.',
            'medicines.array' => 'Danh sách thuốc không hợp lệ.',
            'medicines.min' => 'Phải có ít nhất một thuốc.',
            'medicines.*.medicine_id.required' => 'Vui lòng chọn thuốc.',
            'medicines.*.medicine_id.exists' => 'Thuốc được chọn không tồn tại.',
            'medicines.*.quantity.required' => 'Vui lòng nhập số lượng thuốc.',
            'medicines.*.quantity.integer' => 'Số lượng thuốc phải là số nguyên.',
            'medicines.*.quantity.min' => 'Số lượng thuốc tối thiểu là 1.',
            'medicines.*.usage_instructions.string' => 'Hướng dẫn sử dụng phải là chuỗi ký tự.',
        ];
        
    }
}
