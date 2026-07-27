@extends('layouts.app')

@section('content')
@php
    // Ambil akun Google pengguna yang sedang login
    $currentUser = Auth::user();

    // Ambil semua transaksi
    $allTransactions = \App\Models\Transaction::latest()->get();

    // Filter transaksi berdasarkan akun Google
    $transactions = $allTransactions->filter(function($item) use ($currentUser) {
        if (!$currentUser) return false;

        $googleEmail = strtolower(trim($currentUser->email ?? ''));
        $trxEmail = strtolower(trim($item->email ?? $item->customer_email ?? $item->user_email ?? ''));

        if (!empty($googleEmail) && !empty($trxEmail)) {
            return $googleEmail === $trxEmail;
        }

        $buyerName = strtolower(trim($item->name ?? $item->customer_name ?? $item->nama ?? ''));
        $authName  = strtolower(trim($currentUser->name ?? ''));

        return !empty($buyerName) && !empty($authName) && $buyerName === $authName;
    });
@endphp

<!-- CSS Khusus Cetak / Simpan PDF Sempurna -->
<style>
    @media print {
        /* 1. Paksa Browser Mencetak Warna Background & Gambar */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* 2. Sembunyikan Elemen Non-Tiket */
        nav, footer, .no-print, .ticket-grid {
            display: none !important;
        }

        /* 3. Setting Kertas A4 */
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            background: white !important;
            color: #0f172a !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        main {
            min-height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* 4. Tampilan Modal Tiket Pas Diprint */
        #ticket-modal {
            position: static !important;
            background: transparent !important;
            display: block !important;
            padding: 0 !important;
            inset: auto !important;
        }

        .modal-content {
            box-shadow: none !important;
            border: 2px solid #1e293b !important;
            border-radius: 2rem !important;
            width: 100% !important;
            max-width: 580px !important;
            margin: 20px auto !important;
            page-break-inside: avoid !important;
            background-color: #ffffff !important;
        }

        /* Enforce Warna Background Khusus Cetak */
        .print-bg-dark {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }
        .print-bg-light {
            background-color: #f8fafc !important;
        }
        .print-bg-header {
            background-color: #f1f5f9 !important;
        }
    }
</style>

