<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Promotion;
use App\Models\PromotionUserUsage;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\WorkingSchedule;
use App\Models\Appointment;
use App\Models\DoctorLeave;
use App\Models\Payment; // Assumed Payment model
use App\Models\PaymentHistory;
use App\Models\Wallet;
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
    public function show($service_id, Request $request)
    {
        $request->session()->forget([
            'selected_promotion_code',
            'selected_promotion_id',
            'selected_promotion_discount',
            'applied_promotion_code',
            'temp_booking_data'
        ]);
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

        $service = Service::findOrFail($validated['service_id']);
        $doctor_id = $validated['doctor_id'];
        $queryMonth = $validated['month'];
        $queryYear = $validated['year'];

        $start_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->startOfDay();
        $end_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->endOfMonth()->endOfDay();
        $search_start_date = $start_of_month->copy()->subWeeks(1);
        $search_end_date = $end_of_month->copy()->addWeeks(1);
        $current_datetime = Carbon::now();

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

        $schedules = WorkingSchedule::whereIn('doctor_id', $doctor_ids)
            ->where('status', 'Đã xét duyệt')
            ->whereBetween('day', [$search_start_date->toDateString(), $search_end_date->toDateString()])
            ->with('shift')
            ->get();

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

        return response()->json($available_dates);
    }

    public function getSlots(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $selected_date = Carbon::parse($validated['date'])->startOfDay();
        $service = Service::findOrFail($validated['service_id']);
        $doctor_id = $validated['doctor_id'];
        $current_datetime = Carbon::now();

        // Lấy danh sách bác sĩ và số lịch hẹn (nếu random)
        $doctor_appointments = [];
        if (!$doctor_id) {
            $doctor_appointments = Appointment::whereBetween('appointment_time', [now(), now()->addDays(7)])
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
            while ($current_slot_time->lessThan($end)) {
                $slot_end_time = $current_slot_time->copy()->addMinutes($service->duration);

                // Kiểm tra slot kết thúc không vượt quá end_time
                if ($slot_end_time->greaterThan($end)) {
                    break;
                }

                $slot_full_datetime = $selected_date->copy()->setTimeFrom($current_slot_time);

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

        return response()->json($result);
    }

    public function store(Request $request)
    {
        session()->forget('selected_promotions');

        $booking_data = $request->session()->get('booking_data');
        if (!$booking_data) {
            return redirect()->route('client.services')->with('error', 'Vui lòng chọn dịch vụ trước.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(7)->toDateString(),
            'slot_start' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:255',
        ]);

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor_id = $booking_data['doctor_id'] ?? $request->input('doctor_id');

        $appointment_time = Carbon::parse($validated['date'])->setTimeFromTimeString($validated['slot_start']);

        // Kiểm tra trùng lịch chung (loại trừ 'cancelled' và 'pending')
        $booked = Appointment::where('doctor_id', $doctor_id)
            ->where('appointment_time', $appointment_time)
            ->whereNotIn('status', ['cancelled', 'pending'])
            ->exists();

        if ($booked) {
            return back()->withErrors(['slot_start' => 'Khung giờ này đã được đặt.']);
        }

        // Kiểm tra trùng lịch của bệnh nhân (bao gồm 'pending' để tránh trùng lặp với chính mình)
        $bookedByPatient = Appointment::where('patient_id', auth()->id())
            ->where('appointment_time', $appointment_time)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($bookedByPatient) {
            return back()->withErrors(['slot_start' => 'Bạn đã có lịch hẹn vào thời gian này, vui lòng chọn giờ khác.']);
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
        $promotionId = session('selected_promotion_id');
        $discountPercentage = 0;

        if ($promotionId) {
            $promotion = Promotion::find($promotionId);

            // Nếu promotion không tồn tại hoặc đã hết hạn -> xoá session
            if (
                !$promotion ||
                $promotion->valid_until < now() ||
                $promotion->valid_from > now() ||
                PromotionUserUsage::where('user_id', $user->id)
                ->where('promotion_id', $promotionId)
                ->exists()
            ) {
                $request->session()->forget([
                    'selected_promotion_code',
                    'selected_promotion_id',
                    'selected_promotion_discount'
                ]);
            } else {
                $discountPercentage = $promotion->discount_percentage;
            }
        }
        // ✅ Tính giá và khuyến mãi
        $originalPrice = $service->price;
        $discountPercentage = session('selected_promotion_discount', 0); // Ví dụ: 10 (%)
        $discountAmount = ($originalPrice * $discountPercentage) / 100;

        $finalPrice = max(0, $originalPrice - $discountAmount); // để tránh âm

        return view('client.booking.confirm', compact(
            'service',
            'doctor',
            'appointment_time',
            'user',
            'booking_confirm',
            'originalPrice',
            'discountAmount',
            'finalPrice'
        ));
    }

    public function save(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        $booking_confirm = $request->session()->get('booking_confirm');

        if (!$booking_data || !$booking_confirm) {
            return redirect()->route('client.services')->with('error', 'Dữ liệu đặt lịch không hợp lệ hoặc đã hết hạn. Vui lòng bắt đầu lại.');
        }

        $validated = $request->validate([
            'service_id'        => 'required|exists:services,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'appointment_time'  => 'required|date_format:Y-m-d H:i:s',
            'reason'            => 'nullable|string|max:255',
            'full_name'         => 'required|string|max:255',
            'phone'             => ['required', 'regex:/^(0[0-9]{9})$/'],
            'gender'            => 'nullable|string|in:Nam,Nữ,Khác',
            'date_of_birth'     => 'nullable|date|before_or_equal:today',
            'address'           => 'nullable|string|max:500',
            'promotion_code'    => 'nullable|string|exists:promotions,code',
            'payment_method'    => 'required|in:vnpay,wallet',
        ], [
            'service_id.required'       => 'Vui lòng chọn dịch vụ.',
            'service_id.exists'         => 'Dịch vụ không tồn tại.',
            'doctor_id.required'        => 'Vui lòng chọn bác sĩ.',
            'doctor_id.exists'          => 'Bác sĩ không tồn tại.',
            'appointment_time.required' => 'Vui lòng chọn thời gian đặt lịch.',
            'appointment_time.date_format' => 'Thời gian đặt lịch không đúng định dạng.',
            'full_name.required'        => 'Họ và tên là bắt buộc.',
            'full_name.max'             => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.required'            => 'Số điện thoại là bắt buộc.',
            'phone.regex'               => 'Số điện thoại không đúng định dạng (VD: 0987654321).',
            'gender.in'                 => 'Giới tính không hợp lệ.',
            'date_of_birth.date'        => 'Ngày sinh không hợp lệ.',
            'date_of_birth.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
            'address.max'               => 'Địa chỉ không được vượt quá 500 ký tự.',
            'promotion_code.exists'     => 'Mã khuyến mãi không tồn tại.',
            'payment_method.required'   => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in'         => 'Phương thức thanh toán không hợp lệ.',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $appointment_time = Carbon::parse($validated['appointment_time']);
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('Người dùng chưa đăng nhập hoặc không tìm thấy.');
        }

        // Cập nhật thông tin user trước
        $user->update([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
        ]);

        // Kiểm tra slot có available không
        $booked = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_time', $appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($booked) {
            throw new \Exception('Khung giờ này đã có người khác đặt hoặc không còn khả dụng.');
        }

        // Tính toán discount và final price
        $discountAmount = 0;
        $promotion = null;

        $promotionCode = session('selected_promotion_code') ?? $validated['promotion_code'] ?? null;

        if (!empty($promotionCode)) {
            $promotion = Promotion::where('code', $promotionCode)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->whereDoesntHave('usages', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();

            if ($promotion) {
                $discountAmount = round($service->price * ($promotion->discount_percentage / 100));
                $discountAmount = min($discountAmount, $service->price);
                $request->session()->put('applied_promotion_code', $promotionCode);
            }
        }

        $finalPrice = max(0, $service->price - $discountAmount);
        $paymentMethod = $request->input('payment_method');
        if ($paymentMethod !== 'wallet' && $finalPrice > 0 && $finalPrice < 5000) {
            return redirect()->route('booking.confirm')
                ->with('error', 'Số tiền thanh toán sau khi áp mã phải tối thiểu 5.000đ đối với phương thức này.');
        }
        // Lưu tạm dữ liệu booking vào session để sử dụng sau khi thanh toán thành công
        $tempBookingData = [
            'patient_id' => $user->id,
            'doctor_id' => $validated['doctor_id'],
            'service_id' => $validated['service_id'],
            'appointment_time' => $appointment_time->format('Y-m-d H:i:s'),
            'end_time' => $appointment_time->copy()->addMinutes($service->duration)->format('Y-m-d H:i:s'),
            'reason' => $validated['reason'] ?? null,
            'created_by' => $user->id,
            'qr_code' => (string) Str::uuid(),
            'promotion_id' => $promotion?->id ?? null,
            'final_price' => $finalPrice,
        ];
        $request->session()->put('temp_booking_data', $tempBookingData);

        DB::beginTransaction();

        try {
            // Kiểm tra nếu finalPrice == 0, xử lý như thanh toán miễn phí
            if ($finalPrice == 0) {
                // Tạo appointment
                $appointment = Appointment::create([
                    'patient_id' => $tempBookingData['patient_id'],
                    'doctor_id' => $tempBookingData['doctor_id'],
                    'service_id' => $tempBookingData['service_id'],
                    'appointment_time' => $appointment_time,
                    'end_time' => Carbon::parse($tempBookingData['end_time']),
                    'status' => 'pending',
                    'reason' => $tempBookingData['reason'],
                    'created_by' => $tempBookingData['created_by'],
                    'qr_code' => $tempBookingData['qr_code'],
                ]);

                // Tạo payment với amount 0, status paid, method 'voucher' hoặc 'free'
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'promotion_id' => $tempBookingData['promotion_id'],
                    'amount' => 0,
                    'payment_method' => 'voucher', // hoặc 'free'
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Tạo thêm payment_history để lưu lịch sử thanh toán
                $paymentHistory = PaymentHistory::create([
                    'payment_id'     => $payment->id,
                    'amount'         => $finalPrice,
                    'payment_method' => 'voucher',
                    'payment_date'   => now(),
                ]);

                $order = Order::create([
                    'user_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'payment_id' => $payment->id,
                    'total_amount' => 0,
                    'status' => 'paid',
                    'ordered_at' => now(),
                ]);

                OrderService::create([
                    'order_id' => $order->id,
                    'service_id' => $appointment->service_id,
                    'quantity' => 1,
                    'price' => $appointment->service->price,
                ]);

                if ($tempBookingData['promotion_id']) {
                    PromotionUserUsage::create([
                        'user_id' => $appointment->patient_id,
                        'promotion_id' => $tempBookingData['promotion_id'],
                        'used_at' => now(),
                        'appointment_id' => $appointment->id,
                    ]);
                }

                // Xóa session để tránh reuse
                $request->session()->forget([
                    'selected_promotion_code',
                    'selected_promotion_id',
                    'selected_promotion_discount',
                    'applied_promotion_code'
                ]);

                DB::commit();
                $request->session()->forget(['booking_data', 'booking_confirm', 'temp_booking_data']);
                return redirect()->route('booking.success')
                    ->with('success', 'Đặt lịch thành công');
            }

            if ($validated['payment_method'] === 'wallet') {
                // Xử lý wallet ngay và tạo appointment nếu thành công
                $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

                if ($wallet->balance < $finalPrice) {
                    throw new \Exception('Số dư ví không đủ để thanh toán.');
                }

                $wallet->balance -= $finalPrice;
                $wallet->save();

                $wallet->transactions()->create([
                    'type' => 'payment',
                    'amount' => $finalPrice,
                    'description' => 'Thanh toán lịch hẹn cho dịch vụ ' . $service->name,
                    'status' => 'Hoàn thành',
                ]);

                // Tạo appointment sau khi thanh toán thành công
                $appointment = Appointment::create([
                    'patient_id' => $tempBookingData['patient_id'],
                    'doctor_id' => $tempBookingData['doctor_id'],
                    'service_id' => $tempBookingData['service_id'],
                    'appointment_time' => $appointment_time,
                    'end_time' => Carbon::parse($tempBookingData['end_time']),
                    'status' => 'pending',
                    'reason' => $tempBookingData['reason'],
                    'created_by' => $tempBookingData['created_by'],
                    'qr_code' => $tempBookingData['qr_code'],
                ]);

                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'promotion_id' => $tempBookingData['promotion_id'],
                    'amount' => $finalPrice,
                    'payment_method' => 'wallet',
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $order = Order::create([
                    'user_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'payment_id' => $payment->id,
                    'total_amount' => $finalPrice,
                    'status' => 'paid',
                    'ordered_at' => now(),
                ]);

                OrderService::create([
                    'order_id' => $order->id,
                    'service_id' => $appointment->service_id,
                    'quantity' => 1,
                    'price' => $appointment->service->price,
                ]);

                if ($tempBookingData['promotion_id']) {
                    PromotionUserUsage::create([
                        'user_id' => $appointment->patient_id,
                        'promotion_id' => $tempBookingData['promotion_id'],
                        'used_at' => now(),
                        'appointment_id' => $appointment->id,
                    ]);
                }

                // Xóa session để tránh reuse
                $request->session()->forget([
                    'selected_promotion_code',
                    'selected_promotion_id',
                    'selected_promotion_discount',
                    'applied_promotion_code'
                ]);

                DB::commit();
                $request->session()->forget(['booking_data', 'booking_confirm', 'temp_booking_data']);
                return redirect()->route('booking.success')
                    ->with('success', 'Thanh toán bằng ví thành công! Lịch hẹn của bạn đã được ghi nhận.');
            } else {
                // Kiểm tra giới hạn minimum cho VNPay (giả sử 5000 VND)
                if ($finalPrice < 5000) {
                    throw new \Exception('Số tiền thanh toán phải lớn hơn hoặc bằng 5000 VND để sử dụng VNPay.');
                }

                // Với VNPay, khởi tạo payment trước với appointment_id null
                $payment = Payment::create([
                    'appointment_id' => null, // Sẽ cập nhật sau
                    'promotion_id' => $promotion?->id,
                    'amount' => round($finalPrice),
                    'payment_method' => 'vnpay',
                    'status' => 'pending',
                    'paid_at' => null,
                ]);

                $vnpayUrl = $this->initiateVNPayPayment($payment, $service, $finalPrice, $promotion);
                DB::commit();
                $request->session()->forget(['booking_data', 'booking_confirm']);
                return redirect($vnpayUrl);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Booking save failed: " . $e->getMessage());
            $request->session()->forget('temp_booking_data');
            return redirect()->route('booking.showService', $booking_confirm['service_id'])
                ->with('error', $e->getMessage());
        }
    }

    // New method to initiate VNPay payment
    protected function initiateVNPayPayment($payment, $service, $finalPrice, $promotion = null)
    {
        $vnp_TmnCode = env('VNPAY_TMNCODE', '21XQVOEK');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'HSDJ58ZKRZ24ZSYULQGO0ISQ4205JON1');
        $vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_ReturnUrl = env('VNPAY_RETURN_URL', 'http://localhost:8000/payment/return');

        $vnp_TxnRef = $payment->id . '_' . time();
        $vnp_Amount = round($finalPrice) * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = "Thanh toán cho dịch vụ {$service->name}";
        $vnp_OrderType = 'billpayment';
        $vnp_CreateDate = now()->format('YmdHis');
        $vnp_ExpireDate = now()->addMinutes(5)->format('YmdHis');

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

        ksort($inputData);
        $query = http_build_query($inputData);
        $vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        $payment->update([
            'vnp_txn_ref' => $vnp_TxnRef,
        ]);

        return $vnp_Url;
    }

    // New method to handle VNPay return URL
    public function paymentReturn(Request $request)
    {

        $this->cleanExpiredPayments(); // Dọn dẹp trước khi xử lý

        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $txnRef = $request->vnp_TxnRef;
        $responseCode = $request->vnp_ResponseCode;
        $paymentId = explode('_', $txnRef)[0];

        $payment = Payment::where('id', $paymentId)->first();

        if (!$payment) {
            return redirect()->route('client.services')
                ->with('error', 'Giao dịch không hợp lệ hoặc không tìm thấy.');
        }

        // Kiểm tra checksum trước
        if ($secureHash === $vnp_SecureHash) {
            if ($responseCode === '00') {
                // Thanh toán thành công
                if ($payment->status !== 'paid') {
                    DB::beginTransaction();
                    try {
                        $payDate = Carbon::createFromFormat('YmdHis', $request->vnp_PayDate);

                        $payment->update([
                            'status'              => 'paid',
                            'paid_at'             => $payDate,
                            'vnp_txn_ref'         => $request->vnp_TxnRef,
                            'vnp_transaction_no'  => $request->vnp_TransactionNo,
                            'vnp_response_code'   => $request->vnp_ResponseCode,
                        ]);

                        $payment->note = json_encode([
                            'transaction_date' => $request->vnp_PayDate,
                        ]);
                        $payment->save();

                        PaymentHistory::create([
                            'payment_id'     => $payment->id,
                            'amount'         => $request->vnp_Amount / 100,
                            'payment_method' => 'vnpay',
                            'payment_date'   => $payDate,
                        ]);

                        // Lấy temp_booking_data từ session
                        $tempBookingData = $request->session()->get('temp_booking_data');

                        if (!$tempBookingData) {
                            throw new \Exception('Dữ liệu đặt lịch tạm thời không tồn tại.');
                        }

                        // Kiểm tra lại slot trước khi tạo (để tránh race condition)
                        $appointment_time = Carbon::parse($tempBookingData['appointment_time']);
                        $booked = Appointment::where('doctor_id', $tempBookingData['doctor_id'])
                            ->where('appointment_time', $appointment_time)
                            ->where('status', '!=', 'cancelled')
                            ->exists();

                        if ($booked) {
                            throw new \Exception('Khung giờ này đã bị đặt bởi người khác trong lúc bạn thanh toán. Vui lòng chọn khung giờ khác.');
                        }

                        // Tạo appointment sau khi thanh toán thành công
                        $appointment = Appointment::create([
                            'patient_id' => $tempBookingData['patient_id'],
                            'doctor_id' => $tempBookingData['doctor_id'],
                            'service_id' => $tempBookingData['service_id'],
                            'appointment_time' => $appointment_time,
                            'end_time' => Carbon::parse($tempBookingData['end_time']),
                            'status' => 'pending',
                            'reason' => $tempBookingData['reason'],
                            'created_by' => $tempBookingData['created_by'],
                            'qr_code' => $tempBookingData['qr_code'],
                        ]);

                        // Cập nhật appointment_id vào payment
                        $payment->update(['appointment_id' => $appointment->id]);

                        $order = Order::create([
                            'user_id'        => $appointment->patient_id,
                            'appointment_id' => $appointment->id,
                            'payment_id'     => $payment->id,
                            'total_amount'   => $request->vnp_Amount / 100,
                            'status'         => 'paid',
                            'ordered_at'     => now(),
                        ]);

                        OrderService::create([
                            'order_id'   => $order->id,
                            'service_id' => $appointment->service_id,
                            'quantity'   => 1,
                            'price'      => $appointment->service->price ?? 0,
                        ]);

                        if ($tempBookingData['promotion_id']) {
                            PromotionUserUsage::create([
                                'user_id'       => $appointment->patient_id,
                                'promotion_id'  => $tempBookingData['promotion_id'],
                                'used_at'       => now(),
                                'appointment_id' => $appointment->id,
                            ]);
                        }

                        // Xóa session để tránh reuse
                        $request->session()->forget([
                            'selected_promotion_code',
                            'selected_promotion_id',
                            'selected_promotion_discount',
                            'applied_promotion_code'
                        ]);

                        $request->session()->forget('temp_booking_data');
                        DB::commit();
                        return redirect()->route('booking.success')
                            ->with('success', 'Thanh toán thành công! Lịch hẹn của bạn đã được ghi nhận.');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Log::error("Payment confirmation failed: " . $e->getMessage());
                        $request->session()->forget('temp_booking_data');
                        return redirect()->route('client.services')
                            ->with('error', $e->getMessage());
                    }
                } else {
                    // Đã thanh toán rồi thì chuyển về trang thành công
                    return redirect()->route('booking.success')
                        ->with('success', 'Giao dịch đã được xác nhận trước đó.');
                }
            } elseif ($responseCode === '24') {
                // Người dùng hủy thanh toán
                DB::beginTransaction();
                try {
                    $payment->delete();
                    DB::commit();
                    $request->session()->forget('temp_booking_data');
                    return redirect()->route('client.services')
                        ->with('error', 'Bạn đã hủy giao dịch.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Payment cancellation failed: " . $e->getMessage());
                    return redirect()->route('client.services')
                        ->with('error', 'Có lỗi xảy ra khi xóa dữ liệu.');
                }
            } else {
                // Các mã lỗi khác
                $payment->update(['status' => 'unpaid']);

                $request->session()->forget('temp_booking_data');
                return redirect()->route('client.services')
                    ->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
            }
        } else {
            // Checksum không đúng
            $request->session()->forget('temp_booking_data');
            return redirect()->route('client.services')
                ->with('error', 'Dữ liệu không hợp lệ (checksum sai).');
        }
    }

    /**
     * Xử lý VNPay IPN (server-to-server)
     */
    public function paymentIpn(Request $request)
    {
        // $this->cleanExpiredPayments(); // Dọn dẹp trước khi xử lý (nếu cần, nhưng IPN là async nên có thể không gọi ở đây để tránh overhead)

        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $txnRef = $request->vnp_TxnRef;
        $responseCode = $request->vnp_ResponseCode;
        $paymentId = explode('_', $txnRef)[0];

        $payment = Payment::where('id', $paymentId)->first();

        if (!$payment) {
            return response()->json(['RspCode' => '01', 'Message' => 'Transaction not found']);
        }

        // Kiểm tra checksum trước
        if ($secureHash === $vnp_SecureHash) {
            if ($responseCode === '00') {
                // Giao dịch thành công
                if ($payment->status !== 'paid') {
                    DB::beginTransaction();
                    try {
                        $payDate = Carbon::createFromFormat('YmdHis', $request->vnp_PayDate);

                        // Cập nhật Payment
                        $payment->update([
                            'status'              => 'paid',
                            'paid_at'             => $payDate,
                            'vnp_txn_ref'         => $request->vnp_TxnRef,
                            'vnp_transaction_no'  => $request->vnp_TransactionNo,
                            'vnp_response_code'   => $request->vnp_ResponseCode,
                        ]);

                        // Ghi lại PaymentHistory
                        PaymentHistory::create([
                            'payment_id'     => $payment->id,
                            'amount'         => $request->vnp_Amount / 100,
                            'payment_method' => 'vnpay',
                            'payment_date'   => $payDate,
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
            } elseif ($responseCode === '24') {
                // Người dùng hủy giao dịch
                DB::beginTransaction();
                try {
                    $payment->delete();
                    DB::commit();
                    return response()->json(['RspCode' => '00', 'Message' => 'Cancellation processed']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("IPN cancellation failed: " . $e->getMessage());
                    return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
                }
            } else {
                // Các mã lỗi khác: thất bại
                if ($payment->status !== 'unpaid') {
                    $payment->update(['status' => 'unpaid']);
                }
                return response()->json(['RspCode' => '01', 'Message' => 'Transaction failed']);
            }
        } else {
            // Sai checksum
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid checksum']);
        }
    }

    // New method for success page
    public function success()
    {
        return view('client.booking.success');
    }

    /**
     * Dọn dẹp các Payment và Appointment hết hạn
     */
    protected function cleanExpiredPayments()
    {
        DB::beginTransaction();
        try {
            $expiredPayments = Payment::where('status', 'pending')
                ->whereNotNull('vnp_txn_ref') // Đảm bảo là giao dịch VNPay
                ->where('created_at', '<', now()->subMinutes(5)) // Xóa sau 5 phút (theo vnp_ExpireDate)
                ->get();

            $countPayments = 0;
            foreach ($expiredPayments as $payment) {
                if ($payment->delete()) {
                    $countPayments++;
                }
            }

            DB::commit();
            if ($countPayments > 0) {
                \Log::info("Cleaned up {$countPayments} expired payments due to timeout.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to clean expired payments: " . $e->getMessage());
        }
    }
    /**
     * Xử lý thanh toán bằng ví
     */
    protected function processWalletPayment($user, $appointment, $finalPrice)
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        if ($wallet->balance < $finalPrice) {
            throw new \Exception('Số dư ví không đủ để thanh toán.');
        }

        $wallet->balance -= $finalPrice;
        $wallet->save();

        $wallet->transactions()->create([
            'type' => 'payment',
            'amount' => $finalPrice,
            'description' => 'Thanh toán lịch hẹn #' . $appointment->id . ' cho dịch vụ ' . $appointment->service->name,
            'status' => 'Hoàn thành',
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => $finalPrice,
            'payment_method' => 'wallet',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $order = Order::create([
            'user_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'payment_id' => $payment->id,
            'total_amount' => $finalPrice,
            'status' => 'paid',
            'ordered_at' => now(),
        ]);

        OrderService::create([
            'order_id' => $order->id,
            'service_id' => $appointment->service_id,
            'quantity' => 1,
            'price' => $appointment->service->price,
        ]);

        return $payment;
    }
}
