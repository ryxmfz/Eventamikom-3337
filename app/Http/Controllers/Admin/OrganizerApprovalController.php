<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerApprovalController extends Controller
{
    // Tampilkan daftar semua Penyelenggara/HIMA + Fitur Pencarian Dinamis
    public function index(Request $request)
    {
        $query = User::where(function ($q) {
            $q->whereIn('role', ['organizer', 'superadmin', 'admin'])
              ->orWhereNotNull('organization_name');
        });

        // 🔍 Tangkap kata kunci dari form pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organization_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $organizers = $query->latest()->get();

        return view('admin.organizers.index', compact('organizers'));
    }

    // Superadmin Menyetujui Kelayakan Penyelenggara
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'organizer_status' => 'approved',
            'is_admin'         => 1 // Berikan akses dashboard
        ]);

        $namaPenyelenggara = $user->organization_name ?? $user->name;

        return back()->with('success', 'Penyelenggara ' . $namaPenyelenggara . ' BERHASIL DISETUJUI (APPROVED)!');
    }

    // Superadmin Menolak / Mensuspend Penyelenggara
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'organizer_status' => 'rejected',
            'is_admin'         => 0
        ]);

        $namaPenyelenggara = $user->organization_name ?? $user->name;

        return back()->with('success', 'Penyelenggara ' . $namaPenyelenggara . ' telah ditolak/dibatasi.');
    }
}
