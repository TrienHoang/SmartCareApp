<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function show($id)
    {
        $doctor = Doctor::with([
            'user',
            'department',
            'educations',
            'experiences',
            'achievements',
            'specialties',
            'reviews.patient'
        ])->findOrFail($id);

        $visibleReviews = $doctor->reviews->where('is_visible', true);

        $averageRating = round($visibleReviews->avg('rating'), 1);
        $reviewCount = $visibleReviews->count();

        $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($visibleReviews) {
            return [$star => $visibleReviews->where('rating', $star)->count()];
        });

        return view('client.doctors_detail', compact(
            'doctor',
            'averageRating',
            'reviewCount',
            'ratingBreakdown'
        ));
    }
}
