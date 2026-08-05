<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel referensi wilayah Indonesia (pulau → provinsi → kabupaten → kecamatan).
     *
     * Semua tabel memakai nama (string) sebagai relasi, tanpa foreign key,
     * agar mudah di-seed ulang secara statis tanpa ketergantungan id.
     */
    public function up(): void
    {
        Schema::create('ref_pulau', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        Schema::create('ref_provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('pulau', 100);
            $table->unique(['pulau', 'nama']);
            $table->index('pulau');
            $table->timestamps();
        });

        Schema::create('ref_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('provinsi', 100);
            $table->unique(['provinsi', 'nama']);
            $table->index('provinsi');
            $table->timestamps();
        });

        Schema::create('ref_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kabupaten', 100);
            $table->unique(['kabupaten', 'nama']);
            $table->index('kabupaten');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_kecamatan');
        Schema::dropIfExists('ref_kabupaten');
        Schema::dropIfExists('ref_provinsi');
        Schema::dropIfExists('ref_pulau');
    }
};
