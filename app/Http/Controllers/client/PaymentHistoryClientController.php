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
        $userId = Auth::id();

        $paymentHistories = PaymentHistory::query()
            ->join('payments', 'payment_histories.payment_id', '=', 'payments.id')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->where('appointments.patient_id', $userId)
            ->where('payments.status', 'paid') // ✅ Đúng giá trị
            ->when($request->filled('payment_method'), function ($query) use ($request) {
                $query->where('payment_histories.payment_method', $request->payment_method);
            })
            ->when($request->filled('payment_date'), function ($query) use ($request) {
                $query->whereDate('payment_histories.payment_date', $request->payment_date);
            })
            ->select('payment_histories.*')
            ->orderByDesc('payment_histories.payment_date')
            ->paginate(10);

        return view('client.payment_history.index', compact('paymentHistories'));
    }


    public function show($id)
    {
        $userId = Auth::id();

        $paymentHistory = PaymentHistory::query()
            ->join('payments', 'payment_histories.payment_id', '=', 'payments.id')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('users', 'appointments.patient_id', '=', 'users.id') // user là bệnh nhân
            ->leftJoin('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->leftJoin('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id') // user là bác sĩ
            ->where('appointments.patient_id', $userId)
            ->where('payment_histories.id', $id)
            ->select([
                'payment_histories.*',
                'users.full_name as patient_name',
                'users.email as patient_email',
                'users.phone as patient_phone',
                'doctor_users.full_name as doctor_name',
            ])
            ->firstOrFail();

        return view('client.payment_history.show', compact('paymentHistory'));
    }
}
