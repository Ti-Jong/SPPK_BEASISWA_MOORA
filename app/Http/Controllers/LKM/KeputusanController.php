<?php

namespace App\Http\Controllers\LKM;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\HasilMoora;
use App\Models\Keputusan;
use App\Models\Prodi;
use App\Services\MooraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeputusanController extends Controller
{
    public function __construct(private MooraService $mooraService) {}

    public function index(Request $request)
    {
        try {
            $tahunList = HasilMoora::distinct()
                ->orderByDesc('periode_tahun')
                ->pluck('periode_tahun');

            if ($tahunList->isEmpty()) {
                $tahunList = collect([date('Y')]);
            }

            $tahun = $request->get('tahun', $tahunList->first());

            $rankingPerProdi = $this->mooraService->getRankingPerProdi($tahun);

            $sudahDitetapkan = Keputusan::where('tahun', $tahun)
                ->where('status', 'penerima')
                ->exists();

            $totalPenerima = Keputusan::where('tahun', $tahun)
                ->where('status', 'penerima')
                ->count();

            // Lookup Keputusan berdasarkan calon_id (bukan hasil_moora_id) —
            // satu calon cuma punya satu Keputusan final per tahun.
            $keputusanByCalon = Keputusan::where('tahun', $tahun)
                ->get()
                ->keyBy('calon_id');

            return view('lkm.keputusan.index', compact(
                'rankingPerProdi',
                'tahun',
                'tahunList',
                'sudahDitetapkan',
                'totalPenerima',
                'keputusanByCalon',
            ));

        } catch (\Throwable $e) {
            Log::error('KeputusanController@index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return view('lkm.keputusan.index', [
                'rankingPerProdi'  => collect(),
                'tahun'            => date('Y'),
                'tahunList'        => collect([date('Y')]),
                'sudahDitetapkan'  => false,
                'totalPenerima'    => 0,
                'keputusanByCalon' => collect(),
                'error'            => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Tetapkan penerima beasiswa berdasarkan ranking MOORA.
     *
     * SINGLE-STAGE (sejak refactor): calon status_seleksi = 'lolos' → keputusan 'penerima'.
     * Calon status_seleksi = 'ditolak' → keputusan 'tidak_lolos'.
     * tahap_diterima selalu 1 (mekanisme P1/P2/P3 sudah dihapus).
     */
    public function tetapkan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        try {
            DB::beginTransaction();

            $calonLolos = CalonMahasiswa::where('tahun_akademik', $tahun)
                ->where('status_seleksi', 'lolos')
                ->whereNotNull('prodi_diterima_id')
                ->get();

            if ($calonLolos->isEmpty()) {
                return back()->with('error',
                    'Tidak ada data lolos seleksi. Pastikan BAPSI sudah menjalankan Hitung MOORA dan Kirim ke LKM.'
                );
            }

            // Hapus keputusan lama tahun ini lalu buat ulang
            Keputusan::where('tahun', $tahun)->delete();

            foreach ($calonLolos as $calon) {
                $hasil = HasilMoora::where('calon_id', $calon->id)
                    ->where('periode_tahun', $tahun)
                    ->where('tahap_seleksi', 1)
                    ->first();

                Keputusan::create([
                    'calon_id'        => $calon->id,
                    'tahun'           => $tahun,
                    'hasil_moora_id'  => $hasil?->id,
                    'prodi_id'        => $calon->prodi_diterima_id,
                    'status'          => 'penerima',
                    'tahap_diterima'  => 1,
                    'ranking_prodi'   => $hasil?->ranking_prodi,
                    'ranking_global'  => $hasil?->ranking,
                    'skor_moora'      => $hasil?->skor_moora,
                    'keterangan'      => 'Lolos seleksi MOORA',
                    'ditetapkan_oleh' => auth()->id(),
                    'ditetapkan_at'   => now(),
                ]);
            }

            // Calon ditolak → tidak_lolos
            $ditolak = CalonMahasiswa::where('tahun_akademik', $tahun)
                ->where('status_seleksi', 'ditolak')
                ->get();

            foreach ($ditolak as $calon) {
                $hasil = HasilMoora::where('calon_id', $calon->id)
                    ->where('periode_tahun', $tahun)
                    ->where('tahap_seleksi', 1)
                    ->first();

                Keputusan::create([
                    'calon_id'        => $calon->id,
                    'tahun'           => $tahun,
                    'hasil_moora_id'  => $hasil?->id,
                    'prodi_id'        => $calon->prodi_id,
                    'status'          => 'tidak_lolos',
                    'tahap_diterima'  => 0,
                    'ranking_prodi'   => $hasil?->ranking_prodi,
                    'ranking_global'  => $hasil?->ranking,
                    'skor_moora'      => $hasil?->skor_moora,
                    'keterangan'      => 'Tidak lolos seleksi MOORA',
                    'ditetapkan_oleh' => auth()->id(),
                    'ditetapkan_at'   => now(),
                ]);
            }

            DB::commit();

            $total = $calonLolos->count();
            return back()->with('success', sprintf(
                'Berhasil menetapkan %d penerima dan %d tidak lolos — tahun %d.',
                $total, $ditolak->count(), $tahun
            ));

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KeputusanController@tetapkan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menetapkan: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $tahun  = $request->get('tahun', date('Y'));
        $format = $request->get('format', 'csv');

        $keputusans = Keputusan::with(['calon', 'prodi'])
            ->where('tahun', $tahun)
            ->where('status', 'penerima')
            ->orderBy('prodi_id')
            ->orderBy('ranking_prodi')
            ->get();

        if ($format === 'html') {
            $byProdi = $keputusans->groupBy('prodi_id')->sortKeys();
            return view('lkm.keputusan.print', compact('keputusans', 'byProdi', 'tahun'));
        }

        $filename = 'penerima_beasiswa_' . $tahun . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($keputusans) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'No', 'NIM', 'Nama', 'Program Studi',
                'Rank Prodi', 'Rank Global', 'Skor MOORA', 'Status'
            ]);
            $no = 1;
            foreach ($keputusans as $k) {
                fputcsv($file, [
                    $no++,
                    $k->calon?->nim        ?? '-',
                    $k->calon?->nama       ?? '-',
                    $k->prodi?->nama       ?? '-',
                    $k->ranking_prodi      ?? '-',
                    $k->ranking_global     ?? '-',
                    number_format((float)($k->skor_moora ?? 0), 6),
                    ucfirst($k->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function finalize(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        try {
            Keputusan::where('tahun', $tahun)
                ->whereNull('keterangan')
                ->orWhere('keterangan', 'not like', '%[FINAL]%')
                ->update([
                    'keterangan' => DB::raw("CONCAT(COALESCE(keterangan,''), ' [FINAL]')"),
                ]);

            return back()->with('success', 'Keputusan tahun ' . $tahun . ' telah difinalisasi.');

        } catch (\Throwable $e) {
            Log::error('Finalize Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }
}