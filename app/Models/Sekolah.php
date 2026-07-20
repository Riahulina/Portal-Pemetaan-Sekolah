<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sekolah extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah';

    protected $primaryKey = 'npsn';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
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
        'yayasan',
        'total_siswa',
        'jumlah_siswa_perempuan',
        'jumlah_siswa_laki_laki',
        'gambar_url',
    ];
}
