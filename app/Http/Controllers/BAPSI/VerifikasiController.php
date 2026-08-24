<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    /**
     * Daftar calon yang sudah dikirim BAA — siap diverifikasi BAPSI.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun'); // null = tampilkan semua

        $query = CalonMahasiswa::with(['prodi'])
            ->whereIn('status', [
                'menunggu_verifikasi',
                'diproses',
                'lulus_verifikasi',
                'ditolak',
            ]);

        // Filter tahun — handle semua format: 2023, "2023", "2023/2024"
        if ($tahun) {
            $query->where(function ($q) use ($tahun) {
                // Exact match
                $q->where('tahun_akademik', $tahun);

                // Jika input angka saja (2023), cocokkan juga 2023/2024 dan 2022/2023
                if (is_numeric($tahun)) {
                    $q->orWhere('tahun_akademik', 'LIKE', $tahun . '/%')
                      ->orWhere('tahun_akademik', 'LIKE', '%/' . $tahun);
                }

                // Jika input format 2023/2024, cocokkan juga angka 2023
                if (str_contains((string)$tahun, '/')) {
                    $tahunAngka = explode('/', $tahun)[0];
                    $q->orWhere('tahun_akademik', $tahunAngka);
                }
            });
        }

        $calons = $query
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        // Ambil daftar tahun yang tersedia untuk dropdown
        $tahunList = CalonMahasiswa::whereIn('status', [
                'menunggu_verifikasi', 'diproses', 'lulus_verifikasi', 'ditolak'
            ])
            ->distinct()
            ->orderByDesc('tahun_akademik')
            ->pluck('tahun_akademik');

        return view('bapsi.verifikasi.index', compact('calons', 'tahun', 'tahunList'));
    }

    /**
     * Detail lengkap calon untuk proses verifikasi.
     */
    public function show($id)
    {
        $calon = CalonMahasiswa::with(['prodi', 'dokumen'])->findOrFail($id);

        // Tandai sebagai diproses jika masih menunggu
        if ($calon->status === 'menunggu_verifikasi') {
            $calon->update(['status' => 'diproses']);
        }

        return view('bapsi.verifikasi.show', compact('calon'));
    }

    /**
     * Setujui / verifikasi data calon.
     */
    public function approve(Request $request, $id)
    {
        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['menunggu_verifikasi', 'diproses'])) {
            return back()->with('error', 'Status tidak valid untuk diverifikasi.');
        }

        $calon->update([
            'status'           => 'lulus_verifikasi', // ✅ status baru
            'catatan'          => $request->input('catatan'),
            'alasan_penolakan' => null,
            'verifikasi_by'    => Auth::id(),
            'verifikasi_at'    => now(),
        ]);

        return redirect()->route('bapsi.verifikasi.index')
            ->with('success', "Data {$calon->nama} berhasil diverifikasi.");
    }

    // Alias untuk route bapsi.verifikasi.approve
    public function setujui(Request $request, $id)
    {
        return $this->approve($request, $id);
    }

    /**
     * Tolak data calon — dengan alasan_penolakan terpisah.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:1000',
            'catatan'          => 'nullable|string|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['menunggu_verifikasi', 'diproses'])) {
            return back()->with('error', 'Hanya data yang sedang diproses yang bisa ditolak.');
        }

        $calon->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'catatan'          => $request->catatan,
            'verifikasi_by'    => Auth::id(),
            'verifikasi_at'    => now(),
        ]);

        return redirect()->route('bapsi.verifikasi.index')
            ->with('warning', "Data {$calon->nama} ditolak. BAA akan mendapat notifikasi.");
    }

    // Alias untuk route bapsi.verifikasi.reject
    public function tolak(Request $request, $id)
    {
        return $this->reject($request, $id);
    }

    /**
     * Hitung skor MOORA untuk semua calon terverifikasi.
     */
    public function hitungMoora(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $calons = CalonMahasiswa::where('tahun_akademik', $tahun)
            ->where('status', 'lulus_verifikasi') // ✅ status baru
            ->get();

        if ($calons->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang lulus verifikasi untuk dihitung.');
        }

        $hasil = $this->kalkulasiMoora($calons);

        foreach ($hasil as $item) {
            CalonMahasiswa::where('id', $item['id'])->update([
                'skor_moora' => $item['skor'],
                'ranking'    => $item['rank'],
            ]);
        }

        return redirect()->route('bapsi.verifikasi.index', ['tahun' => $tahun])
            ->with('success', 'Kalkulasi MOORA selesai. Peringkat telah diperbarui.');
    }

    // ─────────────────────────────────────────────
    //  PRIVATE: Kalkulasi MOORA
    // ─────────────────────────────────────────────
    private function kalkulasiMoora($calons)
    {
        $kriteria = [
            'nilai_rapor_sem3' => ['type' => 'benefit', 'bobot' => 0.20],
            'nilai_rapor_sem4' => ['type' => 'benefit', 'bobot' => 0.20],
            'penghasilan_ortu' => ['type' => 'cost',    'bobot' => 0.25],
            'tagihan_listrik'  => ['type' => 'cost',    'bobot' => 0.15],
            'tanggungan_anak'  => ['type' => 'benefit', 'bobot' => 0.10],
        ];

        $matrix = [];
        foreach ($calons as $c) {
            $matrix[$c->id] = [
                'nilai_rapor_sem3' => (float) ($c->nilai_rapor_sem3 ?? 0),
                'nilai_rapor_sem4' => (float) ($c->nilai_rapor_sem4 ?? 0),
                'penghasilan_ortu' => (float) ($c->penghasilan_ortu ?? 0),
                'tagihan_listrik'  => (float) ($c->tagihan_listrik  ?? 0),
                'tanggungan_anak'  => (int)   ($c->tanggungan_anak  ?? 0),
            ];
        }

        $denominator = [];
        foreach ($kriteria as $col => $cfg) {
            $sumSq = array_sum(array_map(fn($r) => pow($r[$col], 2), $matrix));
            $denominator[$col] = $sumSq > 0 ? sqrt($sumSq) : 1;
        }

        $skorList = [];
        foreach ($matrix as $id => $row) {
            $yi = 0;
            foreach ($kriteria as $col => $cfg) {
                $normalized = $row[$col] / $denominator[$col];
                $weighted   = $normalized * $cfg['bobot'];
                $yi += ($cfg['type'] === 'benefit') ? $weighted : -$weighted;
            }
            $skorList[] = ['id' => $id, 'skor' => round($yi, 6)];
        }

        usort($skorList, fn($a, $b) => $b['skor'] <=> $a['skor']);
        foreach ($skorList as $i => &$item) {
            $item['rank'] = $i + 1;
        }

        return $skorList;
    }

    private function skorKondisi(string $kondisi): int
    {
        $map = [
            'yatim_piatu' => 5,
            'yatim'       => 4,
            'piatu'       => 3,
            'cerai'       => 2,
            'lengkap'     => 1,
        ];
        return $map[$kondisi] ?? 0;
    }

    // ─────────────────────────────────────────────────────────────────────
