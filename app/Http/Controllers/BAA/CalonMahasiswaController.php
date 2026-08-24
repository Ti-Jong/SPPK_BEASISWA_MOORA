<?php

namespace App\Http\Controllers\BAA;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalonMahasiswaController extends Controller
{

    // ─────────────────────────────────────────────────────────────────
    //  Helper: normalisasi tahun aktif → selalu string tahun awal
    //  Contoh: "2025/2026" → "2025", 2025 → "2025", "2025" → "2025"
    // ─────────────────────────────────────────────────────────────────
    private function getTahunAwal($raw): string
    {
        $raw = (string) $raw;
        if (str_contains($raw, '/')) {
            return explode('/', $raw)[0];
        }
        return $raw;
    }

    // ─────────────────────────────────────────────────────────────────
    //  INDEX – daftar calon + notifikasi penolakan
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // Simpan tahun ke session sebagai STRING
        if ($request->has('tahun')) {
            session(['baa_tahun_aktif' => $request->tahun]);
        }

        $tahunAktif = $this->getTahunAwal(session('baa_tahun_aktif', now()->year));

        // Query: WHERE tahun_akademik LIKE '2025/%' → match '2025/2026' saja
        $query = CalonMahasiswa::with(['prodi', 'dokumen'])
            ->where('tahun_akademik', 'LIKE', $tahunAktif . '/%');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $calons = $query
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        // Data ditolak untuk panel notifikasi
        $calonDitolakList = CalonMahasiswa::with(['prodi', 'dokumen'])
            ->where('tahun_akademik', 'LIKE', $tahunAktif . '/%')
            ->where('status', 'ditolak')
            ->select([
                'id', 'nim', 'nama', 'prodi_id',
                'alasan_penolakan', 'catatan',
                'verifikasi_by', 'verifikasi_at',
            ])
            ->latest('verifikasi_at')
            ->get();

        $totalDitolak = $calonDitolakList->count();

