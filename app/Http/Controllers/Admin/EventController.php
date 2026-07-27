<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    public function index() {
        $user = Auth::user();
        $isSuperadmin = ($user->role === 'superadmin' || $user->is_admin == 1);

        // 🎯 MULTI-TENANT: Superadmin lihat semua event, Organizer hanya lihat event milik sendiri
        // ✨ Eager loading relasi 'user' agar nama penyelenggara langsung terbaca
        if ($isSuperadmin) {
            $events = Event::with(['category', 'user'])->latest()->paginate(10);
        } else {
            if (Schema::hasColumn('events', 'user_id')) {
                $events = Event::where('user_id', $user->id)->with(['category', 'user'])->latest()->paginate(10);
            } else {
                $events = Event::with(['category', 'user'])->latest()->paginate(10);
            }
        }

        return view('admin.events.index', compact('events'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required|date',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'required|image|mimes:jpg,png,jpeg|max:20480',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($data['poster']); // Hapus key poster agar tidak bentrok dengan database

        // 🎯 KUNCI MULTI-TENANT: Hubungkan event ini ke ID User/Organizer yang sedang login
        if (Schema::hasColumn('events', 'user_id')) {
            $data['user_id'] = Auth::id();
        }

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event) {
        $user = Auth::user();
        $isSuperadmin = ($user->role === 'superadmin' || $user->is_admin == 1);

        // 🛡️ PROTEKSI: Cek apakah user berhak mengedit event ini
        if (!$isSuperadmin && $event->user_id && $event->user_id != $user->id) {
            return redirect()->route('admin.events.index')->with('error', 'Anda tidak memiliki hak akses untuk mengedit event ini.');
        }

        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event) {
        $user = Auth::user();
        $isSuperadmin = ($user->role === 'superadmin' || $user->is_admin == 1);

        // 🛡️ PROTEKSI: Cek apakah user berhak mengupdate event ini
        if (!$isSuperadmin && $event->user_id && $event->user_id != $user->id) {
            return redirect()->route('admin.events.index')->with('error', 'Anda tidak memiliki hak akses untuk memperbarui event ini.');
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'nullable|image|mimes:jpg,png,jpeg|max:20480',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($data['poster']);

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event) {
        $user = Auth::user();
        $isSuperadmin = ($user->role === 'superadmin' || $user->is_admin == 1);

        // 🛡️ PROTEKSI: Cek apakah user berhak menghapus event ini
        if (!$isSuperadmin && $event->user_id && $event->user_id != $user->id) {
            return redirect()->route('admin.events.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus event ini.');
        }

        if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
