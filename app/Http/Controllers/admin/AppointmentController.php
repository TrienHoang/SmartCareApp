<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(){
        $appointments = Appointment::paginate(10);
        return view('admin.Appointment.index', compact('appointments'));
    }
    public function create(){
        return view('admin.Appointment.create', [
            'patients' => User::where('role_id', 'patient')->get(),
            'doctors' => Doctor::with('user')->get(),
            'services' => Service::all(),
        ]);
    }
}
