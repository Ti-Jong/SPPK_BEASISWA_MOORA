<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 100);
            $table->enum('tipe', ['benefit', 'cost']);
            $table->decimal('bobot', 5, 2); // misal: 0.30
            $table->unsignedTinyInteger('urutan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kriteria'); }
};
