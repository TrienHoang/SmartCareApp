<?php

namespace App\Http\Controllers\reception;

use App\Helpers\AppointmentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
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

    public function store(StoreAppointmentRequest $request)
    {
        $appointmentDate = Carbon::parse($request->appointment_time);
        $dayOfWeek = $appointmentDate->format('l');
        $timeOnly = $appointmentDate->format('H:i');
        $day = $appointmentDate->format('Y-m-d');

        $doctor = Doctor::with(['department', 'user'])->findOrFail($request->doctor_id);
        $service  = Service::with('department')->findOrFail($request->service_id);

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
            if (!in_array($dayOfWeek, ['Sunday'])) {
                $working = new \stdClass();
                $working->start_time = '08:00';
                $working->end_time = '17:00';
            } else {
                return back()->withErrors([
                    'doctor_id' => 'Bác sĩ không làm việc vào Chủ nhật. Vui lòng chọn Thứ 2 - Thứ 7.'
                ])->withInput();
            }
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
                'doctor_id' => 'Bác sĩ đang trong thời gian nghỉ phép vào ngày bạn chọn. Vui lòng chọn ngày khác.'
            ])->withInput();
        }

        $appointmentTime = Carbon::parse($request->appointment_time);
        $duration = $service->duration;
        $endTime = $appointmentTime->copy()->addMinutes($duration);

        $patientConflict = Appointment::where('patient_id', $request->patient_id)
            ->where(function ($q) use ($appointmentTime, $endTime) {
                $q->where('appointment_time', '<', $endTime)
                    ->where('end_time', '>', $appointmentTime);
            })
            ->exists();

        if ($patientConflict) {
            return back()->withErrors([
                'appointment_time' => 'Bệnh nhân đã có lịch hẹn khác bị trùng thời gian này!'
            ])->withInput();
        }

        $requestData = $request->only([
            'patient_id',
            'doctor_id',
            'service_id',
            'appointment_time',
            'status',
            'reason',
            'treatment_plan_id',
        ]);

        $requestData['end_time'] = $endTime;

        $appointment = Appointment::create($requestData);

        $price = $service->price;

        $order = Order::create([
            'user_id' => $request->patient_id,
            'appointment_id' => $appointment->id,
            'total_amount' => $price,
            'status' => 'completed',
            'ordered_at' => now(),
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
            'payment_method' => 'cash',
            'paid_at' => now(),
        ]);

        PaymentHistory::create([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'payment_method' => 'cash',
            'payment_date' => now(),
        ]);

        return redirect()->route('receptionist.appointments.index')->with('success', 'Tạo lịch hẹn thành công');
    }
}
