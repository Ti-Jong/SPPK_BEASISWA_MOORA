<?php

// app/Http/Controllers/BAA/ImportCalonController.php

namespace App\Http\Controllers\BAA;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportCalonController extends Controller
{
    const KOLOM_WAJIB = [
        'nim', 'nama', 'asal_sekolah',
        'nilai_rapor_sem3', 'nilai_rapor_sem4',
        'prodi_kode', 'semester', 'tahun_akademik',
        'penghasilan_ortu', 'tagihan_listrik',
        'tanggungan_anak', 'kondisi_ortu',
    ];

    public function index()
    {
        $prodis     = Prodi::where('is_active', true)->orderBy('kode')->get(['kode', 'nama', 'id']);
        $totalProdi = $prodis->count();
        return view('baa.calon.import', compact('totalProdi', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes'    => 'Format file harus CSV atau Excel (.xlsx/.xls).',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $file            = $request->file('file');
        $ext             = strtolower($file->getClientOriginalExtension());
        $tahunDefault    = $request->input('tahun_akademik_default', date('Y') . '/' . (date('Y') + 1));
        $semesterDefault = (int) $request->input('semester_default', 1);

        try {
            // ── Baca file ──
            if (in_array($ext, ['xlsx', 'xls'])) {
                $rows = $this->readExcel($file->getRealPath());
            } else {
                $rows = $this->readCsv($file->getRealPath());
            }

            if (empty($rows)) {
                return back()->with('error', 'File kosong atau tidak dapat dibaca.');
            }

            // ── Deteksi format ──
            $header = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0]);

            if ($this->isFormatBCHSimplified($header)) {
                // Format BARU: calon sudah punya prodi tetap (kolom "Diterima"),
                // tanpa Pilihan 1/2/3, tanpa Nilai Rapor.
                $result = $this->prosesFormatBCHSimplified($rows, $tahunDefault, $semesterDefault);
                $format = 'BCH Simplified (Prodi Tetap)';
            } elseif ($this->isFormatBCH($header)) {
                // Format LAMA: masih ada Pilihan 1/2/3 (data historis sebelum refactor).
                $result = $this->prosesFormatBCH($rows, $tahunDefault, $semesterDefault);
                $format = 'BCH (Legacy — Pilihan 1/2/3)';
            } else {
                $missing = array_diff(self::KOLOM_WAJIB, $header);
                if (!empty($missing)) {
                    $headerInfo = implode(', ', array_slice($header, 0, 6));
                    return back()->with('error',
                        'Format file tidak dikenali.<br>'
                        . 'Header terbaca: <code>' . htmlspecialchars($headerInfo) . '...</code><br>'
                        . 'Gunakan file BCH asli atau template CSV yang disediakan.'
                    );
                }
                $result = $this->prosesFormatSistem($rows, $tahunDefault, $semesterDefault);
                $format = 'Template Sistem';
            }

            return redirect()->route('baa.calon.import.index')
                ->with('import_result', $result)
                ->with('import_format', $format);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import calon error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_calon_beasiswa.csv"',
        ];
        $rows = [
            self::KOLOM_WAJIB,
            ['237110001', 'BUDI SANTOSO', 'SMA N 1 SEMARANG', '88.5', '87.0', 'TI', '3', '2024/2025', '1500000', '75000', '2', 'ada_ayah_ibu_sehat'],
            ['237110002', 'SITI RAHAYU',  'SMA N 2 SEMARANG', '90.0', '91.5', 'AK', '3', '2024/2025', '800000',  '50000', '3', 'hanya_ibu_sehat'],
            ['237110003', 'AHMAD FAUZI',  'SMK N 1 SEMARANG', '82.3', '83.1', 'TE', '5', '2024/2025', '1200000', '100000','2', 'tidak_punya_ayah_ibu'],
        ];
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            foreach ($rows as $row) fputcsv($handle, $row);
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ═══════════════════════════════════════════════════════════════
    // DETEKSI FORMAT
    // ═══════════════════════════════════════════════════════════════

    /**
     * Format BARU (setelah refactor): calon sudah punya prodi TETAP
     * (kolom "Diterima" atau "Prodi Diterima"), TIDAK punya "pilihan 1".
     * Nilai Rapor boleh ADA atau TIDAK ADA — bukan penentu format lagi,
     * karena beberapa varian file BCH menyertakannya sebagai info
     * tambahan meski prodi sudah tetap.
     */
    private function isFormatBCHSimplified(array $header): bool
    {
        $punyaProdiTetap = in_array('diterima', $header) || in_array('prodi diterima', $header);
        $punyaKondisiOrtu = in_array('kondisi orang tua', $header) || in_array('penghasilan ayah', $header);
        $tidakPunyaPilihan = !in_array('pilihan 1', $header);

        return ($punyaProdiTetap || $punyaKondisiOrtu) && $tidakPunyaPilihan;
    }

    /**
     * Format LAMA (legacy): masih ada Pilihan 1/2/3 — dipertahankan untuk
     * kompatibilitas import ulang data historis sebelum refactor.
     */
    private function isFormatBCH(array $header): bool
    {
        return in_array('no pendaftaran', $header)
            || in_array('pilihan 1', $header)
            || in_array('kondisi orang tua', $header)
            || in_array('penghasilan ayah', $header);
    }

    // ═══════════════════════════════════════════════════════════════
    // HELPER: LOOKUP PRODI (dipakai bersama oleh format lama & baru)
    // 3 level pencocokan: exact -> partial (contains) -> word overlap score
    // ═══════════════════════════════════════════════════════════════

    private function cariProdiId(string $namaProdi, array $prodiMap, array &$tidakDitemukan = []): ?int
    {
        $key = strtolower(trim($namaProdi));
        if ($key === '') return null;

        // Level 1: exact match
        if (isset($prodiMap[$key])) {
            return $prodiMap[$key];
        }

        // Level 2: partial match (contains)
        foreach ($prodiMap as $pNama => $pid) {
            if (str_contains($key, $pNama) || str_contains($pNama, $key)) {
                return $pid;
            }
        }

        // Level 3: match per kata
        $bestScore = 0;
        $bestId    = null;
        $keyWords  = array_filter(explode(' ', $key), fn($w) => strlen($w) > 3);

        foreach ($prodiMap as $pNama => $pid) {
            $score = 0;
            foreach ($keyWords as $word) {
                if (str_contains($pNama, $word)) $score++;
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestId    = $pid;
            }
        }

        if ($bestScore > 0) {
            return $bestId;
        }

        $tidakDitemukan[$namaProdi] = true;
        return null;
    }

    // ═══════════════════════════════════════════════════════════════
    // PROSES FORMAT BARU — BCH Simplified (prodi tetap, tanpa pilihan)
    // ═══════════════════════════════════════════════════════════════

    private function prosesFormatBCHSimplified(array $rows, string $tahunDefault, int $semesterDefault): array
    {
        $header   = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0]);
        $colIndex = array_flip($header);

