<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('sekolah_temporary', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('sekolah_temporary', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
