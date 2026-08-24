<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_moora', function (Blueprint $table) {
            if (!Schema::hasColumn('hasil_moora', 'tahap_seleksi')) {
                $table->tinyInteger('tahap_seleksi')->default(1)->after('periode_tahun');
            }
            if (!Schema::hasColumn('hasil_moora', 'prodi_seleksi_id')) {
                $table->unsignedBigInteger('prodi_seleksi_id')->nullable()->after('tahap_seleksi');
                $table->foreign('prodi_seleksi_id')->references('id')->on('prodi')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('hasil_moora', function (Blueprint $table) {
            $table->dropForeign(['prodi_seleksi_id']);
            $table->dropColumn(['tahap_seleksi', 'prodi_seleksi_id']);
        });
    }
};