<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
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
            'patient_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_time' => ['required', 'date', 'after:now'],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Vui lòng chọn bệnh nhân.',
            'patient_id.exists' => 'Bệnh nhân không tồn tại trong hệ thống.',

            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'doctor_id.exists' => 'Bác sĩ không hợp lệ hoặc đã bị xóa.',

            'service_id.required' => 'Vui lòng chọn dịch vụ khám.',
            'service_id.exists' => 'Dịch vụ không hợp lệ hoặc đã bị xóa.',

            'appointment_time.required' => 'Vui lòng chọn thời gian hẹn.',
            'appointment_time.date' => 'Thời gian hẹn phải là định dạng ngày hợp lệ.',
            'appointment_time.after' => 'Không thể đặt lịch ở thời điểm đã qua.',

            'status.required' => 'Vui lòng chọn trạng thái ban đầu của lịch hẹn.',
            'status.in' => 'Trạng thái hẹn không hợp lệ.',

            'reason.string' => 'Lý do phải là chuỗi ký tự.',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự.',
        ];
    }
}
