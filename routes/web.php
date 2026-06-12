<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController as TransactionAdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// --- RUTE FRONTEND ---
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
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// 🚀 BARU DI PERTEMUAN 10: Jalur Antarmuka & Eksekusi Simpan Checkout Pelanggan
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');


// --- RUTE OTENTIKASI & ADMIN ---

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });


    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventAdminController::class);


        Route::get('transactions', [TransactionAdminController::class, 'index'])->name('transactions.index');

    });
});
