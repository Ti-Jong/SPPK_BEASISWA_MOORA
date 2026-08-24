<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
            $table->string('label', 100);
            $table->decimal('range_min', 15, 2)->nullable();
            $table->decimal('range_max', 15, 2)->nullable();
            $table->string('nilai_string', 50)->nullable(); // untuk kondisi ortu
            $table->unsignedTinyInteger('skor');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sub_kriteria'); }
};
