@extends('layouts.admin', ['title' => 'Kelola Partner'])

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kelola Partner</h1>
        <p class="text-sm text-slate-500 mt-1">Manajemen partner pendukung aplikasi web AmikomEventHub.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-slate-900">Tambah Partner Baru</h2>
            <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="mainForm">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Partner</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Bank Amikom, MIKTI"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>
                <div>
                    <label for="logo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Logo Partner</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm">
                    Simpan Partner
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama partner..."
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.partners.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium text-sm px-4 py-2.5 rounded-xl transition flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-4 px-6 w-16">ID</th>
                            <th class="py-4 px-6">Logo</th>
                            <th class="py-4 px-6">Nama Partner</th>
                            <th class="py-4 px-6 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($partners as $partner)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-medium text-slate-400">{{ $partner->id }}</td>
                            <td class="py-4 px-6">
                                @if($partner->logo_url)
                                    <img src="{{ asset($partner->logo_url) }}" alt="Logo" class="h-8 w-auto object-contain rounded">
                                @else
                                    <span class="text-xs text-slate-400 italic">No Logo</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">{{ $partner->name }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button"
                                        data-id="{{ $partner->id }}"
                                        data-name="{{ $partner->name }}"
                                        onclick="openEditModal(this)"
                                        class="text-amber-600 hover:text-amber-700 font-semibold text-xs bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition">
                                        EDIT
                                    </button>
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition">
                                            HAPUS
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">
                                Tidak ada data partner yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Ubah Data Partner</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-semibold">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Partner</label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>
            <div>
                <label for="edit_logo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti Logo (Opsional)</label>
                <input type="file" name="logo" id="edit_logo" accept="image/*"
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl text-sm">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl text-sm shadow-sm">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    // PERBAIKAN: Mengambil data dari objek button HTML secara bersih & perbaikan typo class/List
    function openEditModal(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        document.getElementById('editForm').action = `/admin/partners/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
