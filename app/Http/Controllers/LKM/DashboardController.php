<?php

// ================================================================
// app/Http/Controllers/LKM/DashboardController.php
// REPLACE SELURUH FILE INI
// ================================================================

namespace App\Http\Controllers\LKM;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\HasilMoora;
use App\Models\Keputusan;
//use App\Models\Prodi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ── 1. Daftar tahun tersedia ──
        $tahunList = HasilMoora::distinct()
            ->orderByDesc('periode_tahun')
            ->pluck('periode_tahun');

        // Fallback ke calon_mahasiswa jika hasil_moora kosong
        if ($tahunList->isEmpty()) {
            $tahunList = CalonMahasiswa::distinct()
                ->orderByDesc('tahun_akademik')
                ->pluck('tahun_akademik');
        }

        // ── 2. Tahun aktif ──
        $tahunAktif = $request->input('tahun', $tahunList->first() ?? '-');
        $tahun      = $tahunAktif; // alias agar blade lama ($tahun) tetap jalan

        // ── 3. Semua hasil MOORA periode aktif ──
        $hasilMoora = HasilMoora::with(['calon.prodi'])
            ->where('periode_tahun', $tahunAktif)
            ->orderBy('ranking')
            ->get();

        // ── 4. Top 5 ranking ──
        $topRanking = $hasilMoora->take(5);

        // ── 5. Keputusan yang sudah ditetapkan ──
        $keputusan = Keputusan::whereHas('hasilMoora', function ($q) use ($tahunAktif) {
                $q->where('periode_tahun', $tahunAktif);
            })
            ->with(['hasilMoora.calon.prodi'])
            ->get();

        // ── 6. Stats array (semua key yang dipakai blade) ──
        $stats = [
            'total_ranking'  => $hasilMoora->count(),
            'penerima'       => $keputusan->where('status', 'penerima')->count(),
            'cadangan'       => $keputusan->where('status', 'cadangan')->count(),
            'tidak_lolos'    => $keputusan->where('status', 'tidak_lolos')->count(),
            'belum_ditetapkan' => $hasilMoora->count() - $keputusan->count(),
            'total_prodi'    => $hasilMoora->pluck('calon.prodi_id')->filter()->unique()->count(),
            'skor_tertinggi' => $hasilMoora->max('skor_moora'),
            'skor_terendah'  => $hasilMoora->min('skor_moora'),
        ];

        return view('lkm.dashboard.index', compact(
            'tahunList',
            'tahunAktif',
            'tahun',          // alias — untuk blade yang pakai $tahun
            'hasilMoora',
            'topRanking',
            'keputusan',
            'stats',
        ));
    }
}