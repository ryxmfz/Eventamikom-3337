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
        $code = trim($request->qr_code);

        // 1. Cari Transaksi berdasarkan Order ID (Pencegahan SQL Error jika ticket_code belum ada)
        $query = Transaction::with('event')->where('order_id', $code);

        if (Schema::hasColumn('transactions', 'ticket_code')) {
            $query->orWhere('ticket_code', $code);
        }

        $transaction = $query->first();

        // ❌ A. Jika Kode Tiket Tidak Ditemukan
        if (!$transaction) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ TIKET TIDAK VALID / TIDAK DITEMUKAN!'
            ], 404);
        }

        // ❌ B. Jika Tiket Belum Lunas
        if (!in_array(strtolower($transaction->status), ['paid', 'success', 'settlement'])) {
            return response()->json([
                'status'  => 'warning',
                'message' => '⚠️ TIKET BELUM LUNAS! Status: ' . strtoupper($transaction->status)
            ], 400);
        }

        // 🚫 C. CEGAH DOUBLE ENTRY: Jika Tiket Sudah Pernah Di-scan (Sudah Digunakan)
        if ($transaction->is_checked_in) {
            $waktuCheckin = $transaction->checked_in_at
                ? Carbon::parse($transaction->checked_in_at)->format('H:i:s, d M Y')
                : 'sebelumnya';

            return response()->json([
                'status'  => 'danger',
                'message' => '⛔ DOUBLE ENTRY DETECTED! Tiket ini SUDAH DIGUNAKAN pada ' . $waktuCheckin . ' oleh ' . $transaction->customer_name,
                'data'    => $transaction
            ], 400);
        }

        // 🟢 D. CHECK-IN BERHASIL (Aman dari MassAssignmentException)
        $transaction->is_checked_in = true;
        $transaction->checked_in_at = now();
        $transaction->save();

        return response()->json([
            'status'  => 'success',
            'message' => '✅ CHECK-IN BERHASIL! Selamat Datang, ' . $transaction->customer_name . ' 🎉',
            'data'    => [
                'nama'  => $transaction->customer_name,
                'email' => $transaction->customer_email,
                'event' => $transaction->event->title ?? '-',
                'waktu' => now()->format('H:i:s WIB')
            ]
        ]);
    }
}
