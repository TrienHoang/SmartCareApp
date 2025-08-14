<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    // Xem ví và lịch sử giao dịch
    public function index()
    {
        $wallet = auth()->user()
            ->wallet()
            ->with('transactions')
            ->firstOrCreate(
                ['user_id' => auth()->id()], // điều kiện tìm
                ['balance' => 0]             // nếu tạo mới
            );

        return view('client.wallet.index', compact('wallet'));
    }

    // Rút tiền
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_account' => 'required|string',
            'bank_name' => 'required|string'
        ]);

        $this->walletService->deductBalance(
            auth()->id(),
            $request->amount,
            'withdraw',
            'Rút về TK ngân hàng: ' . $request->bank_account . ' (' . $request->bank_name . ')',
            $request->bank_name,
            'Chờ xử lý'
        );

        return back()->with('success', 'Yêu cầu rút tiền đã được gửi.');
    }

    // Thanh toán từ ví
    public function payWithWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        $this->walletService->deductBalance(
            auth()->id(),
            $request->amount,
            'payment',
            'Thanh toán đặt lịch khám',
            null,
            'Hoàn thành'
        );

        return back()->with('success', 'Thanh toán từ ví thành công.');
    }

    // Hoàn tiền
    public function refund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        $this->walletService->refundBalance(
            auth()->id(),
            $request->amount,
            'Hoàn tiền cho giao dịch trước đó'
        );

        return back()->with('success', 'Hoàn tiền đã được thực hiện.');
    }
}
