<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keputusan', function (Blueprint $table) {
            if (!Schema::hasColumn('keputusan', 'tahap_diterima')) {
                $table->tinyInteger('tahap_diterima')->default(1)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('keputusan', function (Blueprint $table) {
            $table->dropColumn('tahap_diterima');
        });
    }
};
