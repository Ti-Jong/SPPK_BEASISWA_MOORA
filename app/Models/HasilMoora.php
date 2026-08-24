<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HasilMoora extends Model
{
    protected $table = 'hasil_moora';

    protected $fillable = [
        'calon_id',
        'prodi_id',
        'prodi_seleksi_id',   // ← tambah
        'tahap_seleksi',      // ← tambah
        'skor_moora',
        'yi_score',
        'ranking',
        'ranking_prodi',
        'periode_tahun',
        'detail_hitung',
        'dihitung_oleh',
        'dihitung_at',
    ];

    protected $casts = [
        'detail_hitung' => 'array',
        'dihitung_at'   => 'datetime',
    ];

    public function calon()
    {
        return $this->belongsTo(CalonMahasiswa::class, 'calon_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function prodiSeleksi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_seleksi_id');
    }

    public function dihitungOleh()
    {
        return $this->belongsTo(User::class, 'dihitung_oleh');
    }

    public function keputusan()
    {
        return $this->hasOne(\App\Models\Keputusan::class, 'hasil_moora_id');
    }
}