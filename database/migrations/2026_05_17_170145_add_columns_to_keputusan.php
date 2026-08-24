<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keputusan', function (Blueprint $table) {
            if (!Schema::hasColumn('keputusan', 'prodi_id')) {
                $table->unsignedBigInteger('prodi_id')->nullable()->after('calon_id');
                $table->foreign('prodi_id')->references('id')->on('prodi')->nullOnDelete();
            }
            if (!Schema::hasColumn('keputusan', 'tahun')) {
                $table->smallInteger('tahun')->nullable()->after('prodi_id');
            }
            if (!Schema::hasColumn('keputusan', 'tahap_diterima')) {
                $table->tinyInteger('tahap_diterima')->default(1)->after('tahun')
                      ->comment('1=pilihan1, 2=pilihan2');
            }
            if (!Schema::hasColumn('keputusan', 'ranking_prodi')) {
                $table->integer('ranking_prodi')->nullable()->after('tahap_diterima');
            }
            if (!Schema::hasColumn('keputusan', 'ranking_global')) {
                $table->integer('ranking_global')->nullable()->after('ranking_prodi');
            }
            if (!Schema::hasColumn('keputusan', 'skor_moora')) {
                $table->decimal('skor_moora', 10, 6)->nullable()->after('ranking_global');
            }
        });
    }

    public function down(): void
    {
        Schema::table('keputusan', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn([
                'prodi_id', 'tahun', 'tahap_diterima',
                'ranking_prodi', 'ranking_global', 'skor_moora'
            ]);
        });
    }
};