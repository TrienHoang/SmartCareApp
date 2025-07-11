<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Models\Doctor;
use App\Models\TreatmentPlanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TreatmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $currentDoctor = Auth::user()->doctor;
    
        if (!$currentDoctor) {
            abort(403, 'Tài khoản của bạn không được gán là bác sĩ.');
        }
    
        $query = TreatmentPlan::where('doctor_id', $currentDoctor->id)
            ->with('patient');
    
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
        $treatmentPlan = new TreatmentPlan();
        return view('doctor.treatment_plans.create', compact('treatmentPlan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'plan_title' => 'required|string|max:255',
            'total_estimated_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'goal' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'paused', 'cancelled'])],
            'items' => 'required|array|min:1', // Yêu cầu ít nhất 1 item
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.expected_start_date' => 'required|date|after_or_equal:today',
            'items.*.expected_end_date' => 'nullable|date|after_or_equal:items.*.expected_start_date',
            'items.*.frequency' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $currentDoctor = Auth::user()->doctor;
            if (!$currentDoctor) {
                throw new \Exception("Tài khoản của bạn không được gán là bác sĩ. Vui lòng liên hệ quản trị viên.");
            }

            // Kiểm tra patient_id không thuộc role_id 1 hoặc 2
            $patient = User::where('id', $validatedData['patient_id'])
                ->whereNotIn('role_id', [1, 2])
                ->firstOrFail();

            $treatmentPlan = new TreatmentPlan();
            $treatmentPlan->fill($validatedData);
            $treatmentPlan->doctor_id = $currentDoctor->id;
            $treatmentPlan->status = $validatedData['status'] ?? 'draft';
            $treatmentPlan->save();

            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                foreach ($validatedData['items'] as $itemData) {
                    $item = new TreatmentPlanItem($itemData);
                    $item->treatment_plan_id = $treatmentPlan->id;
                    $item->status = 'pending';
                    $item->save();
                }
            }

            $treatmentPlan->histories()->create([
                'changed_by_id' => Auth::id(),
                'change_description' => 'Tạo kế hoạch điều trị mới',
                'old_data' => null,
                'new_data' => $treatmentPlan->toArray(),
                'changed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('doctor.treatment-plans.index')
                ->with('success', 'Kế hoạch điều trị và các bước đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi tạo kế hoạch điều trị: ' . $e->getMessage()]);
        }
    }
    public function show(TreatmentPlan $treatmentPlan)
    {
        // Kiểm tra quyền: Đảm bảo bác sĩ chỉ xem kế hoạch của mình
        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor || $treatmentPlan->doctor_id !== $currentDoctor->id) {
            abort(403, 'Bạn không có quyền xem kế hoạch điều trị này.');
        }

        // Tải các quan hệ để hiển thị thông tin chi tiết
        $treatmentPlan->load(['patient', 'doctor.user', 'items', 'histories.changedBy']);

        return view('doctor.treatment_plans.show', compact('treatmentPlan'));
    }

    public function edit(TreatmentPlan $treatmentPlan)
    {
        // Kiểm tra quyền: Đảm bảo bác sĩ chỉ sửa kế hoạch của mình
        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor || $treatmentPlan->doctor_id !== $currentDoctor->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa kế hoạch điều trị này.');
        }

        // Tải các mục điều trị con để điền vào form
        $treatmentPlan->load('items');

        // Lấy danh sách bệnh nhân để chọn trong dropdown
        $patients = User::select('id', 'full_name', 'phone')->get();

        return view('doctor.treatment_plans.edit', compact('treatmentPlan', 'patients'));
    }

    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
        // Kiểm tra quyền
        $currentDoctor = Auth::user()->doctor;
        if (!$currentDoctor || $treatmentPlan->doctor_id !== $currentDoctor->id) {
            abort(403, 'Bạn không có quyền cập nhật kế hoạch điều trị này.');
        }

        // Validate dữ liệu đầu vào cho Kế hoạch Điều trị chính
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'plan_title' => 'nullable|string|max:255',
            'total_estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'goal' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'paused', 'cancelled'])],
            // Items validation - 'id' là tùy chọn vì có thể có item mới không có ID
            'items' => 'array',
            'items.*.id' => 'nullable|exists:treatment_plan_items,id', // ID của item hiện có
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.expected_start_date' => 'nullable|date',
            'items.*.expected_end_date' => 'nullable|date|after_or_equal:items.*.expected_start_date',
            'items.*.frequency' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
            // 'items.*.status' không cần validate vì sẽ được quản lý ở phần cập nhật tiến độ
        ]);

        DB::beginTransaction(); // Bắt đầu transaction

        try {
            $oldTreatmentPlanData = $treatmentPlan->toArray(); // Lấy dữ liệu cũ của kế hoạch chính

            // Cập nhật Kế hoạch Điều trị chính
            $treatmentPlan->fill($validatedData);
            $treatmentPlan->save();

            $newTreatmentPlanData = $treatmentPlan->toArray(); // Lấy dữ liệu mới của kế hoạch chính

            // Xử lý cập nhật các Bước Điều trị (TreatmentPlanItems)
            $existingItemIds = $treatmentPlan->items->pluck('id')->toArray();
            $updatedItemIds = [];

            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                foreach ($validatedData['items'] as $itemData) {
                    if (isset($itemData['id']) && in_array($itemData['id'], $existingItemIds)) {
                        // Cập nhật item hiện có
                        $item = TreatmentPlanItem::find($itemData['id']);
                        if ($item) {
                            $item->fill($itemData);
                            $item->save();
                            $updatedItemIds[] = $item->id;
                        }
                    } else {
                        // Tạo item mới
                        $item = new TreatmentPlanItem($itemData);
                        $item->treatment_plan_id = $treatmentPlan->id;
                        $item->status = 'pending'; // Mặc định là 'pending' cho item mới
                        $item->save();
                        $updatedItemIds[] = $item->id;
                    }
                }
            }

            // Xóa các item đã bị gỡ bỏ khỏi form (không có trong $updatedItemIds)
            TreatmentPlanItem::where('treatment_plan_id', $treatmentPlan->id)
                ->whereNotIn('id', $updatedItemIds)
                ->delete();

            // Ghi lại Lịch sử thay đổi cho Kế hoạch Điều trị chính
            $treatmentPlan->histories()->create([
                'changed_by_id' => Auth::id(),
                'change_description' => 'Cập nhật kế hoạch điều trị chính',
                'old_data' => $oldTreatmentPlanData,
                'new_data' => $newTreatmentPlanData,
                'changed_at' => now(),
            ]);

            DB::commit(); // Hoàn tất transaction

            return redirect()->route('doctor.treatment-plans.index')
                ->with('success', 'Kế hoạch điều trị và các bước đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack(); // Hủy bỏ transaction nếu có lỗi
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật kế hoạch điều trị: ' . $e->getMessage()]);
        }
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
        $query = $request->input('query');
        $patients = User::whereNotIn('role_id', [1, 2]) // Loại bỏ admin và doctor
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->select('id', 'full_name', 'email')
            ->take(10)
            ->get();

        return response()->json($patients);
    }
}