        $prodis   = Prodi::all(['id', 'nama']);
        $prodiMap = [];
        foreach ($prodis as $p) {
            $prodiMap[strtolower(trim($p->nama))] = $p->id;
        }

        $berhasil            = 0;
        $diperbarui          = 0;
        $gagal               = [];
        $duplikat            = [];
        $prodiTidakDitemukan = [];

        DB::beginTransaction();

        foreach (array_slice($rows, 1) as $rowNum => $row) {
            $lineNum = $rowNum + 2;
            if (empty(array_filter(array_map('strval', $row)))) continue;

            $get = function (string $col) use ($row, $colIndex) {
                $idx = $colIndex[$col] ?? null;
                return $idx !== null ? trim((string)($row[$idx] ?? '')) : '';
            };

            // Getter multi-kandidat: coba beberapa kemungkinan nama header,
            // pakai yang pertama ditemukan. Perlu karena beberapa varian
            // file BCH menamai kolom prodi berbeda ("Diterima" vs "Prodi Diterima").
            $getMulti = function (array $candidates) use ($colIndex, $row) {
                foreach ($candidates as $col) {
                    if (isset($colIndex[$col])) {
                        return trim((string)($row[$colIndex[$col]] ?? ''));
                    }
                }
                return '';
            };

            $nim           = $get('no pendaftaran');
            $nama          = $get('nama');
            $asalSekolah   = $get('asal sekolah');
            $kondisiRaw    = $get('kondisi orang tua');
            $pengAyahRaw   = $get('penghasilan ayah');
            $pengIbuRaw    = $get('penghasilan ibu');
            $listrikRaw    = $get('tagihan listrik');
            $tanggunganRaw = $get('jumlah tanggungan anak');
            $diterimaRaw   = $getMulti(['diterima', 'prodi diterima']);
            $nilaiS3Raw    = $getMulti(['nilai rapor sem.3']);
            $nilaiS4Raw    = $getMulti(['nilai rapor sem.4']);

            $errors = [];
            if (empty($nim))  $errors[] = 'No Pendaftaran kosong';
            if (empty($nama)) $errors[] = 'Nama kosong';
            if (empty($diterimaRaw)) $errors[] = 'Kolom Diterima (prodi) kosong';

            if (!empty($errors)) {
                $gagal[] = ['baris' => $lineNum, 'nim' => $nim ?: '-', 'errors' => $errors];
                continue;
            }

            // Prodi langsung dari kolom "Diterima" — tidak ada lagi Pilihan 1/2/3
            $prodiId = $this->cariProdiId($diterimaRaw, $prodiMap, $prodiTidakDitemukan);

            if (!$prodiId) {
                $gagal[] = [
                    'baris'  => $lineNum,
                    'nim'    => $nim,
                    'errors' => ["Prodi '{$diterimaRaw}' tidak ditemukan/tidak cocok di sistem"],
                ];
                continue;
            }

            $dataBCH = [
                'nama'             => strtoupper($nama),
                'asal_sekolah'     => $asalSekolah ?: null,
                'nilai_rapor_sem3' => is_numeric($nilaiS3Raw) ? (float)$nilaiS3Raw : null,
                'nilai_rapor_sem4' => is_numeric($nilaiS4Raw) ? (float)$nilaiS4Raw : null,
                'prodi_id'         => $prodiId,
                // pilihan_1_id/2_id/3_id sengaja TIDAK diisi — arsitektur baru
                // tidak lagi bergantung pada mekanisme Pilihan 1/2/3.
                'semester'         => $semesterDefault,
                'tahun_akademik'   => $tahunDefault,
                'penghasilan_ortu' => $this->konversiPenghasilan($pengAyahRaw, $pengIbuRaw),
                'penghasilan_ayah' => $this->parseAngkaOrRange($pengAyahRaw),
                'penghasilan_ibu'  => $this->parseAngkaOrRange($pengIbuRaw),
                'tagihan_listrik'  => $this->konversiListrik($listrikRaw),
                'tanggungan_anak'  => $this->konversiTanggungan($tanggunganRaw),
                'kondisi_ortu'     => $this->konversiKondisi($kondisiRaw),
            ];

            $existing = CalonMahasiswa::where('nim', (string)$nim)->first();

            if ($existing) {
                if (in_array($existing->status, ['draft', 'ditolak', 'lulus_verifikasi'])) {
                    $existing->update($dataBCH);
                    $diperbarui++;
                    $duplikat[] = [
                        'baris' => $lineNum,
                        'nim'   => $nim,
                        'nama'  => $nama,
                        'info'  => 'diperbarui',
                    ];
                } else {
                    $duplikat[] = [
                        'baris' => $lineNum,
                        'nim'   => $nim,
                        'nama'  => $nama,
                        'info'  => 'dilewati (status: ' . $existing->status . ')',
                    ];
                }
                continue;
            }

            CalonMahasiswa::create(array_merge($dataBCH, [
                'nim'      => (string) $nim,
                'status'   => 'draft',
                'input_by' => Auth::id(),
            ]));

            $berhasil++;
        }

