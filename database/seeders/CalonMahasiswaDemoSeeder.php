<?php

namespace Database\Seeders;

use App\Models\CalonMahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;

class CalonMahasiswaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $baa   = User::where('role', 'baa')->first();
        $bapsi = User::where('role', 'bapsi')->first();

        $demos = [
            ['nim'=>'2021001','nama'=>'Anastasia Dewi Kurnia',    'prodi'=>'S1-INF', 'semester'=>3,'penghasilan_ortu'=>1500000,'tagihan_listrik'=>100000,'tanggungan_anak'=>4,'kondisi_ortu'=>'yatim',     'status'=>'terverifikasi'],
            ['nim'=>'2021002','nama'=>'Benediktus Arga Pratama',  'prodi'=>'S1-MNJ', 'semester'=>3,'penghasilan_ortu'=>2000000,'tagihan_listrik'=>200000,'tanggungan_anak'=>3,'kondisi_ortu'=>'lengkap',   'status'=>'terverifikasi'],
            ['nim'=>'2021003','nama'=>'Cecilia Rahma Sari',       'prodi'=>'S1-FAR', 'semester'=>3,'penghasilan_ortu'=>800000, 'tagihan_listrik'=>75000, 'tanggungan_anak'=>5,'kondisi_ortu'=>'piatu',     'status'=>'terverifikasi'],
            ['nim'=>'2021004','nama'=>'Darius Putra Santoso',     'prodi'=>'S1-TME', 'semester'=>5,'penghasilan_ortu'=>2500000,'tagihan_listrik'=>250000,'tanggungan_anak'=>2,'kondisi_ortu'=>'lengkap',   'status'=>'terverifikasi'],
            ['nim'=>'2021005','nama'=>'Eunike Florentina',        'prodi'=>'S1-PGSD','semester'=>3,'penghasilan_ortu'=>1200000,'tagihan_listrik'=>85000, 'tanggungan_anak'=>4,'kondisi_ortu'=>'yatimpiatu','status'=>'terverifikasi'],
            ['nim'=>'2021006','nama'=>'Fransiskus Yoga Wibowo',   'prodi'=>'S1-EKO', 'semester'=>5,'penghasilan_ortu'=>1800000,'tagihan_listrik'=>130000,'tanggungan_anak'=>3,'kondisi_ortu'=>'yatim',     'status'=>'terverifikasi'],
            ['nim'=>'2021007','nama'=>'Gabriella Indah Lestari',  'prodi'=>'S1-PSI', 'semester'=>3,'penghasilan_ortu'=>900000, 'tagihan_listrik'=>90000, 'tanggungan_anak'=>4,'kondisi_ortu'=>'piatu',     'status'=>'terverifikasi'],
            ['nim'=>'2021008','nama'=>'Hieronimus Budi Setiawan', 'prodi'=>'S1-AKT', 'semester'=>5,'penghasilan_ortu'=>3000000,'tagihan_listrik'=>300000,'tanggungan_anak'=>2,'kondisi_ortu'=>'lengkap',   'status'=>'terverifikasi'],
            ['nim'=>'2021009','nama'=>'Irene Kusuma Wardani',     'prodi'=>'S1-INF', 'semester'=>3,'penghasilan_ortu'=>700000, 'tagihan_listrik'=>60000, 'tanggungan_anak'=>5,'kondisi_ortu'=>'yatimpiatu','status'=>'terkirim'],
            ['nim'=>'2021010','nama'=>'Johanes Marco Widodo',     'prodi'=>'S1-TEL', 'semester'=>5,'penghasilan_ortu'=>1600000,'tagihan_listrik'=>120000,'tanggungan_anak'=>3,'kondisi_ortu'=>'yatim',     'status'=>'terkirim'],
        ];

        foreach ($demos as $data) {
            $prodi = Prodi::where('kode', $data['prodi'])->first();
            CalonMahasiswa::create([
                'nim'             => $data['nim'],
                'nama'            => $data['nama'],
                'prodi_id'        => $prodi->id,
                'semester'        => $data['semester'],
                'penghasilan_ortu'=> $data['penghasilan_ortu'],
                'tagihan_listrik' => $data['tagihan_listrik'],
                'tanggungan_anak' => $data['tanggungan_anak'],
                'kondisi_ortu'    => $data['kondisi_ortu'],
                'status'          => $data['status'],
                'input_by'        => $baa->id,
                'verifikasi_by'   => $data['status'] === 'terverifikasi' ? $bapsi->id : null,
                'verifikasi_at'   => $data['status'] === 'terverifikasi' ? now() : null,
            ]);
        }
        $this->command->info('10 Demo Calon Mahasiswa seeded.');
    }
}
