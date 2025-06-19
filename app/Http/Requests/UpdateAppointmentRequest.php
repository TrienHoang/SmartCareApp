<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $rules = [
            'appointment_time' => ['required', 'date'],
            'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'],
        ];

        // Nếu status KHÔNG phải là 'completed', yêu cầu thời gian phải sau hiện tại
        if ($this->input('status') !== 'completed') {
            $rules['appointment_time'][] = 'after:now';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'appointment_time.required' => 'Vui lòng chọn thời gian hẹn.',
            'appointment_time.date' => 'Thời gian hẹn không hợp lệ.',
            'appointment_time.after' => 'Thời gian hẹn phải sau thời điểm hiện tại.',
            'status.in' => 'Trạng thái hẹn không hợp lệ.',
        ];
    }
}
