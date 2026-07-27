@extends('layouts.app')

@section('content')
<main class="min-h-[85vh] py-12 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto space-y-10">

    <!-- Header Profil Penyelenggara -->
    <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">

            <!-- Avatar Penyelenggara -->
            <div class="w-28 h-28 rounded-3xl bg-indigo-500/20 border-4 border-white/20 flex items-center justify-center text-4xl font-black shadow-inner">
                🏛️
            </div>

            <!-- Detail & Statistik Rekam Jejak -->
            <div class="space-y-4 text-center md:text-left flex-1">
                <div>
                    <span class="px-3.5 py-1 bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 text-[10px] font-black uppercase tracking-widest rounded-full">
                        Penyelenggara Resmi
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black mt-2 tracking-tight">{{ $organizer->name }}</h1>
                    <p class="text-slate-400 text-sm mt-1">{{ $organizer->email }}</p>
                </div>

                <!-- Card Statistik Bintang & Ulasan -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-white/10">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                        <p class="text-xs text-indigo-200 font-bold uppercase tracking-wider">Rating Penyelenggara</p>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                            <span class="text-3xl font-black text-amber-400">★ {{ $averageRating }}</span>
                            <span class="text-xs text-slate-300">/ 5.0</span>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                        <p class="text-xs text-indigo-200 font-bold uppercase tracking-wider">Total Ulasan</p>
                        <p class="text-3xl font-black text-white mt-1">{{ $totalReviews }}</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 col-span-2 sm:col-span-1">
                        <p class="text-xs text-indigo-200 font-bold uppercase tracking-wider">Total Event</p>
                        <p class="text-3xl font-black text-white mt-1">{{ $events->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekam Jejak Ulasan & Testimoni (Rating & Reviews) -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Rekam Jejak Penilaian & Testimoni 💬</h2>
                <p class="text-slate-500 text-sm mt-0.5">Ulasan transparan dari peserta yang telah menghadiri acara penyelenggara ini.</p>
            </div>
        </div>

        @if($reviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($reviews as $rev)
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md transition space-y-4 flex flex-col justify-between">
                        <div class="space-y-3">
                            <!-- User & Rating -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center text-sm">
                                        {{ strtoupper(substr($rev->user->name ?? 'P', 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm">{{ $rev->user->name ?? 'Peserta' }}</h4>
                                        <p class="text-[10px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- Bintang -->
                                <div class="flex text-amber-400 text-sm font-bold">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $rev->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>

                            <!-- Pesan Testimoni -->
                            <p class="text-slate-600 text-sm italic leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                "{{ $rev->comment }}"
                            </p>
                        </div>

                        <!-- Badge Event Terkait -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-bold text-[10px] uppercase">Acara Ditinjau:</span>
                            <span class="font-bold text-indigo-600 truncate max-w-[200px]">{{ $rev->event->title ?? 'Event' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-3">
                <p class="text-4xl">⭐</p>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Ulasan</h3>
                <p class="text-slate-500 text-xs">Penyelenggara ini belum menerima ulasan dari peserta acara.</p>
            </div>
        @endif
    </div>

</main>
@endsection
