<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;


class BookingController extends Controller {



    public function chonNgay( $service_id)
    {
        // Lấy thông tin dịch vụ theo ID
        $service = Service::findOrFail($service_id);
    
        return view('client.service_detail', compact('service'));
    }
}