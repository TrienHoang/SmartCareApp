<?php

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Auth;

class PaymentHistories extends Controller
{
    public function index()
    {
        $query = PaymentHistory::where('user_id', Auth::id());

        if (request()->filled('keyword')) {
            $keyword = request()->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('transaction_id', 'like', '%' . $keyword . '%')
                    ->orWhere('amount', 'like', '%' . $keyword . '%');
            });
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('client.payment_histories.index', compact('histories'));
    }
}
