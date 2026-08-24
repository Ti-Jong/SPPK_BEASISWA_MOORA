<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\HasilMoora;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ FIX 1: Ambil tahun terbaru dari data yang ada di DB (format "2023/2024")
        $tahun = HasilMoora::orderByDesc('periode_tahun')->value('periode_tahun')
                 ?? CalonMahasiswa::whereIn('status', ['lulus_verifikasi', 'ditetapkan'])
                    ->orderByDesc('tahun_akademik')
                    ->value('tahun_akademik')
                 ?? date('Y') . '/' . (date('Y') + 1);

        $stats = [
            'total' => CalonMahasiswa::count(),

            'terkirim' => CalonMahasiswa::whereIn('status', [
                'menunggu_verifikasi',
                'diproses',
            ])->count(),

            'terverifikasi' => CalonMahasiswa::whereIn('status', [
                'lulus_verifikasi',
                'ditetapkan',
            ])->count(),

            // ✅ FIX 2: Hindari ->get()->filter() — query langsung lebih efisien
            // Kalau isDokumenLengkap() adalah method PHP (bukan query),
            // terpaksa pakai get()->filter(), tapi batasi scope-nya
            'dokumen_kurang' => CalonMahasiswa::whereIn('status', [
                'menunggu_verifikasi',
                'diproses',
            ])->get()->filter(fn($c) => !$c->isDokumenLengkap())->count(),
        ];

        // ✅ FIX 3: Pakai nama tabel yang benar via $table di model
        // (sudah difix di HasilMoora model dengan protected $table = 'hasil_moora')
        $hasilTerbaru = HasilMoora::with(['calon.prodi'])
            ->where('periode_tahun', $tahun)
            ->orderBy('ranking')
            ->take(5)
            ->get();

        // ✅ FIX 4: Tambah relasi prodi langsung dari calon (bukan dari hasil_moora)
        // karena hasil_moora tidak punya relasi prodi langsung
        $calonsMenunggu = CalonMahasiswa::with('prodi')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->latest()
            ->take(8)
            ->get();

        return view('bapsi.dashboard.index', compact(
            'stats',
            'hasilTerbaru',
            'calonsMenunggu',
            'tahun',           // ✅ FIX 5: Pass $tahun ke view untuk ditampilkan
        ));
    }
}