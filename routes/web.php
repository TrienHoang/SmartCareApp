<?php

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;

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

//test tạm thờithời
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

// quản lý danh mục dịch vụ
Route::get('admin/categories', [ServiceCategoryController::class, 'index'])->name('admin.categories.index');
Route::get('admin/categories/create', [ServiceCategoryController::class, 'create'])->name('admin.categories.create');
Route::post('admin/categories/store', [ServiceCategoryController::class, 'store'])->name('admin.categories.store');
Route::get('admin/categories/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('admin.categories.edit');
Route::put('admin/categories/edit/{id}', [ServiceCategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('admin/categories/destroy/{id}', [ServiceCategoryController::class, 'destroy'])->name('admin.categories.destroy');
Route::get('admin/categories/show/{id}', [ServiceCategoryController::class, 'show'])->name('admin.categories.show');

// quản lý dịch vụ
Route::get('admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
Route::get('admin/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
Route::post('admin/services/store', [ServiceController::class, 'store'])->name('admin.services.store');
Route::get('admin/services/edit/{id}', [ServiceController::class, 'edit'])->name('admin.services.edit');
Route::put('admin/services/edit/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
Route::delete('admin/services/destroy/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
Route::get('admin/services/show/{id}', [ServiceController::class, 'show'])->name('admin.services.show');
