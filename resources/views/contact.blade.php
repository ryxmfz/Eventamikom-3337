@extends('layouts.app', ['title' => 'Kontak - AmikomEventHub'])

@section('content')
<div class="max-w-xl mx-auto py-16 px-4 text-center">
    <div class="bg-white p-12 rounded-[2.5rem] shadow-xl border border-slate-100">
        <div class="bg-indigo-100 w-20 h-20 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-8 shadow-inner">✉️</div>
        <h1 class="text-4xl font-black text-slate-900 mb-4">Ayo Ngobrol.</h1>
        <p class="text-slate-500 mb-10 leading-relaxed text-sm">Ada pertanyaan atau ingin bekerja sama? Jangan ragu untuk mengirimkan pesan kepada kami melalui email di bawah ini.</p>

        {{-- 📧 EMAIL RESMI AMIKOM --}}
        <div class="bg-slate-50 py-4 px-6 rounded-2xl font-mono text-indigo-600 font-bold mb-10 select-all border border-slate-200">
            <a href="mailto:admin@amikom.ac.id" class="hover:underline">admin@amikom.ac.id</a>
        </div>

        <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-100 text-sm">
            Kembali ke Home
        </a>
    </div>
</div>
@endsection
