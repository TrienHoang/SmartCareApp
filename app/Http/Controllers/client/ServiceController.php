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

        return view('client.services.index', compact('categories'   ));
    }


    public function show($id)
    {
    $category = ServiceCategory::with(['services' => function ($query) {
        $query->where('status', 1)
        ->withAvg('reviews', 'rating'); // 👈 lấy tổng số sao
    }])->findOrFail($id);

        return view('client.services.show', compact('category'));
    }

    public function detail($id)
    {
        $service = Service::where('status', 1)->findOrFail($id);

        return view('client.services.detail', compact('service'));
    }
}
