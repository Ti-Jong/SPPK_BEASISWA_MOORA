<?php

namespace App\Http\Controllers\BAPSI;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function store(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'label'        => 'required|string|max:100',
            'range_min'    => 'nullable|numeric',
            'range_max'    => 'nullable|numeric',
            'nilai_string' => 'nullable|string|max:50',
            'skor'         => 'required|integer|min:1|max:10',
        ]);
        $validated['kriteria_id'] = $kriteria->id;
        SubKriteria::create($validated);
        return back()->with('success', 'Sub kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, SubKriteria $subKriteria)
    {
        $validated = $request->validate([
            'label'        => 'required|string|max:100',
            'range_min'    => 'nullable|numeric',
            'range_max'    => 'nullable|numeric',
            'nilai_string' => 'nullable|string|max:50',
            'skor'         => 'required|integer|min:0|max:10',
        ]);
        $subKriteria->update($validated);
        return back()->with('success', 'Sub kriteria diperbarui.');
    }

    public function destroy(SubKriteria $subKriteria)
    {
        $subKriteria->delete();
        return back()->with('success', 'Sub kriteria dihapus.');
    }
}
