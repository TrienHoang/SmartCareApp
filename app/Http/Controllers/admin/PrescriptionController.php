<?php

namespace App\Http\Controllers\admin;

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

        if ($request->filled('date_from')) {
            $query->whereDate('prescribed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('prescribed_at', '<=', $request->date_to);
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

        $prescriptions = $query->orderByDesc('prescribed_at')->paginate(10);
        $prescriptions->appends($request->all());
        $medicines = Medicine::pluck('name', 'id')->all();
        $departments = Department::all();

        return view('admin.prescriptions.index', compact('prescriptions', 'medicines', 'departments'));
    }

    public function create()
    {
        $medicalRecords = MedicalRecord::with('appointment.patient')->get();
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
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine) {
                $errors["medicines.$i.medicine_id"] = 'Thuốc không tồn tại.';
            } elseif ($medicine->stock < $item['quantity']) {
                $errors["medicines.$i.quantity"] = 'Thuốc "' . $medicine->name . '" không đủ tồn kho. (Còn: ' . $medicine->stock . ')';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        DB::beginTransaction();

        try {
            $prescription = Prescription::create([
                'medical_record_id' => $request->medical_record_id,
                'prescribed_at' => $prescribedAt,
                'notes' => $request->notes,
            ]);

            foreach ($request->medicines as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'usage_instructions' => $item['usage_instructions'] ?? null,
                ]);

                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('admin.prescriptions.index')->with('success', 'Đơn thuốc đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn thuốc.');
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

        return view('admin.prescriptions.edit', compact('prescription', 'medicalRecords', 'medicines'));
    }


    public function update(UpdatePrescriptionRequest $request, $id)
    {
        $prescription = Prescription::with('items')->findOrFail($id);

        $oldData = [
            'prescribed_at' => $prescription->prescribed_at,
            'notes' => $prescription->notes,
            'medicines' => $prescription->items->map(function ($item) {
                return [
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'usage_instructions' => $item->usage_instructions,
                ];
            })->toArray(),
        ];

        $errors = [];
        foreach ($request->medicines as $i => $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine) {
                $errors["medicines.$i.medicine_id"] = 'Thuốc không tồn tại.';
            } elseif ($medicine->stock < $item['quantity']) {
                $errors["medicines.$i.quantity"] = 'Thuốc "' . $medicine->name . '" không đủ tồn kho. (Còn: ' . $medicine->stock . ')';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        DB::beginTransaction();

        try {
            foreach ($prescription->items as $oldItem) {
                $medicine = Medicine::find($oldItem->medicine_id);
                if ($medicine) {
                    $medicine->increment('stock', $oldItem->quantity);
                }
            }

            $prescription->update([
                'medical_record_id' => $request->medical_record_id,
                'prescribed_at' => $request->prescribed_at,
                'notes' => $request->notes,
            ]);

            $prescription->items()->delete();

            foreach ($request->medicines as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'usage_instructions' => $item['usage_instructions'] ?? null,
                ]);

                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            $newData = [
                'prescribed_at' => $request->prescribed_at,
                'notes' => $request->notes,
                'medicines' => $request->medicines,
            ];

            PrescriptionHistory::create([
                'prescription_id' => $prescription->id,
                'updated_by' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $newData,
                'changed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.prescriptions.index')->with('success', 'Đơn thuốc đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật đơn thuốc.');
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
        $prescription = Prescription::with('items')->findOrFail($id);

        foreach ($prescription->items as $item) {
            $medicine = Medicine::find($item->medicine_id);
            if ($medicine) {
                $medicine->increment('stock', $item->quantity);
            }
        }

        $prescription->delete();
        return redirect()->route('admin.prescriptions.index')
            ->with('success', 'Đơn thuốc đã được xóa mềm và hoàn lại vào thuốc kho.');
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
        $prescription = Prescription::onlyTrashed()->with('items')->findOrFail($id);

        foreach ($prescription->items as $item) {
            $medicine = Medicine::find($item->medicine_id);
            if ($medicine) {
                if ($medicine->stock < $item->quantity) {
                    return redirect()->route('admin.prescriptions.trashed')
                        ->with('error', 'Không thể khôi phục đơn thuốc "' . $prescription->id . '" vì thuốc "' . $medicine->name . '" không đủ tồn kho. Cần ' . $item->quantity . ', hiện tại chỉ còn ' . $medicine->stock . '.');
                }
                $medicine->decrement('stock', $item->quantity);
            }
        }

        $prescription->restore();

        return redirect()->route('admin.prescriptions.trashed')
            ->with('success', 'Đơn thuốc đã được khôi phục và trừ lại thuốc từ kho.');
    }

    public function searchMedicalRecords(Request $request)
    {
        $query = $request->get('q', '');

        $records = MedicalRecord::with('appointment.patient')
            ->whereHas('appointment.patient', function ($q) use ($query) {
                $q->where('full_name', 'like', "%$query%")
                    ->orWhere('phone', 'like', "%$query%");
            })
            ->limit(10)
            ->get();

        return response()->json($records->map(function ($record) {
            return [
                'id' => $record->id,
                'text' => "#{$record->code} - {$record->appointment->patient->full_name}"
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
                'text' => $medicine->name . ' (' . $medicine->unit . ') - Còn: ' . $medicine->stock
            ];
        }));
    }
}
