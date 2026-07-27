<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController as TransactionAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\OrganizerApprovalController;
use App\Http\Controllers\Admin\CheckInController; // 👈 1. Import Controller Scanner
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrganizerController;

// --- 🌐 RUTE FRONTEND / PUBLIK ---
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
Route::get('/my-ticket', [EventController::class, 'ticket'])->middleware('auth')->name('ticket');

// Jalur Antarmuka & Eksekusi Simpan Checkout Pelanggan
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// 💳 Rute Halaman Pembayaran Midtrans Snap
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');

// Rute Halaman Sukses Setelah Pembayaran Midtrans
Route::get('/success', function () {
    return view('success');
});

Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// Route : Untuk Webhook Callback Midtrans
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);


// --- 💬 RUTE ULASAN & PROFIL PENYELENGGARA (SOAL 1 BAGIAN 2) ---
// Route Simpan Ulasan (Perlu Auth User Biasa)
Route::post('/event/{event_id}/review', [ReviewController::class, 'store'])->middleware('auth')->name('review.store');

// Route Halaman Profil Penyelenggara (Dapat Diakses Publik / Pembeli)
Route::get('/organizer/{id}', [OrganizerController::class, 'show'])->name('organizer.show');


// --- 🔑 RUTE OTENTIKASI GOOGLE SSO ---
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// --- 🛡️ RUTE OTENTIKASI & ADMIN ---
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

    // Route khusus internal Admin & Superadmin
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventAdminController::class);

        Route::get('transactions', [TransactionAdminController::class, 'index'])->name('transactions.index');

        // ✨ Modul Kategori
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // ✨ Modul Partner
        Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::post('partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::put('partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');

        // 🛡️ ROUTE SUPERADMIN: Mengawasi Kelayakan Penyelenggara
        Route::get('organizers', [OrganizerApprovalController::class, 'index'])->name('organizers.index');
        Route::match(['post', 'patch'], 'organizers/{id}/approve', [OrganizerApprovalController::class, 'approve'])->name('organizers.approve');
        Route::match(['post', 'patch'], 'organizers/{id}/reject', [OrganizerApprovalController::class, 'reject'])->name('organizers.reject');

        // 📷 2. ROUTE SOAL 2: CHECK-IN QR SCANNER PANITIA
        Route::get('scanner', [CheckInController::class, 'index'])->name('scanner.index');
        Route::post('scanner/scan', [CheckInController::class, 'scan'])->name('scanner.scan');

    });
});
