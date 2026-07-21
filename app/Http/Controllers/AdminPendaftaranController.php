<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class AdminPendaftaranController extends Controller
{
    /**
     * 1. Menampilkan Halaman Utama Pendaftaran (Menunggu Verifikasi + Filter)
     */
    public function index(Request $request)
    {
        // Mapping ID Provinsi EMSIFA ke Nama Provinsi
        $provincesMap = [
            '11' => 'ACEH',
            '12' => 'SUMATERA UTARA',
            '13' => 'SUMATERA BARAT',
            '14' => 'RIAU',
            '15' => 'JAMBI',
            '16' => 'SUMATERA SELATAN',
            '17' => 'BENGKULU',
            '18' => 'LAMPUNG',
            '19' => 'KEPULAUAN BANGKA BELITUNG',
            '21' => 'KEPULAUAN RIAU',
        ];

        $query = SekolahTemporary::with('user')
            ->where('status_verifikasi', 'pending');

        // 1. Filter Provinsi (Aman untuk ID Angka '13' maupun Teks 'SUMATERA BARAT')
        if ($request->filled('provinsi')) {
            $provInput = $request->provinsi;
            $namaProv = $provincesMap[$provInput] ?? $provInput;

            $query->where(function ($q) use ($provInput, $namaProv) {
                $q->where('provinsi', 'ILIKE', '%'.$provInput.'%')
                    ->orWhere('provinsi', 'ILIKE', '%'.$namaProv.'%');
            });
        }

        // 2. Filter Kabupaten/Kota
        if ($request->filled('kabupaten_kota')) {
            $query->where('kabupaten_kota', 'ILIKE', '%'.$request->kabupaten_kota.'%');
        }

        // 3. Filter Jenjang (SD, SMP, SMA, SMK)
        if ($request->filled('jenjang')) {
            $query->where('jenjang', 'ILIKE', $request->jenjang);
        }

        // 4. Filter Pencarian Nama Sekolah / NPSN
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($qBuilder) use ($search) {
                $qBuilder->where('nama_sekolah', 'ILIKE', '%'.$search.'%')
                    ->orWhere('npsn', 'ILIKE', '%'.$search.'%');
            });
        }

        // Paginate & pertahankan query filter di URL
        $pendaftaran = $query->latest()->paginate(5)->withQueryString();

        return view('Admin.pendaftaran', compact('pendaftaran'));
    }

    /**
     * 2. Menampilkan Halaman Detail Pendaftaran Sekolah
     */
    public function show($id)
    {
        $sekolah = SekolahTemporary::with('user')->findOrFail($id);

        return view('Admin.pendaftaranDetail', compact('sekolah'));
    }

    /**
     * 3. Memproses Tindakan Verifikasi (Setuju / Tolak)
     */
    public function verifikasi(Request $request, $id)
    {
        $sekolahTemp = SekolahTemporary::findOrFail($id);
        $status = $request->input('status'); // 'approved' atau 'rejected'

        if ($status === 'approved') {
            // Update status di tabel temporary menjadi approved
            $sekolahTemp->update([
                'status_verifikasi' => 'approved',
                'catatan_admin' => 'Pendaftaran sekolah telah disetujui oleh admin.',
            ]);

            // PROSES COPY DATA: Memindahkan data dari temporary ke tabel sekolah utama
            Sekolah::create([
                'npsn' => $sekolahTemp->npsn,
                'nama_sekolah' => $sekolahTemp->nama_sekolah,
                'jenjang' => $sekolahTemp->jenjang,
                'status' => $sekolahTemp->status ?? 'Swasta',
                'akreditasi' => $sekolahTemp->akreditasi ?? 'B',
                'provinsi' => $sekolahTemp->provinsi,
                'kabupaten_kota' => $sekolahTemp->kabupaten_kota,
                'kecamatan' => $sekolahTemp->kecamatan,
                'kelurahan' => $sekolahTemp->kelurahan,
                'alamat' => $sekolahTemp->alamat,
                'latitude' => $sekolahTemp->latitude,
                'longitude' => $sekolahTemp->longitude,
                'no_telepon' => $sekolahTemp->no_telepon,
                'email' => $sekolahTemp->email,
                'social_media' => $sekolahTemp->social_media,
                'yayasan' => $sekolahTemp->yayasan,
                'total_siswa' => $sekolahTemp->total_siswa ?? 0,
                'jumlah_siswa_perempuan' => $sekolahTemp->siswa_perempuan ?? 0,
                'jumlah_siswa_laki_laki' => $sekolahTemp->siswa_laki ?? 0,
                'gambar_url' => $sekolahTemp->gambar_url,
            ]);

            ActivityLog::create([
                'school_name' => $sekolahTemp->nama_sekolah,
                'action' => 'disetujui',
            ]);

            Cache::forget('admin_dashboard_data');
            Cache::forget('sekolah_wilayah_v2');
            Cache::forget('sekolah_provinsi_summary_v1');

            $filters = [$sekolahTemp->provinsi, '', '', '', ''];
            Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));
            $filters[1] = $sekolahTemp->kabupaten_kota;
            Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));
            $filters[2] = $sekolahTemp->kecamatan;
            Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

            return redirect()->route('admin.pendaftaran.index')->with('success', 'Pendaftaran sekolah berhasil disetujui dan telah masuk ke Manajemen Sekolah!');
        } elseif ($status === 'rejected') {
            $sekolahTemp->update([
                'status_verifikasi' => 'rejected',
                'catatan_admin' => $request->input('catatan_admin', 'Mohon maaf, pendaftaran ditolak karena data tidak sesuai.'),
            ]);

            ActivityLog::create([
                'school_name' => $sekolahTemp->nama_sekolah,
                'action' => 'ditolak',
            ]);

            Cache::forget('admin_dashboard_data');

            return redirect()->route('admin.pendaftaran.index')->with('success', 'Pendaftaran sekolah telah ditolak.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Menampilkan Halaman Form Pendaftaran (Halaman Tambah Data)
     */
    public function formPendaftaran()
    {
        // Mengarahkan ke file: resources/views/admin/formpendaftaran.blade.php
        return view('admin.formpendaftaran');
    }

    /**
     * Memproses Penyimpanan Data dari Form Pendaftaran Langsung ke Tabel Sekolah Utama.
     */
    public function storePendaftaran(Request $request)
    {
        if ($request->filled('no_telepon')) {
            $request->merge(['no_telepon' => ltrim($request->input('no_telepon'), '0')]);
        }

        $request->validate([
            'npsn' => [
                'required', 'string', 'max:10',
                Rule::unique('sekolah', 'npsn')->whereNull('deleted_at'),
                Rule::unique('sekolah_temporary', 'npsn')->whereNull('deleted_at'),
            ],
            'nama_sekolah' => 'required|string|max:150',
            'jenjang' => 'required|in:KB,TK,SD,SMP,SMA,SMK',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'required|in:A,B,C,Tidak Terakreditasi',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'no_telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'social_media' => 'nullable|url|max:255',
            'siswa_laki' => 'required|integer|min:0',
            'siswa_perempuan' => 'required|integer|min:0',
        ]);

        $totalSiswa = (int) $request->siswa_laki + (int) $request->siswa_perempuan;

        Sekolah::create([
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'akreditasi' => $request->akreditasi,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon ?? null,
            'email' => $request->email ?? null,
            'social_media' => $request->social_media ?? null,
            'total_siswa' => $totalSiswa,
            'jumlah_siswa_laki_laki' => (int) $request->siswa_laki,
            'jumlah_siswa_perempuan' => (int) $request->siswa_perempuan,
        ]);

        ActivityLog::create([
            'school_name' => $request->nama_sekolah,
            'action' => 'ditambahkan oleh admin',
        ]);

        Cache::forget('admin_dashboard_data');
        Cache::forget('sekolah_wilayah_v2');
        Cache::forget('sekolah_provinsi_summary_v1');

        return redirect()->route('admin.sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan!');
    }
}
