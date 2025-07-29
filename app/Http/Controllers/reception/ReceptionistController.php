<?php

namespace App\Http\Controllers\reception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function index()
    {
        $title = 'Bảng điều khiển lễ tân';
        return view('reception.dashboard', compact('title'));
    }
}
