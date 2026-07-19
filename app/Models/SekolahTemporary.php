<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahTemporary extends Model
{
    use HasFactory;

    protected $table = 'sekolah_temporary';

    protected $fillable = [
        'user_id',
        'npsn',
        'nama_sekolah',
        'jenjang',
        'status',
        'akreditasi',
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
        'siswa_laki',
        'siswa_perempuan',
        'total_siswa',
        'status_verifikasi',
        'catatan_admin',
    ];
}
