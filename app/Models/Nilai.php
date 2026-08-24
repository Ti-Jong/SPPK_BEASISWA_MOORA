<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $fillable = ['calon_id', 'kriteria_id', 'skor_raw', 'skor_norm', 'skor_weight'];
    protected $casts = ['skor_norm' => 'decimal:6', 'skor_weight' => 'decimal:6'];

    public function calon()    { return $this->belongsTo(CalonMahasiswa::class, 'calon_id'); }
    public function kriteria() { return $this->belongsTo(Kriteria::class); }
}
