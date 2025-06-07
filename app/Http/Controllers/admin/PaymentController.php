<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Hiển thị danh sách thanh toán
    public function index(Request $request)
    {
        $query = Payment::with(['appointment', 'promotion']);

        // Lọc theo trạng thái
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo ngày thanh toán
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('paid_at', [$request->from_date, $request->to_date]);
        }

        // Lọc theo mã cuộc hẹn
        if ($request->appointment_id) {
            $query->where('appointment_id', $request->appointment_id);
        }

        // Sắp xếp mới nhất
        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    // Hiển thị chi tiết 1 thanh toán
    public function show(Payment $payment)
    {
        $payment->load(['appointment', 'promotion']);
        return view('admin.payments.show', compact('payment'));
    }
}
