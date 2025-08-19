<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\ServiceCategory;

class ServiceCategoryComposer
{
    public function compose(View $view)
    {
        $danh_muc_dich_vu = ServiceCategory::withCount(['services' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('status', 'active')
            ->with(['services' => function ($query) {
                $query->where('status', 'active')
                    ->select('id', 'service_cate_id', 'department_id', 'name', 'description', 'image', 'price', 'duration', 'status');
            }])
            ->orderByDesc('services_count')
            ->limit(6)
            ->get();

        $view->with('danh_muc_dich_vu', $danh_muc_dich_vu);
    }
}
