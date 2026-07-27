@extends('layouts.app', ['title' => 'Katalog Event - AmikomEventHub'])

@section('content')
<div class="max-w-7xl mx-auto py-16 px-6">
    <header class="mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-2">Eksplor Katalog.</h1>
        <p class="text-slate-500 font-medium">Temukan event yang sesuai dengan minat Anda di Amikom Yogyakarta.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Card 1: Seminar IT --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition duration-300">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 h-48 flex items-center justify-center text-white text-5xl">
                ⚡
            </div>
            <div class="p-6">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md">Seminar</span>
                <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">Seminar Nasional IT</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">Masa depan AI dan pengaruhnya bagi lulusan Sistem Informasi di era 2026.</p>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">📍 Cinema Amikom</span>
                    <a href="{{ route('home') }}" class="bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold transition">Daftar</a>
                </div>
            </div>
        </div>

        {{-- Card 2: Workshop UI/UX --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition duration-300">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 h-48 flex items-center justify-center text-white text-5xl">
                🎨
            </div>
            <div class="p-6">
                <span class="text-[10px] font-black uppercase tracking-wider text-purple-600 bg-purple-50 px-3 py-1 rounded-md">Workshop</span>
                <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">UI/UX Design Basic</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">Pelajari cara membuat desain aplikasi yang user-friendly menggunakan Figma.</p>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">📍 Lab ICT 2</span>
                    <a href="{{ route('home') }}" class="bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold transition">Daftar</a>
                </div>
            </div>
        </div>

        {{-- Card 3: Finance --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1.5 transition duration-300">
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 h-48 flex items-center justify-center text-white text-5xl">
                📈
            </div>
            <div class="p-6">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-md">Finance</span>
                <h3 class="text-xl font-bold text-slate-800 mt-3 mb-2">Crypto & Stock 101</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">Workshop strategi investasi cerdas bagi mahasiswa di pasar modal & kripto.</p>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">📍 Aula BSC</span>
                    <a href="{{ route('home') }}" class="bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold transition">Daftar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
