<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\HasilMoora;
use App\Models\Keputusan;
use App\Models\Prodi;
use App\Services\MooraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MooraController extends Controller
{
    public function __construct(protected MooraService $moora) {}

    public function index(Request $request)
    {
        $tahunList   = \App\Models\CalonMahasiswa::select('tahun_akademik')->distinct()->orderByDesc('tahun_akademik')->pluck('tahun_akademik');
        $defaultTahun = $tahunList->first() ?? date('Y');
        $tahun       = $request->get('tahun', session('tahun_aktif', $defaultTahun));
        $prodiFilter = $request->get('prodi_id');

        $calons   = CalonMahasiswa::tahun($tahun)->lulusVerifikasi()->get();
        $hasilAda = HasilMoora::where('periode_tahun', $tahun)
                        ->where('tahap_seleksi', 1)
                        ->exists();

        // Hanya prodi aktif dengan kuota > 0
        $prodis = Prodi::aktif()
            ->where('kuota_beasiswa', '>', 0)
            ->withCount([
                'calonMahasiswas as total_lulus' => fn($q) =>
                    $q->where('tahun_akademik', $tahun)->where('status', 'lulus_verifikasi'),
                'hasilMooras as sudah_dihitung' => fn($q) =>
                    $q->where('periode_tahun', $tahun)->where('tahap_seleksi', 1),
            ])
            ->orderBy('nama')
            ->get();

        $rankingPerProdi = [];
        foreach ($prodis as $prodi) {
            $rankingPerProdi[$prodi->id] = HasilMoora::with('calon')
                ->where('prodi_seleksi_id', $prodi->id)
                ->where('periode_tahun', $tahun)
                ->orderBy('ranking_prodi')
                ->get();
        }

        $statistik = [
            'total_lulus'    => $calons->count(),
            'sudah_dihitung' => HasilMoora::where('periode_tahun', $tahun)
                                    ->where('tahap_seleksi', 1)
                                    ->distinct('calon_id')->count(),
            'total_calon'    => $calons->count(),
            'total_prodi'    => $prodis->count(),
            'skor_max'       => HasilMoora::where('periode_tahun', $tahun)->max('skor_moora') ?? 0,
            'skor_avg'       => HasilMoora::where('periode_tahun', $tahun)->avg('skor_moora') ?? 0,
        ];

        $stats         = ['total_lulus' => $calons->count()];
        $calonPerProdi = collect();

        return view('bapsi.moora.index', compact(
            'prodis', 'rankingPerProdi', 'statistik', 'stats',
            'calonPerProdi', 'tahun', 'tahunList', 'calons', 'hasilAda', 'prodiFilter'
        ));
    }

    // ═══════════════════════════════════════════════════════
    // HITUNG MOORA — SINGLE STAGE
    // Mekanisme P1/P2/P3 dihapus. Calon sudah punya prodi_id
    // tetap (diterima lewat jalur akademik), sistem hanya
    // menentukan lolos/tidak berdasarkan skor Yi & kuota prodi.
    // ═══════════════════════════════════════════════════════
    public function hitung(Request $request)
    {
        $tahun = $request->get('tahun', session('tahun_aktif', date('Y')));

        try {
            DB::beginTransaction();

            CalonMahasiswa::tahun($tahun)
                ->siapDihitung()
                ->update(['status_seleksi' => 'belum_diproses', 'prodi_diterima_id' => null]);

            HasilMoora::where('periode_tahun', $tahun)->delete();

            $semuaCalon = CalonMahasiswa::with(['prodi'])
                ->tahun($tahun)
                ->siapDihitung()
                ->whereNotNull('prodi_id')
                ->get();

            if ($semuaCalon->isEmpty()) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada calon lulus verifikasi dengan prodi diterima.');
            }

            // ── Hitung Yi sekali untuk seluruh calon ──
            $skorMap = $this->moora->hitungSemua($semuaCalon, $tahun);

            // ── Simpan hasil, selalu tahap 1 (tidak ada lagi tahap 2/3) ──
            $this->moora->simpanHasil($skorMap, $semuaCalon, $tahun, 1, 'prodi_id');

            $kuotaProdi = Prodi::aktif()
                ->where('kuota_beasiswa', '>', 0)
                ->pluck('kuota_beasiswa', 'id');

            $lolosIds   = [];
            $ditolakIds = [];

            $prodiIds = $semuaCalon->pluck('prodi_id')->unique()->filter()
                ->filter(fn($id) => isset($kuotaProdi[$id]));

            foreach ($prodiIds as $prodiId) {
                $kandidat = HasilMoora::where('prodi_seleksi_id', $prodiId)
                    ->where('periode_tahun', $tahun)->where('tahap_seleksi', 1)
                    ->orderBy('ranking_prodi')->get();

                $diterima = 0;
                foreach ($kandidat as $h) {
                    if ($diterima < $kuotaProdi[$prodiId]) {
                        $lolosIds[] = $h->calon_id;
                        CalonMahasiswa::where('id', $h->calon_id)->update([
                            'status_seleksi'    => 'lolos',
                            'prodi_diterima_id' => $prodiId,
                        ]);
                        $diterima++;
                    } else {
                        $ditolakIds[] = $h->calon_id;
                        CalonMahasiswa::where('id', $h->calon_id)->update([
                            'status_seleksi' => 'ditolak',
                        ]);
                    }
                }
            }

            // Calon dengan prodi kuota=0 / prodi tidak dikenali sistem → ditolak langsung
            foreach ($semuaCalon->filter(fn($c) => !isset($kuotaProdi[$c->prodi_id])) as $c) {
                $ditolakIds[] = $c->id;
                CalonMahasiswa::where('id', $c->id)->update(['status_seleksi' => 'ditolak']);
            }

            DB::commit();

            $totalDitolak = CalonMahasiswa::tahun($tahun)
                ->where('status_seleksi', 'ditolak')
                ->count();

            return back()->with('success', sprintf(
                'Perhitungan MOORA selesai. Lolos: %d | Ditolak: %d',
                count(array_unique($lolosIds)),
                $totalDitolak
            ));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('MOORA Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════
    // KIRIM KE LKM
    // Calon lolos → status 'ditetapkan' + buat record keputusan
    // ═══════════════════════════════════════════════════════
    public function kirimKeLKM(Request $request)
    {
        $tahun = $request->get('tahun', session('tahun_aktif', date('Y')));

        try {
            if (!HasilMoora::where('periode_tahun', $tahun)->where('tahap_seleksi', 1)->exists()) {
                return redirect()->route('bapsi.moora.index')
                    ->with('error', 'Belum ada hasil MOORA tahun ' . $tahun . '. Hitung MOORA terlebih dahulu.');
            }

            $calonLolos = CalonMahasiswa::tahun($tahun)
                ->where('status_seleksi', 'lolos')
                ->whereNotNull('prodi_diterima_id')
                ->get();

            if ($calonLolos->isEmpty()) {
                return redirect()->route('bapsi.moora.index')
                    ->with('error', 'Tidak ada calon lolos seleksi untuk dikirim ke LKM.');
            }

            DB::beginTransaction();

            foreach ($calonLolos as $calon) {
                $calon->update(['status' => 'ditetapkan']);

                $hasilMoora = HasilMoora::where('calon_id', $calon->id)
                    ->where('periode_tahun', $tahun)
                    ->where('tahap_seleksi', 1)
                    ->first();

                Keputusan::updateOrCreate(
                    [
                        'calon_id' => $calon->id,
                        'tahun'    => $tahun,
                    ],
                    [
                        'hasil_moora_id' => $hasilMoora?->id,
                        'prodi_id'       => $calon->prodi_diterima_id,
                        'status'         => 'penerima',
                        'tahap_diterima' => 1,
                        'ranking_prodi'  => $hasilMoora?->ranking_prodi,
                        'ranking_global' => $hasilMoora?->ranking,
                        'skor_moora'     => $hasilMoora?->skor_moora,
                        'ditetapkan_oleh'=> auth()->id(),
                        'ditetapkan_at'  => now(),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('bapsi.moora.index')
                ->with('success', sprintf(
                    '%d calon berhasil dikirim ke LKM untuk tahun %s.',
                    $calonLolos->count(),
                    $tahun
                ));

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Kirim LKM Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('bapsi.moora.index')
                ->with('error', 'Gagal mengirim ke LKM: ' . $e->getMessage());
        }
    }

    public function detail(int $id)
    {
        $hasil = HasilMoora::with(['calon', 'prodiSeleksi'])->findOrFail($id);
        return view('bapsi.moora.detail', compact('hasil'));
    }

    public function export(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $hasil = HasilMoora::with(['calon', 'prodiSeleksi'])
            ->where('periode_tahun', $tahun)
            ->where('tahap_seleksi', 1)
            ->orderBy('ranking')
            ->get();

        $filename = 'hasil_moora_' . $tahun . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($hasil) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Rank', 'NIM', 'Nama', 'Prodi', 'Skor MOORA', 'Status Seleksi']);
            foreach ($hasil as $h) {
                fputcsv($file, [
                    $h->ranking,
                    $h->calon->nim         ?? '-',
                    $h->calon->nama        ?? '-',
                    $h->prodiSeleksi->nama ?? '-',
                    $h->skor_moora,
                    $h->calon->status_seleksi ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ═══════════════════════════════════════════════════════
    // RESET — hapus hasil MOORA, kembalikan status calon
    // ═══════════════════════════════════════════════════════
    public function reset(Request $request)
    {
        $tahun = $request->get('tahun', session('tahun_aktif', date('Y')));

        try {
            DB::beginTransaction();

            HasilMoora::where('periode_tahun', $tahun)->delete();
            \App\Models\Keputusan::where('tahun', $tahun)->delete();

            CalonMahasiswa::where('tahun_akademik', $tahun)
                ->whereIn('status', ['lulus_verifikasi', 'ditetapkan'])
                ->update([
                    'status'            => 'lulus_verifikasi',
                    'status_seleksi'    => 'belum_diproses',
                    'prodi_diterima_id' => null,
                ]);

            DB::commit();
            return redirect()->route('bapsi.moora.index')
                ->with('success', 'Reset hasil MOORA tahun ' . $tahun . ' berhasil.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reset MOORA Error: ' . $e->getMessage());
            return redirect()->route('bapsi.moora.index')
                ->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }
}