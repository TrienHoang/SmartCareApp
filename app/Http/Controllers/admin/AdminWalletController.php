<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Models\User;

class AdminWalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    // Hiển thị danh sách giao dịch rút tiền
    public function index()
    {
        $transactions = WalletTransaction::where('type', 'withdraw')
            ->with('wallet.user')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.wallet.index', compact('transactions'));
    }

    // Cập nhật trạng thái giao dịch
    public function updateStatus(Request $request, $transactionId)
    {
        $request->validate([
            'status' => 'required|in:Hoàn thành,Không thành công'
        ]);

        $transaction = WalletTransaction::findOrFail($transactionId);

        // Chỉ cho phép cập nhật nếu trạng thái hiện tại là "Chờ xử lý"
        if ($transaction->status !== 'Chờ xử lý') {
            return redirect()->route('admin.wallet.index')->with('error', 'Chỉ có thể cập nhật trạng thái từ "Chờ xử lý" sang "Hoàn thành" hoặc "Không thành công".');
        }

        $newStatus = $request->status;
        $userId = $transaction->wallet->user_id;
        $amount = $transaction->amount;

        // Nếu trạng thái là "Không thành công", cộng tiền lại vào ví
        if ($newStatus === 'Không thành công') {
            $this->walletService->addBalance($userId, $amount, 'refund', 'Hoàn tiền do giao dịch rút tiền không thành công ');
        }

        $transaction->update(['status' => $newStatus]);

        $user = $transaction->wallet->user;
        return redirect()->route('admin.wallet.index')->with('success', 'Cập nhật trạng thái thành công cho giao dịch của ' . $user->name);
    }
}
