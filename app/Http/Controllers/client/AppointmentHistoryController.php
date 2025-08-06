<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentHistoryController extends Controller
{
    /**
     * Hiển thị danh sách lịch sử khám của bệnh nhân đã đăng nhập
     */
    public function index()
    {
        // Lấy thông tin người dùng hiện tại (bệnh nhân)
        $user = Auth::user();

        // Lấy danh sách các cuộc hẹn đã hoàn thành
 $appointments = Appointment::with([
    'doctor.user',
    'service',
    'medicalRecord.prescription.items',
    'payment',
    'review',
])
->where('patient_id', $user->id)
->orderByDesc('appointment_time')
->get();

        return view('client.appointments.history', compact('appointments'));
    }

    /**
     * Hiển thị chi tiết một lần khám cụ thể
     */
    public function show($id)
    {
        $appointment = Appointment::with([
            'doctor.user',
            'service',
            'medicalRecord.prescription.items', // Truy cập đơn thuốc thông qua hồ sơ
            'payment',
            'review',
        ])
        ->where('id', $id)
        ->where('patient_id', Auth::id())
        ->first();

        if (!$appointment) {
            return redirect()->route('client.appointments.history')
                ->withErrors(['not_found' => 'Không tìm thấy cuộc hẹn hoặc bạn không có quyền xem.']);
        }

        return view('client.appointments.detail', compact('appointment'));
    }
}
