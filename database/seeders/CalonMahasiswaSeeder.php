<?php

namespace Database\Seeders;

use App\Models\CalonMahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder data contoh calon penerima beasiswa
 * Berguna untuk demo sistem dan pengujian skripsi
 */
class CalonMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $baaUser  = User::where('role', 'baa')->first();
        $bapsiUser = User::where('role', 'bapsi')->first();

        if (!$baaUser) {
            $this->command->warn('User BAA tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $calons = [
            // Informatika
            ['nim'=>'2021INF001','nama'=>'Anastasia Dewi Kurnia',      'prodi'=>'S1-INF',  'semester'=>3, 'penghasilan'=>1500000, 'listrik'=>100000, 'tanggungan'=>4, 'kondisi'=>'yatim',      'status'=>'terverifikasi'],
            ['nim'=>'2021INF002','nama'=>'Benediktus Arga Pratama',    'prodi'=>'S1-INF',  'semester'=>5, 'penghasilan'=>2000000, 'listrik'=>150000, 'tanggungan'=>3, 'kondisi'=>'lengkap',    'status'=>'terverifikasi'],
            ['nim'=>'2021INF003','nama'=>'Callista Rena Wijaya',       'prodi'=>'S1-INF',  'semester'=>3, 'penghasilan'=>800000,  'listrik'=>75000,  'tanggungan'=>5, 'kondisi'=>'yatimpiatu', 'status'=>'terkirim'],

            // Manajemen
            ['nim'=>'2021MNJ001','nama'=>'Darius Putra Santoso',       'prodi'=>'S1-MNJ',  'semester'=>5, 'penghasilan'=>2500000, 'listrik'=>200000, 'tanggungan'=>2, 'kondisi'=>'lengkap',    'status'=>'terverifikasi'],
            ['nim'=>'2021MNJ002','nama'=>'Eunike Florentina Nasib',    'prodi'=>'S1-MNJ',  'semester'=>3, 'penghasilan'=>1200000, 'listrik'=>90000,  'tanggungan'=>4, 'kondisi'=>'piatu',      'status'=>'terverifikasi'],

            // Farmasi
            ['nim'=>'2021FAR001','nama'=>'Fransiskus Yoga Wibowo',     'prodi'=>'S1-FAR',  'semester'=>5, 'penghasilan'=>1800000, 'listrik'=>120000, 'tanggungan'=>3, 'kondisi'=>'yatim',      'status'=>'terverifikasi'],
            ['nim'=>'2021FAR002','nama'=>'Gabriella Indah Lestari',    'prodi'=>'S1-FAR',  'semester'=>3, 'penghasilan'=>900000,  'listrik'=>85000,  'tanggungan'=>4, 'kondisi'=>'piatu',      'status'=>'terverifikasi'],

            // Teknik Mesin
            ['nim'=>'2021TMS001','nama'=>'Hieronimus Budi Setiawan',   'prodi'=>'S1-TMS',  'semester'=>5, 'penghasilan'=>3000000, 'listrik'=>300000, 'tanggungan'=>2, 'kondisi'=>'lengkap',    'status'=>'terverifikasi'],
            ['nim'=>'2021TMS002','nama'=>'Ignatia Sari Pertiwi',       'prodi'=>'S1-TMS',  'semester'=>3, 'penghasilan'=>700000,  'listrik'=>60000,  'tanggungan'=>5, 'kondisi'=>'yatimpiatu', 'status'=>'terverifikasi'],

            // PGSD
            ['nim'=>'2021PGS001','nama'=>'Jonathan Kristiawan',        'prodi'=>'S1-PGSD', 'semester'=>3, 'penghasilan'=>1100000, 'listrik'=>95000,  'tanggungan'=>4, 'kondisi'=>'yatim',      'status'=>'terverifikasi'],
            ['nim'=>'2021PGS002','nama'=>'Katharina Meylinda Putri',   'prodi'=>'S1-PGSD', 'semester'=>5, 'penghasilan'=>2200000, 'listrik'=>180000, 'tanggungan'=>3, 'kondisi'=>'lengkap',    'status'=>'terkirim'],
            ['nim'=>'2021PGS003','nama'=>'Laurentius Bagus Saputra',   'prodi'=>'S1-PGSD', 'semester'=>3, 'penghasilan'=>950000,  'listrik'=>80000,  'tanggungan'=>5, 'kondisi'=>'piatu',      'status'=>'terverifikasi'],

            // Akuntansi
            ['nim'=>'2021AKT001','nama'=>'Maria Cecilia Wahyuningsih', 'prodi'=>'S1-AKT',  'semester'=>5, 'penghasilan'=>1700000, 'listrik'=>140000, 'tanggungan'=>3, 'kondisi'=>'yatim',      'status'=>'terverifikasi'],
            ['nim'=>'2021AKT002','nama'=>'Nikolaus Danu Hermawan',     'prodi'=>'S1-AKT',  'semester'=>3, 'penghasilan'=>2800000, 'listrik'=>220000, 'tanggungan'=>2, 'kondisi'=>'lengkap',    'status'=>'terkirim'],

            // Psikologi
            ['nim'=>'2021PSI001','nama'=>'Oktavia Rahma Dewi',         'prodi'=>'S1-PSI',  'semester'=>3, 'penghasilan'=>600000,  'listrik'=>50000,  'tanggungan'=>6, 'kondisi'=>'yatimpiatu', 'status'=>'terverifikasi'],
            ['nim'=>'2021PSI002','nama'=>'Paulus Agung Nugroho',       'prodi'=>'S1-PSI',  'semester'=>5, 'penghasilan'=>1900000, 'listrik'=>160000, 'tanggungan'=>3, 'kondisi'=>'lengkap',    'status'=>'terverifikasi'],
        ];

        $prodi = Prodi::get()->keyBy('kode');
        $bapsiId = $bapsiUser?->id;

        foreach ($calons as $c) {
            if (!isset($prodi[$c['prodi']])) continue;

            CalonMahasiswa::updateOrCreate(
                ['nim' => $c['nim']],
                [
                    'nama'            => $c['nama'],
                    'prodi_id'        => $prodi[$c['prodi']]->id,
                    'semester'        => $c['semester'],
                    'penghasilan_ortu'=> $c['penghasilan'],
                    'tagihan_listrik' => $c['listrik'],
                    'tanggungan_anak' => $c['tanggungan'],
                    'kondisi_ortu'    => $c['kondisi'],
                    'status'          => $c['status'],
                    'input_by'        => $baaUser->id,
                    'verifikasi_by'   => $c['status'] === 'terverifikasi' ? $bapsiId : null,
                    'verifikasi_at'   => $c['status'] === 'terverifikasi' ? now() : null,
                ]
            );
        }

        $this->command->info('✅ ' . count($calons) . ' data calon mahasiswa seeded.');
    }
}