//  VERIFIKASI SEMUA — bulk approve
// ─────────────────────────────────────────────────────────────────────
public function approveAll(Request $request)
{
    $tahun = $request->get('tahun');

    DB::beginTransaction();
    try {
        $query = CalonMahasiswa::whereIn('status', ['menunggu_verifikasi', 'diproses']);

        if ($tahun) {
            $query->where(function ($q) use ($tahun) {
                $q->where('tahun_akademik', $tahun);
                if (is_numeric($tahun)) {
                    $q->orWhere('tahun_akademik', 'LIKE', $tahun . '/%')
                      ->orWhere('tahun_akademik', 'LIKE', '%/' . $tahun);
                }
                if (str_contains((string)$tahun, '/')) {
                    $tahunAngka = explode('/', $tahun)[0];
                    $q->orWhere('tahun_akademik', $tahunAngka);
                }
            });
        }

        $jumlah = $query->update([
            'status'           => 'lulus_verifikasi',
            'alasan_penolakan' => null,
            'verifikasi_by'    => Auth::id(),
            'verifikasi_at'    => now(),
        ]);

        DB::commit();

        if ($jumlah === 0) {
            return redirect()->route('bapsi.verifikasi.index', ['tahun' => $tahun])
                ->with('error', 'Tidak ada data yang perlu diverifikasi.');
        }

        return redirect()->route('bapsi.verifikasi.index', ['tahun' => $tahun])
            ->with('success', "{$jumlah} data calon berhasil diverifikasi sekaligus.");

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('bapsi.verifikasi.index', ['tahun' => $tahun])
            ->with('error', 'Gagal verifikasi massal: ' . $e->getMessage());
    }
}
}