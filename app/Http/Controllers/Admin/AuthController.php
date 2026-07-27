<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Memproses Log In (Admin/Organizer ke Dashboard, User Biasa ke Home)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 🎯 JIKA ADMIN / SUPERADMIN / ORGANIZER -> MASUK DASHBOARD
            if ($user->is_admin == 1 || in_array($user->role, ['admin', 'superadmin', 'organizer'])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            // 🎯 JIKA USER BIASA (PEMBELI TIKET) -> REDIRECT KE BERANDA PUBLIK
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
        ]);
    }

    // 3. Menampilkan Form Registrasi User Biasa
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Memproses Registrasi User Biasa
    public function storeRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'email.unique' => 'Email ini sudah terdaftar! Silakan login.',
        ]);

        User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'user',
            'organizer_status' => 'approved', // User biasa langsung aktif
            'is_admin'         => 0
        ]);

        return redirect()->route('admin.login')->with('success', 'Pendaftaran Akun Berhasil! Silakan masuk menggunakan email Anda.');
    }

    // 5. Menampilkan Form Registrasi Organizer
    public function showOrganizerRegister()
    {
        return view('auth.register-organizer');
    }

    // 6. Memproses Pengajuan Akun Organizer Baru
    public function storeOrganizerRegister(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6',
        ], [
            'email.unique' => 'Email ini sudah terdaftar!',
        ]);

        User::create([
            'name'              => $request->name,
            'organization_name' => $request->organization_name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'organizer',
            'organizer_status'  => 'pending', // Pending, butuh disetujui Superadmin
            'is_admin'          => 0
        ]);

        return redirect()->route('admin.login')->with('success', 'Pengajuan Penyelenggara dikirim! Akun Anda sedang menunggu verifikasi Superadmin.');
    }

    // 7. Memproses Log Out (Keluar)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
