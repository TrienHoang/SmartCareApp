<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\WorkingSchedule; // Đảm bảo đã import WorkingSchedule model
use App\Models\DoctorLeave;
use Illuminate\Support\Facades\DB; // Để sử dụng database transactions
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AppointmentClientController extends Controller
{
    // Hiển thị danh sách lịch hẹn của bệnh nhân đang đăng nhập
    public function index()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->latest('appointment_time')
            ->get();

        return view('client.appointments.index', compact('appointments'));
    }

    // Xem chi tiết lịch hẹn
    public function show(Appointment $appointment)
    {
        // Chỉ cho phép xem lịch hẹn của chính mình
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập lịch hẹn này.');
        }

        return view('client.appointments.show', compact('appointment'));
    }

    // Form sửa lịch hẹn (chỉ nếu lịch hẹn đang chờ xử lý)
    // public function edit(Appointment $appointment)
    // {
    //     // Kiểm tra quyền sở hữu lịch hẹn
    //     if ($appointment->patient_id !== Auth::id()) {
    //         abort(403, 'Bạn không có quyền sửa lịch hẹn này.');
    //     }

    //     // Chỉ cho phép sửa nếu lịch hẹn ở trạng thái 'pending'
    //     if ($appointment->status !== 'pending') {
    //         return redirect()->route('client.appointments.index')
    //             ->with('error', 'Lịch hẹn đã được xử lý, không thể sửa.');
    //     }

    //     $doctors = Doctor::with('user')->get();
    //     $services = Service::all();

    //     // Định dạng ngày và giờ cho các input trong form
    //     $appointment->appointment_time_formatted = Carbon::parse($appointment->appointment_time)->format('H:i');
    //     $appointment->appointment_date_formatted = Carbon::parse($appointment->appointment_date)->format('Y-m-d');

    //     return view('client.appointments.edit', compact('appointment', 'doctors', 'services'));
    // }

    // public function update(Request $request, Appointment $appointment)
    // {
    //     // Kiểm tra quyền sở hữu lịch hẹn
    //     if ($appointment->patient_id !== Auth::id()) {
    //         abort(403, 'Bạn không có quyền cập nhật lịch hẹn này.');
    //     }

    //     // Chỉ cho phép cập nhật nếu lịch hẹn ở trạng thái 'pending'
    //     if ($appointment->status !== 'pending') {
    //         return redirect()->route('client.appointments.index')
    //             ->with('error', 'Lịch hẹn đã được xử lý, không thể cập nhật.');
    //     }

    //     $validated = $request->validate([
    //         'patient_name' => 'required|string|max:255',
    //         'patient_phone' => 'required|string|min:10|max:11',
    //         'patient_email' => 'nullable|email|max:255',
    //         'patient_gender' => 'required|in:Nam,Nữ,Khác',
    //         'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
    //         'appointment_time' => 'required|date_format:H:i',
    //         'doctor_id' => 'required|exists:doctors,id',
    //         'service_id' => 'required|exists:services,id',
    //         'reason' => 'nullable|string|max:1000',
    //     ]);

    //     $fullAppointmentDateTime = Carbon::createFromFormat(
    //         'Y-m-d H:i',
    //         $validated['appointment_date'] . ' ' . $validated['appointment_time']
    //     );
    //     $dayOfWeek = $fullAppointmentDateTime->format('l');
    //     $day = $fullAppointmentDateTime->format('Y-m-d');
    //     $timeOnly = $fullAppointmentDateTime->format('H:i:s');

    //     // Kiểm tra thời gian hẹn không được ở trong quá khứ
    //     if ($fullAppointmentDateTime->isPast()) {
    //         return back()->withErrors(['appointment_time' => 'Thời gian hẹn không thể ở trong quá khứ.'])->withInput();
    //     }

    //     $doctor = Doctor::with(['department', 'user'])->findOrFail($validated['doctor_id']);
    //     $service = Service::with('department')->findOrFail($validated['service_id']);

    //     // Kiểm tra chuyên khoa của dịch vụ có khớp với chuyên khoa của bác sĩ không
    //     if ((int) $doctor->department_id !== (int) $service->department_id) {
    //         $recommendedList = Service::where('department_id', $doctor->department_id)
    //             ->limit(5)
    //             ->pluck('name')
    //             ->implode(', ');

    //         return back()->withErrors([
    //             'service_id' => 'Dịch vụ bạn chọn thuộc chuyên khoa: ' . ($service->department->name ?? 'Không xác định') .
    //                 ', nhưng bác sĩ được chỉ định hiện thuộc chuyên khoa: ' . ($doctor->department->name ?? 'Không xác định') . '.' .
    //                 ' Bạn có thể chọn một trong các dịch vụ phù hợp: ' . $recommendedList . '.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra trạng thái hoạt động của bác sĩ
    //     if ($doctor->user->status !== 'online') {
    //         return back()->withErrors([
    //             'doctor_id' => 'Bác sĩ hiện không hoạt động, vui lòng chọn bác sĩ khác.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra lịch làm việc của bác sĩ theo ngày cụ thể, sau đó theo ngày trong tuần
    //     $working = WorkingSchedule::where('doctor_id', $validated['doctor_id'])
    //         ->whereDate('day', $day)
    //         ->first();

    //     if (!$working) {
    //         $working = WorkingSchedule::where('doctor_id', $validated['doctor_id'])
    //             ->where('day_of_week', $dayOfWeek)
    //             ->first();
    //     }

    //     // Nếu không tìm thấy lịch làm việc nào cho bác sĩ
    //     if (!$working) {
    //         $workingDays = WorkingSchedule::where('doctor_id', $validated['doctor_id'])
    //             ->pluck('day_of_week')
    //             ->unique()
    //             ->map(fn($day) => trans('days.' . strtolower($day)))
    //             ->implode(', ');

    //         return back()->withErrors([
    //             'appointment_time' => 'Bác sĩ không làm việc ngày này. Các ngày làm việc: ' . ($workingDays ?: 'Không có') . '.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra giờ hẹn có nằm trong giờ làm việc của bác sĩ
    //     if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
    //         return back()->withErrors([
    //             'appointment_time' => 'Giờ hẹn không nằm trong giờ làm việc của bác sĩ: ' .
    //                 Carbon::parse($working->start_time)->format('H:i') . ' - ' .
    //                 Carbon::parse($working->end_time)->format('H:i') . '.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra bác sĩ có đang nghỉ phép không
    //     $onLeave = DoctorLeave::where('doctor_id', $validated['doctor_id'])
    //         ->where('start_date', '<=', $fullAppointmentDateTime->toDateString())
    //         ->where('end_date', '>=', $fullAppointmentDateTime->toDateString())
    //         ->where('approved', true)
    //         ->exists();

    //     if ($onLeave) {
    //         return back()->withErrors([
    //             'appointment_time' => 'Bác sĩ nghỉ phép ngày này. Vui lòng chọn ngày khác.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra trùng lịch hẹn với bác sĩ
    //     $existingDoctorAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
    //         ->whereDate('appointment_time', $day)
    //         ->whereTime('appointment_time', $timeOnly)
    //         ->where('id', '!=', $appointment->id)
    //         ->whereIn('status', ['pending', 'confirmed'])
    //         ->exists();

    //     if ($existingDoctorAppointment) {
    //         return back()->withErrors([
    //             'appointment_time' => 'Bác sĩ đã có lịch hẹn khác vào thời gian này. Vui lòng chọn thời gian khác.'
    //         ])->withInput();
    //     }

    //     // Kiểm tra trùng lịch hẹn của chính bệnh nhân
    //     $duration = $service->duration;
    //     $endTime = $fullAppointmentDateTime->copy()->addMinutes($duration);

    //     $overlappedPatientAppointment = Appointment::where('patient_id', Auth::id())
    //         ->where('id', '!=', $appointment->id)
    //         ->where(function ($q) use ($fullAppointmentDateTime, $endTime) {
    //             $q->where('appointment_time', '<', $endTime)
    //                 ->where('end_time', '>', $fullAppointmentDateTime);
    //         })
    //         ->whereIn('status', ['pending', 'confirmed'])
    //         ->exists();

    //     if ($overlappedPatientAppointment) {
    //         return back()->withErrors([
    //             'appointment_time' => 'Bạn đã có lịch hẹn khác bị trùng thời gian này! Vui lòng chọn thời gian khác.'
    //         ])->withInput();
    //     }

    //     // Cập nhật thông tin cá nhân của người dùng (bệnh nhân đang đăng nhập)
    //     $currentUser = Auth::user();
    //     $currentUser->update([
    //         'full_name' => $validated['patient_name'],
    //         'phone' => $validated['patient_phone'],
    //         'email' => $validated['patient_email'],
    //         'gender' => $validated['patient_gender'],
    //     ]);

    //     // Cập nhật chi tiết lịch hẹn
    //     $appointment->update([
    //         'appointment_date' => $validated['appointment_date'],
    //         'appointment_time' => $validated['appointment_time'],
    //         'end_time'         => $endTime,
    //         'doctor_id'        => $validated['doctor_id'],
    //         'service_id'       => $validated['service_id'],
    //         'reason'           => $validated['reason'],
    //     ]);

    //     return redirect()->route('client.appointments.show', $appointment->id)
    //         ->with('success', 'Cập nhật lịch hẹn thành công.');
    // }


    // Hủy lịch hẹn
    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền hủy lịch hẹn này.');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('client.appointments.index')
                ->with('error', 'Chỉ có thể hủy lịch hẹn khi đang chờ xác nhận.');
        }

        $request->validate([
            'cancel_reason_final' => 'required|string|max:1000',
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason_final,
        ]);


        return redirect()->route('client.appointments.index')
            ->with('success', 'Hủy lịch hẹn thành công.');
    }
}
