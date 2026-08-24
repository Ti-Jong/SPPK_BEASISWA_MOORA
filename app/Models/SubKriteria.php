<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria';
    protected $fillable = [
        'kriteria_id', 'label', 'range_min', 'range_max', 'nilai_string', 'skor',
    ];
    protected $casts = [
        'range_min' => 'decimal:2',
        'range_max' => 'decimal:2',
    ];

    public function kriteria() { return $this->belongsTo(Kriteria::class); }
}
