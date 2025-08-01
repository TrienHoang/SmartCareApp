<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\Service;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::all();
        $doctors = Doctor::with('user')->get();

        $query = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.service'
        ])->orderBy('payment_date', 'desc');

        // ==== Các bộ lọc ====
        if ($request->filled('patient_name')) {
            $query->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
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

        if ($request->filled('doctor_name')) {
            $query->whereHas('payment.appointment.doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // ✅ Bổ sung: lọc theo trạng thái hoàn tiền
        if ($request->filled('refund_status')) {
            $query->where('refund_status', $request->refund_status);
        }

        // ==== Lọc ngày ====
        $today = now()->startOfDay();

        if ($request->filled('date_from') && $request->filled('date_to')) {
            if ($request->date_from > $request->date_to) {
                return back()->withInput()->with('error', '⚠️ Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.');
            }

            if ($request->date_from > $today || $request->date_to > $today) {
                return back()->withInput()->with('error', '⚠️ Ngày không được vượt quá ngày hiện tại.');
            }

            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            if ($request->date_from > $today) {
                return back()->withInput()->with('error', '⚠️ Ngày bắt đầu không được vượt quá ngày hiện tại.');
            }
            $query->whereDate('payment_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            if ($request->date_to > $today) {
                return back()->withInput()->with('error', '⚠️ Ngày kết thúc không được vượt quá ngày hiện tại.');
            }
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // ==== Phân trang ====
        $histories = $query->paginate(10)->withQueryString();

        // ==== Thống kê nhanh ====
        $statQuery = PaymentHistory::with('payment');

        // Apply lại các filter vào $statQuery
        if ($request->filled('patient_name')) {
            $statQuery->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('service_id')) {
            $statQuery->whereHas('payment.appointment.service', function ($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }

        if ($request->filled('doctor_id')) {
            $statQuery->whereHas('payment.appointment.doctor', function ($q) use ($request) {
                $q->where('id', $request->doctor_id);
            });
        }

        if ($request->filled('payment_method')) {
            $statQuery->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $statQuery->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $statQuery->whereDate('payment_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $statQuery->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->filled('refund_status')) {
            $statQuery->where('refund_status', $request->refund_status);
        }


        $all = $statQuery->get();

        $stat = [
            'paid_count'       => $all->where('payment.status', 'paid')->count(),
            'paid_amount'      => $all->where('payment.status', 'paid')->sum('amount'),
            'pending_count'    => $all->where('payment.status', 'pending')->count(),
            'pending_amount'   => $all->where('payment.status', 'pending')->sum('amount'),
            'failed_count'     => $all->where('payment.status', 'failed')->count(),
            'failed_amount'    => $all->where('payment.status', 'failed')->sum('amount'),
            'refunded_count'   => $all->where('payment.status', 'refunded')->count(),
            'refunded_amount'  => $all->where('payment.status', 'refunded')->sum('amount'),
            'refund_completed_count'  => $all->where('refund_status', 'completed')->count(),
            'refund_completed_amount' => $all->where('refund_status', 'completed')->sum('amount'),
            'total_amount'     => $all->sum('amount'),
            'vnpay_count'      => $all->where('payment_method', 'VNPAY')->count(),
            'vnpay_amount'     => $all->where('payment_method', 'VNPAY')->sum('amount'),
        ];

        // Lấy danh sách method để render select
        $paymentMethods = PaymentHistory::distinct()->pluck('payment_method')->filter()->toArray();

        // Danh sách trạng thái hoàn tiền để render dropdown
        $refundStatuses = ['none', 'pending', 'completed', 'failed'];

        return view('admin.payment_histories.index', compact(
            'histories',
            'services',
            'doctors',
            'stat',
            'paymentMethods',
            'refundStatuses'
        ));
    }


    public function show($id)
    {
        $history = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.service'
        ])->findOrFail($id);

        return view('admin.payment_histories.show', compact('history'));
    }

    public function exportDetailPdf($id)
    {
        $history = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.doctor.department',
            'payment.appointment.service',
            'payment.promotion',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.payment_history_detail', compact('history'));

        return $pdf->download("payment_history_detail_{$history->id}.pdf");
    }

    // ✅ BỔ SUNG: VNPAY Callback Handler (FIX thiếu đổi trạng thái thanh toán)
    public function vnpayCallback(Request $request)
    {
        try {
            Log::info('VNPAY Callback received', $request->all());

            $vnp_HashSecret = config('vnpay.hash_secret');
            $vnp_SecureHash = $request->vnp_SecureHash;

            // Verify VNPAY signature
            if (!$this->verifyVnpaySignature($request->all(), $vnp_HashSecret, $vnp_SecureHash)) {
                Log::error('VNPAY signature verification failed');
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            // Find payment by transaction reference
            $payment = Payment::where('vnp_txn_ref', $request->vnp_TxnRef)->first();

            if (!$payment) {
                Log::error('Payment not found for vnp_TxnRef: ' . $request->vnp_TxnRef);
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            DB::beginTransaction();

            try {
                // Update payment status based on VNPAY response
                $newStatus = $request->vnp_ResponseCode == '00' ? 'paid' : 'failed';

                $payment->update([
                    'status' => $newStatus,
                    'vnp_response_code' => $request->vnp_ResponseCode,
                    'vnp_transaction_no' => $request->vnp_TransactionNo ?? null,
                    'paid_at' => $newStatus == 'paid' ? now() : null,
                ]);

                // ✅ FIX: Ghi lịch sử thanh toán khi hoàn thành
                $this->createPaymentHistory($payment, $request);

                DB::commit();

                Log::info('Payment updated successfully', [
                    'payment_id' => $payment->id,
                    'status' => $newStatus,
                    'vnp_response_code' => $request->vnp_ResponseCode
                ]);

                return response()->json(['RspCode' => '00', 'Message' => 'Success']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error updating payment: ' . $e->getMessage());
                return response()->json(['RspCode' => '99', 'Message' => 'System error']);
            }
        } catch (\Exception $e) {
            Log::error('VNPAY Callback error: ' . $e->getMessage());
            return response()->json(['RspCode' => '99', 'Message' => 'System error']);
        }
    }

    // ✅ BỔ SUNG: VNPAY Return URL Handler (cho user redirect)
    public function vnpayReturn(Request $request)
    {
        try {
            $vnp_HashSecret = config('vnpay.hash_secret');
            $vnp_SecureHash = $request->vnp_SecureHash;

            if (!$this->verifyVnpaySignature($request->all(), $vnp_HashSecret, $vnp_SecureHash)) {
                return redirect()->route('payment.error')->with('error', 'Xác thực thanh toán không hợp lệ');
            }

            $payment = Payment::where('vnp_txn_ref', $request->vnp_TxnRef)->first();

            if (!$payment) {
                return redirect()->route('payment.error')->with('error', 'Không tìm thấy thông tin thanh toán');
            }

            if ($request->vnp_ResponseCode == '00') {
                return redirect()->route('payment.success')->with('success', 'Thanh toán thành công');
            } else {
                return redirect()->route('payment.error')->with('error', 'Thanh toán thất bại: ' . $this->getVnpayResponseMessage($request->vnp_ResponseCode));
            }
        } catch (\Exception $e) {
            Log::error('VNPAY Return error: ' . $e->getMessage());
            return redirect()->route('payment.error')->with('error', 'Có lỗi xảy ra trong quá trình xử lý');
        }
    }

    // ✅ BỔ SUNG: Tạo lịch sử thanh toán
    private function createPaymentHistory($payment, $request)
    {
        try {
            PaymentHistory::create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'payment_method' => 'VNPAY',
                'payment_date' => now(),
            ]);

            Log::info('Payment history created for payment_id: ' . $payment->id);
        } catch (\Exception $e) {
            Log::error('Error creating payment history: ' . $e->getMessage());
            throw $e;
        }
    }

    // ✅ BỔ SUNG: Verify VNPAY signature
    private function verifyVnpaySignature($data, $hashSecret, $secureHash)
    {
        $vnp_Url = $data;
        unset($vnp_Url['vnp_SecureHash']);
        ksort($vnp_Url);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($vnp_Url as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $query;
        if (isset($hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $hashSecret);
            return $vnpSecureHash === $secureHash;
        }
        return false;
    }

    // ✅ BỔ SUNG: Get VNPAY response message
    private function getVnpayResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    // ✅ BỔ SUNG: Manual sync payment status (cho admin)
    public function syncPaymentStatus($id)
    {
        try {
            $history = PaymentHistory::with('payment')->findOrFail($id);
            $payment = $history->payment;

            if ($payment->status === 'pending' && $payment->vnp_txn_ref) {
                // Có thể gọi API query transaction status từ VNPAY
                // Tạm thời update manual
                $payment->update(['status' => 'paid', 'paid_at' => now()]);

                return response()->json(['success' => true, 'message' => 'Đã cập nhật trạng thái thanh toán']);
            }

            return response()->json(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
        } catch (\Exception $e) {
            Log::error('Error syncing payment status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    // ✅ BỔ SUNG: Export filtered results to PDF
    public function exportPdf(Request $request)
    {
        $query = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.service'
        ])->orderBy('payment_date', 'desc');

        // Apply same filters as index method
        if ($request->filled('patient_name')) {
            $query->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        $histories = $query->get();

        $pdf = Pdf::loadView('pdf.payment_histories', compact('histories'));

        return $pdf->download('payment_histories_' . date('Y-m-d') . '.pdf');
    }

    // ✅ BỔ SUNG: Search method (nếu khác với index)
    public function search(Request $request)
    {
        // Có thể redirect về index với parameters
        return redirect()->route('payment_histories.index', $request->all());
    }

    // ✅ BỔ SUNG: Bulk export
    public function bulkExport(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một bản ghi để xuất');
        }

        $histories = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.service'
        ])->whereIn('id', $ids)->get();

        $pdf = Pdf::loadView('pdf.payment_histories_bulk', compact('histories'));

        return $pdf->download('payment_histories_bulk_' . date('Y-m-d') . '.pdf');
    }

    // ✅ BỔ SUNG: Get statistics for API
    public function getStats(Request $request)
    {
        $query = PaymentHistory::with('payment');

        // Apply filters...
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        $data = $query->get();

        return response()->json([
            'total_records' => $data->count(),
            'total_amount' => $data->sum('amount'),
            'paid_amount' => $data->where('payment.status', 'paid')->sum('amount'),
            'pending_amount' => $data->where('payment.status', 'pending')->sum('amount'),
            'vnpay_amount' => $data->where('payment_method', 'VNPAY')->sum('amount'),
        ]);
    }

    // ✅ BỔ SUNG: Get chart data for dashboard
    public function getChartData(Request $request)
    {
        $period = $request->input('period', '7days');

        switch ($period) {
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                $groupBy = 'DATE(payment_date)';
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                $groupBy = 'DATE(payment_date)';
                break;
            case '12months':
                $startDate = Carbon::now()->subMonths(12);
                $groupBy = 'DATE_FORMAT(payment_date, "%Y-%m")';
                break;
            default:
                $startDate = Carbon::now()->subDays(7);
                $groupBy = 'DATE(payment_date)';
        }

        $data = PaymentHistory::selectRaw("
            {$groupBy} as period,
            COUNT(*) as total_transactions,
            SUM(amount) as total_amount,
            SUM(CASE WHEN payment_method = 'VNPAY' THEN amount ELSE 0 END) as vnpay_amount
        ")
            ->where('payment_date', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return response()->json($data);
    }
}
