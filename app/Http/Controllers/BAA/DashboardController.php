<?php

namespace App\Http\Controllers\BAA;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

            $stats = [
            'draft'               => CalonMahasiswa::where('status', 'draft')->count(),
            'menunggu_verifikasi' => CalonMahasiswa::where('status', 'menunggu_verifikasi')->count(),
            'lulus_verifikasi'    => CalonMahasiswa::whereIn('status', ['lulus_verifikasi', 'ditetapkan'])->count(),
            'ditolak'             => CalonMahasiswa::where('status', 'ditolak')->count(),
            'total'               => CalonMahasiswa::count(),
        ];

        $calons = CalonMahasiswa::with('prodi')
            ->orderByRaw("FIELD(status,'menunggu_verifikasi','diproses','lulus_verifikasi','ditolak','draft')")
            ->orderBy('created_at', 'desc')
            ->get();

        $ditolakList = CalonMahasiswa::with('prodi')
            ->where('status', 'ditolak')
            ->whereNotNull('alasan_penolakan')
            ->orderBy('verifikasi_at', 'desc')
            ->get();

        return view('baa.dashboard', compact('stats', 'calons', 'ditolakList'));
    }
}