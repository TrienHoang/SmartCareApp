<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Review::where('is_visible', 1)
            ->with(['patient', 'doctor'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $departments = Department::all();

        $doctors = Doctor::whereHas('user', function ($query) {
            $query->where('role_id', 2);
        })
            ->with(['user', 'department'])
            ->get();
        return view('client.home', compact('testimonials', 'departments', 'doctors'));
    }
}
