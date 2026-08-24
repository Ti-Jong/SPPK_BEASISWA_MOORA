<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('calon_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->foreignId('prodi_id')->constrained('prodi')->restrictOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->decimal('penghasilan_ortu', 15, 2);
            $table->decimal('tagihan_listrik', 15, 2);
            $table->unsignedTinyInteger('tanggungan_anak');
            $table->enum('kondisi_ortu', ['lengkap', 'yatim', 'piatu', 'yatimpiatu']);
            $table->enum('status', ['draft', 'terkirim', 'terverifikasi', 'ditolak'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('input_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('verifikasi_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verifikasi_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('calon_mahasiswa'); }
};
