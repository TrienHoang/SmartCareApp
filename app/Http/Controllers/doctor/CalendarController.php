<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class CalendarController extends Controller
{
    public function index()
    {
        return view('doctor.calendar.index');
    }

    public function events(Request $request)
    {
        Log::info('Yêu cầu sự kiện lịch bác sĩ:', [
            'doctor_id' => Auth::id(),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);

        if (!Auth::check()) {
            Log::error('Không tìm thấy người dùng đã xác thực');
            return response()->json(['error' => 'Người dùng chưa đăng nhập'], 401);
        }

        $start = $request->input('start');
        $end = $request->input('end');
        $doctorId = Auth::id();

        // Lấy công việc của bác sĩ
        $tasksQuery = Task::query()
            ->select('id', 'title', 'deadline')
            ->whereNotNull('deadline')
            ->where('assigned_to', $doctorId);

        if ($start && $end) {
            $tasksQuery->whereBetween('deadline', [$start, $end]);
        }

        Log::info('Truy vấn SQL công việc:', [
            'sql' => $tasksQuery->toSql(),
            'bindings' => $tasksQuery->getBindings()
        ]);

        $tasks = $tasksQuery->get();

        Log::info('Công việc tìm thấy:', [
            'count' => $tasks->count(),
            'tasks' => $tasks->toArray()
        ]);

        $taskEvents = $tasks->map(function ($task) {
            // Kiểm tra xem tuyến đường có tồn tại không
            $taskUrl = Route::has('doctor.tasks.show') ? route('doctor.tasks.show', $task->id) : '#';
            return [
                'id' => 'task_' . $task->id,
                'title' => '🗂️ ' . $task->title,
                'start' => $task->deadline,
                'color' => '#0d6efd',
                'url' => $taskUrl,
            ];
        });

        // Lấy lịch hẹn của bác sĩ
        $appointmentsQuery = Appointment::query()
            ->select('id', 'appointment_time', 'patient_id', 'service_id')
            ->where('doctor_id', $doctorId);

        if ($start && $end) {
            $appointmentsQuery->whereBetween('appointment_time', [$start, $end]);
        }

        Log::info('Truy vấn SQL lịch hẹn:', [
            'sql' => $appointmentsQuery->toSql(),
            'bindings' => $appointmentsQuery->getBindings()
        ]);

        $appointments = $appointmentsQuery->get();

        Log::info('Lịch hẹn tìm thấy:', [
            'count' => $appointments->count(),
            'appointments' => $appointments->toArray()
        ]);

        $appointmentEvents = $appointments->map(function ($appt) {
            // Kiểm tra xem tuyến đường có tồn tại không
            $apptUrl = Route::has('doctor.appointments.show') ? route('doctor.appointments.show', $appt->id) : '#';
            return [
                'id' => 'appt_' . $appt->id,
                'title' => '🩺 Lịch khám #' . $appt->id,
                'start' => $appt->appointment_time,
                'color' => '#198754',
                'url' => $apptUrl,
            ];
        });

        $finalEvents = $taskEvents->merge($appointmentEvents);

        Log::info('Sự kiện cuối cùng:', [
            'count' => $finalEvents->count(),
            'events' => $finalEvents->toArray()
        ]);

        return response()->json($finalEvents);
    }

    public function testDatabase()
    {
        try {
            DB::connection()->getPdo();
            $doctorId = Auth::id();

            $tasksCount = Task::where('assigned_to', $doctorId)->count();
            $tasksSample = Task::where('assigned_to', $doctorId)->limit(5)->get();

            $appointmentsCount = Appointment::where('doctor_id', $doctorId)->count();
            $appointmentsSample = Appointment::where('doctor_id', $doctorId)->limit(5)->get();

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
                'error' => 'Kết nối cơ sở dữ liệu thất bại',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}