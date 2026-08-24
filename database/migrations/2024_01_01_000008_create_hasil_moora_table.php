<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hasil_moora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_id')->constrained('calon_mahasiswa')->cascadeOnDelete();
            $table->foreignId('prodi_id')->constrained('prodi')->restrictOnDelete();
            $table->decimal('yi_score', 10, 6);
            $table->unsignedInteger('ranking');
            $table->unsignedSmallInteger('periode_tahun');
            $table->foreignId('dihitung_oleh')->constrained('users')->restrictOnDelete();
            $table->timestamp('dihitung_at');
            $table->timestamps();
            $table->unique(['calon_id', 'periode_tahun']);
            $table->index(['prodi_id', 'periode_tahun', 'ranking']);
        });
    }
    public function down(): void { Schema::dropIfExists('hasil_moora'); }
};
