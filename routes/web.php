<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BAA\DashboardController as BAADashboard;
use App\Http\Controllers\BAA\CalonMahasiswaController;
use App\Http\Controllers\BAA\DokumenController;
use App\Http\Controllers\BAA\ImportCalonController;
use App\Http\Controllers\BAPSI\DashboardController as BAPSIDashboard;
use App\Http\Controllers\BAPSI\VerifikasiController;
use App\Http\Controllers\BAPSI\KriteriaController;
use App\Http\Controllers\BAPSI\KuotaController;
use App\Http\Controllers\BAPSI\MooraController;
use App\Http\Controllers\LKM\DashboardController as LKMDashboard;
use App\Http\Controllers\LKM\RankingController;
use App\Http\Controllers\LKM\KeputusanController;

/*
|--------------------------------------------------------------------------
| Web Routes - SPK Beasiswa Cerdas Humanis
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return redirect()->route('login');
});

// ====== AUTH ROUTES ======
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ====== BAA ROUTES ======
Route::middleware(['auth', 'role:baa'])
    ->prefix('baa')
    ->name('baa.')
    ->group(function () {

    Route::get('/dashboard', [BAADashboard::class, 'index'])->name('dashboard');
    
    // ✅ Import Calon — HARUS di atas resource calon agar tidak bentrok dengan {calon}
    Route::get('calon/import',          [ImportCalonController::class, 'index'])   ->name('calon.import.index');
    Route::post('calon/import',         [ImportCalonController::class, 'store'])   ->name('calon.import.store');
    Route::get('calon/import/template', [ImportCalonController::class, 'template'])->name('calon.import.template');

    // Calon Mahasiswa (resource setelah import)
    Route::resource('calon', CalonMahasiswaController::class);
    Route::post('calon/{id}/kirim', [CalonMahasiswaController::class, 'kirimKeBapsi'])
        ->name('calon.kirim');

    // Dokumen
    Route::resource('dokumen', DokumenController::class)->only(['index', 'store', 'destroy']);
    Route::post('dokumen/upload/{calon}', [DokumenController::class, 'upload'])
        ->name('dokumen.upload');

    // Tambah Prodi baru (quick add)
    Route::post('prodi/quick-store', [\App\Http\Controllers\BAA\ProdiController::class, 'quickStore'])
        ->name('prodi.quick-store');

    Route::post('calon/kirim-semua', [CalonMahasiswaController::class, 'kirimSemuaKeBapsi'])
    ->name('calon.kirim.semua');
});

// ====== BAPSI ROUTES ======
Route::middleware(['auth', 'role:bapsi'])
    ->prefix('bapsi')
    ->name('bapsi.')
    ->group(function () {

    Route::get('/dashboard', [BAPSIDashboard::class, 'index'])->name('dashboard');

    // Verifikasi Dokumen
    Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('verifikasi/{id}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('verifikasi/{id}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
    Route::post('verifikasi/{id}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');

    // Master Kriteria & Sub Kriteria
    //Route::resource('kriteria', KriteriaController::class); baru digantiii
    Route::resource('kriteria', KriteriaController::class)->parameters(['kriteria' => 'kriteria']);

    // Sub Kriteria — route eksplisit agar nama route konsisten
    Route::post('sub-kriteria/{kriteria}/store', [\App\Http\Controllers\BAPSI\SubKriteriaController::class, 'store'])
        ->name('sub-kriteria.store');
    Route::put('sub-kriteria/{subKriteria}', [\App\Http\Controllers\BAPSI\SubKriteriaController::class, 'update'])
        ->name('sub-kriteria.update');
    Route::delete('sub-kriteria/{subKriteria}', [\App\Http\Controllers\BAPSI\SubKriteriaController::class, 'destroy'])
        ->name('sub-kriteria.destroy');

    // Kuota Per Prodi
    Route::get('kuota', [KuotaController::class, 'index'])->name('kuota.index');
    Route::post('kuota/update', [KuotaController::class, 'update'])->name('kuota.update');

    // MOORA
    Route::get('moora', [MooraController::class, 'index'])->name('moora.index');
    Route::post('moora/hitung', [MooraController::class, 'hitung'])->name('moora.hitung');
    Route::get('moora/detail/{id}', [MooraController::class, 'detail'])->name('moora.detail');
    Route::get('moora/export', [MooraController::class, 'export'])->name('moora.export');
    Route::post('moora/kirim-lkm', [MooraController::class, 'kirimKeLKM'])->name('moora.kirim');

    Route::post('verifikasi/approve-all', [VerifikasiController::class, 'approveAll'])
    ->name('verifikasi.approve.all');
});

// ====== LKM ROUTES ======
Route::middleware(['auth', 'role:lkm'])
    ->prefix('lkm')
    ->name('lkm.')
    ->group(function () {

    Route::get('/dashboard', [LKMDashboard::class, 'index'])->name('dashboard');

    // Ranking
    Route::get('ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::get('ranking/prodi/{prodi}', [RankingController::class, 'byProdi'])->name('ranking.prodi');

    // Keputusan Penerima Beasiswa
    Route::get('keputusan', [KeputusanController::class, 'index'])->name('keputusan.index');
    Route::post('keputusan/tetapkan', [KeputusanController::class, 'tetapkan'])->name('keputusan.tetapkan');
    Route::post('keputusan/finalize', [KeputusanController::class, 'finalize'])->name('keputusan.finalize');
    Route::get('keputusan/export', [KeputusanController::class, 'export'])->name('keputusan.export');
});
// --- web.php (tambahkan di dalam group bapsi) ---
Route::delete('/bapsi/moora/reset', [App\Http\Controllers\BAPSI\MooraController::class, 'reset'])
    ->name('bapsi.moora.reset');