<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    // READ + SEARCH BASIC
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->get();

        return view('admin.partners.index', compact('partners', 'search'));
    }

    // CREATE (Proses Upload File Foto)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,png,jpeg|max:5120' // Maksimal 5MB
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            // Menyimpan file ke folder storage/app/public/partners
            $data['logo_url'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($data);
        return redirect()->back()->with('success', 'Partner Berhasil Ditambahkan');
    }

    // UPDATE (Proses Update File Foto)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:5120'
        ]);

        $partner = Partner::findOrFail($id);
        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            // Hapus logo lama dari storage jika ada
            if ($partner->logo_url) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            // Simpan logo baru
            $data['logo_url'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);
        return redirect()->back()->with('success', 'Partner Berhasil Diperbarui');
    }

    // DELETE (Proses Hapus Sekaligus File Fotonya)
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        if ($partner->logo_url) {
            Storage::disk('public')->delete($partner->logo_url);
        }
        $partner->delete();
        return redirect()->back()->with('success', 'Partner Berhasil Dihapus');
    }
}
