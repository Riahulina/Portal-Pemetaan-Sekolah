<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\SekolahTemporary; // Panggil model temporary baru di sini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
     * Method terpisah khusus untuk mengambil data JSON Peta.
     * (Membaca dari tabel utama sekolah yang berisi 53 ribu data)
     */
    public function apiPeta()
    {
        set_time_limit(300);
        $sekolah = Cache::remember('sekolah_map_data', now()->addHours(4), function () {
            return Sekolah::select('npsn', 'nama_sekolah', 'jenjang', 'status', 'provinsi', 'kabupaten_kota', 'kecamatan', 'kelurahan', 'alamat', 'latitude', 'longitude', 'no_telepon', 'email', 'social_media', 'total_siswa')
                ->whereNotNull('latitude')->whereNotNull('longitude')->get();
        });

        return response()->json($sekolah);
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

        // Simpan ke tabel sekolah_temporary
        SekolahTemporary::create([
            'user_id' => Auth::id(),
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'status' => $request->status,
            'provinsi' => $request->provinsi,
            'kabupaten_kota' => $request->kabupaten_kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan ?? null, // Diantisipasi jika kelurahan kosong/null
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'social_media' => $request->social_media,
            'total_siswa' => $request->total_siswa ?? 0,
            'status_verifikasi' => 'pending', // Awal pendaftaran otomatis berstatus pending
        ]);

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
