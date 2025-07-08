<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'doctor_id'        => ['required', 'exists:doctors,id'],
            'service_id'       => ['required', 'exists:services,id'],
            'appointment_time' => ['required', 'date'],
            'status'           => ['nullable', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
            'reason'           => ['nullable', 'string', 'max:255'],
            'treatment_plan_id' => ['nullable', 'exists:treatment_plans,id'],
        ];

        $status = $this->input('status', 'pending');  

        // Ràng buộc thời gian tuỳ trạng thái
        if ($status === 'completed') {
            $rules['appointment_time'][] = 'before_or_equal:now';
        } elseif (!in_array($status, ['confirmed', 'cancelled'])) {
            $rules['appointment_time'][] = 'after:now';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'doctor_id.required'  => 'Vui lòng chọn bác sĩ.',
            'doctor_id.exists'    => 'Bác sĩ không tồn tại.',
            'service_id.required' => 'Vui lòng chọn dịch vụ.',
            'service_id.exists'   => 'Dịch vụ không tồn tại.',

            'appointment_time.required' => 'Vui lòng chọn thời gian hẹn.',
            'appointment_time.date'     => 'Thời gian hẹn không hợp lệ.',
            'appointment_time.after'    => 'Thời gian hẹn phải ở tương lai.',
            'appointment_time.before_or_equal' => 'Khi hoàn thành, thời gian hẹn không được ở tương lai.',

            'status.in'   => 'Trạng thái hẹn không hợp lệ.',
            'reason.max'  => 'Lý do không được vượt quá 255 ký tự.',
            'reason.string' => 'Lý do phải là chuỗi ký tự.',

            'treatment_plan_id.exists' => 'Kế hoạch khám chưa tạo.',
            'treatment_plan_id.required' => 'Vui lòng chọn kế hoạch khám.',
            'treatment_plan_id.integer' => 'ID kế hoạch khám không hợp lệ.',
        ];
    }
}
