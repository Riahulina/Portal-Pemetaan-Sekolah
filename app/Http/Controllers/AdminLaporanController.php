<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(6)));
        $endDate = Carbon::parse($request->input('end_date', now()));

        // 1. Ambil data untuk counter box atas (grand totals up to end date)
        $dateTo = $endDate->endOfDay();

        $totalSekolah = Sekolah::where(function ($q) use ($dateTo) {
            $q->where('created_at', '<=', $dateTo)->orWhereNull('created_at');
        })->count();

        $dateFrom = $startDate->startOfDay();
        $menungguVerifikasi = SekolahTemporary::where('status_verifikasi', 'pending')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $disetujui = SekolahTemporary::where('status_verifikasi', 'approved')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $ditolak = SekolahTemporary::where('status_verifikasi', 'rejected')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // 2. Data untuk Chart Donut (Berdasarkan Jenjang di tabel Sekolah Utama)
        $jenjangData = Sekolah::select('jenjang', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('jenjang')
            ->get();

        // Persiapan struktur data donut chart
        $labelsJenjang = [];
        $valuesJenjang = [];
        foreach ($jenjangData as $item) {
            $labelsJenjang[] = strtoupper($item->jenjang);
            $valuesJenjang[] = $item->total;
        }

        // 3. Data grafik Pendaftaran Harian dari ActivityLog
        $chartData = ActivityLog::where('action', 'mendaftar')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupByRaw('date(created_at)')
            ->orderByRaw('date(created_at)')
            ->get();

        $chartHarianLabels = [];
        $chartHarianValues = [];
        $days = $startDate->diffInDays($endDate);
        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $chartHarianLabels[] = $date->format('j M');
            $match = $chartData->firstWhere('date', $date->format('Y-m-d'));
            $chartHarianValues[] = $match ? $match->count : 0;
        }

        $maxChartValue = max(array_merge($chartHarianValues, [1]));

        // 4. Ringkasan perubahan minggu ini (independent of date filter)
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
            'maxChartValue',
            'approvalTrend',
            'pendingTrend',
            'rejectedTrend',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Fitur 1: Mengunduh File PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(6)));
        $endDate = Carbon::parse($request->input('end_date', now()));

        $dateFrom = $startDate->startOfDay();
        $dateTo = $endDate->endOfDay();

        $totalSekolah = Sekolah::where(function ($q) use ($dateTo) {
            $q->where('created_at', '<=', $dateTo)->orWhereNull('created_at');
        })->count();
        $menungguVerifikasi = SekolahTemporary::where('status_verifikasi', 'pending')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $disetujui = SekolahTemporary::where('status_verifikasi', 'approved')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $ditolak = SekolahTemporary::where('status_verifikasi', 'rejected')
            ->whereBetween('created_at', [$dateFrom, $dateTo])->count();

        $rekapWilayah = Sekolah::select(
            'provinsi',
            'kabupaten_kota',
            DB::raw('COUNT(*) as total_sekolah'),
            DB::raw('SUM(total_siswa) as total_siswa')
        )
            ->where(function ($q) use ($dateTo) {
                $q->where('created_at', '<=', $dateTo)->orWhereNull('created_at');
            })
            ->whereNotNull('provinsi')
            ->whereNotNull('kabupaten_kota')
            ->groupBy('provinsi', 'kabupaten_kota')
            ->orderBy('provinsi')
            ->orderBy('kabupaten_kota')
            ->get();

        $periode = $startDate->format('d M Y').' – '.$endDate->format('d M Y');

        $logoPath = public_path('assets/logo.png');
        $logoTempPath = '';

        if (file_exists($logoPath)) {
            $image = imagecreatefrompng($logoPath);
            $width = imagesx($image);
            $height = imagesy($image);

            $bg = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($bg, 255, 255, 255);
            imagefill($bg, 0, 0, $white);

            imagecopy($bg, $image, 0, 0, 0, 0, $width, $height);

            $logoTempPath = storage_path('app/public/temp_logo.jpg');
            imagejpeg($bg, $logoTempPath, 100);

            imagedestroy($image);
            imagedestroy($bg);
        }

        $pdf = Pdf::loadView('Admin.laporanPdf', compact(
            'totalSekolah',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'rekapWilayah',
            'periode',
            'logoTempPath'
        ));

        return $pdf->setPaper('a4', 'landscape')->download('laporan-sekolah-'.date('Y-m-d').'.pdf');
    }
}
