<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Models\Department;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionHistory;
use App\Models\PrescriptionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with([
            'medicalRecord.appointment.patient',
            'medicalRecord.appointment.doctor.user',
            'prescriptionItems.medicine'
        ]);

        if ($request->filled('patient_name')) {
            $query->whereHas('medicalRecord.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('doctor_name')) {
            $query->whereHas('medicalRecord.appointment.doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        $from_input = $request->date_from;
        $to_input = $request->date_to;

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
            $to = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;

            if ($from && $to && $from->gt($to)) {
                session()->flash('date_swapped', true);

                // Hoán đổi giá trị để không lọc sai
                [$from, $to] = [$to, $from];
                [$from_input, $to_input] = [$to_input, $from_input];
            }

            if ($from && $to) {
                $query->whereBetween('prescribed_at', [$from, $to]);
            } elseif ($from) {
                $query->where('prescribed_at', '>=', $from);
            } elseif ($to) {
                $query->where('prescribed_at', '<=', $to);
            }
        }

        if ($request->filled('medicine_id')) {
            $query->whereHas('prescriptionItems', function ($q) use ($request) {
                $q->where('medicine_id', $request->medicine_id);
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('medicalRecord.appointment.doctor.department', function ($q) use ($request) {
                $q->where('id', $request->department_id);
            });
        }

        $totalQuantity = $query->get()->sum(function ($p) {
            return $p->prescriptionItems->sum('quantity');
        });

        $prescriptions = $query->orderByDesc('prescribed_at')->paginate(10);
        $todayCount = Prescription::whereDate('prescribed_at', today())->count();
        $weeklyCount = Prescription::where('prescribed_at', '>=', now()->startOfWeek())->count();
        $prescriptions->appends($request->all());
        $medicines = Medicine::pluck('name', 'id')->all();
        $departments = Department::all();

        return view('admin.prescriptions.index', compact('prescriptions', 'medicines', 'departments', 'from_input', 'to_input', 'todayCount', 'weeklyCount', 'totalQuantity'));
    }

    public function create()
    {
        $medicalRecords = MedicalRecord::with('appointment.patient')
            ->whereDoesntHave('prescriptions')
            ->whereHas('appointment', function ($q) {
                $q->where('status', 'completed');
            })
            ->get();
        $medicines = Medicine::where('created_at', '>=', now()->subMonths(6))
            ->orderBy('name')
            ->get();

        return view('admin.prescriptions.create', compact('medicalRecords', 'medicines'));
    }
    public function store(StorePrescriptionRequest $request)
    {
        $prescribedAt = Carbon::parse($request->prescribed_at);

        $errors = [];
        foreach ($request->medicines as $i => $item) {
            if (!Medicine::whereKey($item['medicine_id'])->exists()) {
                $errors["medicines.$i.medicine_id"] = 'Thuốc không tồn tại.';
            }
        }
        if ($errors) {
            return back()->withInput()->withErrors($errors);
        }

        DB::beginTransaction();
        try {
            $prescription = Prescription::create([
                'medical_record_id' => $request->medical_record_id,
                'prescribed_at'     => $prescribedAt,
                'notes'             => $request->notes,
            ]);

            foreach ($request->medicines as $item) {
                PrescriptionItem::create([
                    'prescription_id'      => $prescription->id,
                    'medicine_id'          => $item['medicine_id'],
                    'quantity'             => $item['quantity'],
                    'usage_instructions'   => $item['usage_instructions'] ?? null,
                ]);
            }

            DB::commit();
            return to_route('admin.prescriptions.index')
                ->with('success', 'Đơn thuốc đã được tạo thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn thuốc.');
        }
    }



    public function edit($id)
    {
        $prescription = Prescription::with('items.medicine')->findOrFail($id);
        $medicalRecords = MedicalRecord::with('appointment.patient')->get();
        $medicines = Medicine::where('created_at', '>=', now()->subMonths(6))
            ->orderBy('name')
            ->get();

        // Thêm thuộc tính formatted_price cho từng thuốc
        foreach ($medicines as $med) {
            $med->formatted_price = number_format($med->price, 0, ',', '.') . 'đ';
        }

        if ($prescription->is_finalized) {
            session()->flash('warning', '⚠️ Đơn thuốc này đã được đánh dấu hoàn tất. Việc chỉnh sửa cần được cân nhắc kỹ lưỡng.');
        }

        return view('admin.prescriptions.edit', compact('prescription', 'medicalRecords', 'medicines'));
    }


    public function update(UpdatePrescriptionRequest $request, $id)
    {
        $prescription = Prescription::with('items')->findOrFail($id);

        if ($prescription->is_finalized) {
            return back()->with('error', 'Đơn thuốc đã được hoàn tất và không thể chỉnh sửa.');
        }
        $errors = [];
        foreach ($request->medicines as $i => $item) {
            if (!Medicine::whereKey($item['medicine_id'])->exists()) {
                $errors["medicines.$i.medicine_id"] = 'Thuốc không tồn tại.';
            }
        }
        if ($errors) return back()->withInput()->withErrors($errors);

        DB::beginTransaction();
        try {
            // ❌ KHÔNG hoàn kho cũ
            $prescription->items()->delete();   // xoá item cũ

            $prescription->update([
                'medical_record_id' => $request->medical_record_id,
                'prescribed_at'     => $request->prescribed_at,
                'notes'             => $request->notes,
            ]);

            foreach ($request->medicines as $item) {
                PrescriptionItem::create([
                    'prescription_id'    => $prescription->id,
                    'medicine_id'        => $item['medicine_id'],
                    'quantity'           => $item['quantity'],
                    'usage_instructions' => $item['usage_instructions'] ?? null,
                ]);
            }

            // Lưu lịch sử (giữ nguyên) …
            DB::commit();
            return to_route('admin.prescriptions.index')
                ->with('success', 'Đơn thuốc đã được cập nhật thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật đơn thuốc.');
        }
    }


    public function show($id)
    {
        $prescription = Prescription::with([
            'medicalRecord.appointment.patient',
            'items.medicine',
            'histories.user'
        ])->findOrFail($id);

        $prescription->items = $prescription->items->filter(function ($item) {
            return $item->medicine->created_at->gte(now()->subMonths(6));
        });

        $medicineMap = Medicine::pluck('name', 'id')->toArray();

        foreach ($prescription->histories as $history) {
            $newData = $history->new_data;

            foreach ($newData['medicines'] as &$med) {
                $med['medicine_name'] = $medicineMap[$med['medicine_id']] ?? 'Không xác định';
            }

            $history->new_data = $newData;
        }


        return view('admin.prescriptions.show', compact('prescription'));
    }

    public function exportPdf($id)
    {
        $prescription = Prescription::with(['medicalRecord.appointment.patient', 'items.medicine'])
            ->findOrFail($id);

        $prescription->items = $prescription->items->filter(function ($item) {
            return $item->medicine->created_at->gte(now()->subMonths(6));
        });

        $pdf = PDF::loadView('admin.prescriptions.pdf', compact('prescription'));

        return $pdf->download('don-thuoc-' . $prescription->id . '.pdf');
    }

    public function destroy($id)
    {
        Prescription::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Đơn thuốc đã được xóa mềm.'
        ]);
    }


    // Hiển thị danh sách đơn thuốc đã xóa
    public function trashed(Request $request)
    {
        $query = Prescription::onlyTrashed()
            ->with([
                'medicalRecord.appointment.patient',
                'medicalRecord.appointment.doctor.user',
                'prescriptionItems.medicine'
            ]);

        $prescriptions = $query->orderByDesc('prescribed_at')->paginate(10);
        return view('admin.prescriptions.trashed', compact('prescriptions'));
    }

    public function showTrashed($id)
    {
        $prescription = Prescription::onlyTrashed()
            ->with(['medicalRecord.appointment.patient', 'items.medicine'])
            ->findOrFail($id);

        return view('admin.prescriptions.trashed-detail', compact('prescription'));
    }


    // Khôi phục đơn thuốc đã xóa
    public function restore($id)
    {
        try {
            $prescription = Prescription::onlyTrashed()->findOrFail($id);
            $prescription->restore();

            return response()->json([
                'status' => 'success',
                'message' => 'Đơn thuốc đã được khôi phục thành công.'
            ]);
            return to_route('admin.prescriptions.trashed')
                ->with('success', 'Đơn thuốc đã được khôi phục.');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn thuốc không tồn tại hoặc đã được khôi phục trước đó.'
            ], 404);
            return to_route('admin.prescriptions.trashed')
                ->with('error', 'Đơn thuốc không tồn tại hoặc đã được khôi phục.');
        }
    }


    public function searchMedicalRecords(Request $request)
    {
        $query = $request->get('q', '');

        $records = MedicalRecord::with(['appointment.patient', 'appointment.doctor.user'])
            ->whereDoesntHave('prescriptions')
            ->whereHas('appointment', function ($q) {
                $q->where('status', 'completed');
            })
            ->whereHas('appointment.patient', function ($q) use ($query) {
                $q->where('full_name', 'like', "%$query%")
                    ->orWhere('phone', 'like', "%$query%");
            })
            ->limit(10)
            ->get();

        return response()->json($records->map(function ($record) {
            $patientName = $record->appointment->patient->full_name ?? 'Không xác định';
            $doctorName = $record->appointment->doctor->user->full_name ?? 'Không xác định';
            return [
                'id' => $record->id,
                'text' => "#{$record->code} - {$patientName} - BS. {$doctorName}"
            ];
        }));
    }

    public function searchMedicines(Request $request)
    {
        $query = $request->get('q', '');

        $medicines = Medicine::where('name', 'like', "%$query%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($medicines->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'text' => $medicine->name . ' (' . $medicine->unit . ')'
            ];
        }));
    }
}
