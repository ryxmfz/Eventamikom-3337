@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Bar Pencarian -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                Pengawasan Kelayakan Penyelenggara 🛡️
            </h1>
            <p class="text-xs text-slate-500 mt-1">Superadmin Control Panel - Mengatur izin dan kelayakan Organisasi/HIMA dalam mempublikasikan event.</p>
        </div>

        <!-- Bar Pencarian (Search Form) -->
        <form action="{{ route('admin.organizers.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama partner/organisasi..."
                   class="px-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64 bg-white shadow-xs">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-indigo-200 cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <!-- Notifikasi Alert Sukses & Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-center gap-2 shadow-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Data Penyelenggara -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-16">NO</th>
                        <th class="px-6 py-4">ORGANISASI / PENYELENGGARA</th>
                        <th class="px-6 py-4">EMAIL</th>
                        <th class="px-6 py-4">STATUS KELAYAKAN</th>
                        <th class="px-6 py-4 text-center">AKSI SUPERADMIN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($organizers as $index => $org)
                        {{-- 🛡️ FILTER BLADE: Skip jika akun ini milik superadmin/admin --}}
                        @if($org->email === 'admin@amikom.ac.id' || in_array($org->role, ['admin', 'superadmin']))
                            @continue
                        @endif

                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-slate-400 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $org->organization_name ?? $org->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $org->email }}
                            </td>
                            <td class="px-6 py-4">
                                @if($org->organizer_status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full font-bold text-[10px]">
                                        APPROVED 🟢
                                    </span>
                                @elseif($org->organizer_status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-200 rounded-full font-bold text-[10px]">
                                        REJECTED 🔴
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-full font-bold text-[10px]">
                                        PENDING ⏳
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Form Setujui --}}
                                    <form action="{{ route('admin.organizers.approve', $org->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-[11px] transition shadow-xs cursor-pointer">
                                            Setujui (Approve)
                                        </button>
                                    </form>

                                    {{-- Form Tolak --}}
                                    <form action="{{ route('admin.organizers.reject', $org->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-[11px] transition shadow-xs cursor-pointer">
                                            Tolak (Reject)
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                Belum ada data penyelenggara yang terdaftar atau cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
