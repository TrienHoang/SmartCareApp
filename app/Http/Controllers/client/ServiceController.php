<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('status', 1)->get();

        $danh_muc_dich_vu = ServiceCategory::withCount(['services' => function ($query) {
            $query->where('status', 'active');
        }])
        ->where('status', 'active')
        ->with(['services' => function ($query) {
            $query->where('status', 'active')
                ->select('id', 'service_cate_id', 'department_id', 'name', 'description', 'image', 'price', 'duration', 'status');
        }])
        ->orderByDesc('services_count') // sắp xếp theo số lượng dịch vụ
        ->limit(6)
        ->get();

        // dd($danh_muc_dich_vu);

        return view('client.services.index', compact('categories','danh_muc_dich_vu'));
    }


    public function show($id)
    {
        $category = ServiceCategory::with(['services' => function ($query) {
            $query->where('status', 1);
        }])->findOrFail($id);

        return view('client.services.show', compact('category'));
    }

    public function detail($id)
    {
        $service = Service::where('status', 1)->findOrFail($id);

        return view('client.services.detail', compact('service'));
    }
}
