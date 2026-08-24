<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $fillable = [
        'calon_id', 'jenis', 'file_path', 'file_name', 'mime_type', 'file_size', 'status',
    ];

    public function calon() { return $this->belongsTo(CalonMahasiswa::class, 'calon_id'); }

    // URL untuk download/preview
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    // Label jenis dokumen
    public function getLabelJenisAttribute(): string
    {
        return match ($this->jenis) {
            'surat_rekomendasi'  => 'Surat Rekomendasi',
            'rapor_semester_3'   => 'Rapor Semester 3',
            'rapor_semester_4'   => 'Rapor Semester 4',
            'piagam'             => 'Piagam Penghargaan',
            'kartu_keluarga'     => 'Kartu Keluarga',
            'foto_rumah'         => 'Foto Rumah',
            'sktm'               => 'SKTM',
            'tagihan_listrik'    => 'Tagihan Listrik',
            'surat_penghasilan'  => 'Surat Penghasilan Orang Tua',
            default              => $this->jenis,
        };
    }
}
