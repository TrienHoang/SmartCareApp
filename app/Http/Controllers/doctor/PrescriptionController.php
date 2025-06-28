<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Models\Department;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

        $prescriptions = $query->orderByDesc('prescribed_at')->paginate(10);
        $prescriptions->appends($request->all());
        $medicines = Medicine::pluck('name', 'id')->all();
        $departments = Department::all();

        return view('doctor.prescriptions.index', compact('prescriptions', 'medicines', 'departments', 'from_input', 'to_input'));
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


        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function print($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with([
            'medicalRecord.appointment.user',
            'prescriptionItems.medicine'
        ])->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        // Logic xuất PDF ở đây
        return view('doctor.prescriptions.print', compact('prescription'));
    }

    public function create(Request $request)
    {
        $doctor = Auth::user()->doctor;

        // Nếu có medical_record_id từ parameter, load sẵn thông tin
        $medicalRecord = null;
        if ($request->filled('medical_record_id')) {
            $medicalRecord = MedicalRecord::with('appointment.user')
                ->whereHas('appointment', function ($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id);
                })
                ->find($request->medical_record_id);
        }

        return view('doctor.prescriptions.create', compact('medicalRecord'));
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
            return to_route('doctor.prescriptions.index')
                ->with('success', 'Đơn thuốc đã được tạo thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn thuốc.');
        }
    }

    public function edit($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with([
            'medicalRecord.appointment.user',
            'prescriptionItems.medicine'
        ])->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        return view('doctor.prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.usage_instructions' => 'required|string|max:500',
        ]);

        $doctor = Auth::user()->doctor;

        $prescription = Prescription::whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        DB::beginTransaction();
        try {
            // Cập nhật thông tin đơn thuốc
            $prescription->update([
                'notes' => $request->notes,
                'prescribed_at' => $prescription->prescribed_at ?? now(),
            ]);

            // Xóa các thuốc cũ
            $prescription->prescriptionItems()->delete();

            // Thêm các thuốc mới
            foreach ($request->medicines as $medicine) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medicine['medicine_id'],
                    'quantity' => $medicine['quantity'],
                    'usage_instructions' => $medicine['usage_instructions'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('doctor.prescriptions.show', $prescription->id)
                ->with('success', 'Đơn thuốc đã được cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật đơn thuốc']);
        }
    }

    public function exportPdf($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with([
            'medicalRecord.appointment.user',
            'prescriptionItems.medicine'
        ])->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        $pdf = Pdf::loadView('doctor.prescriptions.pdf', compact('prescription', 'doctor'));

        $fileName = 'don-thuoc-' . $prescription->id . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    // AJAX endpoints
    public function searchMedicalRecords(Request $request)
    {
        $query = $request->get('q', '');

        $records = MedicalRecord::with('appointment.patient')
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
                'text' => $medicine->name . ' (' . $medicine->unit . ')'
            ];
        }));
    }
}
