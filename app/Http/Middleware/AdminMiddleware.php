<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan User Sudah Login
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // 2. Superadmin Utama -> SELALU LOLOS
        if ($user->email === 'admin@amikom.ac.id' || in_array($user->role, ['superadmin', 'admin'])) {
            return $next($request);
        }

        // 3. Organizer / HIMA -> WAJIB role 'organizer', is_admin = 1, DAN status = 'approved'
        if ($user->role === 'organizer' && $user->is_admin == 1 && $user->organizer_status === 'approved') {
            return $next($request);
        }

        // ⛔ JIKA DITOLAK (REJECTED), PENDING, ATAU USER BIASA -> KELUARKAN & BLOKIR
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('error', 'Akses Ditolak! Akun organisasi Anda belum disetujui atau telah ditolak oleh Superadmin.');
    }
}
