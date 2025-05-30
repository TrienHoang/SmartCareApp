<?php

use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('admin/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
