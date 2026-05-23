@extends('layouts.admin', ['title' => 'Kelola Kategori'])

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kelola Kategori</h1>
            <p class="text-sm text-slate-500 mt-1">Manajemen kategori untuk mengelompokkan event di AmikomEventHub.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-slate-900">Tambah Kategori Baru</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Seminar, Workshop"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kategori..."
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.categories.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium text-sm px-4 py-2.5 rounded-xl transition flex items-center">
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
                            <th class="py-4 px-6">Nama Kategori</th>
                            <th class="py-4 px-6 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-medium text-slate-400">{{ $category->id }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-900">{{ $category->name }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')"
                                        class="text-amber-600 hover:text-amber-700 font-semibold text-xs tracking-wide bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition">
                                        EDIT
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-700 font-semibold text-xs tracking-wide bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition">
                                            HAPUS
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 italic">
                                Tidak ada data kategori yang ditemukan.
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
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4 transform transition-all">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Ubah Nama Kategori</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-semibold">&times;</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, currentName) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const input = document.getElementById('edit_name');

        // Atur action form dinamis berdasarkan ID kategori yang dipilih
        form.action = `/admin/categories/${id}`;
        // Isi input text dengan nama kategori saat ini
        input.value = currentName;

        // Tampilkan modal ke layar
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
