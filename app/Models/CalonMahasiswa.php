<?php

namespace App\Models;
use App\Models\Dokumen;

use Illuminate\Database\Eloquent\Model;
use App\Models\Keputusan;

class CalonMahasiswa extends Model
{
    protected $table = 'calon_mahasiswa';

    protected $fillable = [
        'nim', 'nama', 'tahun_akademik',
        'asal_sekolah', 'semester', 'input_by',
        'nilai_rapor_sem3', 'nilai_rapor_sem4',
        'prodi_id', 'pilihan_1_id', 'pilihan_2_id',
        'prodi_diterima_id',
        'kondisi_ortu', 'penghasilan_ortu',
        'tagihan_listrik', 'tanggungan_anak',
        'status', 'status_seleksi',
        'catatan', 'alasan_penolakan',
        'verifikasi_by', 'verifikasi_at',
    ];

    protected $casts = [
        'penghasilan_ortu'  => 'integer',
        'tagihan_listrik'   => 'integer',
        'tanggungan_anak'   => 'integer',
        'pilihan_1_id'      => 'integer',
        'pilihan_2_id'      => 'integer',
        'prodi_diterima_id' => 'integer',
    ];

    // â”€â”€â”€ Relasi â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    // Tambah relasi baru untuk prodi asal
    public function prodiAsal()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

        public function pilihan1()
    {
        return $this->belongsTo(Prodi::class, 'pilihan_1_id');
    }

    public function pilihan2()
    {
        return $this->belongsTo(Prodi::class, 'pilihan_2_id');
    }

    // Di app/Models/CalonMahasiswa.php
public function pilihan3()
{
    return $this->belongsTo(Prodi::class, 'pilihan_3_id');
}

    public function prodiDiterima()
    {
       return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    

    public function hasilMoora()
    {
        return $this->hasMany(HasilMoora::class, 'calon_id');
    }

    public function keputusan()
    {
        return $this->hasOne(Keputusan::class, 'calon_id');
    }
        public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'calon_id');
    }

    // âš ï¸ Tidak pakai type hint agar kompatibel PHP 7.x dan 8.x
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_akademik', $tahun);
    }

    public function scopeLulusVerifikasi($query)
    {
        return $query->where('status', 'lulus_verifikasi');
    }

    public function scopeSiapDihitung($query)
    {
    return $query->whereNotIn('status', ['draft', 'ditolak']);
    }

    public function scopeBelumDiproses($query)
    {
        return $query->where('status_seleksi', 'belum_diproses');
    }
    public function isDokumenLengkap(): bool
{
    $wajib = [
        'surat_rekomendasi',
        'rapor_semester_3',
        'rapor_semester_4',
        'kartu_keluarga',
        'sktm',
        'surat_penghasilan',
    ];

    $uploaded = $this->dokumen->pluck('jenis')->toArray();

    foreach ($wajib as $jenis) {
        if (!in_array($jenis, $uploaded)) {
            return false;
        }
    }

    return true;
}
public static function jenisDokumenWajib(): array
{
    return [
        'surat_rekomendasi',
        'rapor_semester_3',
        'rapor_semester_4',
        'piagam',
        'kartu_keluarga',
        'foto_rumah',
        'sktm',
        'tagihan_listrik',
        'surat_penghasilan',
    ];
}
    public function statusDokumen(): string
    {
        $wajib    = self::jenisDokumenWajib();
        $uploaded = $this->dokumen->pluck('jenis')->toArray();
        $terisi   = count(array_intersect($wajib, $uploaded));
        $total    = count($wajib);

        if ($terisi === 0)    return 'belum';
        if ($terisi < $total) return 'sebagian';
        return 'lengkap';
    }}
