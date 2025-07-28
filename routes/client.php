<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\client\PrescriptionClientController;
use App\Http\Controllers\client\ClientFileController;
use App\Http\Controllers\Client\PaymentHistoryClientController; // Đúng namespace, đúng chữ hoa/thường
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AppointmentClientController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', function () {
    return view('client.about');
})->name('about');

Route::get('/dich-vu', function () {
    return view('client.services');
})->name('services');

Route::get('/dat-lich', function () {
    return view('client.booking');
})->name('booking');

Route::get('/lien-he', function () {
    return view('client.contact');
})->name('contact');

Route::get('/tin-tuc', function () {
    return view('client.news');
})->name('news');

Route::get('/chi-tiet-tin-tuc/{id}', function ($id) {
    return view('client.news_detail', ['id' => $id]);
})->name('news_detail');

Route::get('/thong-tin-ca-nhan', function () {
    return view('client.profile');
})->name('profile');

Route::prefix('client/uploads')->name('client.uploads.')->middleware(['auth'])->group(function () {
    Route::get('/', [ClientFileController::class, 'index'])->name('index');
    Route::get('/create', [ClientFileController::class, 'create'])->name('create');
    Route::post('/store', [ClientFileController::class, 'store'])->name('store');
    Route::get('/download/{id}', [ClientFileController::class, 'download'])->name('download');
});

Route::prefix('client/payment_history')->name('client.payment_history.')->middleware(['auth'])->group(function () {
    Route::get('/', [PaymentHistoryClientController::class, 'index'])->name('index');
    Route::get('/{id}', [PaymentHistoryClientController::class, 'show'])->name('show');
});
Route::prefix('client/payment')->name('client.payment.')->middleware(['auth'])->group(function () {
    Route::get('/', [PaymentController::class, 'create'])->name('create');
    Route::get('/return', [PaymentController::class, 'return'])->name('return');
    Route::get('/ipn', [PaymentController::class, 'ipn'])->name('ipn');
});


Route::prefix('client/prescriptions')->name('client.prescriptions.')->middleware(['auth'])->group(function () {
    Route::get('/', [PrescriptionClientController::class, 'index'])->name('index');
    Route::get('/{id}', [PrescriptionClientController::class, 'show'])->name('show');
});

Route::prefix('client/appointments')->name('client.appointments.')->middleware(['auth'])->group(function () {
    Route::get('/', [AppointmentClientController::class, 'index'])->name('index'); // Danh sách lịch hẹn
    // Sửa tên tham số từ {id} thành {appointment} để Route Model Binding hoạt động
    Route::get('/{appointment}', [AppointmentClientController::class, 'show'])->name('show'); // Chi tiết lịch hẹn
    Route::get('/{appointment}/edit', [AppointmentClientController::class, 'edit'])->name('edit'); // Sửa lịch hẹn
    Route::put('/{appointment}', [AppointmentClientController::class, 'update'])->name('update'); // Cập nhật
    Route::delete('/{appointment}', [AppointmentClientController::class, 'destroy'])->name('destroy'); // Hủy lịch hẹn
});
// Sửa lại routes của bạn như sau:

Route::prefix('client/notifications')->middleware('auth')->name('client.notifications.')->group(function () {
    Route::get('/', [ClientNotificationController::class, 'index'])->name('index');
    
    // 👇 Các route tĩnh (string) phải để TRƯỚC
    Route::delete('/delete-all', [ClientNotificationController::class, 'deleteAll'])->name('deleteAll');
    Route::post('/mark-all-as-read', [ClientNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');

    Route::delete('/{id}', [ClientNotificationController::class, 'destroy'])->name('destroy');
    Route::get('/{notification}', [ClientNotificationController::class, 'show'])->name('show');
    Route::post('/{notification}/mark-as-read', [ClientNotificationController::class, 'markAsRead'])->name('mark-as-read');
});






