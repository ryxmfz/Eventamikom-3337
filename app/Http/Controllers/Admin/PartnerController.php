<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap kata kunci pencarian (Soal 3)
        $search = $request->get('search');

        if ($search) {
            // Seleksi hasil berdasarkan nama partner memakai klausa LIKE
            $partners = Partner::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $partners = Partner::all();
        }

        return view('admin.partners.index', compact('partners', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            // Simpan gambar ke folder public/assets/partners agar mudah diakses langsung
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/partners'), $filename);
            $logoUrl = 'assets/partners/' . $filename;
        }

        Partner::create([
            'name' => $request->name,
            'logo_url' => $logoUrl
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner baru berhasil didaftarkan!');
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $logoUrl = $partner->logo_url;
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($partner->logo_url && file_exists(public_path($partner->logo_url))) {
                @unlink(public_path($partner->logo_url));
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/partners'), $filename);
            $logoUrl = 'assets/partners/' . $filename;
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $logoUrl
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil diperbarui!');
    }

    public function destroy(Partner $partner)
    {
        // Hapus file logo dari storage lokal sebelum menghapus record database
        if ($partner->logo_url && file_exists(public_path($partner->logo_url))) {
            @unlink(public_path($partner->logo_url));
        }

        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}