        return view('baa.calon.index', compact(
            'calons',
            'tahunAktif',
            'calonDitolakList',
            'totalDitolak'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    //  SHOW – detail
    // ─────────────────────────────────────────────────────────────────
    public function show($id)
    {
        $calon = CalonMahasiswa::with(['prodi', 'dokumen'])
            ->findOrFail($id);

        return view('baa.calon.show', compact('calon'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────────────────────────
    public function create()
    {
        $prodis     = Prodi::orderBy('nama')->get();
        $tahunAktif = $this->getTahunAwal(session('baa_tahun_aktif', now()->year));
        return view('baa.calon.create', compact('prodis', 'tahunAktif'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'               => 'required|string|max:20|unique:calon_mahasiswa,nim',
            'nama'              => 'required|string|max:255',
            'asal_sekolah'      => 'nullable|string|max:255',
            'prodi_id'          => 'required|exists:prodi,id',
            'tahun_akademik'    => 'required|string|max:20',
            'nilai_rapor_sem3'  => 'nullable|numeric|min:0|max:100',
            'nilai_rapor_sem4'  => 'nullable|numeric|min:0|max:100',
            'penghasilan_ortu'  => 'required|integer',
            'tagihan_listrik'   => 'required|integer',
            'tanggungan_anak'   => 'required|integer|min:0',
            'kondisi_ortu'      => 'required|string',
        ]);

        $validated['semester'] = 1;
        $validated['status']   = 'draft';
        $validated['input_by'] = Auth::id();

        CalonMahasiswa::create($validated);

        return redirect()->route('baa.calon.index')
            ->with('success', 'Data calon berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['draft', 'ditolak'])) {
            return redirect()->route('baa.calon.index')
                ->with('error', 'Data dengan status ' . $calon->status . ' tidak dapat diedit.');
        }

        $prodis = Prodi::orderBy('nama')->get();
        return view('baa.calon.edit', compact('calon', 'prodis'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['draft', 'ditolak'])) {
            return redirect()->route('baa.calon.index')
                ->with('error', 'Data tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nim'               => 'required|string|max:20|unique:calon_mahasiswa,nim,' . $calon->id,
            'nama'              => 'required|string|max:255',
            'asal_sekolah'      => 'nullable|string|max:255',
            'prodi_id'          => 'required|exists:prodi,id',
            'tahun_akademik'    => 'required|string|max:20',
            'nilai_rapor_sem3'  => 'nullable|numeric|min:0|max:100',
            'nilai_rapor_sem4'  => 'nullable|numeric|min:0|max:100',
            'penghasilan_ortu'  => 'required|integer',
            'tagihan_listrik'   => 'required|integer',
            'tanggungan_anak'   => 'required|integer|min:0',
            'kondisi_ortu'      => 'required|string',
        ]);

        // Jika sebelumnya ditolak → reset ke draft & hapus alasan penolakan
        if ($calon->status === 'ditolak') {
            $validated['status']           = 'draft';
            $validated['alasan_penolakan'] = null;
            $validated['catatan']          = null;
            $validated['verifikasi_by']    = null;
            $validated['verifikasi_at']    = null;
        }

        $calon->update($validated);

        return redirect()->route('baa.calon.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────────
    //  KIRIM KE BAPSI
    // ─────────────────────────────────────────────────────────────────
    public function kirimKeBapsi($id)
    {
        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['draft', 'ditolak'])) {
            return back()->with('error',
                'Hanya data berstatus Draft atau Ditolak yang bisa dikirim ke BAPSI.'
            );
        }

        $calon->update([
            'status'           => 'menunggu_verifikasi',
            'alasan_penolakan' => null,
            'verifikasi_by'    => null,
            'verifikasi_at'    => null,
        ]);

        return redirect()->route('baa.calon.index')
            ->with('success', "Data {$calon->nama} berhasil dikirim ke BAPSI.");
    }

    // ─────────────────────────────────────────────────────────────────
    //  DESTROY
    // ─────────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $calon = CalonMahasiswa::findOrFail($id);

        if (!in_array($calon->status, ['draft', 'ditolak'])) {
            return back()->with('error', 'Data tidak dapat dihapus.');
        }

        $calon->delete();

        return redirect()->route('baa.calon.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────
    //  IMPORT EXCEL (halaman)
    // ─────────────────────────────────────────────────────────────────
    public function import()
    {
        $prodis     = Prodi::orderBy('nama')->get();
        $tahunAktif = $this->getTahunAwal(session('baa_tahun_aktif', now()->year));
        return view('baa.calon.import', compact('prodis', 'tahunAktif'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  KIRIM SEMUA KE BAPSI – bulk action
    // ─────────────────────────────────────────────────────────────────
    public function kirimSemuaKeBapsi(Request $request)
    {
        $tahunAktif = $this->getTahunAwal(session('baa_tahun_aktif', now()->year));

        DB::beginTransaction();
        try {
            $jumlah = CalonMahasiswa::where('tahun_akademik', 'LIKE', $tahunAktif . '/%')
                ->whereIn('status', ['draft', 'ditolak'])
                ->update([
                    'status'           => 'menunggu_verifikasi',
                    'alasan_penolakan' => null,
                    'verifikasi_by'    => null,
                    'verifikasi_at'    => null,
                ]);

            DB::commit();

            if ($jumlah === 0) {
                return redirect()->route('baa.calon.index')
                    ->with('error', 'Tidak ada data yang perlu dikirim. Semua data sudah dikirim ke BAPSI.');
            }

            return redirect()->route('baa.calon.index')
                ->with('success', "{$jumlah} data calon berhasil dikirim ke BAPSI sekaligus.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('baa.calon.index')
                ->with('error', 'Gagal mengirim data: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  STORE IMPORT
    // ─────────────────────────────────────────────────────────────────
    public function storeImport(Request $request)
    {
        $request->validate([
            'data'   => 'required|array|min:1',
            'data.*' => 'array',
        ]);

        $tahunAktif = session('baa_tahun_aktif', now()->year);
        $berhasil   = 0;
        $gagal      = 0;

        foreach ($request->data as $row) {
            try {
                CalonMahasiswa::updateOrCreate(
                    ['nim' => $row['nim']],
                    [
                        'nama'             => $row['nama']             ?? null,
                        'asal_sekolah'     => $row['asal_sekolah']     ?? null,
                        'nilai_rapor_sem3' => $row['nilai_rapor_sem3'] ?? null,
                        'nilai_rapor_sem4' => $row['nilai_rapor_sem4'] ?? null,
                        'prodi_id'         => $row['prodi_id']         ?? null,
                        'semester'         => 1,
                        'tahun_akademik'   => $row['tahun_akademik']   ?? $tahunAktif,
                        'penghasilan_ortu' => $row['penghasilan_ortu'] ?? null,
                        'tagihan_listrik'  => $row['tagihan_listrik']  ?? null,
                        'tanggungan_anak'  => $row['tanggungan_anak']  ?? null,
                        'kondisi_ortu'     => $row['kondisi_ortu']     ?? null,
                        'status'           => 'draft',
                        'input_by'         => Auth::id(),
                    ]
                );
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => "Import selesai: {$berhasil} berhasil, {$gagal} gagal.",
            'berhasil' => $berhasil,
            'gagal'    => $gagal,
        ]);
    }
}