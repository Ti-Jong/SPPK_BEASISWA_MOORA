// database/migrations/2024_01_10_add_seleksi_columns_to_calon_mahasiswas.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('calon_mahasiswa', 'pilihan_1_id')) {
                $table->unsignedBigInteger('pilihan_1_id')->nullable()->after('prodi_id');
                $table->foreign('pilihan_1_id')->references('id')->on('prodi')->nullOnDelete();
            }
            if (!Schema::hasColumn('calon_mahasiswa', 'pilihan_2_id')) {
                $table->unsignedBigInteger('pilihan_2_id')->nullable()->after('pilihan_1_id');
                $table->foreign('pilihan_2_id')->references('id')->on('prodi')->nullOnDelete();
            }
            if (!Schema::hasColumn('calon_mahasiswa', 'prodi_diterima_id')) {
                $table->unsignedBigInteger('prodi_diterima_id')->nullable()->after('pilihan_2_id');
                $table->foreign('prodi_diterima_id')->references('id')->on('prodi')->nullOnDelete();
            }
            if (!Schema::hasColumn('calon_mahasiswa', 'status_seleksi')) {
                $table->enum('status_seleksi', [
                    'belum_diproses',
                    'lolos_p1',
                    'gagal_p1',
                    'lolos_p2',
                    'ditolak',
                ])->default('belum_diproses')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['pilihan_1_id']);
            $table->dropForeign(['pilihan_2_id']);
            $table->dropForeign(['prodi_diterima_id']);
            $table->dropColumn(['pilihan_1_id', 'pilihan_2_id', 'prodi_diterima_id', 'status_seleksi']);
        });
    }
};