<?php

namespace App\Http\Controllers\reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReceptionistController extends Controller
{
    public function index()
    {
        $title = 'Bảng điều khiển lễ tân';
        return view('reception.scan_qr', compact('title'));
    }

    public function checkinView()
    {
        return view('reception.checkin');
    }

    // Xử lý dữ liệu từ máy quét QR
    public function processCheckin(Request $request)
    {
        $validated = $request->validate([
            'qr_code_data' => 'required|string|max:255',
        ]);

        $qrCodeData = $validated['qr_code_data'];

        try {
            // Tìm cuộc hẹn bằng mã QR
            $appointment = Appointment::where('qr_code', $qrCodeData)->first();

            Log::info("QR Check-in data: " . $appointment);

            if (!$appointment) {
                return response()->json(['success' => false, 'message' => 'Mã QR không hợp lệ hoặc không tìm thấy cuộc hẹn.'], 404);
            }

            // Kiểm tra trạng thái cuộc hẹn
            if ($appointment->status === 'cancelled') {
                return response()->json(['success' => false, 'message' => 'Cuộc hẹn đã bị hủy.'], 400);
            }
            // if ($appointment->status === 'checked_in') {
            //     return response()->json(['success' => false, 'message' => 'Cuộc hẹn đã được check-in trước đó.'], 400);
            // }
            if ($appointment->status === 'completed') {
                return response()->json(['success' => false, 'message' => 'Cuộc hẹn đã hoàn tất.'], 400);
            }

            // Kiểm tra ngày hẹn có phải là hôm nay không (tùy chọn)
            // if (Carbon::parse($appointment->appointment_time)->toDateString() !== Carbon::today()->toDateString()) {
            //     return response()->json(['success' => false, 'message' => 'Cuộc hẹn này không phải cho hôm nay.'], 400);
            // }

            $doctor_id = $appointment->doctor_id;

            $scheduledRoom = WorkingSchedule::where('doctor_id', $doctor_id)->with('room')
                ->first();

                if ($scheduledRoom && $scheduledRoom->room) {
                    $room = $scheduledRoom->room->name;
                } else {
                    $room = 'Chưa xác định';
                }

            log::info("Scheduled room: " . $room);

            // Cập nhật trạng thái và thời gian check-in
            $appointment->status = 'checked_in';
            $appointment->check_in_time = Carbon::now();
            $appointment->save();

            // Lấy thông tin chi tiết để trả về
            $patient = $appointment->patient;
            $doctor = $appointment->doctor->user;
            $service = $appointment->service;

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công!',
                'appointment' => [
                    'id' => $appointment->id,
                    'patient_name' => $patient->full_name,
                    'patient_phone' => $patient->phone,
                    'doctor_name' => $doctor->full_name,
                    'service_name' => $service->name,
                    'room' =>   $room,
                    'department' => $service->department->name,
                    'appointment_time' => Carbon::parse($appointment->appointment_time)->format('H:i d/m/Y'),
                    'check_in_time' => Carbon::parse($appointment->check_in_time)->format('H:i d/m/Y'),
                    'status' => $appointment->status,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("QR Check-in error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi trong quá trình check-in.'], 500);
        }
    }
}
