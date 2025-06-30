<?php

use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\doctor\FileUploadController;
use App\Http\Controllers\Doctor\PrescriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('doctor')
    ->middleware(['auth'])
    ->name('doctor.dashboard.') // <- THÊM DÒNG NÀY ĐỂ route có prefix tên đúng
    ->group(function () {

        // Route trang dashboard bác sĩ
        Route::get('dashboard', function (Request $request) {
            $user = Auth::user();
            $doctorId = optional($user->doctor)->id ?? \App\Models\Doctor::where('user_id', $user->id)->value('id');

            abort_if(!$doctorId, 403, 'Không tìm thấy bác sĩ');

            return app(DoctorDashboardController::class)->index($request, $doctorId);
        })->name('index');
        // Route export pdf

    });
// Route export excel
Route::get('doctor/{doctor}/dashboard/export-excel', [DoctorDashboardController::class, 'exportExcel'])->name('doctor.dashboard.stats-excel');
// Route export pdf
Route::get('doctor/{doctor}/dashboard/export-pdf', [DoctorDashboardController::class, 'exportPDF'])->name('doctor.dashboard.stats-pdf');
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
                Route::post('/{id}/finalize', [PrescriptionController::class, 'finalize'])->name('finalize');
            });
    });

Route::prefix('doctor')
    ->name('doctor.')
    ->middleware('auth', 'checkRole:doctor')
    ->group(function () {
        Route::prefix('files')
            ->name('files.')
            ->group(function () {
                Route::get('/', [FileUploadController::class, 'index'])->name('index');
            });
    });
