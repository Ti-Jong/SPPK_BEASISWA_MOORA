<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan:
     *  - alasan_penolakan  : alasan spesifik saat BAPSI menolak (terpisah dari catatan)
     *  - skor_moora        : hasil kalkulasi MOORA
     *  - ranking           : peringkat setelah MOORA
     */
    public function up(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('calon_mahasiswa', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('calon_mahasiswa', 'skor_moora')) {
                $table->decimal('skor_moora', 10, 6)->nullable()->after('alasan_penolakan');
            }
            if (!Schema::hasColumn('calon_mahasiswa', 'ranking')) {
                $table->unsignedSmallInteger('ranking')->nullable()->after('skor_moora');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropColumnIfExists('alasan_penolakan');
            $table->dropColumnIfExists('skor_moora');
            $table->dropColumnIfExists('ranking');
        });
    }
};