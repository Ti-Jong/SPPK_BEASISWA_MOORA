<?php
// File: database/migrations/2024_01_01_000011_add_kolom_rapor_to_calon_mahasiswa.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->string('asal_sekolah', 255)->nullable()->after('nama');
            $table->decimal('nilai_rapor_sem3', 5, 2)->nullable()->after('semester');
            $table->decimal('nilai_rapor_sem4', 5, 2)->nullable()->after('nilai_rapor_sem3');
        });
    }

    public function down(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['asal_sekolah', 'nilai_rapor_sem3', 'nilai_rapor_sem4']);
        });
    }
};