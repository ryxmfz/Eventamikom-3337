<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $event_id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $event = Event::findOrFail($event_id);

        // 🎯 CEK 1: Validasi Transaksi Aman (Bebas SQL Column Error)
        $allEventTransactions = Transaction::where('event_id', $event_id)->get();

        $hasPaidTicket = $allEventTransactions->contains(function ($item) use ($user) {
            // Check status harus paid/settlement/success
            $status = strtolower($item->status ?? '');
            $isPaid = in_array($status, ['paid', 'settlement', 'success']);

            if (!$isPaid) return false;

            // Check kaitan user (via user_id, email, atau name) secara safe via PHP
            $userEmail = strtolower(trim($user->email ?? ''));
            $userName  = strtolower(trim($user->name ?? ''));

            $trxUserId = $item->user_id ?? null;
            $trxEmail  = strtolower(trim($item->email ?? $item->customer_email ?? $item->user_email ?? ''));
            $trxName   = strtolower(trim($item->name ?? $item->customer_name ?? $item->nama ?? ''));

            if ($trxUserId && $trxUserId == $user->id) return true;
            if (!empty($userEmail) && !empty($trxEmail) && $userEmail === $trxEmail) return true;
            if (!empty($userName) && !empty($trxName) && ($userName === $trxName || str_contains($userName, $trxName))) return true;

            return false;
        });

        if (!$hasPaidTicket) {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan untuk event yang telah Anda beli tiketnya.');
        }

        // 🎯 CEK 2: ATURAN SYARAT SOAL -> BISA DIISI SEHARI SETELAH ACARA TUNTAS (H+1)
        $eventDate = Carbon::parse($event->date);
        $reviewAllowedDate = $eventDate->copy()->addDay()->startOfDay();

        if (Carbon::now()->lt($reviewAllowedDate)) {
            return back()->with('error', 'Ulasan dan penilaian baru dapat diberikan mulai tanggal ' . $reviewAllowedDate->format('d M Y') . ' (sehari setelah acara selesai).');
        }

        // 🎯 CEK 3: Apakah user sudah pernah isi ulasan di event ini?
        $existingReview = Review::where('user_id', $user->id)
            ->where('event_id', $event_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk acara ini.');
        }

        // Simpan Review jika lolos semua kriteria
        Review::create([
            'user_id'  => $user->id,
            'event_id' => $event_id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda telah berhasil dikirim.');
    }
}
