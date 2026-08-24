<?php

namespace App\Services;

use App\Models\CalonMahasiswa;
use App\Models\HasilMoora;
use App\Models\Prodi;
use App\Models\Kriteria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MooraService
{
    // ════════════════════════════════════════════════════════════
    // LOAD KRITERIA & SUB KRITERIA DARI DB
    // Dipanggil sekali di awal perhitungan, bukan hardcoded
    // ════════════════════════════════════════════════════════════

    /**
     * Load bobot kriteria dari tabel kriteria.
     * Return: ['C1' => 0.30, 'C2' => 0.25, ...]
     */
    private function loadBobot(): array
{
    $rows = Kriteria::query()
    ->whereNull('deleted_at')   
    ->where('is_active', true)
    ->orderBy('urutan')
    ->get();

        $bobot = [];
        foreach ($rows as $r) {
            $bobot[$r->kode] = (float) $r->bobot;
        }

        // Fallback jika DB kosong
        if (empty($bobot)) {
            $bobot = ['C1' => 0.30, 'C2' => 0.25, 'C3' => 0.25, 'C4' => 0.20];
        }

        return $bobot;
    }

    /**
     * Load semua kriteria aktif dari DB.
     */
    private function loadKriteria(): Collection
{
    return Kriteria::query()
        ->whereNull('deleted_at')        // ← tambahkan ini

        ->where('is_active', true)
        ->orderBy('urutan')
        ->get();
}

    /**
     * Load sub kriteria per kriteria_id dari DB.
     */
    private function loadSubKriteria(int $kriteriaId): Collection
    {
        return DB::table('sub_kriteria')
           // ->whereNull('deleted_at')        // ← tambahkan ini

            ->where('kriteria_id', $kriteriaId)
            ->get();
    }

    // ════════════════════════════════════════════════════════════
    // LOOKUP SKOR DARI DB
    // ════════════════════════════════════════════════════════════

    /**
     * Cari skor kondisi orang tua berdasarkan nilai_string.
     */
    private function skorKondisiDariDB(string $nilaiString, Collection $subKriteria)
    
        :int{
        $found = $subKriteria
            ->firstWhere('nilai_string', $nilaiString);
            //cari baris sub kriteria : "Yatim Piatu" → skor 5

        return $found ? (int) $found->skor : 0;
            //  ↑ fallback 0 jika tidak ketemu
    }

    /**
     * Cari skor berdasarkan range (untuk penghasilan & listrik).
     */
    private function skorRangeDariDB(float $nilai, Collection $subKriteria): int
    {
        foreach ($subKriteria as $sub) {
            if ($sub->range_min !== null && $sub->range_max !== null) {
                if ($nilai >= (float)$sub->range_min && $nilai <= (float)$sub->range_max) {
                    return (int) $sub->skor;
                }
            }
        }
        return (int) ($subKriteria->min('skor') ?? 0);
    }

    /**
     * Cari skor tanggungan anak berdasarkan jumlah.
     */
    private function skorTanggunganDariDB(int $jumlah, Collection $subKriteria): int
    {
        if ($jumlah <= 0) return 0;
        $sorted = $subKriteria->sortBy('skor')->values();
        $max    = $sorted->count();
        $idx    = min($jumlah - 1, $max - 1);
        return (int) ($sorted[$idx]->skor ?? 0);
    }

    // ════════════════════════════════════════════════════════════
    // DAFTAR KRITERIA UNTUK TAMPILAN BLADE — dari DB
    // ════════════════════════════════════════════════════════════

        public static function kriteriaList(): array
    {
        // ✅ Ganti DB::table() → Kriteria::query() agar kena SoftDeletes scope
        $rows = Kriteria::query()
            ->whereNull('deleted_at')    // eksplisit, double safety
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $result = [];
        foreach ($rows as $r) {
            $result[$r->kode] = [
                'label' => $r->nama,
                'bobot' => (float) $r->bobot,
                'type'  => $r->tipe,
            ];
        }

        return $result;
    }

    // ════════════════════════════════════════════════════════════
    // HITUNG SKOR MOORA — baca dari DB
    // ════════════════════════════════════════════════════════════

    public function hitungSemua(Collection $calons, int|string $tahun): array
{
    if ($calons->isEmpty()) {
        return [];
    }

    // Load kriteria aktif
    $kriterias = $this->loadKriteria();

    if ($kriterias->isEmpty()) {
        return [];
    }

    // Load bobot
    $bobot = $this->loadBobot();

    // Load sub kriteria
    $subPerKriteria = [];

    foreach ($kriterias as $kriteria) {
        $subPerKriteria[$kriteria->kode] =
            $this->loadSubKriteria($kriteria->id);
    }

    // =========================================
    // BANGUN MATRIKS KEPUTUSAN
    // =========================================
    $matriks = [];

    foreach ($calons as $c) {

        $row = [];

        // C1
        if (isset($subPerKriteria['C1'])) {
            $row['C1'] = $this->skorKondisiDariDB(
                $c->kondisi_ortu ?? '',
                $subPerKriteria['C1']
            );
        }

        // C2
        if (isset($subPerKriteria['C2'])) {
            $row['C2'] = $this->skorRangeDariDB(
                (float) ($c->penghasilan_ortu ?? 0),
                $subPerKriteria['C2']
            );
        }

        // C3
        if (isset($subPerKriteria['C3'])) {
            $row['C3'] = $this->skorRangeDariDB(
                (float) ($c->tagihan_listrik ?? 0),
                $subPerKriteria['C3']
            );
        }

        // C4
        if (isset($subPerKriteria['C4'])) {
            $row['C4'] = $this->skorTanggunganDariDB(
                (int) ($c->tanggungan_anak ?? 0),
                $subPerKriteria['C4']
            );
        }

        $matriks[$c->id] = $row;
    }

    // =========================================
    // HITUNG PEMBAGI
    // =========================================
    $pembagi = [];

    foreach ($bobot as $kode => $nilaiBobot) {

        $pembagi[$kode] = 0;

        foreach ($matriks as $row) {

            $nilai = $row[$kode] ?? 0;

            $pembagi[$kode] += pow($nilai, 2);
        }

        $pembagi[$kode] = sqrt($pembagi[$kode]);

        // Hindari division by zero
        if ($pembagi[$kode] == 0) {
            $pembagi[$kode] = 1;
        }
    }

    // =========================================
    // NORMALISASI + HITUNG YI
    // =========================================
    $scores = [];

    foreach ($matriks as $id => $row) {

        $normalized = [];
        $yi = 0;

        foreach ($kriterias as $kr) {

            $kode = $kr->kode;

            $normalized[$kode] =
                ($row[$kode] ?? 0) / ($pembagi[$kode] ?? 1);

            $nilaiAkhir =
                ($bobot[$kode] ?? 0) * $normalized[$kode];

            if ($kr->tipe === 'benefit') {
                $yi += $nilaiAkhir;
            } else {
                $yi -= $nilaiAkhir;
            }
        }

        $scores[$id] = [
            'yi' => $yi,
            'normalized' => $normalized,
            'raw' => $row,
        ];
    }

    // =========================================
    // SORT BERDASARKAN YI
    // =========================================
    uasort($scores, function ($a, $b) {
        return $b['yi'] <=> $a['yi'];
    });

    // =========================================
    // RANKING GLOBAL
    // =========================================
    $hasil = [];

    $ranking = 1;

    foreach ($scores as $id => $data) {

        $hasil[$id] = [
            'yi' => $data['yi'],
            'normalized' => $data['normalized'],
            'raw' => $data['raw'],
            'ranking_global' => $ranking++,
        ];
    }

    return $hasil;
}

    // ════════════════════════════════════════════════════════════
    // SIMPAN HASIL MOORA KE DB
    // ════════════════════════════════════════════════════════════

    public function simpanHasil(
        array      $skorMap,
        Collection $calons,
        int|string $tahun,
        int        $tahap,
        string     $prodiIdKey
    ): void {
        $byProdi = [];
        foreach ($calons as $c) {
            $prodiId = $c->{$prodiIdKey};
            if (!$prodiId || !isset($skorMap[$c->id])) continue;
            $byProdi[$prodiId][] = $c->id;
        }

        $rankProdi = [];
        foreach ($byProdi as $prodiId => $ids) {
            usort($ids, fn($a, $b) => $skorMap[$b]['yi'] <=> $skorMap[$a]['yi']);
            $r = 1;
            foreach ($ids as $id) {
                $rankProdi[$id] = ['rank' => $r++, 'prodi_id' => $prodiId];
            }
        }

        foreach ($calons as $c) {
            if (!isset($skorMap[$c->id])) continue;
            $prodiId = $c->{$prodiIdKey};
            if (!$prodiId) continue;

            $data = $skorMap[$c->id];
            $rp   = $rankProdi[$c->id] ?? null;

            HasilMoora::updateOrCreate(
                [
                    'calon_id'      => $c->id,
                    'periode_tahun' => $tahun,
                    'tahap_seleksi' => $tahap,
                ],
                [
                    'prodi_seleksi_id' => $prodiId,
                    'prodi_id'         => $prodiId,
                    'yi_score'         => $data['yi'],
                    'skor_moora'       => $data['yi'],
                    'ranking'          => $data['ranking_global'],
                    'ranking_prodi'    => $rp['rank'] ?? null,
                    'detail_hitung'    => json_encode([
                        'skor_per_kriteria' => $data['raw'],
                        'normalized'        => $data['normalized'],
                        'bobot_dari_db'     => $this->loadBobot(),
                        'tahap'             => $tahap,
                        'prodi_pilihan'     => $prodiId,
                    ]),
                ]
            );
        }
    }

    // ════════════════════════════════════════════════════════════
    // RANKING PER PRODI — untuk tampilan LKM
    // ════════════════════════════════════════════════════════════

    public function getRankingPerProdi(int|string $tahun): Collection
    {
        $prodis = Prodi::aktif()
            ->where('kuota_beasiswa', '>', 0)
            ->orderBy('nama')
            ->get();

        $result = collect();

        foreach ($prodis as $prodi) {
            $items = HasilMoora::with(['calon', 'keputusan'])
                ->where('prodi_seleksi_id', $prodi->id)
                ->where('periode_tahun', $tahun)
                ->orderBy('tahap_seleksi')
                ->orderBy('ranking_prodi')
                ->get();

            if ($items->isEmpty()) continue;

            $kuota      = $prodi->kuota_beasiswa;
            $sudahDitap = \App\Models\Keputusan::where('prodi_id', $prodi->id)
                ->where('tahun', $tahun)
                ->where('status', 'penerima')
                ->count();
            $sisaKuota  = max(0, $kuota - $sudahDitap);

            $result->push([
                'prodi'       => $prodi,
                'items'       => $items,
                'total'       => $items->count(),
                'kuota'       => $kuota,
                'sudah_ditap' => $sudahDitap,
                'sisa_kuota'  => $sisaKuota,
            ]);
        }

        return $result;
    }

    // ════════════════════════════════════════════════════════════
    // STATISTIK RINGKASAN MOORA
    // ════════════════════════════════════════════════════════════

    public function getStatistik(int|string $tahun): array
    {
        $query = HasilMoora::where('periode_tahun', $tahun)
            ->where('tahap_seleksi', 1);

        return [
            'total_calon'  => $query->distinct('calon_id')->count('calon_id'),
            'total_prodi'  => Prodi::aktif()->where('kuota_beasiswa', '>', 0)->count(),
            'skor_max'     => (float)($query->max('skor_moora') ?? 0),
            'skor_min'     => (float)($query->min('skor_moora') ?? 0),
            'skor_avg'     => (float)($query->avg('skor_moora') ?? 0),
        ];
    }
}