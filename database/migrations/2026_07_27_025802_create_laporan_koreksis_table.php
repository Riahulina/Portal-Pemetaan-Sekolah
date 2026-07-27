<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_koreksis', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_npsn', 10)->constrained('sekolah', 'npsn')->onDelete('cascade');
            $table->string('nama_pelapor');
            $table->string('email_pelapor');
            $table->text('pesan_koreksi');
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_koreksis');
    }
};
