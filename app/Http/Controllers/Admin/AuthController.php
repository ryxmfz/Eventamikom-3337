<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Fungsi menampilkan halaman view formulir
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Fungsi memproses validasi Submit Log In
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 🎯 CEK HAK AKSES: Izinkan jika is_admin == 1 ATAU role-nya admin/organizer
            if ($user->is_admin == 1 || in_array($user->role, ['admin', 'superadmin', 'organizer'])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            // Jika user biasa (buyer), logout dan kembalikan pesan eror
            Auth::logout();
            return back()->withErrors([
                'email' => 'Anda tidak memiliki hak akses ke panel ini.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
        ]);
    }

    // 3. Fungsi memproses Log Out (Keluar)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
