<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $users = User::all();

        return view('admin.calendar.index', compact('departments', 'users'));
    }

public function events(Request $request)
{
    $type = $request->input('type');
    $departmentId = $request->input('department_id');
    $userId = $request->input('user_id');

    $start = $request->input('start');
    $end = $request->input('end');

    $taskEvents = collect();
    $appointmentEvents = collect();

    // Lấy công việc
    if (!$type || $type === 'task') {
        $tasks = Task::query()
            ->select('id', 'title', 'deadline', 'department_id', 'assigned_to')
            ->whereNotNull('deadline');

        if ($start && $end) {
            $tasks->whereBetween('deadline', [$start, $end]);
        }

        if ($departmentId) {
            $tasks->where('department_id', $departmentId);
        }

        if ($userId) {
            $tasks->where('assigned_to', $userId);
        }

        $taskEvents = $tasks->get()->map(function ($task) {
            return [
                'id' => 'task_' . $task->id,
                'title' => '🗂️ ' . $task->title,
                'start' => $task->deadline,
                'color' => '#0d6efd',
                'url' => route('admin.tasks.show', $task->id),
            ];
        });
    }

    // Lấy cuộc hẹn
    if (!$type || $type === 'appointment') {
        $appointments = Appointment::query()
            ->select('id', 'title', 'start_time', 'department_id', 'doctor_id');

        if ($start && $end) {
            $appointments->whereBetween('start_time', [$start, $end]);
        }

        if ($departmentId) {
            $appointments->where('department_id', $departmentId);
        }

        if ($userId) {
            $appointments->where('doctor_id', $userId);
        }

        $appointmentEvents = $appointments->get()->map(function ($appt) {
            return [
                'id' => 'appt_' . $appt->id,
                'title' => '🩺 ' . $appt->title,
                'start' => $appt->start_time,
                'color' => '#198754',
                'url' => route('admin.appointments.show', $appt->id),
            ];
        });
    }

    return response()->json($taskEvents->merge($appointmentEvents));
}


}
