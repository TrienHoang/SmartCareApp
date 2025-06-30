<?php

use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\PrescriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route cho trang dashboard bác sĩ
// Group toàn bộ route cho bác sĩ
Route::prefix('doctor')
    ->middleware(['auth', 'checkRole:doctor'])
    ->name('doctor.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/{doctor}/dashboard/export-excel', [DoctorDashboardController::class, 'exportExcel'])->name('dashboard.stats-excel');
        Route::get('/{doctor}/dashboard/export-pdf', [DoctorDashboardController::class, 'exportPDF'])->name('dashboard.stats-pdf');

        // Prescriptions (Đơn thuốc)
        Route::prefix('prescriptions')
            ->name('prescriptions.')
            ->group(function () {
                Route::get('/', [PrescriptionController::class, 'index'])->name('index');
                Route::get('/create', [PrescriptionController::class, 'create'])->name('create');
                Route::post('/', [PrescriptionController::class, 'store'])->name('store');

                Route::get('/search-medical-records', [PrescriptionController::class, 'searchMedicalRecords'])->name('searchMedicalRecords');
                Route::get('/search-medicines', [PrescriptionController::class, 'searchMedicines'])->name('searchMedicines');

                Route::get('/{id}', [PrescriptionController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [PrescriptionController::class, 'edit'])->name('edit');
                Route::put('/{id}', [PrescriptionController::class, 'update'])->name('update');

                Route::get('/{id}/export-pdf', [PrescriptionController::class, 'exportPdf'])->name('exportPdf');
            });
    });
