<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\WorkingSchedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

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
        // dd($request->all());

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_option' => 'required|in:random,specific',
            'doctor_id' => 'nullable|required_if:doctor_option,specific|exists:doctors,id',
        ]);

        if ($validated['doctor_option'] === 'specific') {
            // Kiểm tra bác sĩ thuộc dịch vụ
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
            return redirect()->route('services.index')->with('error', 'Vui lòng chọn dịch vụ trước.');
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
            // Thêm month và year vào validation
            'month' => 'required|integer|min:0|max:11', // 0-indexed month (0 for Jan, 11 for Dec)
            'year' => 'required|integer|min:2020',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $doctor_id = $validated['doctor_id'];

        // Lấy tháng và năm từ request của frontend
        $queryMonth = $validated['month']; // Month là 0-indexed từ JS (0-11)
        $queryYear = $validated['year'];

        $start_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->startOfDay();

        $end_of_month = Carbon::create($queryYear, $queryMonth + 1, 1)->endOfMonth()->endOfDay();

        $search_start_date = $start_of_month->copy()->subWeeks(1); // Lùi lại 1 tuần
        $search_end_date = $end_of_month->copy()->addWeeks(1);    // Tiến lên 1 tuần

        $current_today = Carbon::today()->startOfDay();

        if ($doctor_id) {
            $doctor_ids = [$doctor_id];
        } else {
            // Random: Lấy bác sĩ ít lịch nhất trong khoảng thời gian ĐANG ĐƯỢC XEM
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

        // Nếu không tìm thấy bác sĩ nào khớp, trả về mảng rỗng
        if ($doctor_ids->isEmpty()) {
            return response()->json([]);
        }

        // Lấy ngày khả dụng cho các bác sĩ đã chọn trong khoảng thời gian tìm kiếm
        $schedules = WorkingSchedule::whereIn('doctor_id', $doctor_ids)
            ->where('status', 'Đã xét duyệt') // Đảm bảo status khớp với DB của bạn
            ->whereBetween('day', [$search_start_date->toDateString(), $search_end_date->toDateString()])
            ->with('shift')
            ->get();

        $available_dates = [];
        foreach ($schedules as $schedule) {
            $date = Carbon::parse($schedule->day);
            $shift = $schedule->shift;

            if (!$shift) {
                continue; // Bỏ qua nếu không có shift liên quan
            }

            // Đảm bảo start_time và end_time là chuỗi thời gian hợp lệ
            $start_time_str = $shift->start_time;
            $end_time_str = $shift->end_time;

            try {
                $start = Carbon::parse($start_time_str);
                $end = Carbon::parse($end_time_str);
            } catch (\Exception $e) {
                // Log lỗi nếu parse thời gian thất bại
                \Log::error("Invalid shift time for schedule ID {$schedule->id}: Start: {$start_time_str}, End: {$end_time_str}");
                continue;
            }

            $current = $start->copy();

            while ($current->lessThan($end)) {
                $slot_start = $current->format('H:i'); // Ví dụ: "09:00"
                // Tạo đối tượng Carbon cho thời gian slot vào đúng ngày làm việc
                $slot_full_datetime = $date->copy()->setTimeFromTimeString($slot_start);

                // Kiểm tra xem slot này có phải là trong quá khứ so với THỜI ĐIỂM HIỆN TẠI không
                if ($slot_full_datetime->lt(Carbon::now())) {
                    $current->addMinutes($service->duration);
                    continue; // Bỏ qua các slot trong quá khứ
                }

                $booked = Appointment::where('doctor_id', $schedule->doctor_id)
                    ->where('appointment_time', $slot_full_datetime)
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                // Nếu slot chưa được đặt, đánh dấu ngày đó là khả dụng và thoát vòng lặp slot (chỉ cần 1 slot trống)
                if (!$booked) {
                    $available_dates[$date->format('Y-m-d')] = true;
                    break; // Thoát vòng lặp while để đi sang ngày tiếp theo
                }

                $current->addMinutes($service->duration);
            }
        }

        // Trả về đối tượng JSON của các ngày khả dụng
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

        $available_slots = [];

        foreach ($schedules as $schedule) {
            $shift = $schedule->shift;
            if (!$shift) {
                \Log::warning("No shift found for schedule ID {$schedule->id}");
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

                $slot_full_datetime = $selected_date->copy()->setTimeFromTimeString($current_slot_time->format('H:i'));

                // Bỏ qua slot trong quá khứ
                if ($slot_full_datetime->lt($current_datetime)) {
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
        $booking_data = $request->session()->get('booking_data');
        if (!$booking_data) {
            return redirect()->route('services.index')->with('error', 'Vui lòng chọn dịch vụ trước.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(7)->toDateString(),
            'slot_start' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:255',
        ]);

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor_id = $booking_data['doctor_id'] ?? $request->input('doctor_id'); // Nếu random, chọn từ slot

        $appointment_time = Carbon::parse($validated['date'])->setTimeFromTimeString($validated['slot_start']);

        // Kiểm tra slot còn trống
        $booked = Appointment::where('doctor_id', $doctor_id)
            ->where('appointment_time', $appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($booked) {
            return back()->withErrors(['slot_start' => 'Khung giờ này đã được đặt.']);
        }

        // Lưu tạm để xác nhận
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
            return redirect()->route('services.index')->with('error', 'Dữ liệu đặt lịch không hợp lệ.');
        }

        $service = Service::findOrFail($booking_data['service_id']);
        $doctor = Doctor::findOrFail($booking_confirm['doctor_id']);
        $appointment_time = Carbon::parse($booking_confirm['appointment_time']);
        $user = auth()->user();

        return view('booking.confirm', compact('service', 'doctor', 'appointment_time', 'user', 'booking_confirm'));
    }

    public function save(Request $request)
    {
        $booking_data = $request->session()->get('booking_data');
        $booking_confirm = $request->session()->get('booking_confirm');

        if (!$booking_data || !$booking_confirm) {
            return redirect()->route('services.index')->with('error', 'Dữ liệu đặt lịch không hợp lệ.');
        }

        $service = Service::findOrFail($booking_data['service_id']);
        $appointment_time = Carbon::parse($booking_confirm['appointment_time']);

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'patient_id' => auth()->id(),
                'doctor_id' => $booking_confirm['doctor_id'],
                'service_id' => $booking_data['service_id'],
                'appointment_time' => $appointment_time,
                'end_time' => $appointment_time->copy()->addMinutes($service->duration),
                'status' => 'pending',
                'reason' => $booking_confirm['reason'],
                'created_by' => auth()->id(),
            ]);
            $request->session()->forget(['booking_data', 'booking_confirm']);
            DB::commit();

            return redirect()->route('booking.success')->with('success', 'Đặt lịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
