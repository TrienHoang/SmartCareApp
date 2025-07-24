<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;


class BookingController extends Controller
{

    public function show($service_id)
    {

        $service = Service::with(['category', 'department', 'doctors.user', 'doctors.reviews'])
            ->where('id', $service_id)
            ->firstOrFail();

        $relatedServices = Service::where('department_id', $service->department_id)
            ->where('id', '!=', $service->id)
            ->limit(3)
            ->get();

        return view('client.service_detail', compact('service', 'relatedServices'));
    }

    public function storeService(Request $request)
    {

        $rules = [
            'service_id' => 'required|exists:services,id',
            'doctor_option' => 'required|in:auto,manual',
        ];
        if ($request->doctor_option === 'manual') {
            $rules['doctor_id'] = 'required|exists:doctors,id';
        }

        $validated = $request->validate($rules);

        return redirect()->route('booking.chonNgay');
    }

    public function chonNgay()
    {
        return view('client.booking');
    }
}
