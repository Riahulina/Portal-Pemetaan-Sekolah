<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;

class SekolahController extends Controller
{
    public function index()
    {
        set_time_limit(300);

        $sekolah = Sekolah::select(
            'npsn',
            'nama_sekolah',
            'jenjang',
            'status',
            'provinsi',
            'kabupaten_kota',
            'kecamatan',
            'kelurahan',
            'alamat',
            'latitude',
            'longitude',
            'no_telepon',
            'email',
            'social_media',
            'total_siswa',
        )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return response()->json($sekolah);
    }
}
