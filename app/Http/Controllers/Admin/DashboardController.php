<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;        // Import Model Event
use App\Models\Transaction;  // Import Model Transaction
use App\Models\User;         // Import Model User
use App\Models\Category;     // Import Model Category
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🎯 CEK LEVEL AKUN (SUPERADMIN vs ORGANIZER / TENANT HIMA)
        $isSuperadmin = ($user->role === 'superadmin' || $user->is_admin == 1);

        // Pengecekan ketersediaan kolom quantity di database
        $hasQuantityColumn = Schema::hasColumn('transactions', 'quantity');

        if ($isSuperadmin) {
            // 🌐 SUPERADMIN: Lihat seluruh data platform
            $totalRevenue       = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->sum('total_price');

            $ticketsSold        = $hasQuantityColumn
                ? Transaction::whereIn('status', ['settlement', 'success', 'paid'])->sum('quantity')
                : Transaction::whereIn('status', ['settlement', 'success', 'paid'])->count();

            $activeEvents       = Event::where('date', '>=', now())->count();
            $pendingOrders      = Transaction::where('status', 'pending')->count();
            $totalEvents        = Event::count();
            $totalUsers         = User::count();
            $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

            $pendingOrganizersCount = User::where('role', 'organizer')->where('organizer_status', 'pending')->count();

        } else {
            // 🏢 ORGANIZER / TENANT: Data khusus milik sendiri
            if (Schema::hasColumn('events', 'user_id')) {
                $myEventIds = Event::where('user_id', $user->id)->pluck('id');
            } else {
                $myEventIds = Event::pluck('id');
            }

            $totalRevenue  = Transaction::whereIn('event_id', $myEventIds)
                ->whereIn('status', ['settlement', 'success', 'paid'])
                ->sum('total_price');

            $ticketsSold   = $hasQuantityColumn
                ? Transaction::whereIn('event_id', $myEventIds)->whereIn('status', ['settlement', 'success', 'paid'])->sum('quantity')
                : Transaction::whereIn('event_id', $myEventIds)->whereIn('status', ['settlement', 'success', 'paid'])->count();

            $activeEvents  = Event::whereIn('id', $myEventIds)
                ->where('date', '>=', now())
                ->count();

            $pendingOrders = Transaction::whereIn('event_id', $myEventIds)
                ->where('status', 'pending')
                ->count();

            $totalEvents   = Event::whereIn('id', $myEventIds)->count();
            $totalUsers    = User::count();

            $recentTransactions = Transaction::whereIn('event_id', $myEventIds)
                ->with('event')
                ->latest()
                ->take(5)
                ->get();

            $pendingOrganizersCount = 0;
        }

        // 📊 GRAFIK KATEGORI
        $allCategories  = Category::all();
        $categoryLabels = $allCategories->pluck('name');
        $categoryData   = [];

        foreach ($allCategories as $cat) {
            if ($isSuperadmin) {
                $categoryData[] = Event::where('category_id', $cat->id)->count();
            } else {
                $categoryData[] = Event::whereIn('id', $myEventIds)->where('category_id', $cat->id)->count();
            }
        }

        // 📈 KALKULASI DATA TREN DENGAN MULTI-RENTANG WAKTU (FILTER GRAFIK)
        $chartFilters = [
            'today'    => ['labels' => [], 'events' => [], 'tickets' => []],
            '1_week'   => ['labels' => [], 'events' => [], 'tickets' => []],
            '1_month'  => ['labels' => [], 'events' => [], 'tickets' => []],
            '6_months' => ['labels' => [], 'events' => [], 'tickets' => []],
            '1_year'   => ['labels' => [], 'events' => [], 'tickets' => []],
        ];

        // 1. Filter: Hari Ini (Interval 4 Jam)
        for ($h = 0; $h < 24; $h += 4) {
            $start = Carbon::today()->addHours($h);
            $end   = $start->copy()->addHours(4);
            $chartFilters['today']['labels'][] = $start->format('H:i');

            $chartFilters['today']['events'][] = $isSuperadmin
                ? Event::whereBetween('created_at', [$start, $end])->count()
                : Event::whereIn('id', $myEventIds)->whereBetween('created_at', [$start, $end])->count();

            $queryTrx = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->whereBetween('created_at', [$start, $end]);
            if (!$isSuperadmin) $queryTrx->whereIn('event_id', $myEventIds);
            $chartFilters['today']['tickets'][] = $hasQuantityColumn ? $queryTrx->sum('quantity') : $queryTrx->count();
        }

        // 2. Filter: 1 Minggu Terakhir (7 Hari)
        for ($d = 6; $d >= 0; $d--) {
            $date = Carbon::today()->subDays($d);
            $chartFilters['1_week']['labels'][] = $date->format('d M');

            $chartFilters['1_week']['events'][] = $isSuperadmin
                ? Event::whereDate('created_at', $date)->count()
                : Event::whereIn('id', $myEventIds)->whereDate('created_at', $date)->count();

            $queryTrx = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->whereDate('created_at', $date);
            if (!$isSuperadmin) $queryTrx->whereIn('event_id', $myEventIds);
            $chartFilters['1_week']['tickets'][] = $hasQuantityColumn ? $queryTrx->sum('quantity') : $queryTrx->count();
        }

        // 3. Filter: 1 Bulan Terakhir (4 Minggu)
        for ($w = 3; $w >= 0; $w--) {
            $start = Carbon::now()->subWeeks($w)->startOfWeek();
            $end   = $start->copy()->endOfWeek();
            $chartFilters['1_month']['labels'][] = 'Mg ' . (4 - $w) . ' (' . $start->format('d/m') . ')';

            $chartFilters['1_month']['events'][] = $isSuperadmin
                ? Event::whereBetween('created_at', [$start, $end])->count()
                : Event::whereIn('id', $myEventIds)->whereBetween('created_at', [$start, $end])->count();

            $queryTrx = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->whereBetween('created_at', [$start, $end]);
            if (!$isSuperadmin) $queryTrx->whereIn('event_id', $myEventIds);
            $chartFilters['1_month']['tickets'][] = $hasQuantityColumn ? $queryTrx->sum('quantity') : $queryTrx->count();
        }

        // 4. Filter: 6 Bulan Terakhir
        for ($m = 5; $m >= 0; $m--) {
            $monthDate = Carbon::now()->subMonths($m);
            $chartFilters['6_months']['labels'][] = $monthDate->format('M Y');

            $chartFilters['6_months']['events'][] = $isSuperadmin
                ? Event::whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month)->count()
                : Event::whereIn('id', $myEventIds)->whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month)->count();

            $queryTrx = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month);
            if (!$isSuperadmin) $queryTrx->whereIn('event_id', $myEventIds);
            $chartFilters['6_months']['tickets'][] = $hasQuantityColumn ? $queryTrx->sum('quantity') : $queryTrx->count();
        }

        // 5. Filter: 1 Tahun Terakhir (12 Bulan)
        for ($m = 11; $m >= 0; $m--) {
            $monthDate = Carbon::now()->subMonths($m);
            $chartFilters['1_year']['labels'][] = $monthDate->format('M Y');

            $chartFilters['1_year']['events'][] = $isSuperadmin
                ? Event::whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month)->count()
                : Event::whereIn('id', $myEventIds)->whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month)->count();

            $queryTrx = Transaction::whereIn('status', ['settlement', 'success', 'paid'])->whereYear('created_at', $monthDate->year)->whereMonth('created_at', $monthDate->month);
            if (!$isSuperadmin) $queryTrx->whereIn('event_id', $myEventIds);
            $chartFilters['1_year']['tickets'][] = $hasQuantityColumn ? $queryTrx->sum('quantity') : $queryTrx->count();
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'totalEvents',
            'totalUsers',
            'recentTransactions',
            'isSuperadmin',
            'pendingOrganizersCount',
            'chartFilters',
            'categoryLabels',
            'categoryData'
        ));
    }
}
