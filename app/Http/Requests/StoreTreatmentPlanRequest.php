<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:users,id',
            'plan_title' => 'required|string|max:255',
            'total_estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'goal' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'paused', 'cancelled'])],

            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.title' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.expected_start_date' => 'required|date|after_or_equal:today',
            'items.*.expected_end_date' => 'nullable|date|after_or_equal:items.*.expected_start_date',
            'items.*.frequency' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
            'items.*.needs_appointment' => 'nullable|boolean',
            'items.*.appointment_time' => 'nullable|date|after_or_equal:today',
            'items.*.reason' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Vui lòng chọn bệnh nhân.',
            'patient_id.exists' => 'Bệnh nhân không tồn tại.',

            'plan_title.required' => 'Vui lòng nhập tiêu đề kế hoạch.',
            'plan_title.string' => 'Tiêu đề kế hoạch phải là chuỗi.',
            'plan_title.max' => 'Tiêu đề kế hoạch không được vượt quá 255 ký tự.',

            'total_estimated_cost.numeric' => 'Tổng chi phí ước tính phải là số.',
            'total_estimated_cost.min' => 'Tổng chi phí ước tính không được nhỏ hơn 0.',

            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',

            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',

            'items.required' => 'Cần ít nhất một bước điều trị.',
            'items.array' => 'Danh sách bước điều trị không hợp lệ.',

            'items.*.service_id.exists' => 'Dịch vụ không tồn tại.',
            'items.*.title.string' => 'Tiêu đề bước phải là chuỗi.',
            'items.*.title.max' => 'Tiêu đề bước không được vượt quá 255 ký tự.',

            'items.*.description.string' => 'Mô tả phải là chuỗi.',

            'items.*.expected_start_date.required' => 'Vui lòng nhập ngày bắt đầu cho bước điều trị.',
            'items.*.expected_start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'items.*.expected_start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',

            'items.*.expected_end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'items.*.expected_end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

            'items.*.frequency.string' => 'Tần suất phải là chuỗi.',
            'items.*.frequency.max' => 'Tần suất không được vượt quá 255 ký tự.',

            'items.*.notes.string' => 'Ghi chú phải là chuỗi.',

            'items.*.needs_appointment.boolean' => 'Trường lịch hẹn phải là đúng hoặc sai.',

            'items.*.appointment_time.date' => 'Thời gian hẹn không hợp lệ.',
            'items.*.appointment_time.after_or_equal' => 'Thời gian hẹn phải từ hôm nay trở đi.',

            'items.*.reason.string' => 'Lý do phải là chuỗi.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->items ?? [] as $index => $item) {
                if (empty($item['service_id']) && empty($item['title'])) {
                    $validator->errors()->add("items.$index.title", 'Vui lòng nhập tiêu đề nếu không chọn dịch vụ.');
                }

                if (!empty($item['needs_appointment']) && $item['needs_appointment'] == 1 && empty($item['appointment_time'])) {
                    $validator->errors()->add("items.$index.appointment_time", 'Vui lòng nhập thời gian hẹn nếu chọn cần lịch hẹn.');
                }
            }
        });
    }
}
