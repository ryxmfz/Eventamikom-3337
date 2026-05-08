<?php

use App\Http\Controllers\Admin\DashboardController;
// Mengubah alias agar sama persis dengan gambar
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', function () {
    return view('profil');
})->name('profil');
Route::get('/katalog', function () {
    return view('katalog');
})->name('katalog');
Route::get('/bantuan', function () {
    return view('bantuan');
})->name('bantuan');
Route::get('/kontak', function () {
    return view('contact');
})->name('kontak');

Route::get('/event/1', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Menggunakan gaya penulisan chaining (->) sesuai gambar
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Route Resource untuk mengelola event
    Route::resource('events', EventAdminController::class);
});
