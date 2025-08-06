<?php
namespace App\Http\Controllers\doctor;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,confirmed,check_in,completed,cancelled',
    ]);

    $doctorId = Auth::user()->doctor->id;

    // Chỉ tìm lịch hẹn thuộc về bác sĩ hiện tại
    $appointment = Appointment::where('id', $id)
        ->where('doctor_id', $doctorId)
        ->firstOrFail();

    $appointment->status = $request->status;
    $appointment->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
}
