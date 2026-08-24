<?php
// File: database/migrations/2024_01_01_000010_add_tahun_akademik_to_calon_mahasiswa.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->year('tahun_akademik')
                  ->default(date('Y'))
                  ->after('semester')
                  ->comment('Tahun akademik seleksi beasiswa');
        });
    }

    public function down(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('tahun_akademik');
        });
    }
};