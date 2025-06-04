<?php

use App\Http\Controllers\admin\AppointmentController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\FacebookController;
use App\Http\Controllers\auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('postLogin');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('postRegister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Trang nhập email để gửi link
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Gửi email chứa link reset
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Trang nhập mật khẩu mới khi bấm vào link từ email
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Gửi mật khẩu mới về server để cập nhật
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// đăng nhập bằng facebook
Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');

// đăng nhập bằng google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// test tạm thời
Route::get('/test-email', function () {
    return \Illuminate\Support\Facades\Password::sendResetLink(['email' => 'youremail@gmail.com']);
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('admin/users/edit/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('admin/users/edit/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::get('admin/users/search', [UserController::class, 'search'])->name('admin.users.search');

// quản lý lịch hẹn khám
Route::prefix('admin/appointments')->name('admin.appointments.')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('index');
    Route::get('/create', [AppointmentController::class, 'create'])->name('create');
    Route::post('/store', [AppointmentController::class, 'store'])->name('store');
});