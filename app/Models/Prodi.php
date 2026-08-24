<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $fillable = ['kode', 'nama', 'jenjang', 'kuota_beasiswa', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    // ─── Scope ───────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Relasi ───────────────────────────────
    public function calonMahasiswa()
    {
        return $this->hasMany(CalonMahasiswa::class, 'prodi_id');
    }

    // Alias untuk withCount('calonMahasiswas') — Laravel otomatis pluralize
    public function calonMahasiswas()
    {
        return $this->hasMany(CalonMahasiswa::class, 'prodi_id');
    }

    public function hasilMoora()
    {
        return $this->hasMany(HasilMoora::class, 'prodi_id');
    }

    // Alias untuk withCount('hasilMooras')
    public function hasilMooras()
    {
        return $this->hasMany(HasilMoora::class, 'prodi_id');
    }

    public function sisaKuota(int $tahun): int
    {
        $terisi = \App\Models\Keputusan::where('prodi_id', $this->id)
            ->where('tahun', $tahun)
            ->where('status', 'penerima')
            ->count();
        return max(0, $this->kuota_beasiswa - $terisi);
    }
}