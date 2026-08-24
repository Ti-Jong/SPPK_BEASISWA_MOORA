<?php

namespace App\Http\Controllers\BAA;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Quick-add prodi baru dari modal di form input calon
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'kode'    => 'required|string|max:20|unique:prodi,kode',
            'nama'    => 'required|string|max:255',
            'jenjang' => 'required|in:D3,Sarjana,Sarjana Terapan',
        ], [
            'kode.unique' => 'Kode prodi sudah digunakan.',
        ]);

        $prodi = Prodi::create([
            'kode'            => strtoupper(trim($request->kode)),
            'nama'            => trim($request->nama),
            'jenjang'         => $request->jenjang,
            'kuota_beasiswa'  => (int) ($request->kuota_beasiswa ?? 1),
            'is_active'       => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'prodi'   => [
                    'id'     => $prodi->id,
                    'kode'   => $prodi->kode,
                    'nama'   => $prodi->nama,
                    'jenjang'=> $prodi->jenjang,
                    'label'  => $prodi->nama . ' (' . $prodi->jenjang . ')',
                ],
            ]);
        }

        return back()->with('success', "Prodi {$prodi->nama} berhasil ditambahkan.");
    }
}