<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CheckInController extends Controller
{
    // Tampilkan Halaman Kamera QR Scanner
    public function index()
    {
        return view('admin.scanner.index');
    }

    // Proses Logika Scan Kode QR via AJAX
    public function scan(Request $request)
    {
        $rawCode = trim($request->qr_code ?? $request->code ?? '');

        if (!$rawCode) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ KODE QR / ORDER ID TIDAK BOLEH KOSONG!'
            ], 400);
        }

        // 🔍 Extrak data jika QR Code berisi format: AMIKOM-EVENT|ORDER:TRX-xxx|TKT:TKT-xxx
        $code = $rawCode;
        $extractedOrderId = null;
        $extractedTktCode = null;

        if (str_contains($rawCode, '|')) {
            $parts = explode('|', $rawCode);
            foreach ($parts as $part) {
                if (str_starts_with($part, 'ORDER:')) {
                    $extractedOrderId = str_replace('ORDER:', '', $part);
                }
                if (str_starts_with($part, 'TKT:')) {
                    $extractedTktCode = str_replace('TKT:', '', $part);
                }
            }
        }

        // 🔍 Query Pencarian Database Fleksibel (Mencari Order ID, Ticket Code, maupun ID Transaksi)
        $query = Transaction::with('event')->where(function ($q) use ($rawCode, $code, $extractedOrderId, $extractedTktCode) {
            $q->where('order_id', $rawCode)
              ->orWhere('order_id', $code);

            if ($extractedOrderId) {
                $q->orWhere('order_id', $extractedOrderId);
            }

            if (Schema::hasColumn('transactions', 'ticket_code')) {
                $q->orWhere('ticket_code', $rawCode)
                  ->orWhere('ticket_code', $code);
                if ($extractedTktCode) {
                    $q->orWhere('ticket_code', $extractedTktCode);
                }
            }

            // Fallback: Jika TKT-00000001 merujuk pada ID Transaksi
            $targetTkt = $extractedTktCode ?? $code;
            if (preg_match('/TKT-0*(\d+)/i', $targetTkt, $matches)) {
                $q->orWhere('id', $matches[1]);
            }
        });

        $transaction = $query->first();

        // ❌ A. Jika Kode Tiket Tidak Ditemukan
        if (!$transaction) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ TIKET TIDAK VALID / TIDAK DITEMUKAN!'
            ], 404);
        }

        // ❌ B. Jika Tiket Belum Lunas
        $status = strtolower($transaction->status ?? '');
        if (!in_array($status, ['paid', 'success', 'settlement'])) {
            return response()->json([
                'status'  => 'warning',
                'message' => '⚠️ TIKET BELUM LUNAS! Status: ' . strtoupper($transaction->status)
            ], 400);
        }

        // 🚫 C. CEGAH DOUBLE ENTRY: Jika Tiket Sudah Pernah Di-scan
        if ($transaction->is_checked_in) {
            $waktuCheckin = $transaction->checked_in_at
                ? Carbon::parse($transaction->checked_in_at)->format('H:i:s, d M Y')
                : 'sebelumnya';

            $namaPembeli = $transaction->customer_name ?? $transaction->name ?? 'Peserta';

            return response()->json([
                'status'  => 'danger',
                'message' => '⛔ DOUBLE ENTRY DETECTED! Tiket ini SUDAH DIGUNAKAN pada ' . $waktuCheckin . ' oleh ' . $namaPembeli,
                'data'    => $transaction
            ], 400);
        }

        // 🟢 D. CHECK-IN BERHASIL
        $transaction->is_checked_in = true;
        $transaction->checked_in_at = now();
        $transaction->save();

        $namaPembeli = $transaction->customer_name ?? $transaction->name ?? 'Peserta';
        $emailPembeli = $transaction->customer_email ?? $transaction->email ?? '-';

        return response()->json([
            'status'  => 'success',
            'message' => '✅ CHECK-IN BERHASIL! Selamat Datang, ' . $namaPembeli . ' 🎉',
            'data'    => [
                'nama'  => $namaPembeli,
                'email' => $emailPembeli,
                'event' => $transaction->event->title ?? '-',
                'waktu' => now()->format('H:i:s WIB')
            ]
        ]);
    }
}
