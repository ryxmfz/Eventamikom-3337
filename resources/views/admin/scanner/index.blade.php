@extends('layouts.admin')

@section('title', 'QR Check-in Scanner - Admin')
@section('page_title', '📷 Penjaga Pintu (Check-in Scanner)')
@section('page_subtitle', 'Scan QR Code tiket peserta untuk validasi kehadiran di hari-H.')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Box Scanner Kamera --}}
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm text-center">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Arahkan Kamera ke QR Code Tiket</h3>
        <p class="text-xs text-slate-500 mb-6">Pastikan QR Code berada di dalam kotak area pemindaian.</p>

        {{-- Container Kamera HTML5 --}}
        <div id="reader" class="w-full max-w-md mx-auto rounded-2xl overflow-hidden border-2 border-dashed border-indigo-300 bg-slate-50"></div>

        {{-- Input Manual (Fallback / Testing) --}}
        <div class="mt-6 pt-6 border-t border-slate-100 max-w-md mx-auto">
            <label for="manual-code" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 text-left">
                Atau Ketik Order ID Manual:
            </label>
            <div class="flex gap-2">
                <input
                    type="text"
                    id="manual-code"
                    placeholder="Contoh: TRX-1784980428-1Yies"
                    class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none font-mono"
                    onkeypress="if(event.key === 'Enter') submitManualInput()"
                >
                <button
                    type="button"
                    onclick="submitManualInput()"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl text-sm transition-all"
                >
                    Cek Tiket
                </button>
            </div>
        </div>
    </div>

    {{-- Area Alert / Feedback Hasil Scan --}}
    <div id="scan-result" class="hidden p-6 rounded-2xl text-center border font-medium transition-all duration-300">
        <h4 id="result-title" class="text-xl font-black mb-2"></h4>
        <p id="result-message" class="text-sm"></p>
        <div id="result-details" class="mt-4 pt-4 border-t border-black/10 text-xs space-y-1 hidden"></div>
    </div>

</div>

{{-- CDN HTML5-QRCode Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let isProcessing = false;
    let lastScannedCode = null;
    let scanTimeout = null;

    function submitManualInput() {
        const inputElem = document.getElementById('manual-code');
        const code = inputElem.value.trim();
        if (code) {
            processScan(code);
            inputElem.value = ''; // Reset input setelah dikirim
        }
    }

    function processScan(qrCode) {
        if (!qrCode || isProcessing) return;

        // Mencegah scan berulang untuk kode yang sama persis dalam rentang waktu singkat
        if (qrCode === lastScannedCode && isProcessing) return;

        isProcessing = true;
        lastScannedCode = qrCode;

        const resultBox = document.getElementById('scan-result');
        const resultTitle = document.getElementById('result-title');
        const resultMessage = document.getElementById('result-message');
        const resultDetails = document.getElementById('result-details');

        // Reset class styling alert
        resultBox.className = 'p-6 rounded-2xl text-center border font-medium transition-all duration-300';
        resultDetails.classList.add('hidden');

        fetch("{{ route('admin.scanner.scan') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            resultBox.classList.remove('hidden');

            if (res.status === 200) {
                // 🟢 SUKSES CHECK-IN
                resultBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                resultTitle.innerText = "SUCCESS";
                resultMessage.innerText = res.body.message || "Check-in berhasil!";

                if (res.body.data) {
                    resultDetails.classList.remove('hidden');
                    resultDetails.innerHTML = `
                        <p><strong>Nama:</strong> ${res.body.data.nama || '-'}</p>
                        <p><strong>Event:</strong> ${res.body.data.event || '-'}</p>
                        <p><strong>Waktu:</strong> ${res.body.data.waktu || '-'}</p>
                    `;
                }
            } else if (res.status === 400 && res.body.status === 'danger') {
                // ⛔ DOUBLE ENTRY DETECTED
                resultBox.classList.add('bg-rose-50', 'text-rose-800', 'border-rose-200');
                resultTitle.innerText = "DOUBLE ENTRY DETECTED!";
                resultMessage.innerText = res.body.message || "Tiket ini sudah pernah digunakan untuk check-in!";
            } else {
                // ⚠️ WARNING / ERROR LAINNYA
                resultBox.classList.add('bg-amber-50', 'text-amber-800', 'border-amber-200');
                resultTitle.innerText = "SCAN FAILED";
                resultMessage.innerText = res.body.message || "Tiket tidak valid atau tidak ditemukan.";
            }
        })
        .catch(err => {
            console.error("Scan error:", err);
            resultBox.classList.remove('hidden');
            resultBox.classList.add('bg-rose-50', 'text-rose-800', 'border-rose-200');
            resultTitle.innerText = "NETWORK ERROR";
            resultMessage.innerText = "Gagal terhubung ke server. Periksa koneksi internet Anda.";
        })
        .finally(() => {
            // Jeda 2.5 detik sebelum siap scan tiket berikutnya
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(() => {
                isProcessing = false;
                lastScannedCode = null; // Reset agar kode yang sama bisa di-scan ulang jika memang diperlukan
            }, 2500);
        });
    }

    // Callback saat scanner berhasil membaca QR
    function onScanSuccess(decodedText, decodedResult) {
        processScan(decodedText);
    }

    // Inisialisasi Scanner
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        /* verbose= */ false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection
