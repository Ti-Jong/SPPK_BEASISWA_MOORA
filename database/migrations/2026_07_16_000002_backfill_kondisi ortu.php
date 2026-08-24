<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Backfill kondisi_ortu yang masih pakai format LAMA (4 kategori kasar
     * dari dropdown create.blade.php versi awal: yatimpiatu/piatu/yatim/lengkap)
     * menjadi format BARU (11 kategori nilai_string yang cocok dengan
     * SubKriteria kriteria_id=1).
     *
     * Tanpa fix ini, MooraService::skorKondisiDariDB() gagal mencocokkan
     * value lama -> fallback skor 0, mendiamkan bobot C1 (30%) untuk
     * baris yang terdampak.
     *
     * CATATAN: 'lengkap' bersifat AMBIGU di data lama — dropdown versi awal
     * mengelompokkan 6 kondisi berbeda (ayah/ibu sehat, sakit, pensiun, dst.)
     * ke satu value yang sama, sehingga informasi detail sudah hilang sejak
     * awal. Fallback 'lengkap' -> 'ada_ayah_ibu_sehat' adalah asumsi PALING
     * AMAN (kondisi mayoritas & skor terendah/paling konservatif), TAPI ini
     * berarti baris yang aslinya "ayah sakit tidak bekerja" dkk (yang
     * seharusnya dapat skor lebih tinggi) tidak akan otomatis benar.
     * Rekomendasi: setelah backfill ini, minta BAA verifikasi ulang secara
     * manual baris mana saja yang kena fallback 'lengkap', karena data
     * detailnya sudah tidak bisa direkonstruksi otomatis dari value lama.
     */
    public function up(): void
    {
        $map = [
            'yatimpiatu' => 'tidak_punya_ayah_ibu',
            'piatu'      => 'hanya_ayah_sehat',
            'yatim'      => 'hanya_ibu_sehat',
            'lengkap'    => 'ada_ayah_ibu_sehat', // fallback konservatif, lihat catatan di atas
        ];

        $terdampak = [];

        foreach ($map as $lama => $baru) {
            $ids = DB::table('calon_mahasiswa')
                ->where('kondisi_ortu', $lama)
                ->pluck('id')
                ->toArray();

            if (!empty($ids)) {
                DB::table('calon_mahasiswa')
                    ->where('kondisi_ortu', $lama)
                    ->update(['kondisi_ortu' => $baru]);

                $terdampak[$lama] = $ids;
            }
        }

        if (!empty($terdampak)) {
            Log::warning('Backfill kondisi_ortu: baris yang terdampak', $terdampak);

            if (isset($terdampak['lengkap'])) {
                Log::warning(
                    'PERHATIAN: ' . count($terdampak['lengkap']) . ' baris di-fallback ke ' .
                    '"ada_ayah_ibu_sehat" dari value lama "lengkap" yang ambigu. ' .
                    'ID calon yang perlu diverifikasi manual: ' . implode(',', $terdampak['lengkap'])
                );
            }
        }
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan dengan aman — informasi kategori lama
        // (4 kategori kasar) sudah tergantikan value baru yang lebih rinci,
        // dan pemetaan baru->lama bersifat many-to-one (tidak reversibel).
    }
};