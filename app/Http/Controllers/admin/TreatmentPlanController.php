<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class TreatmentPlanController extends Controller
{
    /**
     * Hiển thị danh sách tất cả kế hoạch điều trị.
     */
    public function index(Request $request)
    {
        $query = TreatmentPlan::with(['doctor.user:id,full_name', 'patient:id,full_name']);

        if ($request->filled('doctor')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor . '%');
            });
        }

        if ($request->filled('patient')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $plans = $query->paginate(10)->appends($request->all());

        // Thống kê đơn giản
        $totalPlans = TreatmentPlan::count();
        $completedPlans = TreatmentPlan::where('status', 'hoan_thanh')->count();
        $inProgressPlans = TreatmentPlan::where('status', 'dang_tien_hanh')->count();
        $pausedPlans = TreatmentPlan::where('status', 'tam_dung')->count();
        $cancelledPlans = TreatmentPlan::where('status', 'huy_bo')->count();
        return view('admin.treatment_plans.index', compact(
            'plans',
            'totalPlans',
            'completedPlans',
            'inProgressPlans',
            'pausedPlans',
            'cancelledPlans'
        ));
    }


    /**
     * Hiển thị chi tiết một kế hoạch điều trị.
     */
    public function show($id)
    {
        $plan = TreatmentPlan::with([
            'patient',
            'doctor.user', // This is the crucial part for accessing doctor's specialization
            'items',
            'histories'
        ])->findOrFail($id);
        return view('admin.treatment_plans.show', compact('plan'));
    }

    /**
     * Hiển thị form chỉnh sửa kế hoạch điều trị.
     */
    public function edit($id)
    {
        $plan = TreatmentPlan::with(['items', 'doctor.user', 'patient'])->findOrFail($id);
        return view('admin.treatment_plans.edit', compact('plan'));
    }

    /**
     * Cập nhật thông tin kế hoạch điều trị.
     */

    public function update(Request $request, $id)
    {
        $plan = TreatmentPlan::findOrFail($id);

        // 1. Validate dữ liệu
        $validated = $request->validate([
            'plan_title' => 'required|string|max:255',
            'diagnosis' => 'nullable|string',
            'goal' => 'nullable|string',
            'notes' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',

            // Validate danh sách bước điều trị nếu có
            'items' => 'nullable|array',
            'items.*.id' => 'required|exists:treatment_plan_items,id',
            'items.*.status' => 'required|string|in:chua_thuc_hien,dang_thuc_hien,hoan_thanh,tam_dung',
            'items.*.expected_start_date' => 'nullable|date',
            'items.*.expected_end_date' => 'nullable|date',
        ]);

        // 2. Ghi dữ liệu cũ (bao gồm cả bước)
        $oldPlanData = $plan->toArray();
        $oldItemsData = $plan->items->keyBy('id')->toArray();

        // 3. Cập nhật kế hoạch điều trị chính
        $plan->update([
            'plan_title' => $validated['plan_title'],
            'diagnosis' => $validated['diagnosis'],
            'goal' => $validated['goal'],
            'notes' => $validated['notes'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);

        // 4. Cập nhật các bước điều trị
        foreach ($validated['items'] ?? [] as $index => $itemData) {
            $item = $plan->items()->where('id', $itemData['id'])->first();
            if (!$item) continue;

            $start = $itemData['expected_start_date'] ?? null;
            $end = $itemData['expected_end_date'] ?? null;

            // Validate logic ngày
            if ($start && $start < $plan->start_date) {
                throw ValidationException::withMessages([
                    "items.{$item->id}.expected_start_date" => "Ngày bắt đầu không được trước ngày bắt đầu kế hoạch."
                ]);
            }

            if ($end && $end > $plan->end_date) {
                throw ValidationException::withMessages([
                    "items.{$item->id}.expected_end_date" => "Ngày kết thúc dự kiến không được sau ngày kết thúc kế hoạch."
                ]);
            }

            if ($start && $end && $end < $start) {
                throw ValidationException::withMessages([
                    "items.{$item->id}.expected_end_date" => "Ngày kết thúc dự kiến không thể trước ngày bắt đầu."
                ]);
            }

            // Chuẩn bị dữ liệu cập nhật
            $updates = [
                'status' => $itemData['status'],
                'expected_start_date' => $start,
                'expected_end_date' => $end,
            ];

            // Nếu hoàn thành thì gán actual_end_date
            if ($itemData['status'] === 'hoan_thanh') {
                $now = now();

                if ($start && $now->lt($start)) {
                    return redirect()->back()
                        ->withErrors(["items.{$item->id}.status" => "Không thể hoàn thành trước ngày bắt đầu ({$start})."])
                        ->withInput();
                }

                $updates['actual_end_date'] = $now;
            } else {
                $updates['actual_end_date'] = null;
            }
            if ($itemData['status'] === 'dang_thuc_hien') {
                $now = now();
                if ($start && $now->lt(\Carbon\Carbon::parse($start))) {
                    return redirect()->back()
                        ->withErrors([
                            "items.{$item->id}.status" => "Chỉ có thể chuyển sang trạng thái 'Đang thực hiện' từ ngày bắt đầu dự kiến ({$start})."
                        ])
                        ->withInput();
                }
            }

            $item->update($updates);
        }

        // 5. Lấy lại dữ liệu mới
        $newPlanData = $plan->fresh()->toArray();
        $newItemsData = $plan->items()->get()->keyBy('id')->toArray();

        // 6. So sánh để chỉ lưu những thay đổi
        $changedPlanFields = [];
        foreach ($newPlanData as $key => $value) {
            if (isset($oldPlanData[$key]) && $oldPlanData[$key] != $value) {
                $changedPlanFields[$key] = [
                    'old' => $oldPlanData[$key],
                    'new' => $value,
                ];
            }
        }

        $changedItemFields = [];
        foreach ($newItemsData as $id => $newItem) {
            if (!isset($oldItemsData[$id])) continue;

            foreach ($newItem as $field => $newVal) {
                $oldVal = $oldItemsData[$id][$field] ?? null;
                if ($newVal != $oldVal) {
                    $changedItemFields[$id][$field] = [
                        'old' => $oldVal,
                        'new' => $newVal
                    ];
                }
            }
        }

        // Nếu có thay đổi thì ghi vào lịch sử
        if (!empty($changedPlanFields) || !empty($changedItemFields)) {
            TreatmentPlanHistory::create([
                'treatment_plan_id' => $plan->id,
                'changed_by_id' => auth()->id(),
                'change_description' => 'Cập nhật kế hoạch điều trị và các bước.',
                'old_data' => json_encode([
                    'plan' => $oldPlanData,
                    'items' => $oldItemsData,
                ], JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode([
                    'plan' => $newPlanData,
                    'items' => $newItemsData,
                ], JSON_UNESCAPED_UNICODE),
                'changed_at' => now(),
            ]);
        }

        return redirect()->route('admin.treatment-plans.show', $plan->id)
            ->with('success', 'Cập nhật kế hoạch điều trị và các bước thành công.');
    }




    /**
     * Xem lịch sử thay đổi kế hoạch điều trị.
     */
    public function history($id)
    {
        $plan = TreatmentPlan::findOrFail($id);
        $histories = $plan->histories()->with('changedBy:id,full_name')->get();

        return view('admin.treatment_plans.history', compact('plan', 'histories'));
    }


    public function exportPdf($id)
    {
        $plan = TreatmentPlan::with(['doctor.user', 'patient', 'items'])->findOrFail($id);

        return PDF::loadView('admin.treatment_plans.exportPdf', compact('plan'))
            ->setPaper('a4')
            ->download("ke-hoach-dieu-tri-{$plan->id}.pdf");
    }
    public function exportExcel($id)
    {
        $plan = TreatmentPlan::with(['doctor.user', 'patient', 'items'])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;

        // Tiêu đề
        $sheet->setCellValue("A{$row}", 'KẾ HOẠCH ĐIỀU TRỊ #' . $plan->id);
        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $row += 2;

        // Thông tin bác sĩ
        $sheet->setCellValue("A{$row}", 'Thông tin bác sĩ');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", 'Họ tên');
        $sheet->setCellValue("B{$row}", $plan->doctor->user->full_name ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Chuyên khoa');
        $sheet->setCellValue("B{$row}", $plan->doctor->specialization ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'SĐT');
        $sheet->setCellValue("B{$row}", $plan->doctor->user->phone ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Email');
        $sheet->setCellValue("B{$row}", $plan->doctor->user->email ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Địa chỉ');
        $sheet->setCellValue("B{$row}", $plan->doctor->user->address ?? 'N/A');
        $row += 2;

        // Thông tin bệnh nhân
        $sheet->setCellValue("A{$row}", 'Thông tin bệnh nhân');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", 'Họ tên');
        $sheet->setCellValue("B{$row}", $plan->patient->full_name ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Ngày sinh');
        $sheet->setCellValue("B{$row}", optional($plan->patient->date_of_birth)->format('d/m/Y'));
        $row++;
        $sheet->setCellValue("A{$row}", 'Giới tính');
        $sheet->setCellValue("B{$row}", $plan->patient->gender ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'SĐT');
        $sheet->setCellValue("B{$row}", $plan->patient->phone ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Email');
        $sheet->setCellValue("B{$row}", $plan->patient->email ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Địa chỉ');
        $sheet->setCellValue("B{$row}", $plan->patient->address ?? 'N/A');
        $row += 2;

        // Tổng quan kế hoạch
        $sheet->setCellValue("A{$row}", 'Tổng quan kế hoạch');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue("A{$row}", 'Tiêu đề');
        $sheet->setCellValue("B{$row}", $plan->plan_title);
        $row++;
        $sheet->setCellValue("A{$row}", 'Chẩn đoán');
        $sheet->setCellValue("B{$row}", $plan->diagnosis);
        $row++;
        $sheet->setCellValue("A{$row}", 'Mục tiêu');
        $sheet->setCellValue("B{$row}", $plan->goal);
        $row++;
        $sheet->setCellValue("A{$row}", 'Ghi chú');
        $sheet->setCellValue("B{$row}", $plan->notes);
        $row++;
        $sheet->setCellValue("A{$row}", 'Tổng chi phí');
        $sheet->setCellValue("B{$row}", number_format($plan->total_estimated_cost, 0, ',', '.') . ' VND');
        $row += 2;

        // Bảng bước điều trị
        $sheet->setCellValue("A{$row}", 'Các bước điều trị');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $sheet->fromArray([
            ['STT', 'Tiêu đề', 'Mô tả', 'Tần suất', 'Thời gian', 'Trạng thái', 'Ghi chú']
        ], null, "A{$row}");
        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
        $row++;

        foreach ($plan->items as $index => $item) {
            $timeRange = '';
            if ($item->expected_start_date) {
                $timeRange .= 'Bắt đầu: ' . $item->expected_start_date->format('d/m/Y') . "\n";
            }
            if ($item->expected_end_date) {
                $timeRange .= 'Kết thúc: ' . $item->expected_end_date->format('d/m/Y') . "\n";
            }
            if ($item->actual_end_date) {
                $timeRange .= 'Hoàn tất: ' . $item->actual_end_date->format('d/m/Y');
            }

            $statusMap = [
                'chua_thuc_hien' => 'Chưa thực hiện',
                'dang_thuc_hien' => 'Đang thực hiện',
                'hoan_thanh' => 'Hoàn thành',
                'tam_dung' => 'Tạm dừng'
            ];

            $sheet->fromArray([
                $index + 1,
                $item->title,
                $item->description,
                $item->frequency,
                $timeRange,
                $statusMap[$item->status] ?? 'Không rõ',
                $item->notes
            ], null, "A{$row}");
            $row++;
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Xuất file
        $writer = new Xlsx($spreadsheet);
        $filename = 'ke_hoach_dieu_tri_' . $plan->id . '.xlsx';
        $tempFile = storage_path("app/public/{$filename}");
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    public function destroy($id)
    {
        $plan = TreatmentPlan::findOrFail($id);

        if (in_array($plan->status, ['hoan_thanh', 'dang_tien_hanh'])) {
            return response()->json(['error' => 'Không thể xóa kế hoạch đang tiến hành hoặc đã hoàn thành.'], 403);
        }

        $plan->delete();

        return response()->json(['message' => 'Đã chuyển vào thùng rác.']);
    }

    // Hiển thị danh sách kế hoạch trong thùng rác
    public function trash(Request $request)
    {
        $query = TreatmentPlan::onlyTrashed()->with(['doctor.user:id,full_name', 'patient:id,full_name']);


        // Lọc theo bác sĩ
        if ($request->filled('doctor')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor . '%');
            });
        }

        // Lọc theo ngày xóa
        if ($request->filled('delete_date')) {
            $query->whereDate('deleted_at', $request->delete_date);
        }

        // Tìm kiếm theo tên bệnh nhân hoặc bác sĩ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', fn($sub) => $sub->where('full_name', 'like', "%{$search}%"))
                    ->orWhereHas('doctor.user', fn($sub) => $sub->where('full_name', 'like', "%{$search}%"));
            });
        }

        $plans = $query->orderBy('deleted_at', 'desc')->paginate(10);
        $doctors = Doctor::all();

        return view('admin.treatment_plans.trash', compact('plans', 'doctors'));
    }

    public function restore($id)
    {
        TreatmentPlan::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.treatment-plans.trash')->with('success', 'Khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        TreatmentPlan::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.treatment-plans.trash')->with('success', 'Đã xóa vĩnh viễn!');
    }
}
