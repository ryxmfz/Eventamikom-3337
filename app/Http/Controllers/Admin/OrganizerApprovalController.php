<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerApprovalController extends Controller
{
    // Tampilkan daftar semua Penyelenggara/HIMA + Fitur Pencarian Dinamis (Kecuali Superadmin/Admin)
    public function index(Request $request)
    {
        // 🛡️ Filter Mutlak: Buang email admin utama & role superadmin/admin
        $query = User::where('email', '!=', 'admin@amikom.ac.id')
            ->whereNotIn('role', ['superadmin', 'admin'])
            ->where(function ($q) {
                $q->where('role', 'organizer')
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

        // 🛡️ Proteksi Tambahan Backend: Cegah perubahan pada akun admin/superadmin
        if (in_array($user->role, ['admin', 'superadmin']) || $user->email === 'admin@amikom.ac.id') {
            return back()->with('error', 'Aksi Ditolak: Status Superadmin tidak boleh diubah!');
        }

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

        // 🛡️ Proteksi Tambahan Backend: Cegah penolakan pada akun admin/superadmin
        if (in_array($user->role, ['admin', 'superadmin']) || $user->email === 'admin@amikom.ac.id') {
            return back()->with('error', 'Aksi Ditolak: Superadmin tidak dapat ditolak/dibatasi!');
        }

        $user->update([
            'organizer_status' => 'rejected',
            'is_admin'         => 0
        ]);

        $namaPenyelenggara = $user->organization_name ?? $user->name;

        return back()->with('success', 'Penyelenggara ' . $namaPenyelenggara . ' telah ditolak/dibatasi.');
    }

    // 🗑️ Superadmin Menghapus Akun Penyelenggara Secara Permanen
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 🛡️ Proteksi Tambahan Backend: Cegah penghapusan pada akun admin/superadmin
        if (in_array($user->role, ['admin', 'superadmin']) || $user->email === 'admin@amikom.ac.id') {
            return back()->with('error', 'Aksi Ditolak: Superadmin tidak dapat dihapus!');
        }

        $namaPenyelenggara = $user->organization_name ?? $user->name;

        $user->delete();

        return back()->with('success', 'Penyelenggara ' . $namaPenyelenggara . ' BERHASIL DIHAPUS PERMANEN!');
    }
}
