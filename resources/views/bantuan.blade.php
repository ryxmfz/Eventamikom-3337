@extends('layouts.app', ['title' => 'Bantuan FAQ - AmikomEventHub'])

@section('content')
<div class="max-w-3xl mx-auto py-16 px-6">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-3">Butuh Bantuan?</h1>
        <p class="text-slate-500 text-sm font-medium">Temukan jawaban atas pertanyaan seputar pembelian tiket dan penyelenggaraan event.</p>
    </div>

    <div class="space-y-4">
        <details class="group bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs cursor-pointer open:ring-2 open:ring-indigo-600 transition-all">
            <summary class="flex justify-between items-center font-bold text-slate-800 text-base list-none">
                Apakah event di platform ini gratis?
                <span class="group-open:rotate-180 transition duration-300 text-indigo-600 text-xs">▼</span>
            </summary>
            <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                Tergantung penyelenggara. Sebagian besar event internal kampus gratis, namun beberapa workshop bersertifikat mungkin memerlukan biaya pendaftaran.
            </p>
        </details>

        <details class="group bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs cursor-pointer open:ring-2 open:ring-indigo-600 transition-all">
            <summary class="flex justify-between items-center font-bold text-slate-800 text-base list-none">
                Bagaimana cara menjadi penyelenggara?
                <span class="group-open:rotate-180 transition duration-300 text-indigo-600 text-xs">▼</span>
            </summary>
            <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                Silakan hubungi admin melalui halaman kontak dengan menyertakan proposal kegiatan UKM atau organisasi Anda.
            </p>
        </details>
    </div>
</div>
@endsection
