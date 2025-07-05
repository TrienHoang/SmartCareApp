<?php

use App\Http\Controllers\doctor\DoctorController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorLeaveController;
use App\Http\Controllers\Doctor\FileUploadController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Doctor\DoctorReviewController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;

// ✅ Redirect khi truy cập /doctor/dashboard
Route::middleware(['auth', 'checkRole:doctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/doctor/dashboard', function (Request $request) {
            $doctor = Auth::user();
            return redirect()->route('doctor.dashboard', ['doctor' => $doctor->id]);
        })->name('dashboard');
    });

// ✅ Dashboard và thống kê
Route::prefix('doctor')
    ->middleware(['auth', 'checkRole:doctor'])
    ->name('doctor.dashboard.')
    ->group(function () {
        Route::get('dashboard', function (Request $request) {
            $user = Auth::user();
            $doctorId = optional($user->doctor)->id ?? \App\Models\Doctor::where('user_id', $user->id)->value('id');

            abort_if(!$doctorId, 403, 'Không tìm thấy bác sĩ');
            return app(DoctorDashboardController::class)->index($request, $doctorId);
        })->name('index');
    });

// ✅ Export Excel & PDF
Route::get('doctor/{doctor}/dashboard/export-excel', [DoctorDashboardController::class, 'exportExcel'])->name('doctor.dashboard.stats-excel');
Route::get('doctor/{doctor}/dashboard/export-pdf', [DoctorDashboardController::class, 'exportPDF'])->name('doctor.dashboard.stats-pdf');

// ✅ Nhóm route chính
Route::prefix('doctor')
    ->name('doctor.')
    ->middleware(['auth', 'checkRole:doctor'])
    ->group(function () {

        // 🟩 Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/{doctor}/dashboard/export-excel', [DoctorDashboardController::class, 'exportExcel'])->name('dashboard.stats-excel');
        Route::get('/{doctor}/dashboard/export-pdf', [DoctorDashboardController::class, 'exportPDF'])->name('dashboard.stats-pdf');

        // 🟦 Prescriptions (Đơn thuốc)
        Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
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

        // 🟨 File uploads
        Route::prefix('files')->name('files.')->group(function () {
            Route::get('/', [FileUploadController::class, 'index'])->name('index');
            Route::get('/create', [FileUploadController::class, 'create'])->name('create');
            Route::post('/', [FileUploadController::class, 'store'])->name('store');
            Route::get('/trash', [FileUploadController::class, 'trash'])->name('trash');
            Route::get('/{id}', [FileUploadController::class, 'show'])->name('show');
            Route::get('/{id}/download', [FileUploadController::class, 'download'])->name('download');
            Route::delete('/{id}', [FileUploadController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/update-category', [FileUploadController::class, 'updateCategory'])->name('updateCategory');
            Route::put('/{id}/restore', [FileUploadController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [FileUploadController::class, 'forceDelete'])->name('forceDelete');
            Route::get('/by-appointment/{appointmentId}', [FileUploadController::class, 'getByAppointment'])->name('byAppointment');
        });

        // 🟥 Doctor leaves (Lịch nghỉ phép)
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/', [DoctorLeaveController::class, 'index'])->name('index');
            Route::get('/create', [DoctorLeaveController::class, 'create'])->name('create');
            Route::post('/', [DoctorLeaveController::class, 'store'])->name('store');
            Route::get('/{id}', [DoctorLeaveController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [DoctorLeaveController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorLeaveController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorLeaveController::class, 'destroy'])->name('destroy');
        });
    });

Route::prefix('doctor')
    ->name('doctor.')
    ->middleware(['auth', 'checkRole:doctor'])
    ->group(function () {
        // Danh sách bác sĩ - URL: /doctor
        Route::get('/', [DoctorController::class, 'index'])->name('index');

        // Chi tiết bác sĩ - URL: /doctor/list/{id}
        Route::get('/list/{id}', [DoctorController::class, 'show'])->name('show');
    });


Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/history', [DoctorController::class, 'history'])->name('doctor.history.index');
    Route::get('/doctor/history/{appointment}', [DoctorController::class, 'historyShow'])->name('doctor.history.show');
});


Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {

    // Xem danh sách đánh giá
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // Cập nhật trạng thái hiển thị đánh giá (ẩn / hiện)
    Route::patch('/reviews/{review}/toggle', [ReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
});

        // 🟧 Reviews
        Route::get('/reviews', [DoctorReviewController::class, 'index'])->name('reviews.index');

        // 🟪 Appointments
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
