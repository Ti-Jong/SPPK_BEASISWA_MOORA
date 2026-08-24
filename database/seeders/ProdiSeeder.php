<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            ['kode' => 'D3-TEM',  'nama' => 'Teknologi Elektromedis',                               'jenjang' => 'D3',             'kuota_beasiswa' => 2],
            ['kode' => 'ST-TRM',  'nama' => 'Teknologi Rekayasa Mekatronika',                        'jenjang' => 'Sarjana Terapan', 'kuota_beasiswa' => 2],
            ['kode' => 'S1-AKT',  'nama' => 'Akuntansi',                                            'jenjang' => 'Sarjana',        'kuota_beasiswa' => 3],
            ['kode' => 'S1-EKO',  'nama' => 'Ekonomi',                                              'jenjang' => 'Sarjana',        'kuota_beasiswa' => 3],
            ['kode' => 'S1-MNJ',  'nama' => 'Manajemen',                                            'jenjang' => 'Sarjana',        'kuota_beasiswa' => 3],
            ['kode' => 'S1-FAR',  'nama' => 'Farmasi',                                              'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-BK',   'nama' => 'Bimbingan dan Konseling',                              'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PBSI', 'nama' => 'Pendidikan Bahasa dan Sastra Indonesia',               'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PBI',  'nama' => 'Pendidikan Bahasa Inggris',                            'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PBIO', 'nama' => 'Pendidikan Biologi',                                   'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PAKT', 'nama' => 'Pendidikan Ekonomi Keahlian Khusus Pendidikan Akuntansi','jenjang' => 'Sarjana',     'kuota_beasiswa' => 2],
            ['kode' => 'S1-PEKO', 'nama' => 'Pendidikan Ekonomi Keahlian Khusus Pendidikan Ekonomi','jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PFIS', 'nama' => 'Pendidikan Fisika',                                    'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PGSD', 'nama' => 'Pendidikan Guru Sekolah Dasar',                        'jenjang' => 'Sarjana',        'kuota_beasiswa' => 3],
            ['kode' => 'S1-PKAK', 'nama' => 'Pendidikan Keagamaan Katolik',                         'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PKIM', 'nama' => 'Pendidikan Kimia',                                     'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PMAT', 'nama' => 'Pendidikan Matematika',                                'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PSEJ', 'nama' => 'Pendidikan Sejarah',                                   'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-PSI',  'nama' => 'Psikologi',                                            'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-INF',  'nama' => 'Informatika',                                          'jenjang' => 'Sarjana',        'kuota_beasiswa' => 3],
            ['kode' => 'S1-MAT',  'nama' => 'Matematika',                                           'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-TEL',  'nama' => 'Teknik Elektro',                                       'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-TME',  'nama' => 'Teknik Mesin',                                         'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-SIND', 'nama' => 'Sastra Indonesia',                                     'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-SING', 'nama' => 'Sastra Inggris',                                       'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-SEJ',  'nama' => 'Sejarah',                                              'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
            ['kode' => 'S1-FIL',  'nama' => 'Filsafat Keilahian',                                   'jenjang' => 'Sarjana',        'kuota_beasiswa' => 2],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create(array_merge($prodi, ['is_active' => true]));
        }
        $this->command->info('27 Program Studi seeded.');
    }
}
