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
}
