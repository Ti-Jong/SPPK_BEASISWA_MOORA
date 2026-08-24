<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'kriteria';
    protected $dates    = ['deleted_at'];

    protected $fillable = [
        'kode', 'nama', 'tipe', 'bobot', 'urutan', 'is_active',
    ];

    protected $casts = [
        'bobot'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function isBenefit(): bool { return $this->tipe === 'benefit'; }
    public function isCost(): bool    { return $this->tipe === 'cost'; }

    public function scopeAktif($query)
    {
        return $query->where('is_active', 1);
    }
}