        DB::commit();

        $result = [
            'berhasil'   => $berhasil,
            'diperbarui' => $diperbarui,
            'gagal'      => $gagal,
            'duplikat'   => $duplikat,
        ];
        if (!empty($prodiTidakDitemukan)) {
            $result['prodi_tidak_ditemukan'] = array_keys($prodiTidakDitemukan);
        }
        return $result;
    }

    // ═══════════════════════════════════════════════════════════════
    // PROSES FORMAT LAMA — BCH Legacy (Pilihan 1/2/3), dipertahankan
    // untuk kompatibilitas import ulang data historis
    // ═══════════════════════════════════════════════════════════════

    private function prosesFormatBCH(array $rows, string $tahunDefault, int $semesterDefault): array
    {
        $header   = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0]);
        $colIndex = array_flip($header);

        $prodis   = Prodi::all(['id', 'nama']);
        $prodiMap = [];
        foreach ($prodis as $p) {
            $prodiMap[strtolower(trim($p->nama))] = $p->id;
        }

        $berhasil            = 0;
        $diperbarui          = 0;
        $gagal               = [];
        $duplikat            = [];
        $prodiTidakDitemukan = [];

        DB::beginTransaction();

        foreach (array_slice($rows, 1) as $rowNum => $row) {
            $lineNum = $rowNum + 2;
            if (empty(array_filter(array_map('strval', $row)))) continue;

            $get = function (string $col) use ($row, $colIndex) {
                $idx = $colIndex[$col] ?? null;
                return $idx !== null ? trim((string)($row[$idx] ?? '')) : '';
            };

            $nim           = $get('no pendaftaran');
            $nama          = $get('nama');
            $asalSekolah   = $get('asal sekolah');
            $nilaiS3       = $get('nilai rapor sem.3');
            $nilaiS4       = $get('nilai rapor sem.4');
            $pilihan1      = $get('pilihan 1');
            $pilihan2      = $get('pilihan 2');
            $pilihan3      = $get('pilihan 3');
            $kondisiRaw    = $get('kondisi orang tua');
            $pengAyahRaw   = $get('penghasilan ayah');
            $pengIbuRaw    = $get('penghasilan ibu');
            $listrikRaw    = $get('tagihan listrik');
            $tanggunganRaw = $get('jumlah tanggungan anak');

            $errors = [];
            if (empty($nim))  $errors[] = 'No Pendaftaran kosong';
            if (empty($nama)) $errors[] = 'Nama kosong';

            if (!empty($errors)) {
                $gagal[] = ['baris' => $lineNum, 'nim' => $nim ?: '-', 'errors' => $errors];
                continue;
            }

            $prodiId  = !empty($pilihan1) ? $this->cariProdiId($pilihan1, $prodiMap, $prodiTidakDitemukan) : null;
            $prodiId2 = !empty($pilihan2) ? $this->cariProdiId($pilihan2, $prodiMap) : null;
            $prodiId3 = !empty($pilihan3) ? $this->cariProdiId($pilihan3, $prodiMap) : null;

            $dataBCH = [
                'nama'             => strtoupper($nama),
                'asal_sekolah'     => $asalSekolah ?: null,
                'nilai_rapor_sem3' => is_numeric($nilaiS3) ? (float)$nilaiS3 : null,
                'nilai_rapor_sem4' => is_numeric($nilaiS4) ? (float)$nilaiS4 : null,
                'pilihan_1_id'     => $prodiId,
                'pilihan_2_id'     => $prodiId2,
                'pilihan_3_id'     => $prodiId3,
                'prodi_id'         => $prodiId,
                'semester'         => $semesterDefault,
                'tahun_akademik'   => $tahunDefault,
                'penghasilan_ortu' => $this->konversiPenghasilan($pengAyahRaw, $pengIbuRaw),
                'penghasilan_ayah' => $this->parseAngkaOrRange($pengAyahRaw),
                'penghasilan_ibu'  => $this->parseAngkaOrRange($pengIbuRaw),
                'tagihan_listrik'  => $this->konversiListrik($listrikRaw),
                'tanggungan_anak'  => $this->konversiTanggungan($tanggunganRaw),
                'kondisi_ortu'     => $this->konversiKondisi($kondisiRaw),
            ];

            $existing = CalonMahasiswa::where('nim', (string)$nim)->first();

            if ($existing) {
                if (in_array($existing->status, ['draft', 'ditolak', 'lulus_verifikasi'])) {
                    $existing->update($dataBCH);
                    $diperbarui++;
                    $duplikat[] = [
                        'baris' => $lineNum,
                        'nim'   => $nim,
                        'nama'  => $nama,
                        'info'  => 'diperbarui',
                    ];
                } else {
                    $duplikat[] = [
                        'baris' => $lineNum,
                        'nim'   => $nim,
                        'nama'  => $nama,
                        'info'  => 'dilewati (status: ' . $existing->status . ')',
                    ];
                }
                continue;
            }

            CalonMahasiswa::create(array_merge($dataBCH, [
                'nim'      => (string) $nim,
                'status'   => 'draft',
                'input_by' => Auth::id(),
            ]));

            $berhasil++;
        }

        DB::commit();

        $result = [
            'berhasil'   => $berhasil,
            'diperbarui' => $diperbarui,
            'gagal'      => $gagal,
            'duplikat'   => $duplikat,
        ];
        if (!empty($prodiTidakDitemukan)) {
            $result['prodi_tidak_ditemukan'] = array_keys($prodiTidakDitemukan);
        }
        return $result;
    }

    // ═══════════════════════════════════════════════════════════════
    // PROSES FORMAT TEMPLATE SISTEM
    // ═══════════════════════════════════════════════════════════════

    private function prosesFormatSistem(array $rows, string $tahunDefault, int $semesterDefault): array
    {
        $header   = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0]);
        $colIndex = array_flip($header);
        $prodiMap = Prodi::pluck('id', 'kode')->toArray();

        $berhasil = 0; $gagal = []; $duplikat = [];
        DB::beginTransaction();

        foreach (array_slice($rows, 1) as $rowNum => $row) {
            $lineNum = $rowNum + 2;
            if (empty(array_filter(array_map('strval', $row)))) continue;

            $get = function (string $col) use ($row, $colIndex) {
                $idx = $colIndex[$col] ?? null;
                return $idx !== null ? trim((string)($row[$idx] ?? '')) : '';
            };

            $nim       = $get('nim');
            $nama      = $get('nama');
            $prodiKode = strtoupper($get('prodi_kode'));
            $errors    = [];

            if (empty($nim))  $errors[] = 'NIM kosong';
            if (empty($nama)) $errors[] = 'Nama kosong';
            if (!isset($prodiMap[$prodiKode])) $errors[] = "Kode prodi '{$prodiKode}' tidak ditemukan";

            if (!empty($errors)) {
                $gagal[] = ['baris' => $lineNum, 'nim' => $nim ?: '-', 'errors' => $errors];
                continue;
            }

            if (CalonMahasiswa::where('nim', $nim)->exists()) {
                $duplikat[] = ['baris' => $lineNum, 'nim' => $nim, 'nama' => $nama];
                continue;
            }

            CalonMahasiswa::create([
                'nim'              => $nim,
                'nama'             => $nama,
                'asal_sekolah'     => $get('asal_sekolah'),
                'nilai_rapor_sem3' => (float) $get('nilai_rapor_sem3'),
                'nilai_rapor_sem4' => (float) $get('nilai_rapor_sem4'),
                'prodi_id'         => $prodiMap[$prodiKode],
                'pilihan_1_id'     => $prodiMap[$prodiKode],
                'pilihan_2_id'     => null,
                'pilihan_3_id'     => null,
                'semester'         => (int) ($get('semester') ?: $semesterDefault),
                'tahun_akademik'   => $get('tahun_akademik') ?: $tahunDefault,
                'penghasilan_ortu' => (int) $get('penghasilan_ortu'),
                'tagihan_listrik'  => (int) $get('tagihan_listrik'),
                'tanggungan_anak'  => (int) $get('tanggungan_anak'),
                'kondisi_ortu'     => strtolower($get('kondisi_ortu')),
                'status'           => 'draft',
                'input_by'         => Auth::id(),
            ]);
            $berhasil++;
        }

        DB::commit();
        return ['berhasil' => $berhasil, 'gagal' => $gagal, 'duplikat' => $duplikat];
    }

    // ═══════════════════════════════════════════════════════════════
    // KONVERSI NILAI BCH
    // ═══════════════════════════════════════════════════════════════

    private function konversiKondisi(string $raw): string
    {
        $r = strtolower(trim($raw));
        if (str_contains($r, 'tidak mempunyai ayah dan ibu'))                               return 'tidak_punya_ayah_ibu';
        if (str_contains($r, 'hanya ada ayah') && str_contains($r, 'sakit'))                return 'hanya_ayah_sakit';
        if (str_contains($r, 'hanya ada ibu')  && str_contains($r, 'sakit'))                return 'hanya_ibu_sakit';
        if (str_contains($r, 'keduanya sakit') || (str_contains($r, 'ayah dan ibu') && str_contains($r, 'sakit') && str_contains($r, 'tidak bekerja'))) return 'ayah_ibu_sakit_tidak_bekerja';
        if (str_contains($r, 'hanya ada ayah') && str_contains($r, 'sehat'))                return 'hanya_ayah_sehat';
        if (str_contains($r, 'hanya ada ayah'))                                              return 'hanya_ayah_sehat';
        if (str_contains($r, 'hanya ada ibu')  && str_contains($r, 'sehat'))                return 'hanya_ibu_sehat';
        if (str_contains($r, 'hanya ada ibu'))                                               return 'hanya_ibu_sehat';
        if (str_contains($r, 'ayah sakit') && str_contains($r, 'tidak bekerja'))            return 'ada_ayah_sakit_tidak_bekerja';
        if (str_contains($r, 'ibu sakit')  && str_contains($r, 'tidak bekerja'))            return 'ada_ibu_sakit_tidak_bekerja';
        if (str_contains($r, 'ayah sakit') && str_contains($r, 'pensiun'))                  return 'ada_ayah_sakit_pensiun';
        if (str_contains($r, 'ibu sakit')  && str_contains($r, 'pensiun'))                  return 'ada_ibu_sakit_pensiun';
        return 'ada_ayah_ibu_sehat';
    }

    private function konversiPenghasilan(string $ayah, string $ibu): int
    {
        $nilaiAyah = $this->parseAngkaOrRange($ayah);
        $nilaiIbu  = $this->parseAngkaOrRange($ibu);

        if ($nilaiAyah === null && $nilaiIbu === null) return 0;
        if ($nilaiAyah === null) return $nilaiIbu;
        if ($nilaiIbu  === null) return $nilaiAyah;

        // Opsi 3 (rekomendasi hasil analisis): pakai MINIMUM, bukan dijumlah
        return min($nilaiAyah, $nilaiIbu);
    }

    private function konversiListrik(string $raw): int
    {
        return $this->parseAngkaOrRange($raw, 'listrik') ?? 0;
    }

    /**
     * Parser universal: mendeteksi apakah $raw berupa ANGKA MENTAH (data 2024/2025
     * dan seluruh data format baru) atau TEKS RANGE (format lama), lalu mengembalikan
     * nilai representatif.
     */
    private function parseAngkaOrRange(string $raw, string $tipe = 'penghasilan'): ?int
    {
        $trim = trim($raw);
        if ($trim === '') return null;

        $bersih = preg_replace('/[^\d]/', '', $trim);
        $rasioDigit = strlen($bersih) > 0 ? strlen($bersih) / max(strlen($trim), 1) : 0;

        if (is_numeric($trim) || $rasioDigit > 0.6) {
            return (int) $bersih;
        }

        $r = strtolower($trim);

        if ($tipe === 'listrik') {
            if (str_contains($r, 'lebih besar dari 300')) return 400000;
            if (str_contains($r, 'lebih besar') && str_contains($r, '100') && str_contains($r, '300')) return 200000;
            if (str_contains($r, 'lebih kecil') || str_contains($r, 'sama dengan 100')) return 75000;
            return null;
        }

        $map = [
            'lebih besar dari 3,5 juta'                 => 4000000,
            'lebih besar dari 3.5 juta'                 => 4000000,
            'lebih besar dari 2 juta atau sama dengan'  => 2750000,
            'lebih besar dari 2 juta'                   => 2750000,
            'lebih besar dari 1 juta ato sama dengan 2' => 1500000,
            'lebih besar dari 1 juta'                   => 1500000,
            'lebih kecil ato sama dengan 1.000.000'     => 750000,
            'lebih kecil'                                => 500000,
            'tidak bekerja'                              => 0,
            'tidak ada'                                   => 0,
        ];
        foreach ($map as $pattern => $nilai) {
            if (str_contains($r, $pattern)) return $nilai;
        }
        return null;
    }

    private function konversiTanggungan(string $raw): int
    {
        $r = strtolower($raw);
        if (str_contains($r, 'satu')  || $r === '1') return 1;
        if (str_contains($r, 'dua')   || $r === '2') return 2;
        if (str_contains($r, 'tiga')  || $r === '3') return 3;
        if (str_contains($r, 'empat') || $r === '4') return 4;
        if (str_contains($r, 'lima')  || $r === '5') return 5;
        if (is_numeric(trim($raw))) return (int)trim($raw);
        return 0;
    }

    // ═══════════════════════════════════════════════════════════════
    // BACA FILE — menggunakan PhpSpreadsheet
    // ═══════════════════════════════════════════════════════════════

    private function readExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = [];

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getFormattedValue();
            }

            while (!empty($rowData) && trim((string)end($rowData)) === '') {
                array_pop($rowData);
            }

            if (!empty(array_filter(array_map('strval', $rowData)))) {
                $rows[] = $rowData;
            }
        }

        return $rows;
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);
            $first = fgets($handle);
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") fread($handle, 3);
            $delimiter = substr_count($first, ';') > substr_count($first, ',') ? ';' : ',';
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) $rows[] = $row;
            fclose($handle);
        }
        return $rows;
    }
}