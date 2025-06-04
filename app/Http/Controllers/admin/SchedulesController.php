<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index()
    {
        $schedules = WorkingSchedule::orderBy("id", "desc")->paginate(10);
        return view("admin.schedules.index", compact("schedules"));
    }
}
