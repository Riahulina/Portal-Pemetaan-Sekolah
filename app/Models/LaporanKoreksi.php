<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKoreksi extends Model
{
    protected $fillable = [
        'sekolah_npsn',
        'nama_pelapor',
        'email_pelapor',
        'pesan_koreksi',
        'user_id',
        'status',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_npsn', 'npsn');
    }
}
