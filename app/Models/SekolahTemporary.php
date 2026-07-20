<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SekolahTemporary extends Model
{
    use HasFactory, SoftDeletes;

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
        'yayasan',
        'siswa_laki',
        'siswa_perempuan',
        'total_siswa',
        'gambar_url',
        'status_verifikasi',
        'catatan_admin',
    ];

    /**
     * Konversi tipe data otomatis (Casting)
     */
    protected $casts = [
        'siswa_laki' => 'integer',
        'siswa_perempuan' => 'integer',
        'total_siswa' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relasi ke model User
     * Menghubungkan kolom user_id di tabel sekolah_temporary ke id di tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
