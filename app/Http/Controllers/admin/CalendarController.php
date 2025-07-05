<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    Log::info('Calendar Events Request:', [
        'type' => $request->input('type'),
        'department_id' => $request->input('department_id'),
        'user_id' => $request->input('user_id'),
        'start' => $request->input('start'),
        'end' => $request->input('end'),
    ]);

    $type = $request->input('type');
    $departmentId = $request->input('department_id');
    $userId = $request->input('user_id');
    $start = $request->input('start');
    $end = $request->input('end');

    $taskEvents = collect();
    $appointmentEvents = collect();

    // Lấy công việc
    if (!$type || $type === 'task') {
        $tasksQuery = Task::query()
            ->select('id', 'title', 'deadline', 'department_id', 'assigned_to')
            ->whereNotNull('deadline');

        if ($start && $end) {
            $tasksQuery->whereBetween('deadline', [$start, $end]);
        }

        if ($departmentId) {
            $tasksQuery->where('department_id', $departmentId);
        }

        if ($userId) {
            $tasksQuery->where('assigned_to', $userId);
        }

        Log::info('Tasks SQL Query:', [
            'sql' => $tasksQuery->toSql(),
            'bindings' => $tasksQuery->getBindings()
        ]);

        $tasks = $tasksQuery->get();
        
        Log::info('Tasks found:', [
            'count' => $tasks->count(),
            'tasks' => $tasks->toArray()
        ]);

        $taskEvents = $tasks->map(function ($task) {
            return [
                'id' => 'task_' . $task->id,
                'title' => '🗂️ ' . $task->title,
                'start' => $task->deadline,
                'color' => '#0d6efd',
                'url' => route('admin.tasks.show', $task->id),
            ];
        });
    }

    // Lấy lịch hẹn
    if (!$type || $type === 'appointment') {
        $appointmentsQuery = Appointment::query()
            ->select('id', 'appointment_time', 'doctor_id', 'patient_id', 'service_id');

        if ($start && $end) {
            $appointmentsQuery->whereBetween('appointment_time', [$start, $end]);
        }

        if ($departmentId) {
            // Nếu bạn có cột department_id thì giữ lại, nếu không thì bỏ
            $appointmentsQuery->where('doctor_id', $departmentId); // hoặc sửa theo business logic
        }

        if ($userId) {
            $appointmentsQuery->where('doctor_id', $userId);
        }

        Log::info('Appointments SQL Query:', [
            'sql' => $appointmentsQuery->toSql(),
            'bindings' => $appointmentsQuery->getBindings()
        ]);

        $appointments = $appointmentsQuery->get();
        
        Log::info('Appointments found:', [
            'count' => $appointments->count(),
            'appointments' => $appointments->toArray()
        ]);

        $appointmentEvents = $appointments->map(function ($appt) {
            return [
                'id' => 'appt_' . $appt->id,
                'title' => '🩺 Lịch khám #' . $appt->id,
                'start' => $appt->appointment_time,
                'color' => '#198754',
                'url' => route('admin.appointments.show', $appt->id),
            ];
        });
    }

    $finalEvents = $taskEvents->merge($appointmentEvents);

    Log::info('Final Events:', [
        'count' => $finalEvents->count(),
        'events' => $finalEvents->toArray()
    ]);

    return response()->json($finalEvents);
}


// Thêm method để test database connection
public function testDatabase()
{
    try {
        // Test connection
        DB::connection()->getPdo();
        
        // Test Tasks table
        $tasksCount = DB::table('tasks')->count();
        $tasksSample = DB::table('tasks')->limit(5)->get();
        
        // Test Appointments table
        $appointmentsCount = DB::table('appointments')->count();
        $appointmentsSample = DB::table('appointments')->limit(5)->get();
        
        return response()->json([
            'database_connection' => 'OK',
            'tasks' => [
                'count' => $tasksCount,
                'sample' => $tasksSample,
                'table_structure' => DB::select('DESCRIBE tasks')
            ],
            'appointments' => [
                'count' => $appointmentsCount,
                'sample' => $appointmentsSample,
                'table_structure' => DB::select('DESCRIBE appointments')
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Database connection failed',
            'message' => $e->getMessage()
        ], 500);
    }
}


}
