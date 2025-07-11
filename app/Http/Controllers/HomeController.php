<?php

namespace App\Http\Controllers;

use App\Models\Department;
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

        return view('client.home', compact('testimonials', 'departments'));
    }
}
