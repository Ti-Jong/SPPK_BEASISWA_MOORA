<?php
// database/migrations/xxxx_add_soft_delete_to_kriteria_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->softDeletes(); // tambah kolom deleted_at nullable
        });
    }

    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};