<?php

namespace App\Http\Controllers\LKM;

use App\Http\Controllers\Controller;
use App\Models\HasilMoora;
use App\Models\Prodi;
use App\Services\MooraService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function __construct(private MooraService $mooraService) {}

    public function index(Request $request)
    {
        try {
            $tahunList = HasilMoora::distinct()
                ->orderByDesc('periode_tahun')
                ->pluck('periode_tahun');

            // Fallback jika belum ada hasil MOORA sama sekali
            if ($tahunList->isEmpty()) {
                $tahunList = collect([date('Y')]);
            }

            $tahun       = $request->input('tahun', $tahunList->first());
            $prodiFilter = $request->input('prodi_id');
            $prodis      = Prodi::aktif()
                ->where('kuota_beasiswa', '>', 0)
                ->orderBy('nama')->get();

            if ($prodiFilter) {
                // Mode: satu prodi
                $items = HasilMoora::with(['calon', 'keputusan'])
                    ->where('periode_tahun', $tahun)
                    ->where('prodi_seleksi_id', $prodiFilter)
                    ->orderBy('tahap_seleksi')
                    ->orderBy('ranking_prodi')
                    ->get();
                $prodi = $prodis->firstWhere('id', $prodiFilter);

                $rankingPerProdi = $items->isEmpty()
                    ? collect()
                    : collect([[
                        'prodi'       => $prodi,
                        'items'       => $items,
                        'total'       => $items->count(),
                        'kuota'       => $prodi?->kuota_beasiswa ?? 0,
                        'sudah_ditap' => 0,
                        'sisa_kuota'  => 0,
                    ]]);
            } else {
                // Mode: semua prodi
                $rankingPerProdi = $this->mooraService->getRankingPerProdi($tahun);
            }

            $statistik = $this->mooraService->getStatistik($tahun);
            $maxSkor   = $statistik['skor_max'];
            $minSkor   = $statistik['skor_min'];

            return view('lkm.ranking.index', compact(
                'rankingPerProdi',
                'prodis',
                'tahun',
                'tahunList',
                'prodiFilter',
                'statistik',
                'maxSkor',
                'minSkor',
            ));

        } catch (\Throwable $e) {
            \Log::error('RankingController Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Tetap render halaman dengan data kosong — tidak blank
            return view('lkm.ranking.index', [
                'rankingPerProdi' => collect(),
                'prodis'          => collect(),
                'tahun'           => date('Y'),
                'tahunList'       => collect([date('Y')]),
                'prodiFilter'     => null,
                'statistik'       => [
                    'total_calon' => 0,
                    'total_prodi' => 0,
                    'skor_max'    => 0,
                    'skor_min'    => 0,
                    'skor_avg'    => 0,
                ],
                'maxSkor' => 0,
                'minSkor' => 0,
                'error'   => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function byProdi(Request $request, Prodi $prodi)
    {
        $tahun = $request->input('tahun', date('Y'));

       $items = HasilMoora::with(['calon', 'keputusan'])
            ->where('prodi_seleksi_id', $prodi->id)
            ->where('periode_tahun', $tahun)
            ->orderBy('tahap_seleksi')
            ->orderBy('ranking_prodi')
            ->get();

        return view('lkm.ranking.index', [
            'rankingPerProdi' => collect([[
                'prodi'       => $prodi,
                'items'       => $items,
                'total'       => $items->count(),
                'kuota'       => $prodi->kuota_beasiswa,
                'sudah_ditap' => 0,
                'sisa_kuota'  => 0,
            ]]),
            'prodis'      => Prodi::aktif()->where('kuota_beasiswa', '>', 0)->orderBy('nama')->get(),
            'tahun'       => $tahun,
            'tahunList'   => HasilMoora::distinct()->orderByDesc('periode_tahun')->pluck('periode_tahun'),
            'prodiFilter' => $prodi->id,
            'statistik'   => $this->mooraService->getStatistik($tahun),
            'maxSkor'     => 0,
            'minSkor'     => 0,
        ]);
    }
}