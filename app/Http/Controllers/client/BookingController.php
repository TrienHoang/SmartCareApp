<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\WorkingSchedule;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\Payment; // Assumed Payment model
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Support\Str;
use chillerlan\QRCode\{QRCode, QROptions};
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function show($service_id)
    {
        $service = Service::with(['category', 'department', 'doctors.user', 'doctors.reviews'])
            ->where('id', $service_id)
            ->firstOrFail();

        $relatedServices = Service::where('department_id', $service->department_id)
            ->where('id', '!=', $service->id)
            ->limit(3)
            ->get();
        return view('client.service_detail', compact('service', 'relatedServices'));
    }

    public function prepare(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_option' => 'required|in:random,specific',
            'doctor_id' => 'nullable|required_if:doctor_option,specific|exists:doctors,id',
        ]);

        if ($validated['doctor_option'] === 'specific') {
            $exists = DB::table('doctor_service')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('service_id', $validated['service_id'])
                ->exists();
            if (!$exists) {
                return back()->withErrors(['doctor_id' => 'Bác sĩ không thực hiện dịch vụ này.']);
            }
        }

        $request->session()->put('booking_data', [
            'service_id' => $validated['service_id'],
            'doctor_option' => $validated['doctor_option'],
            'doctor_id' => $validated['doctor_id'] ?? null,
        ]);

        return redirect()->route('booking.create');
    }

    public function create(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        if (!$booking_data) {
            return redirect()->route('client.services')->with('error', 'Vui lòng chọn dịch vụ trước.');
        }

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor = $booking_data['doctor_option'] === 'specific'
            ? Doctor::findOrFail($booking_data['doctor_id'])
            : null;

        return view('client.booking.create', compact('service', 'doctor', 'booking_data'));
    }

    public function getAvailableDates(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'month' => 'required|integer|min:0|max:11',
            'year' => 'required|integer|min:2020',
        ]);

        \Log::info('Validated request for available dates:', $validated);


        $service = Service::findOrFail($validated['service_id']);
        $doctor_id = $validated['doctor_id'];
        $queryMonth = $validated['month'];
        $queryYear = $validated['year'];

        $start_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->startOfDay();
        $end_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->endOfMonth()->endOfDay();
        $search_start_date = $start_of_month->copy()->subWeeks(1);
        $search_end_date = $end_of_month->copy()->addWeeks(1);
        $current_datetime = Carbon::now();

        \Log::info('Time info:', [
            'current_datetime' => $current_datetime->toDateTimeString(),
            'current_timezone' => $current_datetime->timezoneName,
            'search_start_date' => $search_start_date->toDateString(),
            'search_end_date' => $search_end_date->toDateString(),
        ]);


        if ($doctor_id) {
            $doctor_ids = [$doctor_id];
        } else {
            $doctor_ids = Doctor::where('department_id', $service->department_id)
                ->whereIn('id', function ($query) use ($service) {
                    $query->select('doctor_id')
                        ->from('doctor_service')
                        ->where('service_id', $service->id);
                })
                ->leftJoinSub(
                    Appointment::whereBetween('appointment_time', [$search_start_date, $search_end_date])
                        ->where('status', '!=', 'cancelled')
                        ->groupBy('doctor_id')
                        ->select('doctor_id', DB::raw('count(*) as appointment_count')),
                    'appointments',
                    'doctors.id',
                    '=',
                    'appointments.doctor_id'
                )
                ->orderBy('appointment_count', 'asc')
                ->orderByRaw('RAND()')
                ->pluck('doctors.id');
        }

        $doctor_ids = collect($doctor_ids);

        if ($doctor_ids->isEmpty()) {
            return response()->json([]);
        }

        // Lấy lịch nghỉ của bác sĩ
        $leaves = DoctorLeave::whereIn('doctor_id', $doctor_ids)
            ->where('approved', true)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($search_start_date, $search_end_date) {
                $query->whereBetween('start_date', [$search_start_date, $search_end_date])
                    ->orWhereBetween('end_date', [$search_start_date, $search_end_date])
                    ->orWhere(function ($query) use ($search_start_date, $search_end_date) {
                        $query->where('start_date', '<=', $search_start_date)
                            ->where('end_date', '>=', $search_end_date);
                    });
            })
            ->get();

        \Log::info('Doctor leaves found:', ['count' => $leaves->count()]);


        $schedules = WorkingSchedule::whereIn('doctor_id', $doctor_ids)
            ->where('status', 'Đã xét duyệt')
            ->whereBetween('day', [$search_start_date->toDateString(), $search_end_date->toDateString()])
            ->with('shift')
            ->get();

        \Log::info('Schedules found:', ['count' => $schedules->count()]);


        $available_dates = [];
        foreach ($schedules as $schedule) {
            $date = Carbon::parse($schedule->day);

            // Kiểm tra xem ngày có nằm trong lịch nghỉ không
            $is_on_leave = $leaves->contains(function ($leave) use ($date, $schedule) {
                $leave_start = Carbon::parse($leave->start_date);
                $leave_end = Carbon::parse($leave->end_date);
                return $date->between($leave_start, $leave_end) && $leave->doctor_id == $schedule->doctor_id;
            });

            if ($is_on_leave) {
                continue;
            }

            $shift = $schedule->shift;
            if (!$shift) {
                continue;
            }

            try {
                $start = Carbon::parse($shift->start_time);
                $end = Carbon::parse($shift->end_time);
            } catch (\Exception $e) {
                \Log::error("Invalid shift time for schedule ID {$schedule->id}: Start: {$shift->start_time}, End: {$shift->end_time}");
                continue;
            }

            $current = $start->copy();

            while ($current->lessThan($end)) {
                $slot_start = $current->format('H:i');
                $slot_full_datetime = $date->copy()->setTimeFromTimeString($slot_start);

                // Kiểm tra thời gian đặt tối thiểu và thời gian quá khứ
                $hours_diff = $current_datetime->diffInHours($slot_full_datetime, false);
                if ($hours_diff < $service->min_booking_hours || $slot_full_datetime->lt($current_datetime)) {
                    $current->addMinutes($service->duration);
                    continue;
                }

                $booked = Appointment::where('doctor_id', $schedule->doctor_id)
                    ->where('appointment_time', $slot_full_datetime)
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                if (!$booked) {
                    $date_str = $date->format('Y-m-d');
                    if (!in_array($date_str, $available_dates)) {
                        $available_dates[] = $date_str;
                    }
                    break;
                }

                $current->addMinutes($service->duration);
            }
        }

        \Log::info('Available dates:', ['dates' => $available_dates]);

        return response()->json($available_dates);
    }

    public function getSlots(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        \Log::info('Validated request:', $validated);


        $selected_date = Carbon::parse($validated['date'])->startOfDay();
        $service = Service::findOrFail($validated['service_id']);
        $doctor_id = $validated['doctor_id'];
        $current_datetime = Carbon::now();

        \Log::info('Time info:', [
            'current_datetime' => $current_datetime->toDateTimeString(),
            'current_timezone' => $current_datetime->timezoneName,
            'selected_date' => $selected_date->toDateString(),
        ]);

        \Log::info('Service details:', [
            'service_id' => $service->id,
            'duration' => $service->duration,
            'min_booking_hours' => $service->min_booking_hours,
        ]);


        // Lấy danh sách bác sĩ và số lịch hẹn (nếu random)
        $doctor_appointments = [];
        if (!$doctor_id) {
            $doctor_appointments = Appointment::whereBetween('appointment_time', [now(), now()->addDays(40)])
                ->where('status', '!=', 'cancelled')
                ->groupBy('doctor_id')
                ->select('doctor_id', \DB::raw('count(*) as appointment_count'))
                ->pluck('appointment_count', 'doctor_id')
                ->toArray();
        }

        $schedules = WorkingSchedule::whereHas('doctor', function ($query) use ($service, $doctor_id) {
            $query->where('department_id', $service->department_id);
            if ($doctor_id) {
                $query->where('id', $doctor_id);
            } else {
                $query->whereIn('id', function ($subQuery) use ($service) {
                    $subQuery->select('doctor_id')
                        ->from('doctor_service')
                        ->where('service_id', $service->id);
                });
            }
        })
            ->where('day', $selected_date->toDateString())
            ->where('status', 'Đã xét duyệt')
            ->with('shift')
            ->get();

        // Lấy lịch nghỉ của bác sĩ cho ngày được chọn
        $leaves = DoctorLeave::whereIn('doctor_id', $schedules->pluck('doctor_id'))
            ->where('approved', true)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($selected_date) {
                $query->whereBetween('start_date', [$selected_date, $selected_date->copy()->endOfDay()])
                    ->orWhereBetween('end_date', [$selected_date, $selected_date->copy()->endOfDay()])
                    ->orWhere(function ($query) use ($selected_date) {
                        $query->where('start_date', '<=', $selected_date)
                            ->where('end_date', '>=', $selected_date);
                    });
            })
            ->get();

        $available_slots = [];

        foreach ($schedules as $schedule) {
            $shift = $schedule->shift;
            if (!$shift) {
                continue;
            }

            try {
                $start = Carbon::parse($shift->start_time);
                $end = Carbon::parse($shift->end_time);
            } catch (\Exception $e) {
                \Log::error("Invalid shift time for schedule ID {$schedule->id}: Start: {$shift->start_time}, End: {$shift->end_time}");
                continue;
            }

            $current_slot_time = $start->copy();

            \Log::info('Processing schedule:', [
                'schedule_id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'start_time' => $start->toTimeString(),
                'end_time' => $end->toTimeString(),
            ]);

            while ($current_slot_time->lessThan($end)) {
                $slot_end_time = $current_slot_time->copy()->addMinutes($service->duration);

                // Kiểm tra slot kết thúc không vượt quá end_time
                if ($slot_end_time->greaterThan($end)) {
                    break;
                }

                $slot_full_datetime = $selected_date->copy()->setTimeFrom($current_slot_time);

                \Log::info('Slot details:', [
                    'slot_time' => $slot_full_datetime->toDateTimeString(),
                    'slot_timezone' => $slot_full_datetime->timezoneName,
                ]);


                // Kiểm tra lịch nghỉ
                $is_on_leave = $leaves->contains(function ($leave) use ($slot_full_datetime) {
                    $leave_start = Carbon::parse($leave->start_date);
                    $leave_end = Carbon::parse($leave->end_date);
                    return $slot_full_datetime->between($leave_start, $leave_end) && $leave->doctor_id == $schedule->doctor_id;
                });

                if ($is_on_leave) {
                    $current_slot_time->addMinutes($service->duration);
                    continue;
                }

                // Kiểm tra thời gian đặt tối thiểu và thời gian quá khứ
                $hours_diff = $current_datetime->diffInHours($slot_full_datetime, false);
                if ($hours_diff < $service->min_booking_hours || $slot_full_datetime->lt($current_datetime)) {
                    $current_slot_time->addMinutes($service->duration);
                    continue;
                }

                // Kiểm tra slot đã được đặt
                $booked = Appointment::where('doctor_id', $schedule->doctor_id)
                    ->where('appointment_time', $slot_full_datetime)
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                if ($booked) {
                    $current_slot_time->addMinutes($service->duration);
                    continue;
                }

                $slot_key = $current_slot_time->format('H:i') . '-' . $slot_end_time->format('H:i');

                // Kiểm tra slot đã tồn tại
                if (!isset($available_slots[$slot_key])) {
                    $available_slots[$slot_key] = [
                        'start' => $current_slot_time->format('H:i'),
                        'end' => $slot_end_time->format('H:i'),
                        'doctor_id' => $schedule->doctor_id,
                        'appointment_count' => $doctor_id ? 0 : ($doctor_appointments[$schedule->doctor_id] ?? 0),
                    ];
                } else {
                    // Nếu slot đã tồn tại và là random, ưu tiên bác sĩ có ít lịch hẹn hơn
                    if (!$doctor_id) {
                        $current_count = $doctor_appointments[$schedule->doctor_id] ?? 0;
                        $existing_count = $available_slots[$slot_key]['appointment_count'];
                        if ($current_count < $existing_count) {
                            $available_slots[$slot_key]['doctor_id'] = $schedule->doctor_id;
                            $available_slots[$slot_key]['appointment_count'] = $current_count;
                        }
                    }
                }

                $current_slot_time->addMinutes($service->duration);
            }
        }

        // Chuyển mảng kết quả về dạng danh sách
        $result = array_values($available_slots);
        usort($result, function ($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        // Loại bỏ appointment_count khỏi kết quả
        $result = array_map(function ($slot) {
            unset($slot['appointment_count']);
            return $slot;
        }, $result);

        \Log::info('Final result:', ['slots' => $result]);


        return response()->json($result);
    }

    public function store(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        if (!$booking_data) {
            return redirect()->route('client.services')->with('error', 'Vui lòng chọn dịch vụ trước.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(40)->toDateString(),
            'slot_start' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:255',
        ]);

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor_id = $booking_data['doctor_id'] ?? $request->input('doctor_id');

        $appointment_time = Carbon::parse($validated['date'])->setTimeFromTimeString($validated['slot_start']);

        $booked = Appointment::where('doctor_id', $doctor_id)
            ->where('appointment_time', $appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($booked) {
            return back()->withErrors(['slot_start' => 'Khung giờ này đã được đặt.']);
        }

        $request->session()->put('booking_confirm', [
            'service_id' => $booking_data['service_id'],
            'doctor_id' => $doctor_id,
            'appointment_time' => $appointment_time,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('booking.confirm');
    }

    public function confirm(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        $booking_confirm = $request->session()->get('booking_confirm');

        if (!$booking_data || !$booking_confirm) {
            return redirect()->route('client.services')->with('error', 'Dữ liệu đặt lịch không hợp lệ.');
        }

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor = Doctor::with('user')->findOrFail($booking_confirm['doctor_id']);
        $appointment_time = Carbon::parse($booking_confirm['appointment_time']);
        $user = auth()->user();

        return view('client.booking.confirm', compact('service', 'doctor', 'appointment_time', 'user', 'booking_confirm'));
    }

    public function save(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        $booking_confirm = $request->session()->get('booking_confirm');

        if (!$booking_data || !$booking_confirm) {
            return redirect()->route('client.services')->with('error', 'Dữ liệu đặt lịch không hợp lệ hoặc đã hết hạn. Vui lòng bắt đầu lại.');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_time' => 'required|date_format:Y-m-d H:i:s',
            'reason' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'nullable|string|in:Nam,Nữ,Khác',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
        ], [
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'full_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.'
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $appointment_time = Carbon::parse($validated['appointment_time']);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            if ($user) {
                $user->full_name = $validated['full_name'];
                $user->phone = $validated['phone'];
                $user->gender = $validated['gender'];
                $user->date_of_birth = $validated['date_of_birth'];
                $user->address = $validated['address'];
                $user->save();
            } else {
                throw new \Exception('Người dùng chưa đăng nhập hoặc không tìm thấy.');
            }

            $booked = Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_time', $appointment_time)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($booked) {
                DB::rollBack();
                return redirect()->route('booking.showService', $booking_confirm['service_id'])->with('error', 'Khung giờ này đã có người khác đặt hoặc không còn khả dụng.');
            }

            // Tạo một mã QR duy nhất (UUID) cho cuộc hẹn
            $qrCodeData = (string) Str::uuid();

            $appointment = Appointment::create([
                'patient_id' => $user->id,
                'doctor_id' => $validated['doctor_id'],
                'service_id' => $validated['service_id'],
                'appointment_time' => $appointment_time,
                'end_time' => $appointment_time->copy()->addMinutes($service->duration),
                'status' => 'pending',
                'reason' => $validated['reason'] ?? null,
                'created_by' => $user->id,
                'qr_code' => $qrCodeData,
            ]);

            DB::commit();

            // QUAN TRỌNG: Xóa session sau khi commit thành công
            $request->session()->forget(['booking_data', 'booking_confirm']);

            // Redirect với flash session data
            $vnpayUrl = $this->initiateVNPayPayment($appointment, $service);
            return redirect($vnpayUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Booking save failed: " . $e->getMessage() . " - User ID: " . ($user ? $user->id : 'N/A'));
            return back()->with('error', 'Có lỗi xảy ra khi hoàn tất đặt lịch: ' . $e->getMessage());
        }
    }

    // New method to initiate VNPay payment
    protected function initiateVNPayPayment($appointment, $service)
    {
        $vnp_TmnCode = env('VNPAY_TMNCODE', '21XQVOEK');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'HSDJ58ZKRZ24ZSYULQGO0ISQ4205JON1');
        $vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_ReturnUrl = env('VNPAY_RETURN_URL', 'http://localhost:8000/payment/return');

        $vnp_TxnRef = $appointment->id . '_' . time();
        $vnp_Amount = $service->price * 100; // VNPay requires amount * 100 (VND)
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = "Thanh toán lịch hẹn #{$appointment->id} cho dịch vụ {$service->name}";
        $vnp_OrderType = 'billpayment';
        $vnp_CreateDate = now()->format('YmdHis');
        $vnp_ExpireDate = now()->addMinutes(15)->format('YmdHis');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        // Create payment record
        Payment::create([
            'appointment_id' => $appointment->id,
            'promotion_id' => null,
            'amount' => $service->price,
            'payment_method' => 'vnpay',
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return $vnp_Url;
    }

    // New method to handle VNPay return URL
    public function paymentReturn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'HSDJ58ZKRZ24ZSYULQGO0ISQ4205JON1');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $txnRef = $request->vnp_TxnRef;
        $responseCode = $request->vnp_ResponseCode;
        $appointmentId = explode('_', $txnRef)[0];
        $payment = Payment::where('appointment_id', $appointmentId)->first();
        $appointment = Appointment::find($appointmentId);

        if (!$payment || !$appointment) {
            return redirect()->route('client.services')->with('error', 'Giao dịch không hợp lệ hoặc không tìm thấy.');
        }

        if ($secureHash === $vnp_SecureHash && $responseCode === '00') {
            DB::beginTransaction();
            try {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => Carbon::createFromFormat('YmdHis', $request->vnp_PayDate),
                ]);

                $appointment->update([
                    'status' => 'confirmed',
                ]);

                DB::commit();
                return redirect()->route('booking.success')
                    ->with('success', 'Thanh toán thành công! Lịch hẹn của bạn đã được xác nhận.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Payment confirmation failed: " . $e->getMessage());
                return redirect()->route('client.services')
                    ->with('error', 'Có lỗi xảy ra khi xác nhận thanh toán.');
            }
        } else {
            $payment->update([
                'status' => 'unpaid',
            ]);

            $appointment->update([
                'status' => 'cancelled',
            ]);

            return redirect()->route('booking.showService', $appointment->service_id)
                ->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
        }
    }

    // New method to handle VNPay IPN
    public function paymentIpn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'HSDJ58ZKRZ24ZSYULQGO0ISQ4205JON1');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $txnRef = $request->vnp_TxnRef;
        $responseCode = $request->vnp_ResponseCode;
        $appointmentId = explode('_', $txnRef)[0];
        $payment = Payment::where('appointment_id', $appointmentId)->first();
        $appointment = Appointment::find($appointmentId);

        if (!$payment || !$appointment) {
            return response()->json(['RspCode' => '01', 'Message' => 'Transaction not found']);
        }

        if ($secureHash === $vnp_SecureHash && $responseCode === '00') {
            if ($payment->status !== 'paid') {
                DB::beginTransaction();
                try {
                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => Carbon::createFromFormat('YmdHis', $request->vnp_PayDate),
                    ]);

                    $appointment->update([
                        'status' => 'confirmed',
                    ]);

                    DB::commit();
                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("IPN processing failed: " . $e->getMessage());
                    return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
                }
            } else {
                return response()->json(['RspCode' => '02', 'Message' => 'Transaction already confirmed']);
            }
        } else {
            if ($payment->status !== 'unpaid') {
                $payment->update([
                    'status' => 'unpaid',
                ]);

                $appointment->update([
                    'status' => 'cancelled',
                ]);
            }
            return response()->json(['RspCode' => '01', 'Message' => 'Transaction failed']);
        }
    }

    // New method for success page
    public function success()
    {
        return view('client.booking.success');
    }
}
