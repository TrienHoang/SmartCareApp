<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\PaymentHistory;
use App\Models\Service;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách dịch vụ và bác sĩ
        $services = Service::all();
        $doctors = Doctor::with('user')->get(); // Đảm bảo lấy cả thông tin user của bác sĩ

        // Khởi tạo query với eager loading
        $query = PaymentHistory::with([
            'payment.appointment.patient',
            'payment.appointment.doctor.user',
            'payment.appointment.service'
        ])->orderBy('payment_date', 'desc');

        // Lọc theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('payment.appointment.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // Lọc theo dịch vụ
        if ($request->filled('service_id')) {
            $query->whereHas('payment.appointment.service', function ($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }

        // Lọc theo bác sĩ
        if ($request->filled('doctor_id')) {
            $query->whereHas('payment.appointment.doctor', function ($q) use ($request) {
                $q->where('id', $request->doctor_id);
            });
        }

        // Lọc theo khoảng ngày
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Phân trang và giữ các tham số lọc khi chuyển trang
        $histories = $query->paginate(4)->withQueryString();

        return view('admin.payment_histories.index', compact('histories', 'services', 'doctors'));
    }


    public function show($id)
    {
        $history = PaymentHistory::with('payment.appointment.patient', 'payment.appointment.doctor', 'payment.appointment.service')
            ->findOrFail($id);

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

        $pdf = \Barryvdh\DomPDF\Facade\pdf::loadView('pdf.payment_history_detail', compact('history'));

        return $pdf->download("payment_history_detail_{$history->id}.pdf");
    }
}
