<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppointmentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateStatusAppointmentRequest;
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
use App\Models\User;
use App\Models\WorkingSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Lọc theo trạng thái thanh toán (order)
        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'completed') {
                // Chỉ lấy lịch hẹn có ÍT NHẤT 1 payment = paid
                $query->whereHas('payment', fn($q) => $q->where('status', 'paid'));
            } elseif ($request->payment_status === 'unpaid') {
                // Lấy lịch hẹn KHÔNG có bất kỳ payment = paid
                $query->whereDoesntHave('payment', fn($q) => $q->where('status', 'paid'));
            }
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

        if ($conflict['doctor_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Bác sĩ đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        if ($conflict['room_conflict']) {
            return back()->withErrors([
                'appointment_time' => 'Phòng khám đã có lịch hẹn vào thời gian bạn chọn. Vui lòng chọn thời gian khác.'
            ])->withInput();
        }

        $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
            ->whereDate('day', $day)
            ->first();

        if (!$working) {
            $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->where('day_of_week', $dayOfWeek)
                ->first();
        }

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

        // thời gian kết thúc dự kiến
        $appointmentTime = Carbon::parse($request->appointment_time);
        $endTime = $appointmentTime->copy()->addMinutes(30);

        $requestData = $request->only([
            'patient_id',
            'doctor_id',
            'service_id',
            'appointment_time',
            'status',
            'reason'
        ]);

        $requestData['end_time'] = $endTime;

        $appointment = Appointment::create($requestData);

        // Tính giá
        $service = Service::findOrFail($request->service_id);
        $price = $service->price;

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => $request->patient_id,
            'appointment_id' => $appointment->id,
            'total_amount' => $price,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);

        // Gắn dịch vụ vào order_service
        OrderService::create([
            'order_id' => $order->id,
            'service_id' => $service->id,
            'quantity' => 1,
            'price' => $price,
        ]);

        // Tạo payment
        Payment::updateOrCreate([
            'appointment_id' => $appointment->id,
            'amount' => $price,
            'status' => 'unpaid',
        ]);

        // Appointment::create($requestData);

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

        // Kiểm tra lịch làm việc
        $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
            ->whereDate('day', $day)
            ->first();

        if (!$working) {
            $working = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->where('day_of_week', $dayOfWeek)
                ->first();
        }

        if (!$working) {
            $workingDays = WorkingSchedule::where('doctor_id', $request->doctor_id)
                ->pluck('day_of_week')
                ->map(fn($day) => __('days.' . strtolower($day)))
                ->toArray();

            return back()->withErrors([
                'appointment_time' => 'Bác sĩ không làm việc ngày này. Các ngày làm việc: ' . implode(', ', $workingDays) . '.'
            ])->withInput();
        }

        // Kiểm tra giờ hợp lệ
        if ($timeOnly < $working->start_time || $timeOnly >= $working->end_time) {
            return back()->withErrors([
                'appointment_time' => 'Giờ hẹn không nằm trong giờ làm việc: ' . $working->start_time . ' - ' . $working->end_time
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

        // Lưu thông tin cũ
        $oldStatus    = $appointment->status;
        $oldTime      = $appointment->appointment_time;
        $oldDoctorId  = $appointment->doctor_id;
        $oldServiceId = $appointment->service_id;

        $newDoctor = Doctor::with('user')->find($request->doctor_id);
        $newService = Service::find($request->service_id);

        // Tính thời gian kết thúc
        $endTime = Carbon::parse($request->appointment_time)
            ->copy()
            ->addMinutes($newService->duration ?? 30);

        // Chuẩn bị dữ liệu cập nhật
        $updateData = [
            'doctor_id'        => $request->doctor_id,
            'service_id'       => $request->service_id,
            'appointment_time' => $request->appointment_time,
            'end_time'         => $endTime,
            'status'           => $request->status,
            'reason'           => $request->reason,
        ];

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

            DB::commit();
            return redirect()->route('admin.appointments.index')
                ->with('success', 'Cập nhật lịch hẹn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật lịch hẹn.']);
        }
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
        $appointment = Appointment::with(['payment', 'order'])->findOrFail($id);
        $payment = $appointment->payment;
        if (!$payment) {
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount'         => $appointment->service->price,
                'status'         => 'unpaid',
            ]);
        }

        if ($payment->status === 'paid') {
            return back()->with('error', 'Lịch hẹn đã được thanh toán.');
        }

        $payment->update([
            'status'         => 'paid',
            'payment_method' => 'cash',
            'paid_at'        => now(),
        ]);

        PaymentHistory::create([
            'payment_id'     => $payment->id,
            'amount'         => $payment->amount,
            'payment_method' => 'cash',
            'payment_date'   => now(),
        ]);

        if ($appointment->order && $appointment->order->status !== 'completed') {
            $appointment->order->update(['status' => 'completed']);
        }

        return back()->with('success', 'Thanh toán thành công!');
    }
}
