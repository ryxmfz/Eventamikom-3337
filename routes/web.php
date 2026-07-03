<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController as TransactionAdminController;
// Import Controller Kategori & Partner Admin yang baru dibuat
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// --- RUTE FRONTEND ---
// 🚀 FIXED: Diubah ke EventController agar data $events dari database sukses terkirim ke welcome.blade.php
Route::get('/', [EventController::class, 'index'])->name('home');

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

Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// BARU DI PERTEMUAN 10: Jalur Antarmuka & Eksekusi Simpan Checkout Pelanggan
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// 💳 Rute Halaman Pembayaran Midtrans Snap
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');

// 🏁 FIXED: Menambahkan Rute Halaman Sukses Setelah Pembayaran Midtrans (Bisa diakses publik)
Route::get('/success', function () {
    return view('success');
});

// 🏁 FIXED: Menambahkan Rute Halaman Sukses dengan Parameter order_id (Opsional, sesuaikan dengan Controller)
Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

//Route : Untuk Webhook Callback Midtrans
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);


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

        // ✨ BARU: Rute Modul Kategori (Otomatis menjadi admin.categories.index, dst)
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // ✨ BARU: Rute Modul Partner (Otomatis menjadi admin.partners.index, dst)
        Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::post('partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::put('partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    });
});
