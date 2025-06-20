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

        if ($order->status === 'completed' && $request->status === 'cancelled') {
            return back()->with('error', 'Không thể huỷ đơn đã hoàn tất.');
        }

        $order->update([
            'status' => $request->status,
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
