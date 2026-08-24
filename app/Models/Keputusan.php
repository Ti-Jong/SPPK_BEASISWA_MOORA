<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keputusan extends Model
{
    use HasFactory;

    protected $table = 'keputusan';

    protected $fillable = [
        'hasil_moora_id',
        'calon_id',
        'prodi_id',        // ← tambah
        'tahun',           // ← tambah
        'status',
        'tahap_diterima',  // ← tambah
        'ranking_prodi',   // ← tambah
        'ranking_global',  // ← tambah
        'skor_moora',      // ← tambah
        'keterangan',
        'ditetapkan_oleh',
        'ditetapkan_at',
    ];

    protected $casts = ['ditetapkan_at' => 'datetime'];

    public function hasilMoora()     { return $this->belongsTo(HasilMoora::class, 'hasil_moora_id'); }
    public function calon()          { return $this->belongsTo(CalonMahasiswa::class, 'calon_id'); }
    public function prodi()          { return $this->belongsTo(Prodi::class, 'prodi_id'); } // ← tambah
    public function ditetapkanOleh() { return $this->belongsTo(User::class, 'ditetapkan_oleh'); }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'penerima'    => 'Penerima Beasiswa',
            'cadangan'    => 'Cadangan',
            'tidak_lolos' => 'Tidak Lolos',
            default       => '-',
        };
    }
}