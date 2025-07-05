<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\PaymentHistory;
use App\Models\Service;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

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

        // Tìm theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Lọc theo dịch vụ
        if ($request->filled('service_id')) {
            $query->whereHas('payment.appointment.service', function ($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }

        // Lọc theo bác sĩ ID
        if ($request->filled('doctor_id')) {
            $query->whereHas('payment.appointment.doctor', function ($q) use ($request) {
                $q->where('id', $request->doctor_id);
            });
        }

        // Lọc theo tên bác sĩ
        if ($request->filled('doctor_name')) {
            $query->whereHas('payment.appointment.doctor.user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        // ✅ Kiểm tra logic ngày
        $today = Carbon::today();

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

        // Kết quả phân trang
        $histories = $query->paginate(10)->withQueryString();

        // Thống kê nhanh theo trạng thái
        $statQuery = clone $query;
        $all = $statQuery->get();

        $stat = [
            'paid_count'     => $all->where('payment.status', 'paid')->count(),
            'paid_amount'    => $all->where('payment.status', 'paid')->sum('amount'),
            'pending_count'  => $all->where('payment.status', 'pending')->count(),
            'pending_amount' => $all->where('payment.status', 'pending')->sum('amount'),
            'failed_count'   => $all->where('payment.status', 'failed')->count(),
            'failed_amount'  => $all->where('payment.status', 'failed')->sum('amount'),
            'total_amount'   => $all->sum('amount'),
        ];

        return view('admin.payment_histories.index', compact('histories', 'services', 'doctors', 'stat'));
    }


    public function show($id)
    {
        $history = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor',
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
}
