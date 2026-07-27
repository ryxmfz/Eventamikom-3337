@extends('layouts.app', ['title' => 'Profil Pengembang - AmikomEventHub'])

@section('content')
<div class="max-w-4xl mx-auto py-16 px-6">

    {{-- Header Judul Halaman --}}
    <div class="text-center mb-10">
        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-widest border border-indigo-100">
            Developer Profile
        </span>
        <h1 class="text-4xl font-black text-slate-900 mt-3">Tentang Pembuat Platform</h1>
        <p class="text-slate-500 text-sm mt-2">Mengenal lebih dekat kreator di balik pengembangan AmikomEventHub.</p>
    </div>

    {{-- Kartu Profil Modern --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12">

        {{-- Sisi Kiri (Aksen Gradient Indigo) --}}
        <div class="md:col-span-5 bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-700 p-8 text-white flex flex-col items-center justify-center text-center relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="w-28 h-28 bg-white/10 backdrop-blur-md rounded-3xl border-2 border-white/20 flex items-center justify-center text-5xl mx-auto mb-6 shadow-lg">
                    👨‍💻
                </div>
                <h2 class="text-2xl font-black tracking-tight">Reyhan Amin Afrizal</h2>
                <p class="text-indigo-200 text-xs font-mono font-bold mt-2 px-3.5 py-1.5 bg-white/10 rounded-xl inline-block border border-white/10">
                    NIM : 24.12.3337
                </p>
                <div class="mt-6 pt-6 border-t border-white/15 w-full text-xs text-indigo-200 space-y-1">
                    <p class="font-semibold text-white">Universitas AMIKOM Yogyakarta</p>
                    <p>Fakultas Ilmu Komputer • Sistem Informasi</p>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan (Deskripsi & Keahlian) --}}
        <div class="md:col-span-7 p-8 md:p-10 space-y-8 flex flex-col justify-between">

            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-black text-slate-800">Tentang Saya</h3>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Saya adalah mahasiswa Sistem Informasi di Universitas AMIKOM Yogyakarta yang memiliki ketertarikan mendalam pada pengembangan web dan pengeditan video. Saya selalu antusias untuk mempelajari teknologi baru guna menciptakan solusi digital yang bermanfaat.
                </p>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-black text-slate-800">Minat & Keahlian</h3>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <span class="px-3.5 py-2 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-xl border border-indigo-100 flex items-center gap-1.5">
                        ⚡ Web Development (Laravel)
                    </span>
                    <span class="px-3.5 py-2 bg-purple-50 text-purple-700 font-bold text-xs rounded-xl border border-purple-100 flex items-center gap-1.5">
                        🎬 After Effects & Video Editing
                    </span>
                    <span class="px-3.5 py-2 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-100 flex items-center gap-1.5">
                        📈 Stock Investment Analysis
                    </span>
                    <span class="px-3.5 py-2 bg-amber-50 text-amber-700 font-bold text-xs rounded-xl border border-amber-100 flex items-center gap-1.5">
                        ☕ Coffee Lover ☕
                    </span>
                </div>
            </div>

            <div class="pt-4 border-t flex justify-between items-center text-xs text-slate-400 font-medium">
                <span>Mahasiswa Aktif Semester 3</span>
                <a href="{{ route('home') }}" class="text-indigo-600 font-bold hover:underline flex items-center gap-1">
                    ← Kembali ke Home
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