<main class="min-h-[85vh] py-12 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">

    <!-- Flash Alert Notifikasi Sukses / Gagal Ulasan -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between no-print shadow-sm">
            <span>✅ {{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between no-print shadow-sm">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-200/80 no-print">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tiket Saya 🎫</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Terhubung dengan Google: <span class="text-indigo-600 font-bold">{{ Auth::user()?->email }}</span></p>
        </div>
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-2xl font-bold text-xs self-start md:self-auto border border-indigo-100">
            Total Tiket Anda: {{ $transactions->count() }}
        </div>
    </div>

    @if($transactions->count() > 0)
        <!-- Grid Daftar Kartu Tiket Pengguna -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ticket-grid no-print">
            @foreach($transactions as $index => $item)
                @php
                    $isPaid = in_array(strtolower($item->status), ['paid', 'success', 'settlement']);
                    $eventId = $item->event_id ?? $item->event?->id ?? 1;
                    $organizerId = $item->event->user_id ?? $item->event?->user_id ?? 1;
                @endphp

                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">

                    <!-- Card Top -->
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                                #TRX-{{ $item->id }}
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $item->status }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-extrabold text-xl text-slate-900 leading-snug group-hover:text-indigo-600 transition">
                                {{ $item->event->title ?? $item->event_name ?? 'Event Amikom' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                                📅 {{ $item->event->date ?? '2026-06-05' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1.5 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Pemesan:</span>
                                <span class="font-bold text-slate-800">{{ $item->name ?? $item->customer_name ?? Auth::user()?->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Lokasi:</span>
                                <span class="font-bold text-slate-800 truncate max-w-[150px]">{{ $item->event->location ?? 'Amikom' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Bottom / Action -->
                    <div class="p-6 pt-0 space-y-2">
                        <button onclick="openModal('{{ $item->event->title ?? $item->event_name ?? 'Event Amikom' }}', '{{ $item->name ?? $item->customer_name ?? Auth::user()?->name }}', '{{ $item->event->date ?? '2026-06-05' }}', '{{ $item->order_id }}', '{{ $item->event->location ?? 'Amikom' }}', '{{ $item->status }}', '{{ $item->id }}', '{{ $eventId }}')"
                            class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>🔍 Lihat E-Ticket & QR</span>
                        </button>

                        <a href="{{ route('organizer.show', $organizerId) }}"
                            class="block text-center w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[11px] font-bold rounded-xl border border-slate-200/80 transition">
                            🏛️ Profil Penyelenggara
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <!-- Tampilan Jika Kosong -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 p-12 text-center shadow-sm max-w-md mx-auto my-12 space-y-5">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto text-3xl font-black">
                🎫
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900">Belum Ada Tiket</h2>
                <p class="text-slate-500 text-sm mt-1">Belum ada transaksi tiket yang terhubung dengan akun Google ini ({{ Auth::user()?->email }}).</p>
            </div>
            <a href="{{ route('home') }}"
                class="inline-block px-8 py-4 bg-indigo-600 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                Jelajahi Event
            </a>
        </div>
    @endif

</main>

<!-- Pop-up / Modal E-Ticket Resmi -->
<div id="ticket-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="modal-content bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl relative my-8">

        <!-- Modal Header -->
        <div class="p-6 print-bg-header bg-gradient-to-br from-indigo-50 to-slate-50 border-b-2 border-dashed border-slate-200 text-center relative">
            <button onclick="closeModal()" class="no-print absolute top-5 right-5 w-8 h-8 bg-white border border-slate-200 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-100 transition cursor-pointer">
                ✕
            </button>
            <span class="inline-block px-3.5 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-2">
                E-TICKET RESMI
            </span>
            <h2 id="modal-title" class="text-2xl font-black text-slate-900 leading-snug px-2"></h2>

            <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full border border-slate-200"></div>
            <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full border border-slate-200"></div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4 print-bg-light bg-slate-50 p-4.5 rounded-2xl border border-slate-200 text-xs">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pemesan</p>
                    <p id="modal-name" class="font-extrabold text-slate-800 truncate text-sm"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</p>
                    <p id="modal-date" class="font-extrabold text-slate-800 text-sm"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Order ID</p>
                    <p id="modal-order" class="font-bold text-indigo-600 font-mono truncate text-xs"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Lokasi</p>
                    <p id="modal-location" class="font-extrabold text-slate-800 truncate text-sm"></p>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex items-center justify-between p-3.5 rounded-2xl border print-bg-light bg-slate-50 text-xs font-bold">
                <span class="text-slate-500 uppercase tracking-wide text-[10px]">Status Pembayaran</span>
                <span id="modal-status" class="px-3.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider text-white"></span>
            </div>

            <!-- QR Code Box (Menggunakan QR Code ASLI & Dinamis) -->
            <div class="print-bg-dark bg-slate-900 p-6 rounded-3xl text-center text-white space-y-2 border border-slate-800">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Scan QR Check-in Panitia</p>

                <div class="w-36 h-36 bg-white p-2 rounded-2xl mx-auto shadow-md flex items-center justify-center overflow-hidden">
                    <img id="modal-qr-img" src="" alt="QR Code Tiket" class="w-full h-full object-contain">
                </div>

                <p id="modal-tkt" class="font-mono text-xs font-bold text-slate-300 tracking-wider pt-1"></p>
            </div>

            <!-- Form Ulasan & Penilaian Bintang 1-5 (Soal 1 Bagian 2) -->
            <div id="modal-review-section" class="no-print pt-3 border-t border-slate-200 text-left">
                <form id="review-form" action="" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-extrabold uppercase tracking-wider text-slate-700">Beri Ulasan Event 💬</label>
                        <select name="rating" class="p-1.5 rounded-xl border border-slate-200 text-xs font-bold text-amber-600 bg-slate-50 focus:outline-none" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                            <option value="2">⭐⭐ (2 - Kurang)</option>
                            <option value="1">⭐ (1 - Buruk)</option>
                        </select>
                    </div>
                    <textarea name="comment" rows="2" placeholder="Tuliskan testimoni atau pengalaman Anda mengikuti acara ini..."
                        class="w-full p-3 text-xs border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none resize-none bg-slate-50" required></textarea>
                    <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-xl shadow-md transition cursor-pointer">
                        ⭐ Kirim Ulasan & Rating
                    </button>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-2 no-print">
                <button onclick="window.print()" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition cursor-pointer">
                    🖨️ Cetak / Simpan PDF
                </button>
                <button onclick="closeModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-2xl transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script Modal Pop-up + QR Generator ASLI + Review Form -->
<script>
    function openModal(title, name, date, order, location, status, id, eventId) {
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-name').innerText = name;
        document.getElementById('modal-date').innerText = date;
        document.getElementById('modal-order').innerText = order;
        document.getElementById('modal-location').innerText = location;

        const isPaid = ['paid', 'success', 'settlement'].includes(status.toLowerCase());
        const statusEl = document.getElementById('modal-status');
        statusEl.innerText = status.toUpperCase();
        statusEl.className = `px-3.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider text-white ${isPaid ? 'bg-emerald-500' : 'bg-amber-500'}`;

        const tktCode = 'TKT-' + String(id).padStart(8, '0');
        document.getElementById('modal-tkt').innerText = tktCode;

        // ✨ Generate QR Code ASLI berbasis API
        const qrData = encodeURIComponent(`AMIKOM-EVENT|ORDER:${order}|TKT:${tktCode}`);
        document.getElementById('modal-qr-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${qrData}`;

        // ✨ Set Action Route Form Review Dinamis
        const reviewForm = document.getElementById('review-form');
        const reviewSection = document.getElementById('modal-review-section');
        if (reviewForm) {
            reviewForm.action = `/event/${eventId}/review`;
        }

        // Tampilkan section ulasan hanya jika pembayaran LUNAS
        if (isPaid && reviewSection) {
            reviewSection.classList.remove('hidden');
        } else if (reviewSection) {
            reviewSection.classList.add('hidden');
        }

        document.getElementById('ticket-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('ticket-modal').classList.add('hidden');
    }
</script>
@endsection
