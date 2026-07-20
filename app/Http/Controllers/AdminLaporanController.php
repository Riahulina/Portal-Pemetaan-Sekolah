<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminLaporanController extends Controller
{
    public function index()
    {
        // 1. Ambil data untuk counter box atas
        $totalSekolah = Sekolah::count();
        $menungguVerifikasi = SekolahTemporary::where('status_verifikasi', 'pending')->count();
        $disetujui = SekolahTemporary::where('status_verifikasi', 'approved')->count();
        $ditolak = SekolahTemporary::where('status_verifikasi', 'rejected')->count();

        // 2. Data untuk Chart Donut (Berdasarkan Jenjang di tabel Sekolah Utama)
        $jenjangData = Sekolah::select('jenjang', DB::raw('count(*) as total'))
            ->groupBy('jenjang')
            ->get();

        // Persiapan struktur data donut chart
        $labelsJenjang = [];
        $valuesJenjang = [];
        foreach ($jenjangData as $item) {
            $labelsJenjang[] = strtoupper($item->jenjang);
            $valuesJenjang[] = $item->total;
        }

        // 3. Data grafik Pendaftaran Harian (7 hari terakhir) dari ActivityLog
        $chartData = ActivityLog::where('action', 'mendaftar')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupByRaw('date(created_at)')
            ->orderByRaw('date(created_at)')
            ->get();

        $chartHarianLabels = [];
        $chartHarianValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartHarianLabels[] = $date->format('j M');
            $match = $chartData->firstWhere('date', $date->format('Y-m-d'));
            $chartHarianValues[] = $match ? $match->count : 0;
        }

        // 4. Ringkasan perubahan minggu ini
        $thisWeekApprovals = ActivityLog::where('action', 'disetujui')
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $lastWeekApprovals = ActivityLog::where('action', 'disetujui')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $approvalTrend = $lastWeekApprovals > 0
            ? round((($thisWeekApprovals - $lastWeekApprovals) / $lastWeekApprovals) * 100)
            : ($thisWeekApprovals > 0 ? 100 : 0);

        $thisWeekPending = SekolahTemporary::where('status_verifikasi', 'pending')
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $lastWeekPending = SekolahTemporary::where('status_verifikasi', 'pending')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $pendingTrend = $lastWeekPending > 0
            ? round((($thisWeekPending - $lastWeekPending) / $lastWeekPending) * 100)
            : ($thisWeekPending > 0 ? 100 : 0);

        $thisWeekRejected = ActivityLog::where('action', 'ditolak')
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $lastWeekRejected = ActivityLog::where('action', 'ditolak')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $rejectedTrend = $lastWeekRejected > 0
            ? round((($thisWeekRejected - $lastWeekRejected) / $lastWeekRejected) * 100)
            : ($thisWeekRejected > 0 ? 100 : 0);

        return view('Admin.laporan', compact(
            'totalSekolah',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'labelsJenjang',
            'valuesJenjang',
            'chartHarianLabels',
            'chartHarianValues',
            'approvalTrend',
            'pendingTrend',
            'rejectedTrend'
        ));
    }

    /**
     * Fitur 1: Mengunduh File Excel
     */
    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'laporan-sekolah-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Fitur 2: Mengunduh File PDF
     */
    public function exportPdf()
    {
        $totalSekolah = Sekolah::count();
        $menungguVerifikasi = SekolahTemporary::where('status_verifikasi', 'pending')->count();
        $disetujui = SekolahTemporary::where('status_verifikasi', 'approved')->count();
        $ditolak = SekolahTemporary::where('status_verifikasi', 'rejected')->count();

        $rekapWilayah = Sekolah::select(
            'provinsi',
            'kabupaten_kota',
            DB::raw('COUNT(*) as total_sekolah'),
            DB::raw('SUM(total_siswa) as total_siswa')
        )
            ->whereNotNull('provinsi')
            ->whereNotNull('kabupaten_kota')
            ->groupBy('provinsi', 'kabupaten_kota')
            ->orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->get();

        $periode = now()->translatedFormat('F Y');

        $pdf = Pdf::loadView('Admin.laporanPdf', compact(
            'totalSekolah',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'rekapWilayah',
            'periode'
        ));

        return $pdf->setPaper('a4', 'landscape')->download('laporan-sekolah-'.date('Y-m-d').'.pdf');
    }
}
