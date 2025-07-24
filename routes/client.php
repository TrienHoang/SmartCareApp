<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\client\ClientFileController;
use App\Http\Controllers\Client\PaymentHistoryClientController; // Đúng namespace, đúng chữ hoa/thường
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', function () {
    return view('client.about');
})->name('about');

// Dịch vụ
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('client.services');
Route::get('/dich-vu/{id}', [ServiceController::class, 'show'])->name('client.services.show');
Route::get('/dich-vu/chi-tiet/{id}', [ServiceController::class, 'detail'])->name('client.services.detail');

// Tin tức
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('client.news');
Route::get('/tin-tuc/danh-muc/{id}', [NewsController::class, 'category'])->name('client.news.category');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('client.news.show');



// liên hệ
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

Route::get('/dat-lich', function () {
    return view('client.booking');
})->name('booking');

Route::get('/lien-he', function () {
    return view('client.contact');
})->name('contact');

// Route::get('/tin-tuc', function () {
//     return view('client.news');
// })->name('news');

Route::get('/chi-tiet-tin-tuc/{id}', function ($id) {
    return view('client.news_detail', ['id' => $id]);
})->name('news_detail');

Route::get('/thong-tin-ca-nhan', function () {
    return view('client.profile');
})->name('profile');

Route::get('/thong-tin-bac-si', function () {
    return view('client.doctors_detail');
})->name('doctors_detail');

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
