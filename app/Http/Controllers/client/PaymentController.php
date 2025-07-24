<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $appointmentId = $request->input('appointment_id');

        if (!$appointmentId) {
            $appointmentId = DB::table('appointments')->insertGetId([
                'patient_id' => 1,
                'doctor_id' => 1,
                'service_id' => 1,
                'appointment_time' => Carbon::now()->addHours(1),
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = config('services.vnpay.return_url');
        $vnp_IpnUrl = config('services.vnpay.ipn_url');

        $vnp_TxnRef = uniqid();
        $vnp_OrderInfo = 'Payment for appointment ID: ' . $appointmentId;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->input('amount', 10000) * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => Carbon::now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => Carbon::now()->addMinutes(15)->format('YmdHis'),
            "vnp_IpnUrl" => $vnp_IpnUrl,
        ];

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;

        $vnpUrl = $vnp_Url . "?" . http_build_query($inputData);

        DB::table('payments')->insert([
            'appointment_id' => $appointmentId,
            'amount' => $vnp_Amount / 100,
            'vnp_txn_ref' => $vnp_TxnRef,
            'vnp_response_code' => 'pending',
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect($vnpUrl);
    }

    public function return(Request $request)
    {
        if ($request->vnp_ResponseCode === '00') {
            $payment = DB::table('payments')
                ->where('vnp_txn_ref', $request->vnp_TxnRef)
                ->first();

            if ($payment) {
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'vnp_transaction_no' => $request->vnp_TransactionNo,
                        'vnp_response_code' => $request->vnp_ResponseCode,
                        'vnp_secure_hash' => $request->vnp_SecureHash,
                        'bank_code' => $request->vnp_BankCode,
                        'card_type' => $request->vnp_CardType,
                        'pay_date' => $request->vnp_PayDate,
                        'status' => 'success',
                        'updated_at' => Carbon::now(),
                    ]);

                DB::table('appointments')
                    ->where('id', $payment->appointment_id)
                    ->update(['status' => 'confirmed']);
            }

            return view('vnpay.success', [
                'transaction_id' => $request->vnp_TransactionNo,
                'amount' => $request->vnp_Amount / 100,
                'order_id' => $payment->appointment_id ?? $request->vnp_TxnRef,
            ]);
        } else {
            $payment = DB::table('payments')
                ->where('vnp_txn_ref', $request->vnp_TxnRef)
                ->first();

            if ($payment) {
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'vnp_transaction_no' => $request->vnp_TransactionNo,
                        'vnp_response_code' => $request->vnp_ResponseCode,
                        'vnp_secure_hash' => $request->vnp_SecureHash,
                        'status' => 'failed',
                        'updated_at' => Carbon::now(),
                    ]);
            }

            return view('vnpay.fail');
        }
    }

    public function ipn(Request $request)
    {
        $secureHash = $request->input('vnp_SecureHash');
        $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $checkHash = hash_hmac('sha512', $hashData, config('services.vnpay.hash_secret'));

        if ($secureHash === $checkHash) {
            $payment = DB::table('payments')
                ->where('vnp_txn_ref', $request->vnp_TxnRef)
                ->first();

            if ($payment) {
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'appointment_id' => $payment->appointment_id,
                        'amount' => $request->vnp_Amount / 100,
                        'vnp_txn_ref' => $request->vnp_TxnRef,
                        'vnp_transaction_no' => $request->vnp_TransactionNo,
                        'vnp_response_code' => $request->vnp_ResponseCode,
                        'vnp_secure_hash' => $secureHash,
                        'bank_code' => $request->vnp_BankCode,
                        'card_type' => $request->vnp_CardType,
                        'pay_date' => $request->vnp_PayDate,
                        'status' => $request->vnp_ResponseCode === '00' ? 'success' : 'failed',
                        'updated_at' => Carbon::now(),
                    ]);
            }
            return response('IPN OK', 200);
        } else {
            return response('INVALID CHECKSUM', 400);
        }
    }
}
