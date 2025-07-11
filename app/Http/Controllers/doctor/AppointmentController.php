<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function show(Appointment $appointment)
    {
        return view('doctor.appointments.show', compact('appointment'));
    }
}