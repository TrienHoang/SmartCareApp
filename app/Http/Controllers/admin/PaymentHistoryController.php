<?php

// app/Http/Controllers/Admin/PaymentHistoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\PaymentHistory;
use App\Models\Service;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách dịch vụ và bác sĩ để truyền sang view (nếu bạn dùng dropdown filter)
        $services = Service::all();
        $doctors = Doctor::all();

        $query = PaymentHistory::with('payment.appointment.patient', 'payment.appointment.doctor', 'payment.appointment.service')
            ->orderBy('payment_date', 'desc');

        if ($request->filled('patient_name')) {
            $query->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('service_id')) {
            $query->whereHas('payment.appointment.service', function ($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }

        if ($request->filled('doctor_id')) {
            $query->whereHas('payment.appointment.doctor', function ($q) use ($request) {
                $q->where('id', $request->doctor_id);
            });
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        $histories = $query->paginate(20);

        return view('admin.payment_histories.index', compact('histories', 'services', 'doctors'));
    }

    public function show($id)
    {
        $history = PaymentHistory::with('payment.appointment.patient', 'payment.appointment.doctor', 'payment.appointment.service')
            ->findOrFail($id);

        return view('admin.payment_histories.show', compact('history'));
    }
}
