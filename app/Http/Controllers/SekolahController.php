<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
{
    /**
     * Menampilkan halaman "Data Sekolah Saya" untuk User.
     */
    public function index()
    {
        // Mengambil semua pengajuan temporary dari user yang sedang login
        $sekolahList = SekolahTemporary::where('user_id', Auth::id())->get();

        return view('User.dataSekolah', compact('sekolahList'));
    }

    /**
     * Mengambil data wilayah (provinsi → kabupaten → kecamatan) yang unik.
     * Hasil di-cache permanen karena data wilayah sangat jarang berubah.
     */
    public function getWilayah()
    {
        $wilayah = Cache::rememberForever('sekolah_wilayah_v2', function () {
            return DB::table('sekolah')
                ->select('provinsi', 'kabupaten_kota', 'kecamatan')
                ->whereNotNull('provinsi')
                ->whereNotNull('kabupaten_kota')
                ->whereNotNull('kecamatan')
                ->distinct()
                ->get()
                ->toArray();
        });

        return response()->json($wilayah);
    }

    /**
     * Mengambil data sekolah untuk peta — HANYA jika filter provinsi disertakan.
     * Tanpa provinsi, mengembalikan [] untuk melindungi Supabase egress.
     * Hasil di-cache per kombinasi filter (4 jam).
     */
    public function apiPeta(Request $request)
    {
        $provinsi = $request->query('provinsi');
        $kabupaten = $request->query('kabupaten');
        $kecamatan = $request->query('kecamatan');
        $jenjang = $request->query('jenjang');
        $status = $request->query('status');

        if (! $provinsi) {
            return response()->json([]);
        }

        $cacheKey = 'sekolah_map_v5_'.md5(implode('_', [$provinsi, $kabupaten ?? '', $kecamatan ?? '', $jenjang ?? '', $status ?? '']));

        $sekolah = Cache::remember($cacheKey, now()->addHours(4), function () use ($provinsi, $kabupaten, $kecamatan, $jenjang, $status) {
            $query = DB::table('sekolah')
                ->select(
                    'npsn',
                    'nama_sekolah',
                    'jenjang',
                    'status',
                    'provinsi',
                    'kabupaten_kota',
                    'kecamatan',
                    'kelurahan',
                    'latitude',
                    'longitude'
                )
                ->selectRaw('total_siswa::integer as total_siswa')
                ->where('provinsi', $provinsi)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if ($kabupaten) {
                $query->where('kabupaten_kota', $kabupaten);
            }
            if ($kecamatan) {
                $query->where('kecamatan', $kecamatan);
            }
            if ($jenjang && $jenjang !== 'Semua') {
                if ($jenjang === 'SMA/SMK') {
                    $query->whereIn('jenjang', ['SMA', 'SMK']);
                } else {
                    $query->where('jenjang', $jenjang);
                }
            }
            if ($status && $status !== 'Semua') {
                $query->where('status', 'ilike', $status);
            }

            return $query->get()->toArray();
        });

        return response()->json($sekolah);
    }

    /**
     * Mengambil detail lengkap satu sekolah berdasarkan NPSN.
     * Dipanggil on-demand saat user klik marker/paging.
     */
    public function getDetail(string $npsn)
    {
        $sekolah = DB::table('sekolah')
            ->where('npsn', $npsn)
            ->first();

        if (! $sekolah) {
            return response()->json(['message' => 'Sekolah tidak ditemukan'], 404);
        }

        return response()->json($sekolah);
    }

    /**
     * Mengambil ringkasan nasional — jumlah sekolah & siswa per provinsi, beserta koordinat rata-rata.
     * Digunakan untuk tampilan awal peta sebelum user memilih filter wilayah.
     * Cache permanen karena data historis tidak sering berubah.
     */
    public function getProvinsiSummary()
    {
        $summary = Cache::rememberForever('sekolah_provinsi_summary_v1', function () {
            return DB::table('sekolah')
                ->selectRaw('
                    provinsi,
                    COUNT(npsn) as total_sekolah,
                    SUM(CAST(total_siswa AS INTEGER)) as total_siswa,
                    AVG(CAST(latitude AS DECIMAL(10,8))) as lat,
                    AVG(CAST(longitude AS DECIMAL(11,8))) as lng
                ')
                ->whereNotNull('provinsi')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->groupBy('provinsi')
                ->get()
                ->toArray();
        });

        return response()->json($summary);
    }

    /**
     * 1. Method Simpan Formulir ke Tabel Temporary
     */
    public function store(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string|max:10',
            'nama_sekolah' => 'required|string|max:150',
        ]);

        SekolahTemporary::create([
            'user_id' => Auth::id(),
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan ?? null,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'social_media' => $request->social_media,
            'total_siswa' => $request->total_siswa ?? 0,
            'status_verifikasi' => 'pending',
        ]);

        ActivityLog::create([
            'school_name' => $request->nama_sekolah,
            'action' => 'mendaftar',
        ]);

        Cache::forget('admin_dashboard_data');

        return redirect()->route('status.user')->with('success', 'Pendaftaran berhasil dikirim, menunggu tinjauan admin.');
    }

    /**
     * 2. Method Menampilkan Halaman Status Stepper Progres
     */
    public function statusVerifikasi()
    {
        // Cari pengajuan terbaru milik user yang sedang aktif dari tabel temporary
        $sekolah = SekolahTemporary::where('user_id', Auth::id())->latest()->first();

        return view('User.statusVerifikasi', compact('sekolah'));
    }

    public function edit($id)
    {
        // Cari data di tabel temporary berdasarkan id dan pastikan milik user yang login
        $sekolah = SekolahTemporary::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return view('User.Form', compact('sekolah'));
    }

    /**
     * Memproses update data dari Form Edit.
     */
    public function update(Request $request, $id)
    {
        $sekolah = SekolahTemporary::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'npsn' => 'required|string|max:10',
            'nama_sekolah' => 'required|string|max:150',
        ]);

        // Update data dengan nilai baru dari form
        $sekolah->update([
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan ?? null,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'social_media' => $request->social_media,
            'total_siswa' => $request->total_siswa ?? 0,
            'status_verifikasi' => 'pending', // Set kembali ke pending jika user mengubah data
        ]);

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }
}
