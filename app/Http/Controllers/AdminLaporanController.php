<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;          // <-- INI YANG KURANG (Wajib panggil model Sekolah Utama)
use App\Models\SekolahTemporary; // Memanggil model Sekolah Temporary
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanExport;     // Panggil class Export Excel
use Maatwebsite\Excel\Facades\Excel; // Facade Excel
use Barryvdh\DomPDF\Facade\Pdf;       // Facade PDF

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

        // 3. Data palsu/mockup untuk grafik Pendaftaran Harian (Line Chart) seperti digambar
        $chartHarianLabels = ['9 July', '10 July', '11 July', '12 July', '13 July', '14 July', '15 July'];
        $chartHarianValues = [18, 32, 22, 38, 34, 30, 42];

        return view('Admin.laporan', compact(
            'totalSekolah',
            'menungguVerifikasi',
            'disetujui',
            'ditolak',
            'labelsJenjang',
            'valuesJenjang',
            'chartHarianLabels',
            'chartHarianValues'
        ));
    }

    /**
     * Fitur 1: Mengunduh File Excel
     */
    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'laporan-sekolah-' . date('Y-m-d') . '.xlsx');
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

        $sekolahList = Sekolah::latest()->get();

        $pdf = Pdf::loadView('Admin.laporanPdf', compact('totalSekolah', 'menungguVerifikasi', 'disetujui', 'ditolak', 'sekolahList'));

        // Atur ukuran kertas ke A4 Portrait
        return $pdf->setPaper('a4', 'portrait')->download('laporan-sekolah-' . date('Y-m-d') . '.pdf');
    }
}
