@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page_title', 'Dashboard Ringkasan')

@section('content')
    {{-- 📊 4 KARTU RINGKASAN KPI UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Pendapatan --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        {{-- Tiket Terjual --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
            <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }} Tiket</h3>
        </div>

        {{-- Event Aktif --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
            <h3 class="text-2xl font-black">{{ $activeEvents }} Event</h3>
        </div>

        {{-- Pesanan Pending --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
            <h3 class="text-2xl font-black">{{ $pendingOrders }} Pesanan</h3>
        </div>
    </div>

    {{-- 📈 BAGIAN GRAFIK ANALYTICS DENGAN FILTER RENTANG WAKTU --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">

        {{-- Grafik Tren Multi-Filter --}}
        <div class="lg:col-span-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b pb-4">
                <div>
                    <h3 class="font-black text-xl text-slate-800">Pertumbuhan Event & Tiket Terjual</h3>
                    <p class="text-xs text-slate-400 font-medium" id="chartSubtitle">Tren performa platform berdasarkan rentang waktu terpilih</p>
                </div>

                {{-- 🎛️ DROPDOWN FILTER RENTANG WAKTU --}}
                <div class="flex items-center gap-2">
                    <label for="chartFilterSelect" class="text-xs font-bold text-slate-400">Filter:</label>
                    <select id="chartFilterSelect" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl text-xs border border-indigo-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-xs">
                        <option value="today">Hari Ini</option>
                        <option value="1_week">1 Minggu Terakhir</option>
                        <option value="1_month">1 Bulan Terakhir</option>
                        <option value="6_months" selected>6 Bulan Terakhir</option>
                        <option value="1_year">1 Tahun Terakhir</option>
                    </select>
                </div>
            </div>

            <div class="relative w-full h-[300px]">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        {{-- Grafik Distribusi Kategori --}}
        <div class="lg:col-span-4 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between space-y-4">
            <div class="border-b pb-4">
                <h3 class="font-black text-xl text-slate-800">Distribusi Kategori</h3>
                <p class="text-xs text-slate-400 font-medium">Proporsi event berdasarkan kategori</p>
            </div>

            <div class="relative w-full h-[220px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>

            <p class="text-[11px] text-center text-slate-400 font-medium">
                * Statistik real-time dari database
            </p>
        </div>

    </div>

    {{-- 💳 TABEL TRANSAKSI TERAKHIR --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center">
            <h3 class="font-black text-xl">Transaksi Terakhir</h3>
            <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 font-bold hover:underline">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-1/4">Tgl Transaksi</th>
                        <th class="px-8 py-4 w-1/4">Pembeli</th>
                        <th class="px-8 py-4 w-1/4">Event</th>
                        <th class="px-8 py-4 w-[10%]">Status</th>
                        <th class="px-8 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-8 py-6 text-sm text-slate-600 max-w-xs break-all">
                                {{ $trx->created_at ? $trx->created_at->format('d M Y - H:i') : '-' }}<br>
                                <span class="text-xs text-slate-400 font-mono">{{ $trx->order_id }}</span>
                            </td>

                            <td class="px-8 py-6">
                                <p class="font-bold uppercase tracking-wide text-sm truncate max-w-[150px]">
                                    {{ $trx->customer_name }}
                                </p>
                                <p class="text-xs text-slate-400 truncate max-w-[150px]">
                                    {{ $trx->customer_email }}
                                </p>
                            </td>

                            <td class="px-8 py-6 font-medium text-slate-600 max-w-xs truncate">
                                {{ $trx->event->title ?? '-' }}
                            </td>

                            <td class="px-8 py-6 whitespace-nowrap">
                                @if(in_array(strtolower($trx->status), ['paid', 'settlement', 'success', 'capture']))
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">PAID</span>
                                @elseif(strtolower($trx->status) === 'pending')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">PENDING</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">
                                        {{ $trx->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-8 py-6 font-black text-indigo-600 whitespace-nowrap text-right">
                                Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-500">
                                Belum ada data transaksi masuk saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CDN & LOGIKA CHART.JS DENGAN LISTENER FILTER REALTIME --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Master Data Filter dari Controller
            const chartFilters = {!! json_encode($chartFilters ?? []) !!};

            const categoryLabels = {!! json_encode($categoryLabels ?? []) !!};
            const categoryData = {!! json_encode($categoryData ?? []) !!};

            // Default awal: 6 Bulan Terakhir
            const defaultKey = '6_months';
            const initialData = chartFilters[defaultKey] || { labels: [], events: [], tickets: [] };

            // 1. Line Chart: Pertumbuhan Event & Tiket Terjual
            const ctxGrowth = document.getElementById('growthChart').getContext('2d');
            const growthChart = new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: initialData.labels,
                    datasets: [
                        {
                            label: 'Tiket Terjual',
                            data: initialData.tickets,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4
                        },
                        {
                            label: 'Event Dibuat',
                            data: initialData.events,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 12 } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    }
                }
            });

            // 🎛️ EVENT LISTENER: GANTI DATA GRAFIK SECARA REAL-TIME SAAT DROPDOWN DIUBAH
            const filterSelect = document.getElementById('chartFilterSelect');
            filterSelect.addEventListener('change', function () {
                const selectedKey = this.value;
                const newData = chartFilters[selectedKey];

                if (newData) {
                    growthChart.data.labels = newData.labels;
                    growthChart.data.datasets[0].data = newData.tickets;
                    growthChart.data.datasets[1].data = newData.events;
                    growthChart.update();
                }
            });

            // 2. Doughnut Chart: Kategori Event
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: [
                            '#4f46e5', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#3b82f6'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 11 }, padding: 12 }
                        }
                    },
                    cutout: '68%'
                }
            });
        });
    </script>
@endsection
