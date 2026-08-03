<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_koreksis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('
                UPDATE laporan_koreksis l
                SET user_id = u.id
                FROM users u
                WHERE l.user_id IS NULL
                  AND l.email_pelapor = u.email
            ');
        }
    }

    public function down(): void
    {
        Schema::table('laporan_koreksis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
