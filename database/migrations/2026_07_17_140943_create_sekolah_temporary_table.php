<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sekolah_temporary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('npsn', 10);
            $table->string('nama_sekolah', 150);
            $table->string('jenjang', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('akreditasi', 30)->nullable();

            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();

            $table->text('alamat')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('no_telepon', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('social_media')->nullable();
            $table->string('yayasan')->nullable();

            $table->integer('siswa_laki')->default(0);
            $table->integer('siswa_perempuan')->default(0);
            $table->integer('total_siswa')->default(0);
            $table->text('gambar_url')->nullable();

            $table->string('status_verifikasi', 20)->default('pending');
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_temporary');
    }
};
