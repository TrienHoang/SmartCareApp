<?php

namespace App\Http\Controllers\reception;

use App\Helpers\AppointmentHelper;
use App\Http\Controllers\Controller;
use App\Mail\AppointmentConfirmed;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $keyword = $request->q;

        $patients = User::whereIn('role_id', [3, 5])
            ->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            })->get(['id', 'full_name', 'phone', 'email', 'role_id']);

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
        // if (empty($daysOfWeek)) {
        //     $daysOfWeek = [1, 2, 3, 4, 5, 6];
        // }

        // Ngày làm việc cụ thể (YYYY-MM-DD)
        $specificDates = $doctor->workingSchedules()
            ->whereNotNull('day')
            ->where('day', '>=', now()->toDateString())
            ->where('status', 'Đã xét duyệt')
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

        $service = Service::findOrFail($serviceId);
        $slotDuration = $service->duration ?? 30;

        $dayOfWeek = Carbon::parse($date)->format('l');

        // Lấy tất cả working schedule (có thể nhiều ca)
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $doctorId)
            ->where('status', 'Đã xét duyệt')
            ->whereNotNull('shift_id')
            ->where(function ($q) use ($date, $dayOfWeek) {
                $q->whereDate('day', $date)
                    ->orWhere('day_of_week', $dayOfWeek);
            })
            ->get()
            ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
            ->values();

        if ($workings->isEmpty()) {
            return response()->json([]);
        }

        // Lấy các lịch hẹn trong ngày
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_time', $date)
            ->whereNotIn('status', ['cancelled'])
            ->when($currentAppointmentId, fn($q) => $q->where('id', '<>', $currentAppointmentId))
            ->get()
            ->map(fn($a) => [
                'start' => Carbon::parse($a->appointment_time),
                'end'   => Carbon::parse($a->end_time),
            ]);

        $lunchStart = Carbon::parse("$date 12:00");
        $lunchEnd   = Carbon::parse("$date 13:00");

        $availableSlots = [];
        $now = now();

        foreach ($workings as $working) {
            $shiftStart = Carbon::parse("$date {$working->shift->start_time}");
            $shiftEnd   = Carbon::parse("$date {$working->shift->end_time}");

            $start = $shiftStart->copy();
            while ($start->copy()->addMinutes($slotDuration) <= $shiftEnd) {
                $slotStart = $start->copy();
                $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

                // Nếu ngày được chọn là ngày hôm nay, loại bỏ các giờ đã qua
                if ($date === $now->toDateString() && $slotEnd <= $now) {
                    $start->addMinutes(5);
                    continue;
                }

                // Bỏ qua khung giờ rơi vào giờ nghỉ trưa
                if (
                    ($slotStart < $lunchEnd && $slotEnd > $lunchStart)
                ) {
                    $start->addMinutes(5);
                    continue;
                }

                // Kiểm tra xung đột với lịch đã có
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
                    $availableSlots[] = $slotStart->format('H:i');
                    // nhảy tới cuối slot
                    $start = $slotEnd;
                } else {
                    // có conflict thì quét tiếp sau 5 phút
                    $start->addMinutes(5);
                }
            }
        }

        // Loại bỏ trùng và sắp xếp
        $availableSlots = array_values(array_unique($availableSlots));
        sort($availableSlots);

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
            'appointment_time.after' => 'Thời gian hẹn phải lớn hơn hôm nay.',
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
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $validated['doctor_id'])
            ->where('status', 'Đã xét duyệt')
            ->whereDate('day', $day)
            ->whereNotNull('shift_id')
            ->get()
            ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
            ->values();

        // Nếu không có thì lấy theo thứ
        if ($workings->isEmpty()) {
            $workings = WorkingSchedule::with('shift')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('status', 'Đã xét duyệt')
                ->where('day_of_week', $dayOfWeek)
                ->whereNotNull('shift_id')
                ->get()
                ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
                ->values();

            if ($workings->isEmpty()) {
                return back()->withErrors([
                    'doctor_id' => 'Bác sĩ chưa đăng ký lịch làm việc cho ngày này, không thể đặt lịch.'
                ])->withInput();
            }
        }

        // So sánh giờ bằng Carbon để chính xác hơn
        $appointmentTimeOnly = Carbon::createFromFormat('H:i', $timeOnly);

        // Biến cờ để kiểm tra xem có ca phù hợp không
        $validShift = false;

        foreach ($workings as $w) {
            $shiftStart = Carbon::createFromFormat('H:i:s', $w->shift->start_time);
            $shiftEnd   = Carbon::createFromFormat('H:i:s', $w->shift->end_time);

            if ($appointmentTimeOnly->betweenIncluded($shiftStart, $shiftEnd)) {
                $validShift = true;
                break;
            }
        }

        if (!$validShift) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn ngoài khung giờ làm việc của bác sĩ.'
            ])->withInput();
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

            // ✅ Sinh mã QR khi trạng thái đã xác nhận
            if ($appointment->status === 'confirmed') {
                $appointment->update([
                    'qr_code' => (string) Str::uuid(),
                ]);

                $patient = User::find($appointment->patient_id);
                if ($patient && $patient->email) {
                    Mail::to($patient->email)->send(new AppointmentConfirmed($appointment));
                }
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

        $schedule = WorkingSchedule::with('room')
            ->where('doctor_id', $appointment->doctor_id)
            ->whereDate('day', Carbon::parse($appointment->appointment_time)->toDateString()) 
            ->first();
    
        $appointment->room_name = $schedule && $schedule->room
            ? $schedule->room->name
            : 'Chưa xác định';

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

        $oldStatus = $appointment->status;

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

        // Lấy danh sách lịch làm việc theo ngày cụ thể
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $validated['doctor_id'])
            ->whereDate('day', $day)
            ->get()
            ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
            ->values();

        // Nếu không có thì lấy theo thứ
        if ($workings->isEmpty()) {
            $workings = WorkingSchedule::with('shift')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('day_of_week', $dayOfWeek)
                ->get()
                ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
                ->values();

            if ($workings->isEmpty()) {
                return back()->withErrors([
                    'doctor_id' => 'Bác sĩ chưa đăng ký lịch làm việc cho ngày này, không thể đặt lịch.'
                ])->withInput();
            }
        }

        // Kiểm tra thời gian hẹn có nằm trong bất kỳ ca nào
        $appointmentTimeOnly = Carbon::createFromFormat('H:i', $timeOnly);

        $validShift = false;
        foreach ($workings as $w) {
            $shiftStart = Carbon::createFromFormat('H:i:s', $w->shift->start_time);
            $shiftEnd   = Carbon::createFromFormat('H:i:s', $w->shift->end_time);

            if ($appointmentTimeOnly->betweenIncluded($shiftStart, $shiftEnd)) {
                $validShift = true;
                break;
            }
        }

        if (!$validShift) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn ngoài khung giờ làm việc của bác sĩ.'
            ])->withInput();
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

            // Nếu trạng thái mới là confirmed → payment cũng paid
            if ($validated['status'] === 'confirmed' && $appointment->payment->status !== 'paid') {
                $appointment->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => $validated['payment_method'],
                ]);

                PaymentHistory::create([
                    'payment_id' => $appointment->payment->id,
                    'amount' => $appointment->payment->amount,
                    'payment_method' => $validated['payment_method'],
                    'payment_date' => now(),
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            // Gửi mail nếu chuyển sang trạng thái xác nhận
            if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
                Mail::to($appointment->patient->email)->send(new AppointmentConfirmed(
                    $appointment->fresh(['patient', 'doctor.user', 'service'])
                ));
            }

            return redirect()->route('receptionist.appointments.index')
                ->with('success', 'Cập nhật lịch hẹn thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['error' => 'Có lỗi khi cập nhật lịch hẹn: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateStatus($id)
    {
        $appointment = Appointment::with(['payment', 'patient', 'doctor.user', 'service'])->findOrFail($id);

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể cập nhật trạng thái lịch hẹn đang chờ xác nhận.']);
        }

        DB::transaction(function () use ($appointment) {
            $today = Carbon::now()->startOfDay();
            $appointmentDate = Carbon::parse($appointment->appointment_time)->startOfDay();

            $newStatus = $appointmentDate->equalTo($today) ? 'checked_in' : 'confirmed';

            $appointment->update([
                'status' => $newStatus,
                'updated_by' => auth()->id(),
            ]);

            // ✅ Nếu lịch hẹn được xác nhận thì sinh QR code
            if ($appointment->status === 'confirmed') {
                $appointment->update([
                    'qr_code' => (string) Str::uuid(),
                ]);
            }

            if ($appointment->payment && $appointment->payment->status !== 'paid') {
                $appointment->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                PaymentHistory::create([
                    'payment_id' => $appointment->payment->id,
                    'amount' => $appointment->payment->amount,
                    'payment_method' => $appointment->payment->payment_method,
                    'payment_date' => now(),
                    'user_id' => auth()->id(),
                ]);
            }

            // ✅ Gửi mail xác nhận lịch hẹn tại đây
            try {
                Mail::to($appointment->patient->email)->send(new AppointmentConfirmed($appointment));
            } catch (\Exception $e) {
                // Ghi log lỗi nếu cần
                logger()->error('Lỗi gửi email xác nhận: ' . $e->getMessage());
            }
        });

        return back()->with('success', 'Cập nhật trạng thái lịch hẹn và thanh toán thành công. Email đã được gửi.');
    }

    public function cancel($id)
    {
        $appointment = Appointment::with('payment')->findOrFail($id);

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['error' => 'Không thể huỷ lịch hẹn đã hoàn tất hoặc đã huỷ.']);
        }

        if ($appointment->payment && $appointment->payment->status === 'paid') {
            return back()->withErrors(['error' => 'Lịch hẹn đã được thanh toán. Vui lòng liên hệ quản trị viên để xử lý hoàn tiền.']);
        }

        DB::transaction(function () use ($appointment) {
            $appointment->update([
                'status' => 'cancelled',
                'updated_by' => auth()->id(),
            ]);

            if ($appointment->payment) {
                $appointment->payment->update([
                    'status' => 'cancelled',
                    'paid_at' => null,
                ]);
            }

            // Nếu có Order liên quan cũng có thể update trạng thái
            if ($appointment->order) {
                $appointment->order->update([
                    'status' => 'cancelled',
                ]);
            }
        });

        return back()->with('success', 'Đã huỷ lịch hẹn thành công.');
    }

    public function getDoctorsByService(Service $service)
    {
        $doctors = $service->doctors()->with('user')->get();

        return response()->json($doctors);
    }

    public function printPaymentReceipt($id)
    {
        $appointment = Appointment::with([
            'patient',
            'doctor.user',
            'service',
            'payment' => function ($query) {
                $query->with('promotion');
            }
        ])->findOrFail($id);

        // Kiểm tra xem appointment đã có payment chưa
        if (!$appointment->payment) {
            return redirect()->back()->with('error', 'Lịch hẹn này chưa có thông tin thanh toán.');
        }

        // Kiểm tra trạng thái thanh toán
        if ($appointment->payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Chỉ có thể in phiếu thanh toán cho các lịch hẹn đã thanh toán.');
        }

        return view('reception.appointments.payment-receipt', [
            'appointment' => $appointment,
            'payment' => $appointment->payment
        ]);
    }

    public function printPaymentReceiptPDF($id)
    {
        $appointment = Appointment::with([
            'patient',
            'doctor.user',
            'service',
            'payment' => function ($query) {
                $query->with('promotion');
            }
        ])->findOrFail($id);

        // Kiểm tra xem appointment đã có payment chưa
        if (!$appointment->payment) {
            abort(404, 'Không tìm thấy thông tin thanh toán.');
        }

        // Kiểm tra trạng thái thanh toán
        if ($appointment->payment->status !== 'paid') {
            abort(403, 'Chỉ có thể in phiếu thanh toán cho các lịch hẹn đã thanh toán.');
        }

        $pdf = Pdf::loadView('reception.appointments.payment-receipt-pdf', [
            'appointment' => $appointment,
            'payment' => $appointment->payment
        ]);

        $filename = 'phieu-thanh-toan-' . $appointment->id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function checkIn($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Lịch hẹn chưa được xác nhận.');
        }

        if (\Carbon\Carbon::parse($appointment->appointment_time)->toDateString() !== now()->toDateString()) {
            return back()->with('error', 'Chỉ được checked-in vào đúng ngày hẹn.');
        }

        $appointment->status = 'checked_in';
        $appointment->save();

        return back()->with('success', 'Bệnh nhân đã được check-in lịch hẹn.');
    }
}
