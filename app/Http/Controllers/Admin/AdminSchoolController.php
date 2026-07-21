<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Models\SekolahTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSchoolController extends Controller
{
    public function approve(Request $request, $id)
    {
        $sekolah = SekolahTemporary::findOrFail($id);

        ActivityLog::create([
            'school_name' => $sekolah->nama_sekolah,
            'action' => 'disetujui',
        ]);

        Sekolah::create([
            'npsn' => $sekolah->npsn,
            'nama_sekolah' => $sekolah->nama_sekolah,
            'jenjang' => $sekolah->jenjang,
            'status' => $sekolah->status,
            'akreditasi' => $sekolah->akreditasi,
            'provinsi' => $sekolah->provinsi,
            'kabupaten_kota' => $sekolah->kabupaten_kota,
            'kecamatan' => $sekolah->kecamatan,
            'kelurahan' => $sekolah->kelurahan,
            'alamat' => $sekolah->alamat,
            'latitude' => $sekolah->latitude,
            'longitude' => $sekolah->longitude,
            'no_telepon' => $sekolah->no_telepon,
            'email' => $sekolah->email,
            'social_media' => $sekolah->social_media,
            'yayasan' => $sekolah->yayasan,
            'total_siswa' => $sekolah->total_siswa,
            'jumlah_siswa_laki_laki' => $sekolah->siswa_laki,
            'jumlah_siswa_perempuan' => $sekolah->siswa_perempuan,
            'gambar_url' => $sekolah->gambar_url,
        ]);

        $sekolah->forceFill(['status_verifikasi' => 'approved'])->save();

        // Bust admin dashboard cache
        Cache::forget('admin_dashboard_data');

        // Bust static wilayah + summary caches
        Cache::forget('sekolah_wilayah_v2');
        Cache::forget('sekolah_provinsi_summary_v1');

        // Bust dynamic map caches affected by this school's location
        $filters = [$sekolah->provinsi, '', '', '', ''];
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        $filters[1] = $sekolah->kabupaten_kota;
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        $filters[2] = $sekolah->kecamatan;
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        return redirect()->route('admin.dashboard')->with('success', "Sekolah \"{$sekolah->nama_sekolah}\" berhasil disetujui.");
    }

    public function reject(Request $request, $id)
    {
        $sekolah = SekolahTemporary::findOrFail($id);

        ActivityLog::create([
            'school_name' => $sekolah->nama_sekolah,
            'action' => 'ditolak',
        ]);

        $sekolah->forceFill(['status_verifikasi' => 'rejected'])->save();

        Cache::forget('admin_dashboard_data');

        return redirect()->route('admin.dashboard')->with('success', "Pengajuan \"{$sekolah->nama_sekolah}\" ditolak.");
    }
}
