<?php

namespace App\Http\Controllers;

use App\Models\SekolahTemporary;
use App\Models\Sekolah; // Memanggil model Sekolah Utama
use Illuminate\Http\Request;

class AdminPendaftaranController extends Controller
{
    /**
     * 1. Menampilkan Halaman Utama Pendaftaran (Menunggu Verifikasi)
     */
    public function index()
    {
        $pendaftaran = SekolahTemporary::with('user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->paginate(5); // Sesuai dengan mockup 5 data per halaman

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
                'catatan_admin' => 'Pendaftaran sekolah telah disetujui oleh admin.'
            ]);

            // PROSES COPY DATA: Memindahkan data dari temporary ke tabel sekolah utama kamu
            Sekolah::create([
                'npsn'                   => $sekolahTemp->npsn,
                'nama_sekolah'           => $sekolahTemp->nama_sekolah,
                'jenjang'                => $sekolahTemp->jenjang,
                'status'                 => $sekolahTemp->status ?? 'Swasta',
                'akreditasi'             => $sekolahTemp->akreditasi ?? 'B',
                'provinsi'               => $sekolahTemp->provinsi,
                'kabupaten_kota'         => $sekolahTemp->kabupaten_kota,
                'kecamatan'              => $sekolahTemp->kecamatan,
                'kelurahan'              => $sekolahTemp->kelurahan,
                'alamat'                 => $sekolahTemp->alamat,
                'latitude'               => $sekolahTemp->latitude,
                'longitude'              => $sekolahTemp->longitude,
                'no_telepon'             => $sekolahTemp->no_telepon,
                'email'                  => $sekolahTemp->email,
                'social_media'           => $sekolahTemp->social_media,
                'total_siswa'            => $sekolahTemp->total_siswa ?? 0,
                'jumlah_siswa_perempuan' => $sekolahTemp->siswa_perempuan ?? 0,
                'jumlah_siswa_laki_laki' => $sekolahTemp->siswa_laki ?? 0,
            ]);

            return redirect()->route('admin.pendaftaran.index')->with('success', 'Pendaftaran sekolah berhasil disetujui dan telah masuk ke Manajemen Sekolah!');
        } elseif ($status === 'rejected') {
            $sekolahTemp->update([
                'status_verifikasi' => 'rejected',
                'catatan_admin' => $request->input('catatan_admin', 'Mohon maaf, pendaftaran ditolak karena data tidak sesuai.')
            ]);

            return redirect()->route('admin.pendaftaran.index')->with('success', 'Pendaftaran sekolah telah ditolak.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
