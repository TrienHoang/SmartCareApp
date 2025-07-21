<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\PaymentHistoryClientController; // Đúng namespace, đúng chữ hoa/thường
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Client\ReviewReplyController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\DoctorController;

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

// Route::get('/thong-tin-bac-si', function () {
//     return view('client.doctors_detail');
// })->name('doctors_detail');

Route::get('/thong-tin-bac-si/{id}', [DoctorController::class, 'show'])->name('doctor.show');



Route::middleware(['auth'])->group(function () {
    // Gửi đánh giá
    Route::post('/doctors/{doctor}/reviews', [ReviewReplyController::class, 'store'])->name('reviews.store');

    // Gửi phản hồi đánh giá
Route::post('/reviews/{review}/replies', [ReviewReplyController::class, 'storeReply'])->name('reviews.replies.store');

    // Đánh dấu đánh giá là hữu ích
    Route::post('/reviews/{review}/useful', [ReviewReplyController::class, 'markUseful'])->name('reviews.useful');

});

// Route hiển thị chi tiết bác sĩ (không yêu cầu đăng nhập)
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Danh sách lịch sử khám
    Route::get('/appointment-history', [AppointmentHistoryController::class, 'index'])
        ->name('appointments.history');

    // Xem chi tiết từng cuộc khám
    Route::get('/appointment-history/{id}', [AppointmentHistoryController::class, 'show'])
        ->name('appointments.detail');
});


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
