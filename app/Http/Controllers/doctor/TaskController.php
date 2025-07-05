<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Task;

class TaskController extends Controller
{
    public function show(Task $task)
    {
        return view('doctor.tasks.show', compact('task'));
    }
}