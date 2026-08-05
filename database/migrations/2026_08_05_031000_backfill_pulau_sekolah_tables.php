<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill kolom pulau dengan 'Sumatera' untuk seluruh data sekolah
     * yang sudah ada sebelumnya (data eksisting saat ini semuanya berada
     * di Pulau Sumatera).
     *
     * Kolom pulau dijamin sudah ada karena migration add_pulau
     * (2026_08_05_030655 dan 2026_08_05_030826) berjalan lebih dulu.
     */
    public function up(): void
    {
        DB::table('sekolah')
            ->where(fn ($q) => $q->whereNull('pulau')->orWhere('pulau', ''))
            ->update(['pulau' => 'Sumatera']);

        DB::table('sekolah_temporary')
            ->where(fn ($q) => $q->whereNull('pulau')->orWhere('pulau', ''))
            ->update(['pulau' => 'Sumatera']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('sekolah')
            ->where('pulau', 'Sumatera')
            ->update(['pulau' => null]);

        DB::table('sekolah_temporary')
            ->where('pulau', 'Sumatera')
            ->update(['pulau' => null]);
    }
};
