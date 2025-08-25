<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentClientController extends Controller
{
    /**
     * Danh sách lịch hẹn của bệnh nhân
     */
    public function index()
    {
        $user = Auth::user();

        $appointments = Appointment::where('patient_id', Auth::id())
            // Ẩn các lịch hẹn đã hủy quá 7 ngày
            ->where(function ($query) {
                $query->whereNull('canceled_at')
                    ->orWhere('canceled_at', '>=', now()->subDays(7));
            })
            // Sắp xếp: lịch chưa hủy trước, lịch đã hủy sau
            ->orderByRaw("CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END ASC")
            // Sắp xếp theo ngày gần nhất
            ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, appointment_time, NOW())) ASC')
            ->get();

        return view('client.appointments.index', compact('appointments', 'user'));
    }



    /**
     * Chi tiết lịch hẹn
     */
    public function show($id)
    {
        $user = Auth::user();
        $appointment = Appointment::where('id', $id)
            ->where('patient_id', Auth::id())
            ->first();

        if (!$appointment) {
            return redirect()->route('client.appointments.index')
                ->with('error', 'Không tìm thấy lịch hẹn.');
        }

        return view('client.appointments.show', compact('appointment', 'user'));
    }

    /**
     * Hủy lịch hẹn + hoàn tiền vào ví
     */
    public function cancel($id)
    {
        DB::beginTransaction();

        try {
            // Lấy lịch hẹn
            $appointment = Appointment::where('id', $id)
                ->where('patient_id', Auth::id())
                ->first();

            if (!$appointment) {
                return redirect()->back()->with('error', 'Không tìm thấy lịch hẹn.');
            }

            // Trạng thái không thể hủy
            $nonCancelableStatuses = ['cancelled', 'completed', 2, 3];
            if (in_array($appointment->status, $nonCancelableStatuses, true)) {
                return redirect()->back()->with('error', 'Lịch đã hoàn tất hoặc đã bị hủy.');
            }

            // Tìm payment liên quan
            $payment = Payment::where('appointment_id', $appointment->id)->first();

            // Lấy số tiền hoàn
            $refundAmount = 0;
            if ($payment && $payment->amount > 0) {
                $refundAmount = $payment->amount;
            } elseif (!empty($appointment->total_amount)) {
                $refundAmount = $appointment->total_amount;
            }

            // Cập nhật trạng thái appointment
            $appointment->status = is_numeric($appointment->status) ? 2 : 'cancelled';
            $appointment->canceled_at = now();
            $appointment->save();


            // Cập nhật trạng thái payment nếu có
            if ($payment) {
                $payment->refund_status = 'completed';
                $payment->save();
            }

            // Hoàn tiền vào ví
            if ($refundAmount > 0) {
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['balance' => 0]
                );

                $wallet->balance += $refundAmount;
                $wallet->save();

                // Lưu lịch sử giao dịch
                WalletTransaction::create([
                    'wallet_id'   => $wallet->id,
                    'type'        => 'refund',
                    'amount'      => $refundAmount,
                    'description' => 'Hoàn tiền khi hủy lịch hẹn #' . $appointment->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Hủy lịch thành công và hoàn tiền vào ví.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi hủy lịch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
