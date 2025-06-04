<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index()
    {
        $schedules = WorkingSchedule::orderBy("id", "desc")->paginate(10);
        // If you want to filter by doctor_id, you can uncomment the following lines
        $doctors = Doctor::all();

        // if (request()->has('day_of_week')) {
        //     $schedules->where('day_of_week', request()->get('day_of_week'));
        // }
        return view("admin.schedules.index", compact("schedules", "doctors"));
    }
    public function create()
    {
        $doctors = Doctor::all();
        return view("admin.schedules.create", compact("doctors"));
    }
    public function store(Request $request)
    {
        $request->validate([
            "doctor_id" => "required|exists:doctors,id",
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        WorkingSchedule::create($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $doctors = Doctor::all();
        return view("admin.schedules.edit", compact("schedule", "doctors"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "doctor_id" => "required|exists:doctors,id",
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = WorkingSchedule::findOrFail($id);
        $schedule->update($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
    public function show($id)
    {
        $schedule = WorkingSchedule::findOrFail($id);
        return view("admin.schedules.show", compact("schedule"));
    }
}
