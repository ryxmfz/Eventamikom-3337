<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index() {
        // Menggunakan paginate agar sesuai dengan instruksi modul sebelumnya
        $events = Event::with('category')->latest()->paginate(10);
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
            'poster'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            // Simpan file ke folder 'posters' di disk 'public'
            $path = $request->file('poster')->store('posters', 'public');
            $data['poster_path'] = $path;
        }

        // Hapus key 'poster' agar tidak menyebabkan error saat create()
        // karena di database kolomnya bernama 'poster_path'
        unset($data['poster']);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event) {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus poster lama jika ada
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($data['poster']);

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event) {
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
