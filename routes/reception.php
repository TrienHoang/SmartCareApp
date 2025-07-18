<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\reception\ReceptionistController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'receptionist'])->group(function () {
    Route::get('/receptionist/dashboard', [ReceptionistController::class, 'index'])->name('receptionist.dashboard');
});
