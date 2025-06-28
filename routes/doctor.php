<?php

use App\Http\Controllers\Doctor\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('doctor')
    ->name('doctor.')
    ->middleware('auth', 'checkRole:doctor')
    ->group(function () {
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
