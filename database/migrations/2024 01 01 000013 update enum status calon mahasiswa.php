<?php

// database/migrations/2024_01_01_000013_update_enum_status_calon_mahasiswa.php
//
// Jalankan dengan: php artisan migrate

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update ENUM kolom status — tambahkan semua nilai yang dipakai di sistem
        DB::statement("
            ALTER TABLE calon_mahasiswa
            MODIFY COLUMN status ENUM(
                'draft',
                'menunggu_verifikasi',
                'diproses',
                'lulus_verifikasi',
                'ditolak',
                'ditetapkan'
            ) NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        // Rollback ke ENUM lama (sebelum ada menunggu_verifikasi)
        DB::statement("
            ALTER TABLE calon_mahasiswa
            MODIFY COLUMN status ENUM(
                'draft',
                'ditolak',
                'ditetapkan'
            ) NOT NULL DEFAULT 'draft'
        ");
    }
};