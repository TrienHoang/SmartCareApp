<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function show($id)
    {
        $doctor = Doctor::with([
            'user',
            'department',
            'educations',
            'experiences',
            'achievements',
            'specialties'
        ])->findOrFail($id);

        return view('client.doctors_detail', compact('doctor'));
    }
}
