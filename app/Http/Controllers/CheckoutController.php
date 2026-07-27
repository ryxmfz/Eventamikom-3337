<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);

        // =========================================================================
        // ⚡ FITUR SOAL 2: BYPASS TRANSAKSI EVENT GRATIS (Rp 0)
        // =========================================================================
        if ($event->price == 0) {
            // A. Rekam Transaksi Langsung LUNAS (PAID) tanpa biaya admin
            $transaction = Transaction::create([
                'event_id'       => $event->id,
                'order_id'       => $orderId,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price'    => 0,
                'status'         => 'PAID', // Langsung Lunas tanpa Midtrans
                'snap_token'     => null,
            ]);

            // B. Kurangi Stok Tiket Secara Real-time
            $event->decrement('stock');

            // C. Redirect Langsung ke Halaman Sukses
            return redirect()->route('checkout.success', $transaction->order_id)
                             ->with('success', '🎉 Berhasil! Tiket gratis Anda telah diterbitkan.');
        }

        // =========================================================================
        // 💳 ALUR REGULER: EVENT BERBAYAR (> Rp 0) LEWAT MIDTRANS
        // =========================================================================
        $totalPrice = $event->price + 5000; // Menambahkan biaya admin (dummy)

        // 4. Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
            'snap_token'     => null,
        ]);

        // --- INTEGRASI SNAP MIDTRANS ---
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY') ?? 'SB-Mid-server-uSBmZ6AEEbeXxV5w8KB6_5e6';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = 0;
        \Midtrans\Config::$curlOptions[CURLOPT_HTTPHEADER] = [];

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::where('order_id', $order_id)->firstOrFail();

        // 🛡️ AMAN: Jika transaksi event gratis / status sudah LUNAS, tidak perlu panggil Midtrans API
        if ($transaction->total_price == 0 || in_array(strtolower($transaction->status), ['paid', 'success'])) {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        try {
            $midtransStatus = \Midtrans\Transaction::status($order_id);

            if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                $transaction->update(['status' => 'success']);
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}
