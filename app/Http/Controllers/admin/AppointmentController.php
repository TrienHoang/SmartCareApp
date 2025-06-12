<?php

namespace App\Http\Controllers\admin;

use App\Helpers\AppointmentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateStatusAppointmentRequest;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\Service;
use App\Models\User;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with([
            'patient:id,full_name,phone,email',
            'doctor.user:id,full_name',
            'doctor.department:id,name',
            'doctor.room:id,name',
            'service:id,name,price',
        ]);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo bác sĩ
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Lọc theo khoa
        if ($request->filled('department_id')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Lọc theo dịch vụ
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_time', '<=', $request->date_to);
        }

        // Tìm kiếm theo tên bệnh nhân
        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'appointment_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $appointments = $query->paginate($perPage)->appends($request->all());

        // Dữ liệu cho filter
        $doctors = Doctor::with('user:id,full_name')->get();
        $departments = Department::all();
        $services = Service::where('status', 'active')->get();

        // Thống kê nhanh
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'today' => Appointment::whereDate('appointment_time', Carbon::today())->count(),
        ];

        return view('admin.Appointment.index', compact(
            'appointments',
            'doctors',
            'departments',
            'services',
            'stats'
        ));
    }

    public function create()
    {
        return view('admin.Appointment.create', [
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');

        // Kiểm tra xem bác sĩ có lịch hẹn trùng không
        $conflict = AppointmentHelper::isConflict(
            $request->doctor_id,
            $request->appointment_time,
            $request->service_id
        );

        if($conflict['doctor_conflict']){
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        if($conflict['room_conflict']){
            return back()->withErrors([
                'appointment_time' => 'Phòng khám đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->whereDate('day', $day)
            ->first();

        if (!$working) {
            $workingDays = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->pluck('day_of_week')
                ->map(function ($day) {
                    return __('days.' . strtolower($day));
                })
                ->toArray();

            $daysText = implode(', ', $workingDays);

            return back()->withErrors([
                'doctor_id' => 'Bác sĩ không làm việc vào ngày bạn chọn. Các ngày làm việc là: ' . $daysText . '.'
            ])->withInput();
        }

        // Kiểm tra giờ làm việc

        if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn không nằm trong khung giờ làm việc của bác sĩ. '
                    . 'Khung giờ làm việc là từ ' . $working->start_time . ' đến ' . $working->end_time . '.'
            ])->withInput();
        }


        $onLeave = DoctorLeave::where('doctor_id', $request->doctor_id)
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();
        if ($onLeave) {
            return back()->withErrors([
                'doctor_id' => 'Bác sĩ đang trong thời gian nghỉ phép vào ngày bạn chọn. Vui lòng chọn ngày khác.'
            ])->withInput();
        }

        Appointment::create($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Tạo lịch hẹn thành công');
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('admin.Appointment.edit', [
            'appointment' => $appointment,
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);


        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->back()->withErrors([
                'status' => 'Không thể cập nhật lịch hẹn đã hoàn thành hoặc đã hủy.'
            ]);
        }

        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');

        // Kiểm tra xem bác sĩ có lịch hẹn trùng không
        $conflict = AppointmentHelper::isConflict(
            $request->doctor_id,
            $request->appointment_time,
            $request->service_id,
            $appointment->id
        );

        if($conflict['doctor_conflict']){
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        if($conflict['room_conflict']){
            return back()->withErrors([
                'appointment_time' => 'Phòng khám đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->whereDate('day', $day)
            ->first();

        if (!$working) {
            $workingDays = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->pluck('day_of_week')
                ->map(function ($day) {
                    return __('days.' . strtolower($day));
                })
                ->toArray();

            $daysText = implode(', ', $workingDays);

            return back()->withErrors([
                'appointment_time' => 'Bác sĩ không làm việc vào ngày này. Các ngày làm việc là: ' . $daysText . '.'
            ])->withInput();
        }

        if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn không nằm trong khung giờ làm việc của bác sĩ. '
                    . 'Khung giờ làm việc là từ ' . $working->start_time . ' đến ' . $working->end_time . '.'
            ])->withInput();
        }

        $onLeave = DoctorLeave::where('doctor_id', $request->doctor_id)
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();
        if ($onLeave) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đang trong thời gian nghỉ phép vào ngày bạn chọn. Vui lòng chọn ngày khác.'
            ])->withInput();
        }

        $appointment->update($request->all());

        return redirect()->route('admin.appointments.index')->with('success', 'Cập nhật lịch hẹn thành công');
    }


    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->status = 'cancelled';
            $appointment->save();
            return redirect()->route('admin.appointments.index')->with('success', 'Hủy lịch hẹn thành công');
        }

        return redirect()->route('admin.appointments.index')->withErrors(['status' => 'Không thể hủy lịch hẹn đã hoàn thành hoặc đã hủy.']);
    }
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        // $appointment = Appointment::with(['patient', 'doctor.user', 'doctor.room', 'service', 'logs'])->findOrFail($id);
        return view('admin.Appointment.show', compact('appointment'));
    }

    public function updateStatus(UpdateStatusAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $oldStatus = $appointment->status;
        $appointment->update([
            'status' => $request->status,
            'cancel_reason' => $request->status === 'cancelled' ? $request->note : null
        ]);

        // Log thay đổi trạng thái
        $appointment->logs()->create([
            'changed_by' => auth()->id(),
            'status_before' => $oldStatus,
            'status_after' => $request->status,
            'change_time' => now(),
            'note' => $request->note
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
