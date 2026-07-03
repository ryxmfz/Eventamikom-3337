@extends('layouts.app')

@section('content')
    {{-- ================= HERO SECTION ================= --}}
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                #1 Event Platform
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <img src="{{ asset('assets/concert.png') }}" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= JELAJAHI KATEGORI SECTION (Soal 4) ================= --}}
    <section class="max-w-7xl mx-auto px-6 py-10 border-t border-b border-slate-100">
        <h3 class="text-xl font-extrabold text-slate-800 mb-6 text-center md:text-left">
            Jelajahi Kategori Event
        </h3>
        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
            @foreach($categories as $category)
                <span class="px-5 py-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-600 rounded-2xl font-bold text-sm transition-all shadow-xs cursor-pointer">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>
    </section>

    {{-- ================= EVENTS LIST SECTION ================= --}}
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
            <div class="flex gap-2">
                <button class="p-3 border rounded-xl hover:bg-white hover:shadow-md transition font-bold text-sm text-slate-700">
                    Semua Kategori
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- 🚀 PROSES PERULANGAN DINAMIS DARI DATABASE --}}
            @forelse($events as $event)
                <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="relative overflow-hidden aspect-[3/4]">
                        {{-- Deteksi Poster Gambar --}}
                        @php
                            $posterEvent = $event->poster ?? $event->image ?? $event->gambar ?? $event->poster_path;

                            // Logika penentu gambar cadangan otomatis berdasarkan kategori jika di storage kosong
                            $defaultImage = 'assets/concert.png';
                            if (isset($event->category) && strtolower($event->category->name) == 'technology') {
                                $defaultImage = 'assets/workshop.png';
                            } elseif (isset($event->category) && strtolower($event->category->name) == 'coding') {
                                $defaultImage = 'assets/hackathon.png';
                            }
                        @endphp

                        <img src="{{ $posterEvent ? asset('storage/' . $posterEvent) : asset($defaultImage) }}"
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                            {{ $event->category->name ?? 'Umum' }}
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $event->date }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="text-2xl font-black text-indigo-600">
                                {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            </span>
                            {{-- 🎯 LINK TOMBOL DETAIL OTOMATIS BERUBAH SESUAI ID EVENT --}}
                            <a href="{{ route('events.show', $event->id) }}"
                               class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-slate-500">
                    Belum ada data event aktif yang tersedia saat ini.
                </div>
            @endforelse
        </div>
    </section>

    {{-- ================= OFFICIAL PARTNERS SECTION (Soal 4) ================= --}}
    <section class="bg-slate-50 border-t border-slate-100 py-16 w-full">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-center text-xs font-bold uppercase tracking-wider text-slate-400 mb-8">
                Partner Resmi yang Mendukung AmikomEventHub
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 items-center justify-items-center">
                @foreach($partners as $partner)
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-2xs flex flex-col items-center justify-center w-full h-28 hover:scale-105 transition-transform duration-300">
                        {{-- 🛠️ FIXED: Sekarang membaca file local upload menggunakan asset('storage/...') atau link URL lama secara cerdas --}}
                        <img src="{{ str_starts_with($partner->logo_url, 'http') ? $partner->logo_url : asset('storage/' . $partner->logo_url) }}"
                             alt="Logo {{ $partner->name }}"
                             class="max-h-12 max-w-full object-contain mb-2">
                        <span class="text-xs text-slate-400 font-medium truncate w-full text-center px-2">
                            {{ $partner->name }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
