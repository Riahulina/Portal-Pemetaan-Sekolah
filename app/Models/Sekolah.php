<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    protected function jenjang(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? strtoupper($value) : null,
        );
    }

    protected function kabupaten_kota(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? Str::title($value) : null,
        );
    }

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value ? strtoupper($value) : null;
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(LaporanKoreksi::class, 'sekolah_npsn', 'npsn');
    }
}
