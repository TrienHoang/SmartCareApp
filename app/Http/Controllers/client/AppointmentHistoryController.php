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

        // Lấy danh sách các cuộc hẹn có trạng thái 'completed' (đã khám xong)
        $appointments = Appointment::with([
            'doctor.user',           // Lấy thông tin bác sĩ (user liên kết với bác sĩ)
            'service',               // Lấy thông tin dịch vụ khám
            'medicalRecord',         // Lấy hồ sơ khám bệnh (triệu chứng, chẩn đoán,...)
            'prescription.items',    // Lấy đơn thuốc và danh sách thuốc kê
            'payment',               // Lấy thông tin thanh toán (nếu có)
            'review',                // Lấy đánh giá của bệnh nhân (nếu có)
        ])
        ->where('patient_id', $user->id)            // Chỉ lấy lịch sử của bệnh nhân hiện tại
        ->where('status', 'completed')              // Chỉ lấy những cuộc hẹn đã hoàn thành
        ->orderByDesc('appointment_time')           // Sắp xếp theo thời gian khám giảm dần
        ->get();

        // Trả về view hiển thị danh sách lịch sử khám
        return view('client.appointments.history', compact('appointments'));
    }

    /**
     * Hiển thị chi tiết một lần khám cụ thể
     * @param int $id Mã cuộc hẹn
     */
    public function show($id)
    {
        // Kiểm tra cuộc hẹn có tồn tại và thuộc về bệnh nhân hiện tại hay không
        $appointment = Appointment::with([
            'doctor.user',           // Bác sĩ khám
            'service',               // Dịch vụ khám
            'medicalRecord',         // Hồ sơ khám
            'prescription.items',    // Đơn thuốc
            'payment',               // Thanh toán
            'review',                // Đánh giá
        ])
        ->where('id', $id)
        ->where('patient_id', Auth::id()) // Đảm bảo người dùng chỉ xem được lịch sử của chính mình
        ->first();

        // Nếu không tìm thấy cuộc hẹn phù hợp, trả về lỗi 404
        if (!$appointment) {
            return redirect()->route('client.appointments.history')
                ->withErrors(['not_found' => 'Không tìm thấy cuộc hẹn hoặc bạn không có quyền xem.']);
        }

        // Trả về view chi tiết cuộc hẹn
        return view('client.appointments.detail', compact('appointment'));
    }
}
