<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class KuotaController extends Controller
{
    public function index()
    {
        $prodis = Prodi::where('is_active', true)->orderBy('nama')->get();
        return view('bapsi.kuota.index', compact('prodis'));
    }

    public function update(Request $request)
    {
        $request->validate(['kuota' => 'required|array', 'kuota.*' => 'required|integer|min:0|max:100']);
        foreach ($request->kuota as $prodiId => $jumlah) {
            Prodi::where('id', $prodiId)->update(['kuota_beasiswa' => $jumlah]);
        }
        return back()->with('success', 'Kuota berhasil diperbarui.');
    }
}
