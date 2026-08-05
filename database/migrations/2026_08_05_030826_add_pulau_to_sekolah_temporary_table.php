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
        Schema::table('sekolah_temporary', function (Blueprint $table) {
            // Menambahkan kolom pulau setelah kolom provinsi
            $table->string('pulau', 100)->nullable()->after('provinsi');

            // Menambahkan index untuk optimasi query/filtering
            $table->index('pulau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah_temporary', function (Blueprint $table) {
            // Menghapus index dan kolom saat rollback
            $table->dropIndex(['pulau']);
            $table->dropColumn('pulau');
        });
    }
};
