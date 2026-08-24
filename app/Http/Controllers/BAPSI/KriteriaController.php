<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::with('subKriterias')
            ->whereNull('deleted_at')
            ->orderBy('urutan')
            ->get();

        return response()
            ->view('bapsi.kriteria.index', compact('kriterias'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'kode'   => 'required|string|max:5',
        'nama'   => 'required|string|max:100',
        'tipe'   => 'required|in:benefit,cost',
        'bobot'  => 'required|numeric|min:0.01|max:1',
        'urutan' => 'required|integer|min:1',
    ]);

    $validated['kode'] = strtoupper($validated['kode']);

    // ── Cek dulu apakah kode ini masih dipakai kriteria yang AKTIF ──
    $aktifSamaKode = Kriteria::whereNull('deleted_at')->where('kode', $validated['kode'])->exists();
    if ($aktifSamaKode) {
        return back()->withInput()->with('error',
            "Kode [{$validated['kode']}] sudah dipakai kriteria lain yang masih aktif. Gunakan kode berbeda."
        );
    }

    // ── Validasi total bobot kriteria AKTIF tidak melebihi 100% ──
    $totalBobotAktif    = Kriteria::where('is_active', true)->sum('bobot');
    $totalSetelahTambah = $totalBobotAktif + $validated['bobot'];

    if ($totalSetelahTambah > 1.0001) {
        $sisaBobot = max(0, 1 - $totalBobotAktif);
        return back()->withInput()->with('error',
            "Gagal menambah kriteria: total bobot akan menjadi " . round($totalSetelahTambah * 100, 1) . "%. " .
            "Sisa maksimal yang bisa dipakai adalah " . round($sisaBobot * 100, 1) . "%."
        );
    }

    // ── Kalau kode ini pernah dipakai kriteria yang sudah dihapus, PULIHKAN + TIMPA ──
    $trashedSamaKode = Kriteria::onlyTrashed()->where('kode', $validated['kode'])->first();
    if ($trashedSamaKode) {
        $trashedSamaKode->restore();
        $trashedSamaKode->update($validated);
        return back()->with('success',
            "Kriteria [{$validated['kode']}] berhasil ditambahkan (kode dipakai ulang dari kriteria yang sudah dihapus sebelumnya)."
        );
    }

    Kriteria::create($validated);

    return back()->with('success', 'Kriteria berhasil ditambahkan.');
}
    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'tipe'   => 'required|in:benefit,cost',
            'bobot'  => 'required|numeric|min:0.01|max:1',
            'urutan' => 'required|integer|min:1',
        ]);

        // ── Validasi total bobot, KECUALI kriteria ini sendiri ──
        $totalBobotLain     = Kriteria::where('is_active', true)
            ->where('id', '!=', $kriteria->id)
            ->sum('bobot');
        $totalSetelahUpdate = $totalBobotLain + $validated['bobot'];

        if ($totalSetelahUpdate > 1.0001) {
            $sisaBobot = max(0, 1 - $totalBobotLain);
            return back()->withInput()->with('error',
                "Gagal update: total bobot akan menjadi " . round($totalSetelahUpdate * 100, 1) . "%. " .
                "Maksimal bobot untuk kriteria ini adalah " . round($sisaBobot * 100, 1) . "%."
            );
        }

        $kriteria->update($validated);

        return back()->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cari manual — hindari flakiness route model binding + SoftDeletes
        $kriteria = Kriteria::whereNull('deleted_at')->find($id);

        if (! $kriteria) {
            return back()->with('error', 'Kriteria tidak ditemukan atau sudah dihapus.');
        }

        // Guard: blok hapus jika sudah punya data nilai MOORA
        if ($kriteria->nilais()->exists()) {
            return back()->with('error',
                "Kriteria [{$kriteria->kode}] tidak dapat dihapus karena sudah " .
                "digunakan dalam perhitungan MOORA. Gunakan toggle untuk menonaktifkan."
            );
        }

        try {
            DB::beginTransaction();

            $kode = $kriteria->kode;
            $nama = $kriteria->nama;

            $kriteria->subKriterias()->delete();

            $result = $kriteria->delete();

            if (! $result) {
                throw new \Exception('Soft delete mengembalikan false.');
            }

            DB::commit();

            return back()->with('success', "Kriteria [{$kode} — {$nama}] berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal hapus kriteria', [
                'kriteria_id' => $id,
                'error'       => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus kriteria: ' . $e->getMessage());
        }
    }

    public function toggleAktif(Kriteria $kriteria)
    {
        // ── Kalau mau MENGAKTIFKAN, cek dulu tidak bikin total >100% ──
        if (! $kriteria->is_active) {
            $totalBobotAktif   = Kriteria::where('is_active', true)
                ->where('id', '!=', $kriteria->id)
                ->sum('bobot');
            $totalSetelahAktif = $totalBobotAktif + $kriteria->bobot;

            if ($totalSetelahAktif > 1.0001) {
                return back()->with('error',
                    "Tidak bisa mengaktifkan [{$kriteria->kode}]: total bobot kriteria aktif akan menjadi " .
                    round($totalSetelahAktif * 100, 1) . "%. Nonaktifkan/turunkan bobot kriteria lain dulu."
                );
            }
        }

        $kriteria->update(['is_active' => ! $kriteria->is_active]);
        $status = $kriteria->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kriteria [{$kriteria->kode}] berhasil {$status}.");
    }
}