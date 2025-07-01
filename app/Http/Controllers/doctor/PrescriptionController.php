<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Tài khoản chưa được gán thông tin bác sĩ.');
        }

        $query = Prescription::with([
            'medicalRecord.appointment.patient',
            'prescriptionItems.medicine'
        ])->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        });

        if ($request->filled('patient_name')) {
            $query->whereHas('medicalRecord.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
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

        $prescriptions = $query->orderByDesc('prescribed_at')->paginate(10);

        return view('doctor.prescriptions.index', compact('prescriptions', 'from_input', 'to_input'));
    }

    public function create()
    {
        $doctor = Auth::user()->doctor;

        $medicalRecords = MedicalRecord::with('appointment.patient')
            ->whereHas('appointment', function ($q) use ($doctor) {
                $q->where('status', 'completed')
                    ->where('doctor_id', $doctor->id);
            })
            ->whereDoesntHave('prescriptions')
            ->get();

        $medicines = Medicine::where('created_at', '>=', now()->subMonths(6))
            ->orderBy('name')
            ->get();

        return view('doctor.prescriptions.create', compact('medicalRecords', 'medicines'));
    }

    public function store(StorePrescriptionRequest $request)
    {
        // $request->validate([
        //     'medical_record_id' => 'required|exists:medical_records,id',
        //     'prescribed_at' => 'required|date',
        //     'medicines' => 'required|array|min:1',
        //     'medicines.*.medicine_id' => 'required|exists:medicines,id',
        //     'medicines.*.quantity' => 'required|numeric|min:1',
        //     'medicines.*.usage_instructions' => 'nullable|string|max:255',
        // ]);

        $prescribedAt = Carbon::parse($request->prescribed_at);

        DB::beginTransaction();
        try {
            $prescription = Prescription::create([
                'medical_record_id' => $request->medical_record_id,
                'prescribed_at'     => $prescribedAt,
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

            DB::commit();
            return redirect()->route('doctor.prescriptions.index')->with('success', 'Đơn thuốc đã được kê thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi kê đơn thuốc.');
        }
    }

    public function show($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with([
            'medicalRecord.appointment.patient',
            'prescriptionItems.medicine'
        ])
            ->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->findOrFail($id);

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function exportPdf($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with([
            'medicalRecord.appointment.patient',
            'prescriptionItems.medicine'
        ])
            ->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->findOrFail($id);

        $pdf = Pdf::loadView('doctor.prescriptions.pdf', compact('prescription'));

        return $pdf->download('don-thuoc-' . $prescription->id . '.pdf');
    }
    public function searchMedicalRecords(Request $request)
    {
        $query = $request->get('q', '');
        $doctor = Auth::user()->doctor;

        if (!$doctor) return response()->json([]);

        $records = MedicalRecord::with('appointment.patient')
            ->whereDoesntHave('prescriptions')
            ->whereHas('appointment', function ($q) use ($doctor) {
                $q->where('status', 'completed')
                    ->where('doctor_id', $doctor->id);
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
                'text' => "#{$record->code} - {$record->appointment->patient->full_name}",
                'diagnosis' => $record->diagnosis
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

    public function edit($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::with(['prescriptionItems.medicine', 'medicalRecord.appointment.patient'])
            ->whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->findOrFail($id);

        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.prescriptions.edit', compact('prescription', 'medicines'));
    }

    public function update(UpdatePrescriptionRequest $request, $id)
    {
        $doctor = Auth::user()->doctor;

        // Lấy đơn thuốc thuộc bác sĩ đang đăng nhập
        $prescription = Prescription::whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        // ✅ Ngăn không cho sửa nếu đã finalized
        if ($prescription->is_finalized) {
            return redirect()->route('doctor.prescriptions.index')
                ->with('error', 'Đơn thuốc này đã được hoàn tất và không thể chỉnh sửa.');
        }

        // ✅ Ngăn vượt quá số lần chỉnh sửa
        if ($prescription->edit_count >= 3) {
            return redirect()->route('doctor.prescriptions.index')
                ->with('error', 'Bạn đã vượt quá số lần chỉnh sửa đơn thuốc cho phép.');
        }

        DB::beginTransaction();
        try {
            // ✅ Cập nhật thông tin đơn thuốc
            $prescription->update([
                'prescribed_at' => Carbon::parse($request->prescribed_at),
                'notes' => $request->notes,
                'is_finalized' => $request->has('is_finalized'), // checkbox từ form
            ]);

            // ✅ Refresh lại model để lấy giá trị mới của is_finalized
            $prescription->refresh();

            // ✅ Cập nhật danh sách thuốc
            $newItems = collect($request->medicines)->filter(function ($item) {
                return !empty($item['medicine_id']) && !empty($item['quantity']);
            });

            // Xóa các thuốc cũ không còn trong danh sách mới
            $existingMedicineIds = $newItems->pluck('medicine_id')->toArray();
            $prescription->prescriptionItems()->whereNotIn('medicine_id', $existingMedicineIds)->delete();

            // Cập nhật hoặc thêm mới thuốc
            foreach ($newItems as $item) {
                $prescription->prescriptionItems()->updateOrCreate(
                    [
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $item['medicine_id'],
                    ],
                    [
                        'quantity' => $item['quantity'],
                        'usage_instructions' => $item['usage_instructions'] ?? null,
                    ]
                );
            }

            // ✅ Tăng số lần chỉnh sửa nếu chưa finalized
            $prescription->increment('edit_count');

            DB::commit();
            return redirect()->route('doctor.prescriptions.index')->with('success', 'Đã cập nhật đơn thuốc thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật đơn thuốc.');
        }
    }
    public function finalize($id)
    {
        $doctor = Auth::user()->doctor;

        $prescription = Prescription::whereHas('medicalRecord.appointment', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->findOrFail($id);

        if ($prescription->is_finalized) {
            return back()->with('info', 'Đơn thuốc này đã được hoàn tất trước đó.');
        }

        $prescription->update([
            'is_finalized' => true
        ]);

        return redirect()->route('doctor.prescriptions.index')->with('success', 'Đơn thuốc đã được xác nhân hoàn tất.');
    }
}
