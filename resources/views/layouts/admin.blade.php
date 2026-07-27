<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 flex min-h-screen">
    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">

        {{-- 🌐 LOGO KINI BISA DIKLIK UNTUK KEMBALI KE HALAMAN UTAMA --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-90 transition" title="Kembali ke Halaman Utama">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl shadow-sm">AH</div>
            <span class="text-xl font-bold text-white tracking-tight">AmikomEventHub</span>
        </a>

        <nav class="flex-1 space-y-2 overflow-y-auto">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Dashboard
            </a>

            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.events.*') || request()->routeIs('events.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Kelola Event
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Laporan Transaksi
            </a>

            {{-- 📷 MENU SOAL 2: Scan Check-in Panitia --}}
            <a href="{{ route('admin.scanner.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.scanner.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Scan Check-in
            </a>

            {{-- ✨ Menu Kelola Kategori ✨ --}}
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Kelola Kategori
            </a>

            {{-- ✨ Menu Kelola Partner ✨ --}}
            <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.partners.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                Kelola Partner
            </a>

            {{-- 🛡️ KHUSUS SUPERADMIN: Menu Pengawasan Kelayakan Penyelenggara (Soal 1 Bagian 3) --}}
            @if(auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->email === 'admin@amikom.ac.id'))
                <a href="{{ route('admin.organizers.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.organizers.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                    Kelola Penyelenggara
                </a>
            @endif
        </nav>

        {{-- BAGIAN BAWAH SIDEBAR --}}
        <div class="pt-4 border-t border-indigo-800 space-y-2">

            {{-- 🌐 TOMBOL MENU NAVIGASI KE HALAMAN UTAMA PUBLIK --}}
            <a href="{{ route('home') }}" class="w-full flex items-center gap-3 px-4 py-3 text-indigo-200 hover:text-white hover:bg-indigo-800/60 rounded-xl transition font-semibold text-sm">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Lihat Website
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-rose-300 hover:text-rose-100 hover:bg-rose-500/10 rounded-xl transition font-semibold text-sm text-left">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto">
        @yield('content')
    </main>
</body>
</html>
