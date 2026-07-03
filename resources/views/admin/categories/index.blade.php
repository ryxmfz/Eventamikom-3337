@extends('layouts.admin')

@section('content')
    {{-- --- HEADER PANEL --- --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Kategori</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Buat dan atur kategori acara Anda di sini.</p>
        </div>

        {{-- Form Pencarian Elegan (Soal 3) --}}
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari kategori..."
                   class="bg-white border border-slate-200 text-sm font-medium px-5 py-3 rounded-2xl w-full md:w-64 focus:outline-none focus:border-indigo-600 transition shadow-xs">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:scale-105 transition">
                Cari
            </button>
        </form>
    </div>

    {{-- --- FORM INPUT TAMBAH KATEGORI (Soal 1 - Create) --- --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
        <h3 class="text-sm font-black uppercase tracking-wider text-slate-400 mb-4">Tambah Kategori Baru</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
            @csrf
            <input type="text" name="name" required placeholder="Masukkan nama kategori baru..."
                   class="bg-slate-50 border border-slate-100 text-sm font-semibold px-5 py-3.5 rounded-2xl flex-1 focus:outline-none focus:bg-white focus:border-indigo-600 transition">
            <button type="submit" class="px-8 py-3.5 bg-indigo-600 text-white font-black text-sm rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition whitespace-nowrap">
                + Simpan Kategori
            </button>
        </form>
    </div>

    {{-- --- TABEL DATA KATEGORI (Soal 1 & 3) --- --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-[10%]">No</th>
                        <th class="px-8 py-4 w-[65%]">Nama Kategori (Ubah Langsung)</th>
                        <th class="px-8 py-4 text-center w-[25%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50/80 transition">
                            {{-- Nomor Urut Row --}}
                            <td class="px-8 py-5 font-bold text-slate-400 text-sm">
                                {{ $index + 1 }}
                            </td>

                            {{-- Form Inline Edit (Soal 1 - Update) --}}
                            <td class="px-8 py-5">
                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" id="edit-form-{{ $category->id }}" class="flex items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}"
                                           class="bg-transparent border border-transparent font-bold text-slate-800 text-sm px-3 py-1.5 rounded-xl focus:bg-slate-50 focus:border-slate-200 focus:outline-none transition w-full max-w-md">
                                </form>
                            </td>

                            {{-- Tombol Aksi Kotak Sesuai Tema --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    {{-- Submit Edit --}}
                                    <button type="submit" form="edit-form-{{ $category->id }}" title="Simpan Perubahan"
                                            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-100 transition shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>

                                    {{-- Hapus Data (Soal 1 - Delete) --}}
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Kategori"
                                                class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-100 transition shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-slate-400 font-medium text-sm">
                                Belum ada data kategori yang terdaftar atau cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
