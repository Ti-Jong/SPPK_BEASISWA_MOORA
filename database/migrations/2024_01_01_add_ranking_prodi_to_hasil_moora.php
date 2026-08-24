<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_moora', function (Blueprint $table) {

            // ranking_prodi : peringkat dalam prodi masing-masing
            if (!Schema::hasColumn('hasil_moora', 'ranking_prodi')) {
                $table->unsignedInteger('ranking_prodi')
                      ->nullable()
                      ->after('ranking')
                      ->comment('Ranking dalam prodi masing-masing');
            }

            // skor_moora : alias yi_score yang lebih deskriptif
            if (!Schema::hasColumn('hasil_moora', 'skor_moora')) {
                $table->decimal('skor_moora', 10, 6)
                      ->nullable()
                      ->after('yi_score')
                      ->comment('Alias yi_score — hasil akhir MOORA');
            }

            // detail_hitung : JSON breakdown per kriteria
            if (!Schema::hasColumn('hasil_moora', 'detail_hitung')) {
                $table->json('detail_hitung')
                      ->nullable()
                      ->after('skor_moora')
                      ->comment('Breakdown detail perhitungan per kriteria');
            }
        });

        // Index tambahan untuk query per-prodi
        Schema::table('hasil_moora', function (Blueprint $table) {
            try {
                $table->index(
                    ['prodi_id', 'periode_tahun', 'ranking_prodi'],
                    'idx_hasil_moora_prodi_tahun_rankprodi'
                );
            } catch (\Exception $e) {
                // Index sudah ada — skip
            }
        });
    }

    public function down(): void
    {
        Schema::table('hasil_moora', function (Blueprint $table) {
            try {
                $table->dropIndex('idx_hasil_moora_prodi_tahun_rankprodi');
            } catch (\Exception $e) {}

            foreach (['ranking_prodi', 'skor_moora', 'detail_hitung'] as $col) {
                if (Schema::hasColumn('hasil_moora', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
