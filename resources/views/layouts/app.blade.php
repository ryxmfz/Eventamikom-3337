<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AmikomEventHub - Temukan Event Seru!' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navigation Bar -->
    <nav
        class="glass sticky top-6 z-40 mx-4 md:mx-8 mt-4 px-6 py-3.5 rounded-2xl border border-white/40 shadow-lg shadow-slate-200/50 flex justify-between items-center transition-all">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform">
                    AH
                </div>
                <span class="text-xl font-black tracking-tight text-slate-900 group-hover:text-indigo-600 transition">AmikomEventHub</span>
            </a>
        </div>

        <!-- Navigation Links (✨ SUDAH DITAMBAHKAN MENU PROFIL ✨) -->
        <div class="hidden md:flex items-center gap-8 font-semibold text-sm">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }} transition">Home</a>
            <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }} transition">Profil</a>
            <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }} transition">Katalog</a>
            <a href="{{ route('bantuan') }}" class="{{ request()->routeIs('bantuan') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }} transition">Bantuan</a>
            <a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }} transition">Kontak</a>
        </div>

        <!-- ✨ AUTHENTICATION NAVIGATION ✨ -->
        <div class="flex items-center gap-3">
            @auth
                <!-- Link Tiket Saya -->
                <a href="{{ route('ticket') }}"
                   class="hidden sm:flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 bg-white/80 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition border border-slate-200/80 shadow-2xs">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Tiket Saya
                </a>

                <!-- 🎯 Tombol Admin / Organizer Panel -->
                @if(Auth::user()->is_admin == 1 || in_array(Auth::user()->role, ['admin', 'superadmin', 'organizer']))
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 rounded-xl transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard Panel
                    </a>
                @endif

                <!-- Chip User Profile -->
                <div class="flex items-center gap-2.5 pl-1.5 pr-3 py-1 bg-white border border-slate-200/80 rounded-2xl shadow-2xs">
                    <div class="w-7 h-7 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xs font-extrabold uppercase shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-xs font-bold text-slate-800 max-w-[130px] truncate">
                        {{ Auth::user()->name }}
                    </span>
                </div>

                <!-- Tombol Logout Minimalis -->
                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            title="Keluar / Logout"
                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 flex items-center justify-center cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            @else
                <!-- Tombol Masuk / Daftar saat Guest -->
                <a href="{{ route('login') }}"
                   class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk / Daftar
                </a>
            @endauth
        </div>
    </nav>

    <!-- Content Section -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan
                    penyelenggara profesional.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('profil') }}" class="hover:text-white transition">Profil</a></li>
                    <li><a href="{{ route('katalog') }}" class="hover:text-white transition">Katalog Event</a></li>
                    <li><a href="{{ route('bantuan') }}" class="hover:text-white transition">Bantuan</a></li>
                    <li><a href="{{ route('kontak') }}" class="hover:text-white transition">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li>admin@amikom.ac.id</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>
