<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // ✨ Bypass SSL untuk Laragon Localhost & gunakan stateless agar tidak mismatch session
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->stateless()
                ->user();

            // 1. Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // 2. Jika pengguna baru, buatkan akun Pembeli Tiket biasa (is_admin = 0)
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make('password123'), // Password dummy aman
                    'is_admin'  => 0, // Default 0 untuk pembeli
                ]);
            } else {
                // 3. Jika akun sudah ada, perbarui google_id
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            // Login-kan user ke dalam sistem
            Auth::login($user);

            // 🎯 SMART REDIRECT
            // Jika akun adalah Admin (is_admin == 1), arahkan ke Dashboard Admin
            if ($user->is_admin == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
            }

            // Jika akun Pembeli Biasa (is_admin == 0), arahkan ke Halaman Utama
            return redirect()->route('home')->with('success', 'Berhasil login via Google!');

        } catch (Exception $e) {
            // 🛑 PERBAIKAN: Redirect halus kembali ke halaman login jika user klik Batal/Error
            return redirect()->route('login')->with('error', 'Gagal login via Google atau dibatalkan. Silakan coba lagi.');
        }
    }
}
