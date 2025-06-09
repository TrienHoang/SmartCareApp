<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
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

        $appointments = $query->paginate(15)->appends($request->all());

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

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'reason' => 'nullable|string|max:255',
        ]);

        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');

        $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->exists();

        if (!$working) {
            $workingDays = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->pluck('day_of_week')
                ->map(function ($day) {
                    return __('days.' . strtolower($day));
                })
                ->toArray();

            $daysText = implode(', ', $workingDays);

            return back()->withErrors([
                'doctor_id' => 'Bác sĩ không làm việc vào ngày bạn chọn. Vui lòng chọn ngày khác. '
                    . 'Các ngày làm việc của bác sĩ là: ' . $daysText . '.'
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'appointment_time' => 'required|date|after:now',
            'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment = Appointment::findOrFail($id);

        // Nếu lịch hẹn đã hoàn thành hoặc đã hủy thì không cho cập nhật nữa
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->back()->withErrors([
                'status' => 'Không thể cập nhật lịch hẹn đã hoàn thành hoặc đã hủy.'
            ]);
        }

        // Kiểm tra lịch làm việc của bác sĩ cho thời gian mới
        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');

        $working = WorkingSchedule::where('doctor_id', $appointment->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->exists();

        if (!$working) {
            $workingDays = WorkingSchedule::where('doctor_id', $appointment->doctor_id)
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
        return view('admin.Appointment.show', compact('appointment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'note' => 'nullable|string|max:500'
        ]);

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
