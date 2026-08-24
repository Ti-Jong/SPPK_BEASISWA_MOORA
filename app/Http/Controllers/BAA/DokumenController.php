<?php

namespace App\Http\Controllers\BAA;

use App\Http\Controllers\Controller;
use App\Models\CalonMahasiswa;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $calons = CalonMahasiswa::with(['dokumen' => function ($q) {
                $q->orderBy('jenis');
            }, 'prodi'])
            ->where('input_by', auth()->id())
            ->latest()
            ->get();

        return view('baa.dokumen.index', compact('calons'));
    }

    public function upload(Request $request, CalonMahasiswa $calon)
    {
        $request->validate([
            'jenis' => 'required|in:surat_rekomendasi,rapor_semester_3,rapor_semester_4,piagam,kartu_keluarga,foto_rumah,sktm,tagihan_listrik,surat_penghasilan',
            'file'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Hapus dokumen lama jenis yang sama
        $lama = Dokumen::where('calon_id', $calon->id)
            ->where('jenis', $request->jenis)
            ->first();

        if ($lama) {
            Storage::disk('public')->delete($lama->file_path);
            // Hapus juga dari public/storage
            $publicPath = public_path('storage/' . $lama->file_path);
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
            $lama->delete();
        }

        // Simpan file baru ke storage
        $file = $request->file('file');
        $path = $file->store('dokumen/' . $calon->id, 'public');

        // ✅ Copy file ke public/storage agar bisa diakses langsung
        $sourcePath = storage_path('app/public/' . $path);
        $destPath   = public_path('storage/' . $path);

        // Buat folder jika belum ada
        $destDir = dirname($destPath);
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Copy file
        copy($sourcePath, $destPath);

        Dokumen::create([
            'calon_id'  => $calon->id,
            'jenis'     => $request->jenis,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status'    => 'pending',
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $calon = CalonMahasiswa::findOrFail($dokumen->calon_id);
        if ($calon->input_by !== auth()->id()) {
            abort(403, 'Tidak diizinkan.');
        }

        // Hapus dari storage
        Storage::disk('public')->delete($dokumen->file_path);

        // Hapus juga dari public/storage
        $publicPath = public_path('storage/' . $dokumen->file_path);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}