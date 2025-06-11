<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'prescribed_at' => ['required', 'date', 'before_or_equal:now'],
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.usage_instructions' => 'nullable|string',
        ]);

        $prescribedAt = \Carbon\Carbon::parse($request->prescribed_at);

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
        }

        return redirect()->route('admin.prescriptions.index')->with('success', 'Đơn thuốc đã được tạo thành công.');
    }


    public function edit($id)
    {
        $prescription = Prescription::with('items.medicine')->findOrFail($id);
        $medicalRecords = MedicalRecord::with('appointment.patient')->get();
        $medicines = Medicine::where('created_at', '>=', now()->subMonths(6))
            ->orderBy('name')
            ->get();

        return view('admin.prescriptions.edit', compact('prescription', 'medicalRecords', 'medicines'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'prescribed_at' => 'required|date',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.usage_instructions' => 'nullable|string',
            'prescribed_at' => ['required', 'date', 'before_or_equal:now'],
        ]);

        $prescription = Prescription::findOrFail($id);
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
        }

        return redirect()->route('admin.prescriptions.index')->with('success', 'Đơn thuốc đã được cập nhật thành công.');
    }

    public function show($id)
    {
        $prescription = Prescription::with(['medicalRecord.appointment.patient', 'items.medicine'])
            ->findOrFail($id);
        $prescription->items = $prescription->items->filter(function ($item) {
            return $item->medicine->created_at->gte(now()->subMonths(6));
        });

        return view('admin.prescriptions.show', compact('prescription'));
    }

    public function exportPdf($id)
    {
        $prescription = Prescription::with(['medicalRecord.appointment.patient', 'items.medicine'])
            ->findOrFail($id);
        
        $prescription->items = $prescription->items->filter(function ($item){
            return $item->medicine->created_at->gte(now()->subMonths(6));
        });

        $pdf = PDF::loadView('admin.prescriptions.pdf', compact('prescription'));

        return $pdf->download('don-thuoc-' . $prescription->id . '.pdf');
    }
}
