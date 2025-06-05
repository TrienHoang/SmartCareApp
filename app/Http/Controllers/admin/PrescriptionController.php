<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['medicalRecord.appointment.patient'])
            ->orderByDesc('prescribed_at')
            ->paginate(10);

        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $medicalRecords = MedicalRecord::with('appointment.patient')->get();
        $medicines = Medicine::all();

        return view('admin.prescriptions.create', compact('medicalRecords', 'medicines'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'prescribed_at' => 'required|date',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.usage_instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'medical_record_id' => $request->medical_record_id,
            'prescribed_at' => $request->prescribed_at,
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
        $medicines = Medicine::all();

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

    public function show($id){
        $prescription = Prescription::with(['medicalRecord.appointment.patient', 'items.medicine'])
            ->findOrFail($id);

        return view('admin.prescriptions.show', compact('prescription'));
    }
}
