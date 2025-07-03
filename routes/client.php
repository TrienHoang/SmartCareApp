<?php

use App\Http\Controllers\client\ClientFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::prefix('client/uploads')->name('client.uploads.')->middleware(['auth'])->group(function () {
    Route::get('/', [ClientFileController::class, 'index'])->name('index');
});