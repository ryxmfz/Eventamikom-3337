<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {

        $events = Event::with('category')->latest()->get();
        return view('welcome', compact('events'));
    }

    public function show(Event $event)
    {
       
        return view('events.show', compact('event'));
    }
}
