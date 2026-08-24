<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('calon_mahasiswa', function (Blueprint $table) {
        $table->unsignedBigInteger('pilihan_3_id')->nullable()->after('pilihan_2_id');
        $table->foreign('pilihan_3_id')->references('id')->on('prodi')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('calon_mahasiswa', function (Blueprint $table) {
        $table->dropForeign(['pilihan_3_id']);
        $table->dropColumn('pilihan_3_id');
    });
}
};
