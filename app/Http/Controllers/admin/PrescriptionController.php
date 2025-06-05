<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(){
        $prescriptions = Prescription::with(['medicalRecord.appointment.patient'])
        ->orderByDesc('prescribed_at')
        ->paginate(10);

        return view('admin.prescriptions.index', compact('prescriptions'));
    }
}
