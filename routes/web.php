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
