<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = config('services.vnpay.return_url');
        $vnp_IpnUrl = config('services.vnpay.ipn_url');

        $vnp_TxnRef = uniqid();
        $vnp_OrderInfo = 'Payment for appointment';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => now()->addMinutes(15)->format('YmdHis'),
            "vnp_IpnUrl" => $vnp_IpnUrl
        ];

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;

        $vnpUrl = $vnp_Url . "?" . http_build_query($inputData);

        return redirect($vnpUrl);
    }

    public function return(Request $request)
    {
        if ($request->vnp_ResponseCode === '00') {
            return view('vnpay.success', [
                'transaction_id' => $request->vnp_TransactionNo,
                'amount' => $request->vnp_Amount / 100,
                'order_id' => $request->vnp_TxnRef
            ]);
        } else {
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
            DB::table('payments')->insert([
                'appointment_id' => null,
                'amount' => $request->vnp_Amount / 100,
                'vnp_txn_ref' => $request->vnp_TxnRef,
                'vnp_transaction_no' => $request->vnp_TransactionNo,
                'vnp_response_code' => $request->vnp_ResponseCode,
                'vnp_secure_hash' => $secureHash,
                'bank_code' => $request->vnp_BankCode,
                'card_type' => $request->vnp_CardType,
                'pay_date' => $request->vnp_PayDate,
                'status' => $request->vnp_ResponseCode === '00' ? 'success' : 'failed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response('IPN OK', 200);
        } else {
            return response('INVALID CHECKSUM', 400);
        }
    }
}
