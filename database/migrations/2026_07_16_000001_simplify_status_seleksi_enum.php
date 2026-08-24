<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PENTING: urutan operasi di sini disengaja.
     * 1) Normalisasi dulu SEMUA baris yang masih pakai value ENUM lama
     *    (lolos_p1, lolos_p2, gagal_p1) menjadi value baru.
     * 2) Baru ubah definisi ENUM setelah tidak ada baris yang memakai
     *    value lama — supaya ALTER TABLE tidak gagal atau diam-diam
     *    mengosongkan data (tergantung SQL mode MySQL).
     */
    /**
     * PENTING: urutan operasi di sini disengaja, 3 langkah:
     * 1) LEBARKAN dulu definisi ENUM supaya memuat value LAMA
     *    (lolos_p1, gagal_p1, lolos_p2) DAN value BARU ('lolos')
     *    sekaligus — supaya UPDATE di langkah 2 tidak ditolak MySQL
     *    karena 'lolos' belum dikenal sebagai value sah.
     * 2) Normalisasi data: pindahkan semua baris yang masih pakai
     *    value lama ke value baru.
     * 3) PERSEMPIT ENUM jadi 3 value final, setelah dipastikan
     *    tidak ada lagi baris yang memakai value lama.
     */
    public function up(): void
    {
        // ── Langkah 1: lebarkan ENUM (union lama + baru) ──
        DB::statement("
            ALTER TABLE calon_mahasiswa
            MODIFY COLUMN status_seleksi
            ENUM('belum_diproses','lolos_p1','gagal_p1','lolos_p2','ditolak','lolos')
            NOT NULL DEFAULT 'belum_diproses'
        ");

        // ── Langkah 2: normalisasi data lama ──
        DB::table('calon_mahasiswa')
            ->whereIn('status_seleksi', ['lolos_p1', 'lolos_p2'])
            ->update(['status_seleksi' => 'lolos']);

        DB::table('calon_mahasiswa')
            ->where('status_seleksi', 'gagal_p1')
            ->update(['status_seleksi' => 'ditolak']);

        // ── Langkah 3: persempit ENUM jadi 3 value final ──
        DB::statement("
            ALTER TABLE calon_mahasiswa
            MODIFY COLUMN status_seleksi
            ENUM('belum_diproses','lolos','ditolak')
            NOT NULL DEFAULT 'belum_diproses'
        ");
    }

    public function down(): void
    {
        // Kembalikan definisi ENUM lama (data yang sudah dinormalisasi
        // ke 'lolos'/'ditolak' TIDAK bisa dikembalikan otomatis ke
        // lolos_p1/lolos_p2/gagal_p1 karena informasi tahap sudah hilang
        // saat up() dijalankan — ini backward-compatible di level skema
        // saja, bukan pemulihan data penuh).
        DB::statement("
            ALTER TABLE calon_mahasiswa
            MODIFY COLUMN status_seleksi
            ENUM('belum_diproses','lolos_p1','gagal_p1','lolos_p2','ditolak')
            NOT NULL DEFAULT 'belum_diproses'
        ");
    }
};