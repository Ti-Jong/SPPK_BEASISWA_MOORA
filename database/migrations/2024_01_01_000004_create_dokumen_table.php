<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_id')->constrained('calon_mahasiswa')->cascadeOnDelete();
            $table->enum('jenis', ['surat_rekomendasi','rapor_semester_3','rapor_semester_4','piagam','kartu_keluarga','foto_rumah','sktm','tagihan_listrik','surat_penghasilan']);
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dokumen'); }
};
