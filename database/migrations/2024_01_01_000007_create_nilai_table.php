<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_id')->constrained('calon_mahasiswa')->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
            $table->unsignedTinyInteger('skor_raw');
            $table->decimal('skor_norm', 10, 6);
            $table->decimal('skor_weight', 10, 6);
            $table->timestamps();
            $table->unique(['calon_id', 'kriteria_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('nilai'); }
};
