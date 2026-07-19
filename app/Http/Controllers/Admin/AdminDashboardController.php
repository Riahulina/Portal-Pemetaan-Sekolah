<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $cached = Cache::remember('admin_dashboard_data', now()->addMinutes(5), function () {
            $totalSekolah = Sekolah::count();

            $sekolahTanpaKoordinat = Sekolah::whereNull('latitude')
                ->orWhereNull('longitude')
                ->count();

            $menungguVerifikasi = SekolahTemporary::count();

            $disetujui = ActivityLog::where('action', 'disetujui')->count();

            $ditolak = ActivityLog::where('action', 'ditolak')->count();

            $lineChartData = ActivityLog::where('action', 'mendaftar')
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('date(created_at) as date, count(*) as count')
                ->groupByRaw('date(created_at)')
                ->orderByRaw('date(created_at)')
                ->get()
                ->toArray();

            $donutData = Sekolah::select('jenjang', DB::raw('count(*) as count'))
                ->groupBy('jenjang')
                ->get()
                ->toArray();

            return compact(
                'totalSekolah',
                'sekolahTanpaKoordinat',
                'menungguVerifikasi',
                'disetujui',
                'ditolak',
                'lineChartData',
                'donutData'
            );
        });

        $recentActivity = ActivityLog::latest()->limit(5)->get();

        return view('admin.dashboard', array_merge($cached, compact('recentActivity')));
    }
}
