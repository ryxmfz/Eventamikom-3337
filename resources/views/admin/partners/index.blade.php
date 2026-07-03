@extends('layouts.admin')

@section('content')
    {{-- --- HEADER PANEL --- --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Partner</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Buat dan atur partner resmi Anda di sini.</p>
        </div>

        {{-- Form Pencarian Elegan --}}
        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama partner..."
                   class="bg-white border border-slate-200 text-sm font-medium px-5 py-3 rounded-2xl w-full md:w-64 focus:outline-none focus:border-indigo-600 transition shadow-xs">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-100 hover:scale-105 transition">
                Cari
            </button>
        </form>
    </div>

    {{-- --- FORM INPUT TAMBAH PARTNER (Mendukung Pilih File Foto) --- --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
        <h3 class="text-sm font-black uppercase tracking-wider text-slate-400 mb-4">Tambah Partner Baru</h3>
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            <div class="md:col-span-2">
                <input type="text" name="name" required placeholder="Nama perusahaan partner..."
                       class="bg-slate-50 border border-slate-100 text-sm font-semibold px-5 py-3.5 rounded-2xl w-full focus:outline-none focus:bg-white focus:border-indigo-600 transition">
            </div>
            <div class="md:col-span-2">
                {{-- Input bertipe File untuk mengambil foto dari komputer --}}
                <input type="file" name="logo" required
                       class="bg-slate-50 border border-slate-100 text-sm font-semibold px-5 py-2.5 rounded-2xl w-full focus:outline-none focus:bg-white focus:border-indigo-600 transition file:mr-4 file:py-1 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <button type="submit" class="px-6 py-3.5 bg-indigo-600 text-white font-black text-sm rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition whitespace-nowrap">
                + Simpan Partner
            </button>
        </form>
    </div>

    {{-- --- TABEL DATA PARTNER --- --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-[8%]">No</th>
                        <th class="px-8 py-4 w-[18%]">Logo</th>
                        <th class="px-8 py-4 w-[49%]">Detail Partner & Perubahan Instan</th>
                        <th class="px-8 py-4 text-center w-[25%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($partners as $index => $partner)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-8 py-6 font-bold text-slate-400 text-sm">
                                {{ $index + 1 }}
                            </td>

                            {{-- Render Foto Terupload --}}
                            <td class="px-8 py-6">
                                <div class="w-20 h-14 bg-slate-50 border border-slate-100 rounded-xl overflow-hidden shadow-2xs flex items-center justify-center p-1.5">
                                    @if($partner->logo_url)
                                        <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="text-[10px] text-slate-300">No Image</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Form Edit Inline + Opsional Ganti Foto --}}
                            <td class="px-8 py-6">
                                <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" id="partner-form-{{ $partner->id }}" class="flex flex-col gap-2 max-w-xl">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $partner->name }}" title="Nama Partner"
                                           class="bg-transparent border border-transparent font-bold text-slate-800 text-sm px-2 py-1 rounded-lg focus:bg-slate-50 focus:border-slate-200 focus:outline-none transition w-full">
                                    <div class="flex items-center gap-2 px-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase whitespace-nowrap">Ganti Foto:</span>
                                        <input type="file" name="logo" class="text-xs text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-600">
                                    </div>
                                </form>
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="submit" form="partner-form-{{ $partner->id }}" title="Simpan Perubahan"
                                            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-100 transition shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>

                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Partner"
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
                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium text-sm">
                                Belum ada data partner yang terdaftar atau cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
