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
        Schema::table('sekolah', function (Blueprint $table) {
            // Menambahkan kolom pulau setelah kolom provinsi
            $table->string('pulau', 100)->nullable()->after('provinsi');

            // Menambahkan index jika nantinya sering digunakan untuk filtering/pencarian
            $table->index('pulau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            // Menghapus index dan kolom saat rollback
            $table->dropIndex(['pulau']);
            $table->dropColumn('pulau');
        });
    }
};
