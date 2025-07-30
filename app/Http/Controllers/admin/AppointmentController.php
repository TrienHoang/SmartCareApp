<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppointmentHelper;
use App\Helpers\TreatmentPlanHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateStatusAppointmentRequest;
use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use App\Models\AppointmentLog;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\MedicalRecord;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\Service;
use App\Models\Shift;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundSuccessfulMail;

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
            'payment' => fn($q) => $q->orderBy('paid_at', 'desc'),
            'order:id,appointment_id,status',
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
        $from_input = $request->date_from;
        $to_input = $request->date_to;

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
            $to = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;

            if ($from && $to && $from->gt($to)) {
                [$from, $to] = [$to, $from];
                [$from_input, $to_input] = [$to_input, $from_input];

                return redirect()->route('admin.appointments.index', [
                    'date_from' => $from_input,
                    'date_to' => $to_input,
                ])->with('date_swapped', true);
            }

            if ($from && $to) {
                $query->whereBetween('appointment_time', [$from, $to]);
            } elseif ($from) {
                $query->where('appointment_time', '>=', $from);
            } elseif ($to) {
                $query->where('appointment_time', '<=', $to);
            }
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
            'stats',
            'from_input',
            'to_input',
        ));
    }

    public function create()
    {
        return view('admin.Appointment.create', [
            'patients' => User::where('role_id', 3)->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
            'treatmentPlans' => TreatmentPlan::whereIn('status', ['chua_tien_hanh', 'dang_tien_hanh'])->get(),
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        // dd($request->all());
        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeekEn = $appointmentDate->format('l'); // e.g., 'Monday'
        $dayOfWeekVN = [
            'Sunday' => 'Chủ nhật',
            'Monday' => 'Thứ hai',
            'Tuesday' => 'Thứ ba',
            'Wednesday' => 'Thứ tư',
            'Thursday' => 'Thứ năm',
            'Friday' => 'Thứ sáu',
            'Saturday' => 'Thứ bảy',
        ][$dayOfWeekEn] ?? $dayOfWeekEn;

        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');

        $doctor = Doctor::with(['department', 'user'])->findOrFail($request->doctor_id);
        $service = Service::with('department')->findOrFail($request->service_id);

        if ((int) $doctor->department_id !== (int) $service->department_id) {
            $recommendedList = Service::where('department_id', $doctor->department_id)
                ->limit(5)->pluck('name')->implode(', ');

            return back()->withErrors([
                'service_id' => 'Dịch vụ bạn chọn thuộc chuyên khoa: ' . ($service->department->name ?? 'Không xác định') .
                    ', nhưng bác sĩ được chỉ định hiện thuộc chuyên khoa: ' . ($doctor->department->name ?? 'Không xác định') . '.' .
                    ' Bạn có thể chọn một trong các dịch vụ phù hợp: ' . $recommendedList . '.'
            ])->withInput();
        }

        if ($doctor->user->status !== 'online') {
            return back()->withErrors([
                'doctor_id' => 'Bác sĩ hiện không hoạt động, vui lòng chọn bác sĩ khác.'
            ])->withInput();
        }

        $conflict = AppointmentHelper::isConflict(
            $request->doctor_id,
            $request->appointment_time,
            $request->service_id
        );

        if ($conflict['doctor_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đã có lịch hẹn vào thời gian bạn chọn.'
            ])->withInput();
        }

        if ($conflict['room_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Phòng khám đã có lịch hẹn vào thời gian này.'
            ])->withInput();
        }

        // === Lấy lịch làm việc ===
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $request->doctor_id)
            ->whereDate('day', $day)
            ->where('status', 'Đã xét duyệt')
            ->get();

        if ($workings->isEmpty()) {
            $workings = WorkingSchedule::with('shift')
                ->where('doctor_id', $request->doctor_id)
                ->where('day_of_week', $dayOfWeekVN)
                ->where('status', 'Đã xét duyệt')
                ->get();
        }

        // Bác sĩ đk lịch làm việc mới đặt được lịch hẹn
        if ($workings->isEmpty()) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ chưa đăng ký lịch làm việc vào ngày ' . $day .
                    '. Vui lòng chọn bác sĩ khác hoặc ngày khác.'
            ])->withInput();
        }

        // Trong giờ nghỉ trưa
        if ($timeOnly >= '12:00' && $timeOnly < '13:00') {
            return back()->withErrors([
                'appointment_time' => 'Không thể đặt lịch trong giờ nghỉ trưa (12:00–13:00).'
            ])->withInput();
        }

        $serviceDuration = $service->duration ?? 0;
        $now = Carbon::now();
        $appointmentEnd = $appointmentDate->copy()->addMinutes($serviceDuration);

        if ($appointmentEnd->lt($now)) {
            return back()->withErrors([
                'appointment_time' => 'Không thể đặt lịch cho thời điểm đã qua.'
            ])->withInput();
        }

        $lunchStart = Carbon::parse("$day 12:00");
        $lunchEnd = Carbon::parse("$day 13:00");

        if (
            ($appointmentDate->lt($lunchStart) && $appointmentEnd->gt($lunchStart)) ||
            ($appointmentDate->between($lunchStart, $lunchEnd)) ||
            ($appointmentEnd->between($lunchStart, $lunchEnd))
        ) {
            return back()->withErrors([
                'appointment_time' => 'Thời gian khám trùng hoặc tràn sang giờ nghỉ trưa.'
            ])->withInput();
        }

        // === Kiểm tra thời gian có nằm trong ca làm việc không ===
        $isWithinWorkingTime = false;

        foreach ($workings as $w) {
            $shift = $w->shift;
            if ($shift && $shift->start_time && $shift->end_time) {
                $appointmentTime = Carbon::createFromFormat('H:i', $timeOnly);
                $shiftStart = Carbon::createFromFormat('H:i:s', $shift->start_time);
                $shiftEnd = Carbon::createFromFormat('H:i:s', $shift->end_time);

                if ($appointmentTime->betweenIncluded($shiftStart, $shiftEnd)) {
                    $isWithinWorkingTime = true;
                    break;
                }
            }
        }

        if (!$isWithinWorkingTime) {
            $workingHours = $workings->map(function ($w) {
                if ($w->shift && $w->shift->start_time && $w->shift->end_time) {
                    return $w->shift->start_time . ' - ' . $w->shift->end_time;
                }
                return 'Không rõ ca trực';
            });

            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn không nằm trong giờ làm việc của bác sĩ: ' .
                    $workingHours->join(', ')
            ])->withInput();
        }

        // === Kiểm tra bác sĩ có nghỉ phép không ===
        $onLeave = DoctorLeave::where('doctor_id', $request->doctor_id)
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();

        if ($onLeave) {
            return back()->withErrors([
                'doctor_id' => 'Bác sĩ đang trong thời gian nghỉ phép vào ngày này.'
            ])->withInput();
        }

        // === Xung đột lịch bệnh nhân ===
        $patientConflict = Appointment::where('patient_id', $request->patient_id)
            ->where(function ($q) use ($appointmentDate, $appointmentEnd) {
                $q->where('appointment_time', '<', $appointmentEnd)
                    ->where('end_time', '>', $appointmentDate);
            })
            ->exists();

        if ($patientConflict) {
            return back()->withErrors([
                'appointment_time' => 'Bệnh nhân đã có lịch hẹn trùng thời gian.'
            ])->withInput();
        }

        // === Tạo lịch hẹn ===
        $requestData = $request->only([
            'patient_id',
            'doctor_id',
            'service_id',
            'appointment_time',
            'status',
            'reason',
            'treatment_plan_id',
        ]);

        $requestData['end_time'] = $appointmentEnd;
        $requestData['created_by'] = auth()->id();

        $appointment = Appointment::create($requestData);

        // === Tạo đơn hàng và thanh toán ===
        $price = $service->price;

        $order = Order::create([
            'user_id' => $request->patient_id,
            'appointment_id' => $appointment->id,
            'total_amount' => $price,
            'status' => 'completed',
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
            'status' => 'paid',
            'payment_method' => 'bank',
            'paid_at' => now(),
        ]);

        PaymentHistory::create([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'payment_method' => 'bank',
            'payment_date' => now(),
        ]);

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
            'treatmentPlans' => TreatmentPlan::where('patient_id', $appointment->patient_id)
                ->where(function ($q) use ($appointment) {
                    $q->whereIn('status', ['chua_tien_hanh', 'dang_tien_hanh'])
                        ->orWhere('id', $appointment->treatment_plan_id);
                })
                ->get(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Không cho cập nhật nếu đã hoàn thành hoặc đã hủy
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return back()->withErrors([
                'status' => 'Không thể cập nhật lịch hẹn đã hoàn thành hoặc đã hủy.'
            ]);
        }

        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');

        // Nếu chuyển sang completed mà thời gian vẫn ở tương lai → lỗi
        if (
            $appointment->status !== 'completed' &&
            $request->status === 'completed' &&
            $appointmentDate->isFuture()
        ) {
            return back()->withErrors([
                'appointment_time' => 'Không thể hoàn thành lịch hẹn khi thời gian hẹn vẫn còn ở tương lai.'
            ])->withInput();
        }

        $doctor = Doctor::with(['department', 'user'])->findOrFail($request->doctor_id);
        $service = Service::with('department')->findOrFail($request->service_id);

        if ((int) $doctor->department_id !== (int) $service->department_id) {
            $recommendedList = Service::where('department_id', $doctor->department_id)
                ->limit(5)
                ->pluck('name')
                ->implode(', ');

            return back()->withErrors([
                'service_id' => 'Dịch vụ bạn chọn thuộc chuyên khoa: ' . ($service->department->name ?? 'Không xác định') .
                    ', nhưng bác sĩ được chỉ định hiện thuộc chuyên khoa: ' . ($doctor->department->name ?? 'Không xác định') . '.' .
                    ' Bạn có thể chọn một trong các dịch vụ phù hợp: ' . $recommendedList . '.'
            ])->withInput();
        }

        if ($doctor->user->status !== 'online') {
            return back()->withErrors([
                'doctor_id' => 'Bác sĩ hiện không hoạt động, vui lòng chọn bác sĩ khác.'
            ])->withInput();
        }

        if (
            $request->status === 'confirmed' &&
            $appointmentDate->isPast() &&
            $appointment->status === 'pending'
        ) {
            return back()->withErrors([
                'status' => 'Không thể xác nhận lịch hẹn đã quá ngày.'
            ])->withInput();
        }

        // Kiểm tra trùng lịch
        $conflict = AppointmentHelper::isConflict(
            $request->doctor_id,
            $request->appointment_time,
            $request->service_id,
            $appointment->id
        );

        if ($conflict['doctor_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đã có lịch hẹn vào thời gian này. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        if ($conflict['room_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Phòng khám đã có lịch hẹn vào thời gian này. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        // Tìm theo ngày cụ thể
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $request->doctor_id)
            ->where('status', 'Đã xét duyệt')
            ->whereDate('day', $day)
            ->whereNotNull('shift_id')
            ->get()
            ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
            ->values();

        if ($workings->isEmpty()) {
            // Tìm theo thứ trong tuần
            $workings = WorkingSchedule::with('shift')
                ->where('doctor_id', $request->doctor_id)
                ->where('status', 'Đã xét duyệt')
                ->where('day_of_week', $dayOfWeek)
                ->whereNotNull('shift_id')
                ->get()
                ->filter(fn($w) => $w->shift && $w->shift->start_time && $w->shift->end_time)
                ->values();

            logger()->info('📌 LỊCH THEO THỨ (ĐÃ LỌC SHIFT):', $workings->toArray());

            if ($workings->isEmpty()) {
                return back()->withErrors([
                    'appointment_time' => 'Bác sĩ chưa đăng ký lịch làm việc vào ngày ' . $day .
                        '. Vui lòng chọn bác sĩ khác hoặc ngày khác.'
                ])->withInput();
            }
        }

        // Kiểm tra nghỉ trưa từ 12:00 đến 13:00
        if ($timeOnly >= '12:00' && $timeOnly < '13:00') {
            return back()->withErrors([
                'appointment_time' => 'Không thể đặt lịch trong thời gian nghỉ trưa (12:00 - 13:00).'
            ])->withInput();
        }

        // Kiểm tra giờ có nằm trong bất kỳ ca nào không (so sánh bằng Carbon)
        $isWithinWorkingTime = $workings->contains(function ($w) use ($timeOnly) {
            if (!isset($w->shift, $w->shift->start_time, $w->shift->end_time)) {
                return false;
            }

            $appointmentTime = Carbon::createFromFormat('H:i', $timeOnly);
            $shiftStart = Carbon::createFromFormat('H:i:s', $w->shift->start_time);
            $shiftEnd = Carbon::createFromFormat('H:i:s', $w->shift->end_time);

            return $appointmentTime->betweenIncluded($shiftStart, $shiftEnd);
        });

        if (!$isWithinWorkingTime) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn không nằm trong giờ làm việc của bác sĩ: ' .
                    $workings->pluck('shift.start_time')->join(', ') . ' - ' .
                    $workings->pluck('shift.end_time')->join(', ')
            ])->withInput();
        }

        // Kiểm tra nghỉ phép
        $onLeave = DoctorLeave::where('doctor_id', $request->doctor_id)
            ->where('start_date', '<=', $appointmentDate)
            ->where('end_date', '>=', $appointmentDate)
            ->where('approved', true)
            ->exists();

        if ($onLeave) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ nghỉ phép ngày này. Vui lòng chọn ngày khác.'
            ])->withInput();
        }

        // Nếu trạng thái mới là 'completed' thì tạo medical record nếu chưa có
        if ($request->status === 'completed') {
            $existing = MedicalRecord::where('appointment_id', $appointment->id)->exists();

            if (!$existing) {
                MedicalRecord::create([
                    'appointment_id' => $appointment->id,
                    'code' => 'MR' . now()->format('YmdHis') . $appointment->id,
                ]);
            }
        }

        // Lưu thông tin cũ
        $oldStatus    = $appointment->status;
        $oldTime      = $appointment->appointment_time;
        $oldDoctorId  = $appointment->doctor_id;
        $oldServiceId = $appointment->service_id;

        $newDoctor = Doctor::with('user')->find($request->doctor_id);
        $newService = Service::find($request->service_id);

        $patientId = $request->has('patient_id') ? $request->patient_id : $appointment->patient_id;

        $appointmentTime = Carbon::parse($request->appointment_time);
        $duration = $newService->duration;
        $endTime = $appointmentTime->copy()->addMinutes($duration);

        // Không cho đặt lịch nếu thời gian khám rơi vào hoặc kéo dài sang giờ nghỉ trưa (12:00 - 13:00)
        $lunchStart = Carbon::parse($appointmentDate->format('Y-m-d') . ' 12:00');
        $lunchEnd = Carbon::parse($appointmentDate->format('Y-m-d') . ' 13:00');

        if (
            ($appointmentDate->lt($lunchStart) && $endTime->gt($lunchStart)) ||
            ($appointmentDate->between($lunchStart, $lunchEnd)) ||
            ($endTime->between($lunchStart, $lunchEnd))
        ) {
            return back()->withErrors([
                'appointment_time' => 'Thời gian khám rơi vào hoặc kéo dài sang giờ nghỉ trưa (12:00 - 13:00). Vui lòng chọn thời gian khác.'
            ])->withInput();
        }


        $overlappedAppointments = Appointment::where('patient_id', $patientId)
            ->where('id', '!=', $appointment->id)
            ->where(function ($q) use ($appointmentTime, $endTime) {
                $q->where('appointment_time', '<', $endTime)
                    ->where('end_time', '>', $appointmentTime);
            })
            ->get();

        if ($overlappedAppointments->count() > 0) {
            // DEBUG, để xem trong thực tế nó có chạy vào đây không!
            // dd($overlappedAppointments);
            return back()->withErrors([
                'appointment_time' => 'Bệnh nhân đã có lịch hẹn khác bị trùng thời gian này!'
            ])->withInput();
        }
        // Chuẩn bị dữ liệu cập nhật
        $updateData = [
            'doctor_id'        => $request->doctor_id,
            'service_id'       => $request->service_id,
            'appointment_time' => $request->appointment_time,
            'end_time'         => $endTime,
            'status'           => $request->status,
            'reason'           => $request->reason,
            'treatment_plan_id' => $request->treatment_plan_id,
        ];

        if ($request->status === 'completed' && !$appointment->treatment_plan_id) {
            $plan = TreatmentPlan::where('patient_id', $appointment->patient_id)
                ->whereDate('start_date', '<=', $appointmentDate)
                ->whereDate('end_date', '>=', $appointmentDate)
                ->first();

            if ($plan) {
                $updateData['treatment_plan_id'] = $plan->id;
            }
        }

        // Ghi log thay đổi
        $changes = [];

        if ($oldTime != $request->appointment_time) {
            $changes[] = 'Thời gian: ' . optional($oldTime)->format('d/m/Y H:i') .
                ' → ' . Carbon::parse($request->appointment_time)->format('d/m/Y H:i');
        }

        if ($oldDoctorId != $request->doctor_id) {
            $oldDoctor = optional($appointment->doctor->user)->full_name ?? 'Không xác định';
            $newDoctorName = optional($newDoctor->user)->full_name ?? 'Không xác định';
            $changes[] = 'Bác sĩ: ' . $oldDoctor . ' → ' . $newDoctorName;
        }

        if ($oldServiceId != $request->service_id) {
            $oldServiceName = optional($appointment->service)->name ?? 'Không xác định';
            $newServiceName = optional($newService)->name ?? 'Không xác định';
            $changes[] = 'Dịch vụ: ' . $oldServiceName . ' → ' . $newServiceName;
        }

        if ($oldStatus != $request->status) {
            $changes[] = 'Trạng thái: ' . $oldStatus . ' → ' . $request->status;
        }

        if ($appointment->treatment_plan_id != $request->treatment_plan_id) {
            $oldPlan = TreatmentPlan::find($appointment->treatment_plan_id)?->title ?? 'Không có';
            $newPlan = TreatmentPlan::find($request->treatment_plan_id)?->title ?? 'Không có';
            $changes[] = 'Kế hoạch điều trị: ' . $oldPlan . ' → ' . $newPlan;
        }

        $payment = $appointment->payment;

        if ($request->status === 'completed' && $payment && $payment->status !== 'paid') {
            return back()->withErrors([
                'status' => 'Không thể hoàn thành lịch hẹn khi chưa thanh toán đủ.'
            ])->withInput();
        }

        $oldPrice = optional($appointment->service)->price ?? 0;
        $newPrice = $newService->price;
        $priceChanged = $oldPrice != $newPrice;

        if ($payment) {
            $alreadyPaid = PaymentHistory::where('payment_id', $payment->id)->sum('amount');
            $remaining = $newPrice - $alreadyPaid;

            if (abs($remaining) < 1) {
                $payment->status = 'paid';
                $payment->note = null;
                $remaining = 0;
            } elseif ($remaining > 0) {
                $payment->status = 'underpaid';
                $payment->note = 'Cần thu thêm: ' . number_format($remaining) . '₫';
            } else {
                $payment->status = 'overpaid';
                $payment->note = 'Cần hoàn lại: ' . number_format(abs($remaining)) . '₫';
            }

            $payment->amount = $newPrice;
            $payment->save();
        }

        DB::beginTransaction();

        try {
            $appointment->update($updateData);

            if (!empty($changes)) {
                AppointmentLog::create([
                    'appointment_id' => $appointment->id,
                    'changed_by'     => auth()->id(),
                    'status_before'  => $oldStatus,
                    'status_after'   => $request->status,
                    'change_time'    => now(),
                    'note'           => implode("\n", $changes),
                ]);
            }
            // Cập nhật trạng thái của kế hoạch điều trị 
            TreatmentPlanHelper::updatePlanStatus($appointment->treatment_plan_id);

            DB::commit();

            // Gửi mail nếu chuyển sang trạng thái xác nhận
            if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
                Mail::to($appointment->patient->email)->send(new AppointmentConfirmed(
                    $appointment->fresh(['patient', 'doctor.user', 'service'])
                ));
            }

            if ($payment && $priceChanged && $payment->status !== 'paid') {
                if ($payment->status === 'underpaid') {
                    session()->flash('warning', 'Dịch vụ mới đắt hơn, cần thu thêm tiền từ bệnh nhân.');
                } elseif ($payment->status === 'overpaid') {
                    session()->flash('warning', 'Dịch vụ mới rẻ hơn, cần hoàn lại tiền cho bệnh nhân.');
                }
            }

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Cập nhật lịch hẹn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật lịch hẹn.']);
        }
    }


    public function cancel($id)
    {
        $appointment = Appointment::with(['payment', 'patient'])->findOrFail($id);

        // 1. Không cho hủy nếu đã hoàn thành hoặc đã hủy
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->route('admin.appointments.index')->withErrors([
                'status' => 'Không thể hủy lịch hẹn đã hoàn thành hoặc đã hủy trước đó.',
            ]);
        }

        $payment = $appointment->payment;

        /**
         * 2. Nếu đã thanh toán
         */
        if ($payment && $payment->status === 'paid') {

            // 2.1 Đã xác nhận
            if ($appointment->status === 'confirmed') {

                // Kiểm tra xem bác sĩ có nghỉ đột xuất (urgent) và không có người thay thế hay không
                if ($this->checkDoctorUnavailable($appointment)) {

                    // Trường hợp do lỗi của phòng khám => hủy và HOÀN TIỀN
                    $appointment->status = 'cancelled';
                    $appointment->save();

                    $result = $this->performVnpayRefund($payment);
                    $this->logRefundResult($payment, $result, 'Hủy do bác sĩ nghỉ đột xuất');

                    if ($result['success']) {
                        return redirect()->route('admin.appointments.index')
                            ->with('success', 'Hủy lịch hẹn do bác sĩ nghỉ đột xuất và đã hoàn tiền qua VNPay.');
                    }

                    return redirect()->route('admin.appointments.index')
                        ->withErrors(['error' => 'Hủy thành công nhưng hoàn tiền thất bại: ' . $result['message']]);
                }

                // Nếu không phải do lỗi phòng khám => hủy nhưng KHÔNG HOÀN TIỀN
                $appointment->status = 'cancelled';
                $appointment->save();

                $payment->refund_status = 'none';
                $payment->note = 'Hủy nhưng không hoàn tiền do lịch đã xác nhận';
                $payment->save();

                return redirect()->route('admin.appointments.index')
                    ->with('success', 'Hủy lịch hẹn thành công. Không hoàn tiền.');
            }

            // 2.2 Đã thanh toán nhưng chưa xác nhận => hủy và hoàn tiền
            if ($appointment->status === 'pending') {
                $appointment->status = 'cancelled';
                $appointment->save();

                $result = $this->performVnpayRefund($payment);
                $this->logRefundResult($payment, $result, 'Hủy do chưa xác nhận');

                if ($result['success']) {
                    return redirect()->route('admin.appointments.index')
                        ->with('success', 'Hủy lịch hẹn thành công và đã hoàn tiền qua VNPay.');
                }

                return redirect()->route('admin.appointments.index')
                    ->withErrors(['error' => 'Hủy thành công nhưng hoàn tiền thất bại: ' . $result['message']]);
            }
        }

        /**
         * 3. Nếu chưa thanh toán => hủy bình thường
         */
        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->status = 'cancelled';
            $appointment->save();

            return redirect()->route('admin.appointments.index')->with('success', 'Hủy lịch hẹn thành công.');
        }

        return redirect()->route('admin.appointments.index')->withErrors([
            'status' => 'Không thể hủy lịch hẹn.',
        ]);
    }

    /**
     * Kiểm tra bác sĩ có nghỉ đột xuất không (urgent + approved)
     */
    protected function checkDoctorUnavailable($appointment)
    {
        $date = Carbon::parse($appointment->appointment_time)->toDateString();

        return DoctorLeave::where('doctor_id', $appointment->doctor_id)
            ->where('approved', 1)
            ->where('urgent', 1)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }

    /**
     * Ghi lại kết quả refund vào payment
     */
    protected function logRefundResult($payment, $result, $reason)
    {
        if ($result['success']) {
            $payment->refund_status = 'success';
            $payment->note = $reason . ' - Hoàn tiền thành công';
        } else {
            $payment->refund_status = 'failed';
            $payment->note = $reason . ' - Hoàn tiền thất bại: ' . $result['message'];
        }
        $payment->save();
    }


    public function show($id)
    {
        $appointment = Appointment::with([
            'patient',
            'doctor.user',
            'doctor.room',
            'service',
            'logs',
            'treatmentPlan.treatmentPlanItems',
            'treatmentPlan.doctor.user',
            'payment.histories',
        ])->findOrFail($id);

        $payment = $appointment->payment;

        $totalAmount = $payment->amount ?? 0;
        $paidAmount = $payment?->histories->sum('amount') ?? 0;
        $remainingAmount = max($totalAmount - $paidAmount, 0);
        $overpaidAmount = max($paidAmount - $totalAmount, 0);


        return view('admin.Appointment.show', compact(
            'appointment',
            'totalAmount',
            'paidAmount',
            'remainingAmount',
            'overpaidAmount'
        ));
    }

    public function updateStatus(UpdateStatusAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $oldStatus = $appointment->status;
        $appointmentDate = Carbon::parse($appointment->appointment_time);

        // Kiểm tra nếu cập nhật sang 'completed' thì không được để thời gian hẹn ở tương lai
        if (
            $request->status === 'completed' &&
            $appointmentDate->isFuture()
        ) {
            return redirect()->back()->withErrors([
                'status' => 'Không thể hoàn thành lịch hẹn khi thời gian hẹn vẫn còn ở tương lai.'
            ]);
        }
        // Cập nhật trạng thái và lý do hủy nếu có
        $appointment->update([
            'status' => $request->status,
            'cancel_reason' => $request->status === 'cancelled' ? $request->note : null
        ]);

        // Ghi log thay đổi trạng thái
        $appointment->logs()->create([
            'changed_by' => auth()->id(),
            'status_before' => $oldStatus,
            'status_after' => $request->status,
            'change_time' => now(),
            'note' => $request->note
        ]);

        // Tự động tạo hồ sơ bệnh án nếu hoàn thành mà chưa có
        if ($request->status === 'completed') {
            $existing = MedicalRecord::where('appointment_id', $appointment->id)->exists();

            if (!$existing) {
                MedicalRecord::create([
                    'appointment_id' => $appointment->id,
                    'code' => 'MR' . now()->format('YmdHis') . $appointment->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    public function searchPatients(Request $request)
    {
        $query = $request->get('q', '');

        $patients = User::where('role_id', 3)
            ->where('status', 'online')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('full_name', 'like', "%$query%")
                        ->orWhere('phone', 'like', "%$query%")
                        ->orWhere('email', 'like', "%$query%");
                });
            })
            ->select('id', 'full_name', 'phone', 'email')
            ->limit(10)
            ->get();

        return response()->json($patients);
    }

    public function pay($id)
    {
        $appointment = Appointment::with(['payment', 'order', 'service'])->findOrFail($id);

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Không thể thanh toán cho lịch hẹn đã bị hủy.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Lịch hẹn đã hoàn thành, không thể thanh toán.');
        }

        $payment = $appointment->payment;
        $servicePrice = $appointment->service->price;

        if (!$payment) {
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount'         => $servicePrice,
                'status'         => 'unpaid',
            ]);
        }

        // Tính lại số tiền đã thanh toán
        $alreadyPaid = PaymentHistory::where('payment_id', $payment->id)->sum('amount');
        $remaining = $servicePrice - $alreadyPaid;

        // Nếu đã trả đủ hoặc thừa
        if ($remaining <= 0) {
            if ($remaining < 0) {
                $payment->update([
                    'status' => 'overpaid',
                    'note'   => 'Bệnh nhân đã thanh toán dư: ' . number_format(abs($remaining)) . '₫',
                ]);
                return back()->with('warning', 'Bệnh nhân đã thanh toán dư: ' . number_format(abs($remaining)) . '₫, cần hoàn tiền.');
            }

            $payment->update([
                'status' => 'paid',
                'note'   => null,
            ]);
            return back()->with('info', 'Lịch hẹn đã được thanh toán đủ.');
        }

        // Thu phần còn thiếu
        PaymentHistory::create([
            'payment_id'     => $payment->id,
            'amount'         => $remaining,
            'payment_method' => 'bank',
            'payment_date'   => now(),
        ]);

        $payment->update([
            'status'         => 'paid',
            'payment_method' => 'bank',
            'paid_at'        => now(),
            'note'           => null,
            'amount'         => $servicePrice,
        ]);

        if ($appointment->order && $appointment->order->status !== 'completed') {
            $appointment->order->update(['status' => 'completed']);
        }

        return back()->with('success', 'Đã thanh toán đủ: ' . number_format($remaining) . '₫');
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

    public function getTreatmentPlanDetails($id)
    {
        $plan = TreatmentPlan::with(['doctor', 'treatmentPlanItems' => function ($q) {
            $q->orderBy('expected_start_date');
        }])->findOrFail($id);

        return response()->json([
            'doctor_id' => $plan->doctor_id,
            'service_id' => $plan->treatmentPlanItems->first()->service_id ?? null,
            'expected_start_date' => $plan->treatmentPlanItems->first()->expected_start_date ?? null,
        ]);
    }

    public function getTreatmentPlansByPatient($patientId)
    {
        $plans = TreatmentPlan::with('doctor.user')
            ->where('patient_id', $patientId)
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'plan_title' => $plan->plan_title,
                    'doctor_name' => $plan->doctor->user->full_name ?? 'Không rõ'
                ];
            });

        return response()->json($plans);
    }


    public function refund($appointmentId)
    {
        $appointment = Appointment::with('payment')->findOrFail($appointmentId);
        $payment = $appointment->payment;

        if (!$payment || $payment->status !== 'paid') {
            return back()->withErrors(['error' => 'Lịch hẹn chưa được thanh toán.']);
        }

        if ($payment->refund_status === 'completed') {
            return back()->withErrors(['error' => 'Giao dịch đã được hoàn tiền trước đó.']);
        }

        // VNPay config
        $tmnCode     = config('services.vnpay.tmn_code');
        $hashSecret  = config('services.vnpay.hash_secret');
        $refundUrl   = config('services.vnpay.refund_url');

        // Lấy dữ liệu giao dịch gốc
        $amount            = intval($payment->amount * 100); // VNPay dùng đơn vị xu
        $txnRef            = $payment->vnp_txn_ref;
        $transactionNo     = $payment->vnp_transaction_no;
        $transactionDate   = \Carbon\Carbon::parse($payment->paid_at)->format('YmdHis');
        $requestId         = Str::random(13);
        $createDate        = now()->format('YmdHis');
        $ipAddr            = request()->ip();

        $data = [
            'vnp_Version'          => '2.1.0',
            'vnp_Command'          => 'refund',
            'vnp_TmnCode'          => $tmnCode,
            'vnp_TransactionType'  => '02', // Hoàn tiền toàn phần
            'vnp_TxnRef'           => $txnRef,
            'vnp_TransactionNo'    => $transactionNo,
            'vnp_Amount'           => $amount,
            'vnp_OrderInfo'        => "Hoàn tiền lịch hẹn #$appointment->id",
            'vnp_TransactionDate'  => $transactionDate,
            'vnp_CreateBy'         => 'system',
            'vnp_CreateDate'       => $createDate,
            'vnp_IpAddr'           => $ipAddr,
            'vnp_RequestId'        => $requestId,
        ];

        // Tạo secure hash
        ksort($data);
        $hashData = urldecode(http_build_query($data));
        $secureHash = hash_hmac('sha512', $hashData, $hashSecret);
        $data['vnp_SecureHash'] = $secureHash;

        \Log::info('🧾 Gọi refund thực tế', ['payment_id' => $payment->id]);
        \Log::debug('🔐 Hash string', ['hash_string' => $hashData]);
        \Log::debug('VNPay Refund input data', $data);

        try {
            // Gửi POST JSON
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($refundUrl, $data);

            \Log::info('📥 VNPay Refund HTTP Status: ' . $response->status());
            \Log::info('📥 VNPay Refund Body: ' . $response->body());

            if ($response->successful()) {
                $payment->update([
                    'status'         => 'refunded',
                    'refund_status'  => 'completed',
                    'refunded_at'    => now(),
                ]);

                try {
                    // Xác định lý do gửi
                    if ($appointment->status === 'cancelled') {
                        $reason = 'Lịch hẹn đã bị huỷ. Chúng tôi xin lỗi nếu có sự bất tiện xảy ra.';
                    } elseif ($appointment->cancel_reason === 'doctor_unavailable') {
                        $reason = 'Bác sĩ xin nghỉ đột xuất. Chúng tôi xin lỗi vì sự bất tiện này.';
                    } else {
                        $reason = 'Hoàn tiền theo chính sách hoặc yêu cầu từ phía quý khách.';
                    }

                    Mail::to($appointment->patient->email)
                        ->send(new RefundSuccessfulMail($appointment, $reason));
                } catch (\Throwable $e) {
                    Log::error('Lỗi gửi mail hoàn tiền', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage()
                    ]);
                }

                return back()->with('success', 'Hoàn tiền thành công.');
            } else {
                $payment->update(['refund_status' => 'failed']);

                \Log::error('❌ VNPay HTTP ERROR', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return back()->withErrors(['error' => 'Hoàn tiền thất bại: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            $payment->update(['refund_status' => 'failed']);

            \Log::error('❌ Exception refund', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền.']);
        }
    }


    protected function performVnpayRefund($payment)
    {
        if (empty($payment->vnp_txn_ref) || empty($payment->vnp_transaction_no)) {
            return ['success' => false, 'message' => 'Không tìm thấy mã giao dịch VNPay.'];
        }

        if (empty($payment->paid_at)) {
            return ['success' => false, 'message' => 'Không tìm thấy thời gian thanh toán.'];
        }

        $vnp_TmnCode     = config('services.vnpay.tmn_code');
        $vnp_HashSecret  = config('services.vnpay.hash_secret');
        $vnp_RefundUrl   = config('services.vnpay.refund_url');

        $vnp_RequestId       = uniqid();
        $vnp_Version         = '2.1.0';
        $vnp_Command         = 'refund';
        $vnp_TxnRef          = $payment->vnp_txn_ref;
        $vnp_Amount          = $payment->amount * 100; // nhân 100 theo yêu cầu của VNPay
        $vnp_TransactionType = '02'; // 02 = Hoàn toàn bộ
        $vnp_TransactionNo   = $payment->vnp_transaction_no;
        $vnp_TransactionDate = Carbon::parse($payment->paid_at)->format('YmdHis');
        $vnp_CreateBy        = auth()->user()->name ?? 'system';
        $vnp_CreateDate      = now()->format('YmdHis');
        $vnp_IpAddr          = request()->ip();

        $inputData = [
            'vnp_RequestId'       => $vnp_RequestId,
            'vnp_Version'         => $vnp_Version,
            'vnp_Command'         => $vnp_Command,
            'vnp_TmnCode'         => $vnp_TmnCode,
            'vnp_TransactionType' => $vnp_TransactionType,
            'vnp_TxnRef'          => $vnp_TxnRef,
            'vnp_Amount'          => $vnp_Amount,
            'vnp_TransactionNo'   => $vnp_TransactionNo,
            'vnp_TransactionDate' => $vnp_TransactionDate,
            'vnp_CreateBy'        => $vnp_CreateBy,
            'vnp_CreateDate'      => $vnp_CreateDate,
            'vnp_IpAddr'          => $vnp_IpAddr,
            'vnp_OrderInfo'       => 'Hoàn tiền lịch hẹn #' . $payment->appointment_id,
        ];

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $inputData['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post($vnp_RefundUrl, $inputData);

            if (!$response->ok()) {
                Log::error('VNPay Refund HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'message' => 'VNPay không phản hồi đúng: HTTP ' . $response->status()];
            }

            $result = $response->json();

            if (!is_array($result)) {
                Log::error('VNPay Refund invalid JSON', ['body' => $response->body()]);
                return ['success' => false, 'message' => 'Phản hồi VNPay không hợp lệ.'];
            }

            Log::info('VNPay Refund response', $result);

            if (($result['vnp_ResponseCode'] ?? null) === '00') {
                $payment->refund_status = 'completed';
                $payment->refunded_at = now();
                $payment->save();

                // Ghi log lịch sử hoàn tiền
                PaymentHistory::create([
                    'payment_id' => $payment->id,
                    'amount' => -1 * $payment->amount,
                    'payment_method' => 'vnpay_refund',
                    'payment_date' => now(),
                ]);

                try {
                    $appointment = $payment->appointment;

                    // Xác định lý do nếu có cột cancellation_reason
                    if ($appointment->status === 'cancelled') {
                        $reason = 'Lịch hẹn đã bị huỷ. Chúng tôi xin lỗi nếu có sự bất tiện xảy ra.';
                    } elseif ($appointment->cancel_reason === 'doctor_unavailable') {
                        $reason = 'Bác sĩ xin nghỉ đột xuất. Chúng tôi xin lỗi vì sự bất tiện này.';
                    } else {
                        $reason = 'Hoàn tiền theo chính sách hoặc yêu cầu từ phía quý khách.';
                    }

                    Mail::to($appointment->patient->email)
                        ->send(new RefundSuccessfulMail($appointment, $reason));
                } catch (\Throwable $e) {
                    Log::error('Lỗi gửi mail hoàn tiền (performVnpayRefund)', [
                        'appointment_id' => $payment->appointment_id,
                        'error' => $e->getMessage(),
                    ]);
                }


                return ['success' => true];
            }

            $message = $result['vnp_Message'] ?? 'Không rõ lỗi';
            return ['success' => false, 'message' => 'Hoàn tiền thất bại: ' . $message];
        } catch (\Throwable $e) {
            Log::error('VNPay Refund exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => 'Có lỗi khi hoàn tiền: ' . $e->getMessage()];
        }
    }

    public function getDoctorsByService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $doctors = $service->doctors()->with('user')->get();
        return response()->json($doctors);
    }

    public function getAvailableTimesByDate(Request $request, Doctor $doctor)
    {
        $date = $request->input('date');
        $serviceId = $request->input('service_id');
        $treatmentPlanId = $request->input('treatment_plan_id');
        $currentAppointmentId = $request->input('current_appointment_id');
        $slotDuration = 30;

        if (!$date) {
            return response()->json(['message' => 'Thiếu ngày khám'], 400);
        }

        // ⏱️ Lấy thời lượng của dịch vụ nếu có
        if ($serviceId) {
            $service = Service::find($serviceId);
            if ($service && $service->duration) {
                $slotDuration = $service->duration;
            }
        }

        // 🔄 Nếu có treatment_plan_id, override doctor
        if ($treatmentPlanId) {
            $plan = TreatmentPlan::find($treatmentPlanId);
            if ($plan && $plan->doctor_id) {
                $doctor = Doctor::find($plan->doctor_id);
            }
        }

        if (!$doctor) {
            return response()->json(['message' => 'Không xác định được bác sĩ'], 400);
        }

        $dayOfWeek = Carbon::parse($date)->format('l');

        // Lấy lịch làm việc (nếu có)
        $workings = WorkingSchedule::with('shift')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'Đã xét duyệt')
            ->where(function ($q) use ($dayOfWeek, $date) {
                $q->where('day_of_week', $dayOfWeek)
                    ->orWhereDate('day', $date);
            })
            ->get();

        $workingPeriods = [];

        if ($workings->isNotEmpty()) {
            foreach ($workings as $w) {
                if ($w->shift) {
                    $workingPeriods[] = [
                        'start' => $w->shift->start_time,
                        'end'   => $w->shift->end_time,
                    ];
                }
            }
        } else {
            // fallback mặc định khi không có lịch
            $workingPeriods = [
                ['start' => '07:00', 'end' => '12:00'],
                ['start' => '13:00', 'end' => '17:00'],
            ];
        }

        // 📆 Lấy các lịch đã đặt trong ngày
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', $date)
            ->whereNotIn('status', ['cancelled'])
            ->when($currentAppointmentId, function ($q) use ($currentAppointmentId) {
                $q->where('id', '<>', $currentAppointmentId);
            })
            ->get()
            ->map(function ($a) use ($slotDuration) {
                return [
                    'start' => Carbon::parse($a->appointment_time),
                    'end'   => $a->end_time
                        ? Carbon::parse($a->end_time)
                        : Carbon::parse($a->appointment_time)->copy()->addMinutes($slotDuration),
                ];
            });

        $availableSlots = [];

        foreach ($workingPeriods as $block) {
            $start = Carbon::parse("$date {$block['start']}");
            $end = Carbon::parse("$date {$block['end']}");

            while ($start->copy()->addMinutes($slotDuration) <= $end) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($slotDuration);

                $conflict = false;
                foreach ($appointments as $appt) {
                    if ($slotStart < $appt['end'] && $slotEnd > $appt['start']) {
                        $conflict = true;
                        break;
                    }
                }

                if ($conflict) {
                    $start->addMinutes(5);
                    continue;
                }

                // Nếu không xung đột, thêm slot này
                $availableSlots[] = $slotStart->format('H:i');
                $start = $slotEnd;
            }
        }

        return response()->json($availableSlots);
    }
}
