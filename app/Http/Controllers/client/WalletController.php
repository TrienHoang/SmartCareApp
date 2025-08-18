<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $wallet = auth()->user()
            ->wallet()
            ->with(['transactions' => function ($query) {
                $query->whereIn('type', ['withdraw'])->orderBy('created_at', 'desc');
            }])
            ->firstOrCreate(
                ['user_id' => auth()->id()],
                ['balance' => 0]
            );

        return view('client.wallet.index', compact('wallet', 'user'));
    }

    // Rút tiền
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string',
        ], [
            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.numeric'  => 'Số tiền phải là số.',
            'amount.min'      => 'Số tiền tối thiểu là 10.000 VNĐ.',

            'account_holder_name.required' => 'Vui lòng nhập tên chủ tài khoản.',
            'account_holder_name.string'   => 'Tên chủ tài khoản phải là chuỗi ký tự.',
            'account_holder_name.max'      => 'Tên chủ tài khoản không được vượt quá 255 ký tự.',

            'account_number.required' => 'Vui lòng nhập số tài khoản.',
            'account_number.string'   => 'Số tài khoản không hợp lệ.',
            'account_number.max'      => 'Số tài khoản không được vượt quá 255 ký tự.',

            'bank_name.required' => 'Vui lòng nhập tên ngân hàng.',
            'bank_name.string'   => 'Tên ngân hàng không hợp lệ.',
        ]);

        try {
            $this->walletService->deductBalance(
                auth()->id(),
                $request->amount,
                'withdraw',
                'Rút về TK ngân hàng: ' . $request->account_number . ' (' . $request->bank_name . ') - Chủ tài khoản: ' . $request->account_holder_name,
                $request->bank_name,
                'Chờ xử lý',
                $request->account_holder_name,
            );

            return back()->with('success', 'Yêu cầu rút tiền đã được gửi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
