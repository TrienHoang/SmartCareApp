<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentHistory;

class PaymentHistoryClientController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $userId = Auth::id();

        $paymentHistories = PaymentHistory::with(['payment.appointment'])
            ->whereHas('payment.appointment', function ($query) use ($userId) {
                $query->where('patient_id', $userId);
            })
            ->whereHas('payment', function ($query) {
                $query->whereIn('status', ['paid', 'refunded']);
            })
            ->when($request->filled('payment_method'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->when($request->filled('payment_date'), function ($query) use ($request) {
                $query->whereDate('payment_date', $request->payment_date);
            })
            ->orderByDesc('payment_date')
            ->paginate(10);

        return view('client.payment_history.index', compact('paymentHistories', 'user'));
    }
    public function show($id)
    {
        $user = Auth::user();

        $userId = Auth::id();

        $paymentHistory = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
        ])
            ->whereHas('payment.appointment', function ($query) use ($userId) {
                $query->where('patient_id', $userId);
            })
            ->findOrFail($id);

        $appointment = $paymentHistory->payment->appointment;
        $patient = $appointment->patient;
        $doctorUser = optional($appointment->doctor)->user;

        return view('client.payment_history.show', compact('paymentHistory', 'patient', 'doctorUser', 'user'));
    }
}
