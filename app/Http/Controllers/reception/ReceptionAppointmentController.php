<?php

namespace App\Http\Controllers\reception;

use App\Helpers\AppointmentHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentLog;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\MedicalRecord;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\Service;
use App\Models\User;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReceptionAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'service'])
            ->orderBy('appointment_time', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_time', '<=', $request->date_to);
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Search by patient name or phone
        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $appointments = $query->paginate(15);

        // Get filter options
        $doctors = Doctor::with('user')->get();
        $services = Service::where('status', 'active')->get();

        // Statistics for dashboard
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'today' => Appointment::whereDate('appointment_time', Carbon::today())->count(),
        ];

        return view('reception.appointments.index', compact('appointments', 'doctors', 'services', 'stats'));
    }

    public function create()
    {
        $patients = User::where('role_id', 4)->get();

        $doctors = Doctor::with('user')->get();
        $services = Service::where('status', 'active')->get();

        return view('reception.appointments.create', compact('patients', 'doctors', 'services'));
    }


    public function searchPatients(Request $request)
    {
        $patients = User::whereIn('role_id', [3, 5])
            ->where('full_name', 'like', '%' . $request->q . '%')
            ->get(['id', 'full_name', 'role_id']);

        return response()->json($patients);
    }

    public function getDoctorServices(Doctor $doctor)
    {
        $services = $doctor->services()->with('department')->get();
        return response()->json($services);
    }

    public function getDoctorWorkingDays(Doctor $doctor)
    {
        $dayMap = [
            'Sunday' => 0,
            'Chủ nhật' => 0,
            'Monday' => 1,
            'Thứ hai' => 1,
            'Tuesday' => 2,
            'Thứ ba' => 2,
            'Wednesday' => 3,
            'Thứ tư' => 3,
            'Thursday' => 4,
            'Thứ năm' => 4,
            'Friday' => 5,
            'Thứ sáu' => 5,
            'Saturday' => 6,
            'Thứ bảy' => 6,
        ];

        // Ngày làm việc theo thứ (0-6)
        $daysOfWeek = $doctor->workingSchedules()
            ->whereNotNull('day_of_week')
            ->pluck('day_of_week')
            ->map(fn($d) => $dayMap[$d] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // ⚡ Nếu không có dữ liệu, mặc định Thứ 2–Thứ 7
        if (empty($daysOfWeek)) {
            $daysOfWeek = [1, 2, 3, 4, 5, 6];
        }

        // Ngày làm việc cụ thể (YYYY-MM-DD)
        $specificDates = $doctor->workingSchedules()
            ->whereNotNull('day')
            ->where('day', '>=', now()->toDateString())
            ->pluck('day')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // Ngày nghỉ phép (đã duyệt)
        $vacationDates = $doctor->leaves()
            ->where('end_date', '>=', now())
            ->get()
            ->flatMap(function ($leave) {
                $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
                return collect($period)->map(fn($date) => $date->format('Y-m-d'));
            })
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'daysOfWeek' => $daysOfWeek,
            'specificDates' => $specificDates,
            'vacationDates' => $vacationDates,
        ]);
    }

    public function getAvailableSlots(Request $request, $doctorId)
    {
        $date = $request->input('date', now()->toDateString());
        $serviceId = $request->input('service_id');
        $currentAppointmentId = $request->input('current_appointment_id');

        if (!$serviceId) {
            return response()->json(['error' => 'Service ID is required'], 400);
        }

        // Lấy thời gian của dịch vụ
        $service = Service::findOrFail($serviceId);
        $slotDuration = $service->duration ?? 30; // fallback 30 phút nếu không có

        // Giả sử bác sĩ làm từ 08:00–12:00 & 13:00–17:00
        $workingHours = [
            ['start' => '08:00', 'end' => '12:00'],
            ['start' => '13:00', 'end' => '17:00'],
        ];

        // Lấy các lịch đã đặt trong ngày
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', $date)
            ->whereNotIn('status', ['cancelled'])
            ->when($currentAppointmentId, fn($q) => $q->where('id', '<>', $currentAppointmentId))
            ->get()
            ->map(function ($a) {
                return [
                    'start' => Carbon::parse($a->appointment_time),
                    'end'   => Carbon::parse($a->end_time),
                ];
            });

        $availableSlots = [];

        foreach ($workingHours as $block) {
            $start = Carbon::parse("$date {$block['start']}");
            $end = Carbon::parse("$date {$block['end']}");

            while ($start->copy()->addMinutes($slotDuration) <= $end) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($slotDuration);

                // Kiểm tra xung đột
                $conflict = false;
                foreach ($appointments as $appt) {
                    if (
                        $slotStart < $appt['end'] &&
                        $slotEnd > $appt['start']
                    ) {
                        $conflict = true;
                        break;
                    }
                }

                if (!$conflict) {
                    // Nếu không xung đột, thêm slot này
                    $availableSlots[] = $slotStart->format('H:i');
                    // Và nhảy thẳng tới cuối khoảng này
                    $start = $slotEnd;
                    continue;
                }

                // Nếu có xung đột, chỉ nhảy 5 phút để tiếp tục quét
                $start->addMinutes(5);
            }
        }

        return response()->json($availableSlots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_time' => ['required', 'date', 'after:now'],
            'reason' => ['nullable', 'string', 'max:255'],
            'treatment_plan_id' => ['nullable', 'exists:treatment_plans,id'],
            'payment_method' => ['required', Rule::in(['cash', 'card', 'bank'])],
        ], [
            'patient_id.required' => 'Vui lòng chọn bệnh nhân.',
            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'service_id.required' => 'Vui lòng chọn dịch vụ khám.',
            'appointment_time.required' => 'Vui lòng chọn thời gian hẹn.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $appointmentDate = Carbon::parse($validated['appointment_time']);
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');
        $dayOfWeek = $appointmentDate->format('l');

        $doctor = Doctor::with(['department', 'user'])->findOrFail($validated['doctor_id']);
        $service = Service::with('department')->findOrFail($validated['service_id']);

        // Kiểm tra chuyên khoa
        if ($doctor->department_id !== $service->department_id) {
            $recommendedList = Service::where('department_id', $doctor->department_id)
                ->limit(5)
                ->pluck('name')
                ->implode(', ');

            return back()->withErrors([
                'service_id' => 'Dịch vụ bạn chọn thuộc chuyên khoa: ' . ($service->department->name ?? 'Không xác định') .
                    ', nhưng bác sĩ thuộc chuyên khoa: ' . ($doctor->department->name ?? 'Không xác định') . '.' .
                    ' Bạn có thể chọn: ' . $recommendedList . '.'
            ])->withInput();
        }

        // Kiểm tra trạng thái bác sĩ
        if ($doctor->user->status !== 'online') {
            return back()->withErrors(['doctor_id' => 'Bác sĩ hiện không hoạt động, vui lòng chọn bác sĩ khác.'])->withInput();
        }

        // Kiểm tra xung đột lịch
        $conflict = AppointmentHelper::isConflict(
            $validated['doctor_id'],
            $validated['appointment_time'],
            $validated['service_id']
        );

        if ($conflict['doctor_conflict']) {
            return back()->withErrors(['appointment_time' => 'Bác sĩ đã có lịch hẹn thời gian này.'])->withInput();
        }

        if ($conflict['room_conflict']) {
            return back()->withErrors(['appointment_time' => 'Phòng khám đã có lịch hẹn thời gian này.'])->withInput();
        }

        // Kiểm tra lịch làm việc
        $working = WorkingSchedule::where('doctor_id', $validated['doctor_id'])
            ->whereDate('day', $day)
            ->first()
            ?? WorkingSchedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$working) {
            if ($dayOfWeek === 'Sunday') {
                return back()->withErrors(['doctor_id' => 'Bác sĩ không làm việc Chủ nhật.'])->withInput();
            }
            $working = (object) ['start_time' => '08:00', 'end_time' => '17:00'];
        }

        if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
            return back()->withErrors(['appointment_time' => 'Giờ hẹn ngoài khung giờ làm việc (' . $working->start_time . ' - ' . $working->end_time . ').'])->withInput();
        }

        // Kiểm tra nghỉ phép
        $onLeave = DoctorLeave::where('doctor_id', $validated['doctor_id'])
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();

        if ($onLeave) {
            return back()->withErrors(['doctor_id' => 'Bác sĩ đang nghỉ phép ngày này.'])->withInput();
        }

        // Kiểm tra trùng lịch bệnh nhân
        $appointmentTime = $appointmentDate;
        $endTime = $appointmentTime->copy()->addMinutes($service->duration);

        $patientConflict = Appointment::where('patient_id', $validated['patient_id'])
            ->where(function ($q) use ($appointmentTime, $endTime) {
                $q->where('appointment_time', '<', $endTime)
                    ->where('end_time', '>', $appointmentTime);
            })
            ->exists();

        if ($patientConflict) {
            return back()->withErrors(['appointment_time' => 'Bệnh nhân đã có lịch hẹn trùng giờ này.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $endTime = $appointmentDate->copy()->addMinutes($service->duration);

            // 🎯 Logic trạng thái dựa trên payment_method
            $appointmentStatus = $validated['payment_method'] === 'cash' ? 'confirmed' : 'pending';
            $paymentStatus = $validated['payment_method'] === 'cash' ? 'paid' : 'pending';
            $orderStatus = $validated['payment_method'] === 'cash' ? 'completed' : 'pending';

            $appointment = Appointment::create([
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'],
                'service_id' => $validated['service_id'],
                'appointment_time' => $validated['appointment_time'],
                'end_time' => $endTime,
                'status' => $appointmentStatus,
                'reason' => $validated['reason'] ?? null,
                'treatment_plan_id' => $validated['treatment_plan_id'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $price = $service->price;

            $order = Order::create([
                'user_id' => $validated['patient_id'],
                'appointment_id' => $appointment->id,
                'total_amount' => $price,
                'status' => $orderStatus,
                'ordered_at' => now(),
                'created_by' => auth()->id(),
            ]);

            OrderService::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => 1,
                'price' => $price,
            ]);

            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $price,
                'status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'user_id' => auth()->id(),
            ]);

            // Chỉ ghi PaymentHistory nếu đã trả
            if ($paymentStatus === 'paid') {
                PaymentHistory::create([
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_date' => now(),
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('receptionist.appointments.index')
                ->with('success', 'Tạo lịch hẹn thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['error' => 'Có lỗi khi tạo lịch hẹn: ' . $e->getMessage()])->withInput();
        }
    }

    public function pay($id)
    {
        $appointment = Appointment::findOrFail($id);

        DB::transaction(function () use ($appointment) {
            $payment = $appointment->payment;
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $appointment->update([
                'status' => 'confirmed',
            ]);
        });

        return back()->with('success', 'Đã xác nhận thanh toán & lịch hẹn.');
    }


    public function confirmPayment($id)
    {
        $appointment = Appointment::findOrFail($id);

        $payment = $appointment->payment;

        if (!$payment || $payment->status === 'paid') {
            return back()->with('error', 'Không tìm thấy khoản cần xác nhận hoặc đã thanh toán.');
        }

        DB::transaction(function () use ($appointment, $payment) {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $appointment->update([
                'status' => 'confirmed',
            ]);

            PaymentHistory::create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_date' => now(),
                'user_id' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Xác nhận thanh toán & cập nhật lịch hẹn thành công.');
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])->findOrFail($id);
        $doctor = $appointment->doctor;
        $service = $appointment->service;

        return view('reception.appointments.show', compact('appointment', 'doctor', 'service'));
    }

    public function edit($id)
    {
        $appointment = Appointment::with([
            'patient',
            'doctor.user',
            'service',
            'payment'
        ])->findOrFail($id);

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->route('receptionist.appointments.index')
                ->withErrors(['error' => 'Không thể sửa lịch hẹn đã hoàn tất hoặc đã huỷ.']);
        }

        $patients = User::where('role_id', 4)->get();
        $doctors = Doctor::with('user')->get();
        $services = Service::where('status', 'active')->get();

        return view('reception.appointments.edit', compact(
            'appointment',
            'patients',
            'doctors',
            'services'
        ));
    }


    public function update(Request $request, $id)
    {

        $appointment = Appointment::with(['service'])->findOrFail($id);

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['error' => 'Không thể sửa lịch hẹn đã hoàn tất hoặc đã huỷ.'])->withInput();
        }

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_time' => ['required', 'date', 'after:now'],
            'reason' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', Rule::in(['cash', 'card', 'bank'])],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
        ], [
            'patient_id.required' => 'Vui lòng chọn bệnh nhân.',
            'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
            'service_id.required' => 'Vui lòng chọn dịch vụ khám.',
            'appointment_time.required' => 'Vui lòng chọn thời gian hẹn.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'status.required' => 'Vui lòng chọn trạng thái lịch hẹn.',
        ]);

        $appointmentDate = Carbon::parse($validated['appointment_time']);
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');
        $dayOfWeek = $appointmentDate->format('l');

        $doctor = Doctor::with(['department', 'user'])->findOrFail($validated['doctor_id']);
        $service = Service::with('department')->findOrFail($validated['service_id']);

        // Kiểm tra chuyên khoa
        if ($doctor->department_id !== $service->department_id) {
            $recommendedList = Service::where('department_id', $doctor->department_id)
                ->limit(5)
                ->pluck('name')
                ->implode(', ');

            return back()->withErrors([
                'service_id' => 'Dịch vụ bạn chọn thuộc chuyên khoa: ' . ($service->department->name ?? 'Không xác định') .
                    ', nhưng bác sĩ thuộc chuyên khoa: ' . ($doctor->department->name ?? 'Không xác định') . '.' .
                    ' Bạn có thể chọn: ' . $recommendedList . '.'
            ])->withInput();
        }

        // Kiểm tra trạng thái bác sĩ
        if ($doctor->user->status !== 'online') {
            return back()->withErrors(['doctor_id' => 'Bác sĩ hiện không hoạt động, vui lòng chọn bác sĩ khác.'])->withInput();
        }

        // Kiểm tra xung đột lịch
        $conflict = AppointmentHelper::isConflict(
            $validated['doctor_id'],
            $validated['appointment_time'],
            $validated['service_id'],
            $appointment->id
        );

        if ($conflict['doctor_conflict']) {
            return back()->withErrors(['appointment_time' => 'Bác sĩ đã có lịch hẹn thời gian này.'])->withInput();
        }

        if ($conflict['room_conflict']) {
            return back()->withErrors(['appointment_time' => 'Phòng khám đã có lịch hẹn thời gian này.'])->withInput();
        }

        // Kiểm tra lịch làm việc
        $working = WorkingSchedule::where('doctor_id', $validated['doctor_id'])
            ->whereDate('day', $day)
            ->first()
            ?? WorkingSchedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$working) {
            if ($dayOfWeek === 'Sunday') {
                return back()->withErrors(['doctor_id' => 'Bác sĩ không làm việc Chủ nhật.'])->withInput();
            }
            $working = (object) ['start_time' => '08:00', 'end_time' => '17:00'];
        }

        if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
            return back()->withErrors(['appointment_time' => 'Giờ hẹn ngoài khung giờ làm việc (' . $working->start_time . ' - ' . $working->end_time . ').'])->withInput();
        }

        // Kiểm tra nghỉ phép
        $onLeave = DoctorLeave::where('doctor_id', $validated['doctor_id'])
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();

        if ($onLeave) {
            return back()->withErrors(['doctor_id' => 'Bác sĩ đang nghỉ phép ngày này.'])->withInput();
        }

        // Kiểm tra trùng lịch bệnh nhân
        $appointmentTime = $appointmentDate;
        $endTime = $appointmentTime->copy()->addMinutes($service->duration);

        $patientConflict = Appointment::where('patient_id', $validated['patient_id'])
            ->where('id', '!=', $appointment->id) // loại chính nó ra
            ->where(function ($q) use ($appointmentTime, $endTime) {
                $q->where('appointment_time', '<', $endTime)
                    ->where('end_time', '>', $appointmentTime);
            })
            ->exists();


        DB::beginTransaction();

        try {
            $endTime = $appointmentDate->copy()->addMinutes($service->duration);

            // $appointmentStatus = $validated['payment_method'] === 'cash' ? 'confirmed' : 'pending';
            $paymentStatus = $validated['payment_method'] === 'cash' ? 'paid' : 'pending';
            $orderStatus = $validated['payment_method'] === 'cash' ? 'completed' : 'pending';

            $appointment->update([
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'],
                'service_id' => $validated['service_id'],
                'appointment_time' => $validated['appointment_time'],
                'end_time' => $endTime,
                'status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            $appointment->order->update([
                'total_amount' => $service->price,
                'status' => $orderStatus,
            ]);

            DB::table('order_service')
                ->where('order_id', $appointment->order->id)
                ->update([
                    'service_id' => $service->id,
                    'price' => $service->price,
                    'updated_at' => now(),
                ]);


            $appointment->payment->update([
                'amount' => $service->price,
                'status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
            ]);

            DB::commit();

            return redirect()->route('receptionist.appointments.index')
                ->with('success', 'Cập nhật lịch hẹn thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['error' => 'Có lỗi khi cập nhật lịch hẹn: ' . $e->getMessage()])->withInput();
        }
    }
}
