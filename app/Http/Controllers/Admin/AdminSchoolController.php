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
            'total_siswa' => $sekolah->total_siswa,
        ]);

        $sekolah->delete();

        Cache::forget('admin_dashboard_data');

        return redirect()->route('admin.dashboard')->with('success', "Sekolah \"{$sekolah->nama_sekolah}\" berhasil disetujui.");
    }

    public function reject(Request $request, $id)
    {
        $sekolah = SekolahTemporary::findOrFail($id);

        ActivityLog::create([
            'school_name' => $sekolah->nama_sekolah,
            'action' => 'ditolak',
        ]);

        $sekolah->delete();

        Cache::forget('admin_dashboard_data');

        return redirect()->route('admin.dashboard')->with('success', "Pengajuan \"{$sekolah->nama_sekolah}\" ditolak.");
    }
}
