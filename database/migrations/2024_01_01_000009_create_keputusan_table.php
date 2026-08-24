<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('keputusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_moora_id')->constrained('hasil_moora')->cascadeOnDelete();
            $table->foreignId('calon_id')->constrained('calon_mahasiswa')->cascadeOnDelete();
            $table->enum('status', ['penerima', 'cadangan', 'tidak_lolos']);
            $table->text('keterangan')->nullable();
            $table->foreignId('ditetapkan_oleh')->constrained('users')->restrictOnDelete();
            $table->timestamp('ditetapkan_at');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('keputusan'); }
};
