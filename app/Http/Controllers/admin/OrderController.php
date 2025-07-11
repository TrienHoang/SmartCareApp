<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->start_date && $request->end_date && $request->end_date < $request->start_date) {
        return back()->withInput()->with('error', 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.');
    }
        $orders = Order::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->whereDate('ordered_at', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('ordered_at', '<=', $request->end_date))
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('services', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,paid,completed,cancelled']);

        $current = $order->status;
        $target = $request->status;

        $allowedTransitions = [
            'pending' => ['paid', 'cancelled'],
            'paid' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($target, $allowedTransitions[$current])) {
            return back()->with('error', 'Chuyển trạng thái không hợp lệ!');
        }

        $order->update([
            'status' => $target,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }




    public function exportPdf(Order $order)
    {
        $order->load('user', 'services');

        $pdf = Pdf::loadView('admin.orders.pdf', compact('order'));
        return $pdf->download('order-' . $order->id . '.pdf');
    }

}
