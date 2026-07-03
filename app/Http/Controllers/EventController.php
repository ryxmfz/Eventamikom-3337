<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category; 
use App\Models\Partner;

class EventController extends Controller
{
    public function index()
    {
        // Tetap mempertahankan pengambilan data event bawaan praktikum kamu
        $events = Event::with('category')->latest()->get();


        $categories = Category::all();
        $partners = Partner::all();


        return view('welcome', compact('events', 'categories', 'partners'));
    }

    public function show(Event $event)
    {
        // 🛠️ FIX: Ubah dari 'events.show' menjadi 'event-detail' agar pas dengan nama file Blademu
        return view('event-detail', compact('event'));
    }
}
