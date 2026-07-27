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
        // Pengambilan data event bawaan praktikum
        $events = Event::with('category')->latest()->get();
        $categories = Category::all();
        $partners = Partner::all();

        return view('welcome', compact('events', 'categories', 'partners'));
    }

    public function show(Event $event)
    {
        return view('event-detail', compact('event'));
    }

    // ✨ FIX: Mengarahkan langsung ke 'ticket'
    public function ticket()
    {
        return view('ticket');
    }
}
