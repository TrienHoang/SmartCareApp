<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\StoreTreatmentPlanRequest;
use App\Models\Appointment;
use App\Models\AppointmentLog;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Service;
use App\Models\TreatmentPlanHistory;
use App\Models\TreatmentPlanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TreatmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $currentDoctor = Auth::user()->doctor;

        if (!$currentDoctor) {
            abort(403, 'Tài khoản của bạn không được gán là bác sĩ.');
        }

        $query = TreatmentPlan::where('doctor_id', $currentDoctor->id)
            ->with(relations: 'patient');

        // Lọc theo tiêu đề kế hoạch
        if ($request->filled('plan_title')) {
            $query->where('plan_title', 'LIKE', '%' . $request->plan_title . '%');
        }

        // Lọc theo bệnh nhân
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày bắt đầu và ngày kết thúc
        if ($request->filled('start_date_filter') && $request->filled('end_date_filter')) {
            $startDate = \Carbon\Carbon::parse($request->start_date_filter)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date_filter)->endOfDay();
            if ($startDate->lte($endDate)) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                });
            } else {
                return redirect()->back()->with('error', 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.');
            }
        } elseif ($request->filled('start_date_filter')) {
            $query->whereDate('start_date', '>=', $request->start_date_filter);
        } elseif ($request->filled('end_date_filter')) {
            $query->whereDate('end_date', '<=', $request->end_date_filter);
        }

        $treatmentPlans = $query->latest()->paginate(10);

        // Không cần tải danh sách bệnh nhân vì dùng AJAX
        $patients = collect();

        $statuses = ['all', 'draft', 'active', 'completed', 'paused', 'cancelled'];

        return view('doctor.treatment_plans.index', compact('treatmentPlans', 'patients', 'statuses'));
    }

    public function create()
    {
        if (Auth::user()->role_id !== 2) {
            return redirect()->route('home')->withErrors(['error' => 'Chỉ bác sĩ được phép truy cập']);
        }

        $doctorId = Auth::user()->doctor->id;

        // Lấy danh sách dịch vụ mà bác sĩ được phép thực hiện
        $services = Service::where('status', 'active')
            ->whereIn('id', function ($query) use ($doctorId) {
                $query->select('service_id')
                    ->from('doctor_service')
                    ->where('doctor_id', $doctorId);
            })
            ->select('id', 'name', 'price', 'duration')
            ->get();

        return view('doctor.treatment_plans.create', [
            'treatmentPlan' => new TreatmentPlan(),
            'services' => $services,
        ]);
    }

    public function store(StoreTreatmentPlanRequest $request)
    {

        $validatedData =  $request->validated();

        if (Auth::user()->role_id !== 2) {
            return back()->withInput()->withErrors(['error' => 'Chỉ bác sĩ được phép thực hiện']);
        }

        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor) {
            return back()->withInput()->withErrors(['error' => 'Tài khoản không được gán là bác sĩ']);
        }

        // Kiểm tra bệnh nhân có lịch hẹn hoàn thành với bác sĩ
        $hasAppointment = DB::table('appointments')
            ->where('patient_id', $validatedData['patient_id'])
            ->where('doctor_id', $currentDoctor->id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasAppointment) {
            return back()->withInput()->withErrors(['error' => 'Bệnh nhân này chưa có lịch hẹn hoàn thành với bạn']);
        }

        // Kiểm tra bệnh nhân không phải admin hoặc bác sĩ
        $patient = User::where('id', $validatedData['patient_id'])
            ->whereNotIn('role_id', [1, 2])
            ->first();

        if (!$patient) {
            return back()->withInput()->withErrors(['error' => 'Bệnh nhân không hợp lệ']);
        }

        DB::beginTransaction();
        try {
            // Tạo kế hoạch điều trị
            $treatmentPlan = new TreatmentPlan();
            $treatmentPlan->fill([
                'patient_id' => $validatedData['patient_id'],
                'doctor_id' => $currentDoctor->id,
                'plan_title' => $validatedData['plan_title'],
                'total_estimated_cost' => $validatedData['total_estimated_cost'] ?? 0,
                'notes' => $validatedData['notes'],
                'diagnosis' => $validatedData['diagnosis'],
                'goal' => $validatedData['goal'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'status' => $validatedData['status'] ?? 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $treatmentPlan->save();

            $totalEstimatedCost = 0;
            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                foreach ($validatedData['items'] as $itemData) {

                    if (!empty($itemData['expected_end_date']) && strtotime($itemData['expected_end_date']) > strtotime($treatmentPlan->end_date)) {
                        throw new \Exception("Ngày kết thúc của bước điều trị '{$itemData['title']}' không được vượt quá ngày kết thúc kế hoạch.");
                    }

                    // Kiểm tra service_id (nếu có)
                    if (!empty($itemData['service_id'])) {
                        $service = Service::where('id', $itemData['service_id'])
                            ->where('status', 'active')
                            ->whereIn('id', function ($query) use ($currentDoctor) {
                                $query->select('service_id')
                                    ->from('doctor_service')
                                    ->where('doctor_id', $currentDoctor->id);
                            })->first();

                        if (!$service) {
                            throw new \Exception("Dịch vụ ID {$itemData['service_id']} không hợp lệ hoặc không được phép");
                        }
                        $totalEstimatedCost += $service->price;
                    }

                    // Tạo bước điều trị
                    $item = new TreatmentPlanItem([
                        'title' => $itemData['service_id'] ? Service::find($itemData['service_id'])->name : $itemData['title'],
                        'description' => $itemData['service_id'] ? Service::find($itemData['service_id'])->description : $itemData['description'],
                        'expected_start_date' => $itemData['expected_start_date'],
                        'expected_end_date' => $itemData['expected_end_date'],
                        'frequency' => $itemData['frequency'],
                        'status' => $itemData['status'] ?? 'pending',
                        'notes' => $itemData['notes'],
                        'service_id' => $itemData['service_id'],
                    ]);
                    $item->treatment_plan_id = $treatmentPlan->id;
                    $item->save();
                    // Ghi lịch sử cho bước điều trị
                    TreatmentPlanHistory::create([
                        'treatment_plan_id' => $treatmentPlan->id,
                        'changed_by_id' => Auth::id(),
                        'change_description' => $itemData['service_id']
                            ? "Added service: {$item->title}"
                            : "Added custom step: {$item->title}",
                        'new_data' => json_encode($item->toArray()),
                        'changed_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Tạo lịch hẹn nếu cần
                    if (!empty($itemData['needs_appointment']) && !empty($itemData['appointment_time'])) {
                        // Kiểm tra lịch làm việc
                        $isAvailable = DB::table('working_schedules')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('day', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('start_time', '<=', date('H:i:s', strtotime($itemData['appointment_time'])))
                            ->where('end_time', '>=', date('H:i:s', strtotime($itemData['appointment_time'])))
                            ->exists();

                        if (!$isAvailable) {
                            throw new \Exception("Bác sĩ không làm việc vào thời gian: {$itemData['appointment_time']}");
                        }

                        // Kiểm tra lịch nghỉ phép
                        $isOnLeave = DB::table('doctor_leaves')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('start_date', '<=', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('end_date', '>=', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('approved', 1)
                            ->exists();

                        if ($isOnLeave) {
                            throw new \Exception("Bác sĩ đang nghỉ phép vào thời gian: {$itemData['appointment_time']}");
                        }

                        // Kiểm tra trùng lịch hẹn
                        $isBooked = DB::table('appointments')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('appointment_time', $itemData['appointment_time'])
                            ->exists();

                        if ($isBooked) {
                            throw new \Exception("Lịch hẹn vào thời gian {$itemData['appointment_time']} đã được đặt");
                        }

                        // Tạo lịch hẹn
                        $appointment = new Appointment([
                            'patient_id' => $treatmentPlan->patient_id,
                            'doctor_id' => $currentDoctor->id,
                            'service_id' => $itemData['service_id'],
                            'appointment_time' => $itemData['appointment_time'],
                            'reason' => $itemData['reason'],
                            'status' => 'pending',
                            'treatment_plan_id' => $treatmentPlan->id,
                            'treatment_plan_item_id' => $item->id,
                        ]);
                        $appointment->save();

                        // Ghi log lịch hẹn
                        AppointmentLog::create([
                            'appointment_id' => $appointment->id,
                            'changed_by' => Auth::id(),
                            'status_after' => 'pending',
                            'note' => 'Created appointment for treatment plan',
                            'change_time' => now(),
                        ]);
                    }
                }

                // Cập nhật total_estimated_cost nếu cần
                if ($totalEstimatedCost > 0) {
                    $treatmentPlan->total_estimated_cost = $totalEstimatedCost;
                    $treatmentPlan->save();
                }
            }

            // Ghi lịch sử tạo kế hoạch
            TreatmentPlanHistory::create([
                'treatment_plan_id' => $treatmentPlan->id,
                'changed_by_id' => Auth::id(),
                'change_description' => 'Created new treatment plan',
                'new_data' => json_encode($treatmentPlan->toArray()),
                'changed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            DB::commit();

            return redirect()->route('doctor.treatment-plans.index')
                ->with('success', 'Kế hoạch điều trị và các bước đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        if (Auth::user()->role_id !== 2) {
            return redirect()->route('home')->withErrors(['error' => 'Chỉ bác sĩ được phép truy cập']);
        }

        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor) {
            return redirect()->route('home')->withErrors(['error' => 'Tài khoản không được gán là bác sĩ']);
        }

        // Lấy kế hoạch điều trị với thông tin bệnh nhân và bác sĩ
        $treatmentPlan = TreatmentPlan::where('id', $id)
            ->where('doctor_id', $currentDoctor->id)
            ->with([
                'patient' => function ($query) {
                    $query->select('id', 'full_name', 'email');
                },
                'doctor.user' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'items' => function ($query) {
                    $query->with([
                        'service' => function ($q) {
                            $q->select('id', 'name', 'price');
                        },
                        'appointments' => function ($q) {
                            $q->select('id', 'treatment_plan_item_id', 'appointment_time', 'reason', 'status', 'service_id');
                        }
                    ]);
                }
            ])
            ->firstOrFail();

        // Lấy danh sách dịch vụ mà bác sĩ được phép thực hiện
        $services = Service::where('status', 'active')
            ->whereIn('id', function ($query) use ($currentDoctor) {
                $query->select('service_id')
                    ->from('doctor_service')
                    ->where('doctor_id', $currentDoctor->id);
            })
            ->select('id', 'name', 'price', 'duration')
            ->get();

        return view('doctor.treatment_plans.edit', [
            'treatmentPlan' => $treatmentPlan,
            'services' => $services,
        ]);
    }

    public function update(StoreTreatmentPlanRequest $request, $id)
    {
        $validatedData = $request->validated();

        if (Auth::user()->role_id !== 2) {
            return back()->withInput()->withErrors(['error' => 'Chỉ bác sĩ được phép thực hiện']);
        }

        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor) {
            return back()->withInput()->withErrors(['error' => 'Tài khoản không được gán là bác sĩ']);
        }

        // Kiểm tra kế hoạch thuộc bác sĩ
        $treatmentPlan = TreatmentPlan::where('id', $id)
            ->where('doctor_id', $currentDoctor->id)
            ->firstOrFail();

        // Kiểm tra bệnh nhân không phải admin hoặc bác sĩ
        $patient = User::where('id', $validatedData['patient_id'])
            ->whereNotIn('role_id', [1, 2])
            ->first();

        if (!$patient) {
            return back()->withInput()->withErrors(['error' => 'Bệnh nhân không hợp lệ']);
        }

        // Kiểm tra patient_id không thay đổi
        if ($treatmentPlan->patient_id != $validatedData['patient_id']) {
            return back()->withInput()->withErrors(['error' => 'Không thể thay đổi bệnh nhân của kế hoạch']);
        }

        DB::beginTransaction();
        try {
            // Lưu dữ liệu cũ để ghi lịch sử
            $oldData = $treatmentPlan->toArray();

            // Cập nhật kế hoạch điều trị
            $treatmentPlan->fill([
                'plan_title' => $validatedData['plan_title'],
                'total_estimated_cost' => $validatedData['total_estimated_cost'] ?? 0,
                'notes' => $validatedData['notes'],
                'diagnosis' => $validatedData['diagnosis'],
                'goal' => $validatedData['goal'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'status' => $validatedData['status'] ?? 'draft',
                'updated_at' => now(),
            ]);
            $treatmentPlan->save();

            // Ghi lịch sử cập nhật kế hoạch
            TreatmentPlanHistory::create([
                'treatment_plan_id' => $treatmentPlan->id,
                'changed_by_id' => Auth::id(),
                'change_description' => 'Updated treatment plan',
                'old_data' => json_encode($oldData),
                'new_data' => json_encode($treatmentPlan->toArray()),
                'changed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalEstimatedCost = 0;
            // Xử lý các bước điều trị
            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                // Lấy danh sách ID bước điều trị hiện tại
                $existingItemIds = $treatmentPlan->items()->pluck('id')->toArray();
                $submittedItemIds = array_filter(array_column($validatedData['items'], 'id'));

                // Xóa các bước không còn trong danh sách gửi lên
                TreatmentPlanItem::where('treatment_plan_id', $treatmentPlan->id)
                    ->whereNotIn('id', $submittedItemIds)
                    ->get()
                    ->each(function ($item) {
                        TreatmentPlanHistory::create([
                            'treatment_plan_id' => $item->treatment_plan_id,
                            'changed_by_id' => Auth::id(),
                            'change_description' => "Deleted treatment plan item: {$item->title}",
                            'old_data' => json_encode($item->toArray()),
                            'new_data' => null,
                            'changed_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $item->delete();
                    });

                // Thêm hoặc cập nhật các bước
                foreach ($validatedData['items'] as $index => $itemData) {
                    if (!empty($itemData['expected_end_date']) && !empty($treatmentPlan->end_date) && strtotime($itemData['expected_end_date']) > strtotime($treatmentPlan->end_date)) {
                        throw new \Exception("Ngày kết thúc của bước điều trị '{$itemData['title']}' không được vượt quá ngày kết thúc kế hoạch (bước {$index})");
                    }

                    // Kiểm tra service_id (nếu có)
                    $service = null;
                    if (!empty($itemData['service_id'])) {
                        $service = Service::where('id', $itemData['service_id'])
                            ->where('status', 'active')
                            ->whereIn('id', function ($query) use ($currentDoctor) {
                                $query->select('service_id')
                                    ->from('doctor_service')
                                    ->where('doctor_id', $currentDoctor->id);
                            })->first();

                        if (!$service) {
                            throw new \Exception("Dịch vụ ID {$itemData['service_id']} không hợp lệ hoặc không được phép (bước {$index})");
                        }
                        $totalEstimatedCost += $service->price;
                    }

                    // Cập nhật hoặc tạo bước điều trị
                    $item = !empty($itemData['id'])
                        ? TreatmentPlanItem::where('id', $itemData['id'])->where('treatment_plan_id', $treatmentPlan->id)->firstOrFail()
                        : new TreatmentPlanItem();

                    $oldItemData = $item->exists ? $item->toArray() : null;

                    $item->fill([
                        'treatment_plan_id' => $treatmentPlan->id,
                        'service_id' => $itemData['service_id'] ?? null,
                        'title' => $itemData['service_id'] ? $service->name : $itemData['title'],
                        'description' => $itemData['service_id'] ? $service->description : $itemData['description'],
                        'expected_start_date' => $itemData['expected_start_date'],
                        'expected_end_date' => $itemData['expected_end_date'],
                        'frequency' => $itemData['frequency'],
                        'status' => $itemData['status'] ?? 'pending',
                        'notes' => $itemData['notes'],
                    ]);
                    $item->save();

                    // Ghi lịch sử cho bước điều trị
                    TreatmentPlanHistory::create([
                        'treatment_plan_id' => $treatmentPlan->id,
                        'changed_by_id' => Auth::id(),
                        'change_description' => !empty($itemData['id'])
                            ? ($itemData['service_id'] ? "Updated service: {$item->title}" : "Updated custom step: {$item->title}")
                            : ($itemData['service_id'] ? "Added service: {$item->title}" : "Added custom step: {$item->title}"),
                        'old_data' => $oldItemData ? json_encode($oldItemData) : null,
                        'new_data' => json_encode($item->toArray()),
                        'changed_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Xử lý lịch hẹn
                    if (!empty($itemData['needs_appointment']) && !empty($itemData['appointment_time'])) {
                        // Kiểm tra lịch làm việc
                        $isAvailable = DB::table('working_schedules')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('date', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('start_time', '<=', date('H:i:s', strtotime($itemData['appointment_time'])))
                            ->where('end_time', '>=', date('H:i:s', strtotime($itemData['appointment_time'])))
                            ->exists();

                        if (!$isAvailable) {
                            throw new \Exception("Bác sĩ không làm việc vào thời gian: {$itemData['appointment_time']} (bước {$index})");
                        }

                        // Kiểm tra lịch nghỉ phép
                        $isOnLeave = DB::table('doctor_leaves')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('start_date', '<=', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('end_date', '>=', date('Y-m-d', strtotime($itemData['appointment_time'])))
                            ->where('approved', 1)
                            ->exists();

                        if ($isOnLeave) {
                            throw new \Exception("Bác sĩ đang nghỉ phép vào thời gian: {$itemData['appointment_time']} (bước {$index})");
                        }

                        // Kiểm tra trùng lịch hẹn
                        $isBooked = DB::table('appointments')
                            ->where('doctor_id', $currentDoctor->id)
                            ->where('appointment_time', $itemData['appointment_time'])
                            ->when(!empty($itemData['appointment_id']), function ($query) use ($itemData) {
                                $query->where('id', '!=', $itemData['appointment_id']);
                            })
                            ->exists();

                        if ($isBooked) {
                            throw new \Exception("Lịch hẹn vào thời gian {$itemData['appointment_time']} đã được đặt (bước {$index})");
                        }

                        // Cập nhật hoặc tạo lịch hẹn
                        $appointment = !empty($itemData['appointment_id'])
                            ? Appointment::where('id', $itemData['appointment_id'])
                            ->where('treatment_plan_id', $treatmentPlan->id)
                            ->where('treatment_plan_item_id', $item->id)
                            ->firstOrFail()
                            : new Appointment();

                        $oldAppointmentData = $appointment->exists ? $appointment->toArray() : null;

                        $appointment->fill([
                            'patient_id' => $treatmentPlan->patient_id,
                            'doctor_id' => $currentDoctor->id,
                            'service_id' => $itemData['service_id'] ?? null,
                            'appointment_time' => $itemData['appointment_time'],
                            'reason' => $itemData['reason'] ?? null,
                            'status' => 'pending',
                            'treatment_plan_id' => $treatmentPlan->id,
                            'treatment_plan_item_id' => $item->id,
                        ]);
                        $appointment->save();

                        // Ghi log lịch hẹn
                        AppointmentLog::create([
                            'appointment_id' => $appointment->id,
                            'changed_by' => Auth::id(),
                            'status_before' => $oldAppointmentData['status'] ?? null,
                            'status_after' => 'pending',
                            'note' => !empty($itemData['appointment_id'])
                                ? "Updated appointment for treatment plan item: {$item->title}"
                                : "Created appointment for treatment plan item: {$item->title}",
                            'change_time' => now(),
                        ]);

                        // Gửi thông báo cho bệnh nhân
                        Notification::create([
                            'notifiable_type' => 'App\Models\User',
                            'notifiable_id' => $treatmentPlan->patient_id,
                            'data' => json_encode([
                                'message' => !empty($itemData['appointment_id'])
                                    ? "Lịch hẹn vào {$itemData['appointment_time']} đã được cập nhật cho kế hoạch: {$treatmentPlan->plan_title}"
                                    : "Lịch hẹn mới vào {$itemData['appointment_time']} đã được tạo cho kế hoạch: {$treatmentPlan->plan_title}"
                            ]),
                            'created_at' => now(),
                        ]);
                    }
                }

                // Cập nhật total_estimated_cost nếu cần
                if ($totalEstimatedCost > 0) {
                    $treatmentPlan->total_estimated_cost = $totalEstimatedCost;
                    $treatmentPlan->save();
                }
            }

            // Gửi thông báo cho bệnh nhân
            // Notification::create([
            //     'notifiable_type' => 'App\Models\User',
            //     'notifiable_id' => $treatmentPlan->patient_id,
            //     'data' => json_encode([
            //         'message' => "Kế hoạch điều trị: {$treatmentPlan->plan_title} đã được cập nhật"
            //     ]),
            //     'created_at' => now(),
            // ]);

            DB::commit();

            return redirect()->route('doctor.treatment-plans.show', $treatmentPlan->id)
                ->with('success', 'Kế hoạch điều trị và các bước đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        if (Auth::user()->role_id !== 2) {
            return redirect()->route('home')->withErrors(['error' => 'Chỉ bác sĩ được phép truy cập']);
        }

        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor) {
            return redirect()->route('home')->withErrors(['error' => 'Tài khoản không được gán là bác sĩ']);
        }

        // Lấy kế hoạch điều trị với thông tin bệnh nhân và bác sĩ
        $treatmentPlan = TreatmentPlan::where('id', $id)
            ->where('doctor_id', $currentDoctor->id)
            ->with([
                'patient' => function ($query) {
                    $query->select('id', 'full_name', 'email');
                },
                'doctor.user' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'items' => function ($query) {
                    $query->with([
                        'service' => function ($q) {
                            $q->select('id', 'name', 'price');
                        }
                    ]);
                },
                'items.appointments' => function ($query) {
                    $query->select('id', 'treatment_plan_item_id', 'appointment_time', 'reason', 'status', 'service_id')
                        ->with(['service' => function ($q) {
                            $q->select('id', 'name');
                        }]);
                }
            ])
            ->firstOrFail();
        $statusBadgeClass = $this->getBadgeClassForStatus($treatmentPlan->status);
        return view('doctor.treatment_plans.show', [
            'treatmentPlan' => $treatmentPlan,
            'statusBadgeClass' => $statusBadgeClass,
        ]);
    }

    public function updateItemStatus(Request $request, $itemId)
    {
        $treatmentPlanItem = TreatmentPlanItem::findOrFail($itemId);

        // Kiểm tra quyền: Đảm bảo bác sĩ hiện tại là người tạo kế hoạch chứa item này
        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor || $treatmentPlanItem->treatmentPlan->doctor_id !== $currentDoctor->id) {
            return response()->json(['message' => 'Bạn không có quyền cập nhật bước điều trị này.'], 403);
        }

        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'paused', 'cancelled'])],
            'notes' => 'nullable|string',
        ]);

        $currentStatus = $treatmentPlanItem->status;
        $newStatus = $validatedData['status'];

        // Xác định các trạng thái cho phép dựa trên trạng thái hiện tại
        $allowedTransitions = [
            'pending' => ['in_progress'], // Chỉ cho phép chuyển sang in_progress từ pending
            'in_progress' => ['completed', 'paused', 'cancelled'], // Cho phép linh hoạt từ in_progress
            'completed' => [], // Không cho phép chuyển từ completed (chỉ giữ nguyên hoặc hủy)
            'paused' => ['in_progress', 'completed', 'cancelled'], // Cho phép quay lại từ paused
            'cancelled' => [], // Không cho phép chuyển từ cancelled
        ];

        // Kiểm tra điều kiện chuyển trạng thái
        if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return response()->json([
                'message' => 'Không thể chuyển từ trạng thái "' . ucfirst(str_replace('_', ' ', $currentStatus)) .
                    '" sang trạng thái "' . ucfirst(str_replace('_', ' ', $newStatus)) . '".',
            ], 422); // 422 Unprocessable Entity cho lỗi validation
        }

        DB::beginTransaction();

        try {
            $oldItemData = $treatmentPlanItem->toArray();

            $treatmentPlanItem->status = $newStatus;
            $treatmentPlanItem->notes = $validatedData['notes'] ?? $treatmentPlanItem->notes;
            $treatmentPlanItem->save();

            $newItemData = $treatmentPlanItem->toArray();

            $treatmentPlanItem->treatmentPlan->histories()->create([
                'changed_by_id' => Auth::id(),
                'change_description' => "Cập nhật trạng thái bước '{$treatmentPlanItem->title}' từ '{$currentStatus}' thành '{$newStatus}'",
                'old_data' => $oldItemData,
                'new_data' => $newItemData,
                'changed_at' => now(),
            ]);

            $this->updateTreatmentPlanOverallStatus($treatmentPlanItem->treatment_plan_id);

            DB::commit();

            return response()->json([
                'message' => 'Cập nhật trạng thái bước điều trị thành công!',
                'new_status_text' => ucfirst(str_replace('_', ' ', $treatmentPlanItem->status)),
                'new_notes' => $treatmentPlanItem->notes,
                'badge_class' => $this->getBadgeClassForStatus($treatmentPlanItem->status),
                'plan_status_text' => ucfirst(str_replace('_', ' ', $treatmentPlanItem->treatmentPlan->status)),
                'plan_badge_class' => $this->getBadgeClassForStatus($treatmentPlanItem->treatmentPlan->status),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage()], 500);
        }
    }

    protected function updateTreatmentPlanOverallStatus($treatmentPlanId)
    {
        $treatmentPlan = TreatmentPlan::find($treatmentPlanId);
        if (!$treatmentPlan) return;

        $itemStatuses = $treatmentPlan->items->pluck('status')->all();
        if (empty($itemStatuses)) {
            $treatmentPlan->status = 'pending';
        } elseif (in_array('pending', $itemStatuses) || in_array('in_progress', $itemStatuses)) {
            $treatmentPlan->status = 'in_progress';
        } elseif ($itemStatuses === ['completed']) {
            $treatmentPlan->status = 'completed';
        } else {
            $treatmentPlan->status = 'paused';
        }
        $treatmentPlan->save();
    }

    protected function getBadgeClassForStatus($status)
    {
        return match ($status) {
            'pending' => 'badge-warning',
            'in_progress' => 'badge-info',
            'completed' => 'badge-success',
            'paused' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function destroy(TreatmentPlan $treatmentPlan)
    {
        // Kiểm tra quyền: Đảm bảo bác sĩ hiện tại là người tạo kế hoạch này
        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor || $treatmentPlan->doctor_id !== $currentDoctor->id) {
            abort(403, 'Bạn không có quyền xóa kế hoạch điều trị này.');
        }

        DB::beginTransaction();
        try {
            // Xóa tất cả các bước điều trị con liên quan
            $treatmentPlan->items()->delete();
            // Xóa tất cả lịch sử thay đổi liên quan
            $treatmentPlan->histories()->delete();
            // Xóa kế hoạch điều trị chính
            $treatmentPlan->delete();

            DB::commit();
            return redirect()->route('doctor.treatment-plans.index')
                ->with('success', 'Kế hoạch điều trị và các mục liên quan đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa kế hoạch điều trị: ' . $e->getMessage()]);
        }
    }

    public function searchPatient(Request $request)
    {
        if (Auth::user()->role_id !== 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = $request->input('query');
        $doctorId = Auth::user()->doctor->id;

        $patients = DB::table('appointments')
            ->join('users', 'appointments.patient_id', '=', 'users.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->where('doctors.user_id', Auth::id())
            ->where('appointments.status', 'completed')
            ->where(function ($q) use ($query) {
                $q->where('users.full_name', 'LIKE', "%{$query}%")
                    ->orWhere('users.email', 'LIKE', "%{$query}%");
            })
            ->distinct()
            ->select('users.id', 'users.full_name', 'users.email')
            ->take(10)
            ->get();

        return response()->json($patients);
    }
}
