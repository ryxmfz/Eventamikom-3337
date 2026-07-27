<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrganizerController extends Controller
{
    public function show($id)
    {
        // 1. Cari Penyelenggara / User
        $organizer = User::find($id);

        if (!$organizer) {
            $organizer = (object)[
                'id' => $id,
                'name' => 'AMIKOM EVENT HUB',
                'email' => 'admin@amikom.ac.id'
            ];
        } else {
            // ✨ Jika nama akun Admin Amikom, ubah tampilan namanya menjadi AMIKOM EVENT HUB
            if (strtolower($organizer->name) === 'admin amikom' || $organizer->id == 1) {
                $organizer->name = 'AMIKOM EVENT HUB';
            }
        }

        // 2. Ambil event milik penyelenggara secara aman
        if (Schema::hasColumn('events', 'user_id')) {
            $events = Event::where('user_id', $id)->latest()->get();
        } elseif (Schema::hasColumn('events', 'organizer_id')) {
            $events = Event::where('organizer_id', $id)->latest()->get();
        } elseif (Schema::hasColumn('events', 'partner_id')) {
            $events = Event::where('partner_id', $id)->latest()->get();
        } else {
            $events = Event::latest()->get();
        }

        $eventIds = $events->pluck('id');

        // 3. Ambil seluruh rekam jejak ulasan & rating dari event terkait
        $reviews = Review::whereIn('event_id', $eventIds)
            ->with(['user', 'event'])
            ->latest()
            ->get();

        // 4. Hitung Statistik Rekam Jejak
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

        return view('organizer.show', compact('organizer', 'events', 'reviews', 'totalReviews', 'averageRating'));
    }
}
