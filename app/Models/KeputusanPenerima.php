<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeputusanPenerima extends Model
{
    protected $table = 'keputusan_penerimaas';

    protected $fillable = [
        'calon_id',
        'prodi_id',
        'tahun',
        'tahap_diterima',
        'ranking_prodi',
        'ranking_global',
        'skor_moora',
        'status',
    ];

    protected $casts = [
        'tahun'          => 'integer',
        'tahap_diterima' => 'integer',
        'ranking_prodi'  => 'integer',
        'ranking_global' => 'integer',
        'skor_moora'     => 'float',
    ];

    public function calon()
    {
        return $this->belongsTo(CalonMahasiswa::class, 'calon_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
