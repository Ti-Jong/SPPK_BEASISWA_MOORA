<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // C1: Kondisi Orang Tua — BENEFIT (semakin tinggi semakin butuh)
        $c1 = Kriteria::create(['kode'=>'C1','nama'=>'Kondisi Orang Tua','tipe'=>'benefit','bobot'=>0.30,'urutan'=>1]);
        SubKriteria::insert([
            ['kriteria_id'=>$c1->id,'label'=>'Yatim Piatu','nilai_string'=>'yatimpiatu','skor'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c1->id,'label'=>'Yatim',      'nilai_string'=>'yatim',     'skor'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c1->id,'label'=>'Piatu',      'nilai_string'=>'piatu',     'skor'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c1->id,'label'=>'Lengkap',    'nilai_string'=>'lengkap',   'skor'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // C2: Penghasilan Orang Tua — COST (semakin tinggi = semakin kaya = skor tinggi, tapi dikurangi)
        $c2 = Kriteria::create(['kode'=>'C2','nama'=>'Penghasilan Orang Tua','tipe'=>'cost','bobot'=>0.35,'urutan'=>2]);
        SubKriteria::insert([
            ['kriteria_id'=>$c2->id,'label'=>'≤ Rp 1.000.000',           'range_min'=>0,       'range_max'=>1000000,   'skor'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c2->id,'label'=>'Rp 1.000.001 – 2.000.000', 'range_min'=>1000001, 'range_max'=>2000000,   'skor'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c2->id,'label'=>'Rp 2.000.001 – 3.000.000', 'range_min'=>2000001, 'range_max'=>3000000,   'skor'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c2->id,'label'=>'> Rp 3.000.000',           'range_min'=>3000001, 'range_max'=>999999999, 'skor'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // C3: Tagihan Listrik — COST
        $c3 = Kriteria::create(['kode'=>'C3','nama'=>'Tagihan Listrik','tipe'=>'cost','bobot'=>0.20,'urutan'=>3]);
        SubKriteria::insert([
            ['kriteria_id'=>$c3->id,'label'=>'≤ Rp 100.000',           'range_min'=>0,      'range_max'=>100000,   'skor'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c3->id,'label'=>'Rp 100.001 – 150.000',   'range_min'=>100001, 'range_max'=>150000,   'skor'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c3->id,'label'=>'Rp 150.001 – 250.000',   'range_min'=>150001, 'range_max'=>250000,   'skor'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c3->id,'label'=>'> Rp 250.000',           'range_min'=>250001, 'range_max'=>999999999,'skor'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // C4: Tanggungan Anak — BENEFIT
        $c4 = Kriteria::create(['kode'=>'C4','nama'=>'Tanggungan Anak','tipe'=>'benefit','bobot'=>0.15,'urutan'=>4]);
        SubKriteria::insert([
            ['kriteria_id'=>$c4->id,'label'=>'≥ 5 anak','range_min'=>5, 'range_max'=>99,'skor'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c4->id,'label'=>'4 anak',  'range_min'=>4, 'range_max'=>4, 'skor'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c4->id,'label'=>'3 anak',  'range_min'=>3, 'range_max'=>3, 'skor'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['kriteria_id'=>$c4->id,'label'=>'≤ 2 anak','range_min'=>0, 'range_max'=>2, 'skor'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        $this->command->info('4 Kriteria + Sub Kriteria seeded.');
    }
}
