<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
                ->whereNull('deleted_at')
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
                ->whereNull('deleted_at')
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
            ->whereNull('deleted_at')
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
                ->whereNull('deleted_at')
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
        // Strip leading zero from phone number before validation
        if ($request->filled('no_telepon')) {
            $request->merge(['no_telepon' => ltrim($request->input('no_telepon'), '0')]);
        }

        $request->validate([
            'npsn' => [
                'required', 'string', 'max:10',
                Rule::unique('sekolah_temporary', 'npsn')->whereNull('deleted_at'),
                Rule::unique('sekolah', 'npsn')->whereNull('deleted_at'),
            ],
            'nama_sekolah' => 'required|string|max:150',
            'jenjang' => 'required|in:KB,TK,SD,SMP,SMA,SMK',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'required|in:A,B,C,Tidak Terakreditasi',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'no_telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'social_media' => 'nullable|url|max:255',
            'siswa_laki' => 'required|integer|min:0',
            'siswa_perempuan' => 'required|integer|min:0',
        ]);

        SekolahTemporary::create([
            'user_id' => Auth::id(),
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'akreditasi' => $request->akreditasi,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan ?? null,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon ?? null,
            'email' => $request->email ?? null,
            'social_media' => $request->social_media ?? null,
            'siswa_laki' => $request->siswa_laki,
            'siswa_perempuan' => $request->siswa_perempuan,
            'total_siswa' => $request->siswa_laki + $request->siswa_perempuan,
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
     *    Supports optional {id?} parameter — remembers last viewed via session.
     */
    public function statusVerifikasi($id = null)
    {
        $userId = Auth::id();

        if ($id) {
            $sekolah = SekolahTemporary::where('id', $id)->where('user_id', $userId)->first();
            if ($sekolah) {
                session(['last_viewed_status_id' => $sekolah->id]);
            }
        } else {
            $lastId = session('last_viewed_status_id');
            $sekolah = $lastId
                ? SekolahTemporary::where('id', $lastId)->where('user_id', $userId)->first()
                : null;
        }

        if (! $sekolah) {
            $sekolah = SekolahTemporary::where('user_id', $userId)->latest()->first();
        }

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

        // Strip leading zero from phone number before validation
        if ($request->filled('no_telepon')) {
            $request->merge(['no_telepon' => ltrim($request->input('no_telepon'), '0')]);
        }

        $request->validate([
            'npsn' => [
                'required',
                'numeric',
                Rule::unique('sekolah_temporary', 'npsn')
                    ->ignore($sekolah->id, 'id')
                    ->where(function ($query) {
                        $query->whereNull('deleted_at');
                        $query->where('status_verifikasi', '!=', 'approved');
                    }),
                Rule::unique('sekolah', 'npsn')
                    ->where(function ($query) {
                        $query->whereNull('deleted_at');
                    }),
            ],
            'nama_sekolah' => 'required|string|max:150',
            'jenjang' => 'required|in:KB,TK,SD,SMP,SMA,SMK',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'required|in:A,B,C,Tidak Terakreditasi',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'no_telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'social_media' => 'nullable|url|max:255',
            'siswa_laki' => 'required|integer|min:0',
            'siswa_perempuan' => 'required|integer|min:0',
        ]);

        // Update data dengan nilai baru dari form
        $sekolah->update([
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'akreditasi' => $request->akreditasi,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan ?? null,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon ?? null,
            'email' => $request->email ?? null,
            'social_media' => $request->social_media ?? null,
            'siswa_laki' => $request->siswa_laki,
            'siswa_perempuan' => $request->siswa_perempuan,
            'total_siswa' => $request->siswa_laki + $request->siswa_perempuan,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    /**
     * Menghapus pengajuan sekolah milik user sendiri.
     * Hanya boleh dihapus jika status masih pending atau rejected.
     */
    public function destroy($id)
    {
        $sekolah = SekolahTemporary::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (in_array($sekolah->status_verifikasi, ['approved'])) {
            return back()->with('error', 'Data yang sudah disetujui tidak dapat dihapus.');
        }

        $sekolah->delete();

        \Cache::forget('sekolah_provinsi_summary_v1');
        \Cache::forget('sekolah_wilayah_v2');

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
