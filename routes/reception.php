<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Reception\ReceptionAppointmentController;
use App\Http\Controllers\reception\ReceptionistController;
use App\Http\Controllers\Reception\WorkingScheduleController;

// ✅ Dashboard và thống kê
Route::prefix('receptionist')
    ->middleware(['auth', 'checkRole:receptionist'])
    ->name('receptionist.')
    ->group(function () {
        Route::get('dashboard', function (Request $request) {
            $user = Auth::user();
            abort_if(!$user, 403, 'Bạn chưa đăng nhập');
            return app(ReceptionistController::class)->index($request, $user->id);
        })->name('dashboard');
    });

// ✅ Nhóm route chính
Route::prefix('receptionist')
    ->middleware(['auth', 'checkRole:receptionist'])
    ->name('receptionist.')
    ->group(function () {

        // 🟩 Appointments (Quản lý lịch hẹn)
        Route::prefix('appointments')->name('appointments.')->group(function () {
            // Danh sách lịch hẹn
            Route::get('/', [ReceptionAppointmentController::class, 'index'])->name('index');

            Route::get('/doctor/{doctor}/available-slots', [ReceptionAppointmentController::class, 'getAvailableSlots'])
                ->name('doctor.available-slots');

            Route::get('/create', [ReceptionAppointmentController::class, 'create'])->name('create');
            Route::post('/store', [ReceptionAppointmentController::class, 'store'])->name('store');

            Route::get('/{id}', [ReceptionAppointmentController::class, 'show'])->name('show');

            Route::get('/edit/{id}', [ReceptionAppointmentController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [ReceptionAppointmentController::class, 'update'])->name('update');

            // Cập nhật trạng thái (xác nhận, hoàn thành)
            Route::patch('/{id}/update-status', [ReceptionAppointmentController::class, 'updateStatus'])->name('update-status');

            Route::patch('/{id}/cancel', [ReceptionAppointmentController::class, 'cancel'])->name('cancel');

            Route::post('/{id}/pay', [ReceptionAppointmentController::class, 'pay'])->name('pay');

            Route::patch('/{id}/confirm-payment', [ReceptionAppointmentController::class, 'confirmPayment'])->name('confirm-payment');

            Route::get('/patients/search', [ReceptionAppointmentController::class, 'searchPatients'])->name('patients.search');

            Route::get('/doctor/{doctor}/services', [ReceptionAppointmentController::class, 'getDoctorServices'])->name('doctor.services');

            Route::get('/doctor/{doctor}/working-days', [ReceptionAppointmentController::class, 'getDoctorWorkingDays'])->name('doctor.working-days');

            Route::get('/services/{service}/doctors', [ReceptionAppointmentController::class, 'getDoctorsByService'])->name('services.doctors');
        });

        // Lịch làm việc của bác sĩ
        Route::prefix('doctors')->name('doctors.')->group(function () {
            // Trang chọn ngày
            Route::get('/working-schedule', [WorkingScheduleController::class, 'index'])
                ->name('index');

            // Trang hiển thị lịch làm việc theo ngày
            Route::get('/working-schedule/{date}', [WorkingScheduleController::class, 'show'])
                ->name('show');
        });
    });
