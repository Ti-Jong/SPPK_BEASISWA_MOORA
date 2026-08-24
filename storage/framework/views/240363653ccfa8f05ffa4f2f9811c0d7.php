<?php $__env->startSection('title', 'Hitung MOORA'); ?>
<?php $__env->startSection('page-title', 'Hitung MOORA'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statistik       = $statistik       ?? null;
    $rankingPerProdi = $rankingPerProdi ?? [];
    $calonPerProdi   = $calonPerProdi   ?? collect();
    $stats           = $stats           ?? ['total_lulus' => 0];

    $totalLolosCount = \App\Models\CalonMahasiswa::where('tahun_akademik', $tahun)
        ->where('status_seleksi', 'lolos')->count();
    $totalDitolakCount = \App\Models\CalonMahasiswa::where('tahun_akademik', $tahun)
        ->where('status_seleksi', 'ditolak')->count();
?>


<?php if(session('success')): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?></div>
<?php endif; ?>


<form method="GET" action="<?php echo e(route('bapsi.moora.index')); ?>"
      style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap">

    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Periode:</label>
    <select name="tahun"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:160px"
            onchange="this.form.submit()">
        <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($t); ?>" <?php echo e($t == $tahun ? 'selected' : ''); ?>><?php echo e($t); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <?php if($hasilAda && $prodis->isNotEmpty()): ?>
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Prodi:</label>
    <select name="prodi_id"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:200px"
            onchange="this.form.submit()">
        <option value="">— Semua Prodi —</option>
        <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($p->id); ?>" <?php echo e($p->id == $prodiFilter ? 'selected' : ''); ?>>
                <?php echo e($p->nama); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php endif; ?>

    <span style="font-size:0.78rem;color:var(--text-muted)"><?php echo e($calons->count()); ?> calon lulus verifikasi</span>
</form>


<?php if($hasilAda && $statistik): ?>
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val"><?php echo e($statistik['total_calon']); ?></div>
            <div class="stat-label">Total Calon Diranking</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <div class="stat-val"><?php echo e($statistik['total_prodi']); ?></div>
            <div class="stat-label">Program Studi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($statistik['skor_max'], 4)); ?></div>
            <div class="stat-label">Skor Tertinggi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($statistik['skor_avg'], 4)); ?></div>
            <div class="stat-label">Rata-rata Skor</div>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3><i class="fas fa-calculator" style="color:var(--primary-light)"></i> Perhitungan MOORA</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <form method="POST" action="<?php echo e(route('bapsi.moora.hitung')); ?>" style="display:inline"
                  onsubmit="return confirm('Hitung ulang MOORA untuk periode <?php echo e($tahun); ?>?\nData ranking lama akan ditimpa.')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                <button class="btn btn-primary" <?php echo e($calons->isEmpty() ? 'disabled' : ''); ?>>
                    <i class="fas fa-play"></i>
                    <?php echo e($hasilAda ? 'Hitung Ulang' : 'Hitung MOORA'); ?>

                </button>
            </form>

            <?php if($hasilAda): ?>
            <form method="POST" action="<?php echo e(route('bapsi.moora.kirim')); ?>" style="display:inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                <button class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kirim ke LKM
                </button>
            </form>

            <form method="POST" action="<?php echo e(route('bapsi.moora.reset')); ?>" style="display:inline"
                  onsubmit="return confirm('Reset semua data MOORA periode <?php echo e($tahun); ?>?\nTindakan ini tidak bisa dibatalkan.')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                <button class="btn btn-danger">
                    <i class="fas fa-trash"></i> Reset
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card-body">
        <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:12px;font-weight:600">
            <i class="fas fa-info-circle"></i> Kriteria & Bobot MOORA
        </p>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <?php $__currentLoopData = \App\Services\MooraService::kriteriaList(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $krit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="background:<?php echo e($krit['type'] === 'benefit' ? '#f0fdf4' : '#fff7ed'); ?>;
                        border:1px solid <?php echo e($krit['type'] === 'benefit' ? '#86efac' : '#fed7aa'); ?>;
                        border-radius:8px;padding:10px 14px;min-width:160px">
                <div style="font-size:0.7rem;font-weight:700;
                            color:<?php echo e($krit['type'] === 'benefit' ? '#15803d' : '#c2410c'); ?>;
                            text-transform:uppercase;letter-spacing:.05em">
                    <?php echo e(strtoupper($krit['type'])); ?>

                </div>
                <div style="font-size:0.85rem;font-weight:700;margin:3px 0"><?php echo e($krit['label']); ?></div>
                <div style="font-size:0.78rem;color:var(--text-muted)">
                    Bobot: <strong><?php echo e($krit['bobot']); ?></strong>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($calons->isEmpty()): ?>
        <div class="alert alert-warning" style="margin-top:14px">
            <i class="fas fa-exclamation-triangle"></i>
            Tidak ada calon yang lulus verifikasi untuk periode <strong><?php echo e($tahun); ?></strong>.
            Pastikan Admin BAA sudah menginput data dan Admin BAPSI sudah memverifikasi.
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-user-check"></i></div>
        <div>
            <div class="stat-val"><?php echo e($stats['total_lulus']); ?></div>
            <div class="stat-label">Total Lulus Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-val" style="color:#16a34a"><?php echo e($totalLolosCount); ?></div>
            <div class="stat-label">Lolos</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="stat-val" style="color:#dc2626"><?php echo e($totalDitolakCount); ?></div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>
</div>


<?php if($hasilAda): ?>
    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $kuota    = $prodi->kuota_beasiswa ?? 5;
        $dihitung = $prodi->sudah_dihitung ?? 0;
        $ada      = isset($rankingPerProdi[$prodi->id]) && $rankingPerProdi[$prodi->id]->isNotEmpty();
    ?>
    <div class="card" style="margin-bottom:18px">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
            <h3 style="display:flex;align-items:center;gap:8px">
                <i class="fas fa-building-columns" style="color:var(--primary-light)"></i>
                <?php echo e($prodi->nama); ?>

            </h3>
            <div style="display:flex;gap:8px">
                <span style="background:#eef0fb;color:#1d4ed8;font-size:0.72rem;font-weight:700;
                             padding:4px 10px;border-radius:6px">
                    Kuota: <?php echo e($kuota); ?>

                </span>
                <span style="background:var(--surface);color:var(--text-muted);font-size:0.72rem;font-weight:700;
                             padding:4px 10px;border-radius:6px;border:1px solid var(--border)">
                    Dihitung: <?php echo e($dihitung); ?>

                </span>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            <?php if($ada): ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Rank Prodi</th>
                            <th>Rank Global</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Skor MOORA</th>
                            <th>Status Seleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $rankingPerProdi[$prodi->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sel   = $h->calon->status_seleksi ?? '-';
                        $lolos = $sel === 'lolos';
                        $selStyle = match($sel) {
                            'lolos'   => ['bg' => '#dcfce7', 'fg' => '#16a34a'],
                            'ditolak' => ['bg' => '#fef2f2', 'fg' => '#dc2626'],
                            default   => ['bg' => '#f1f5f9', 'fg' => '#64748b'],
                        };
                    ?>
                    <tr style="<?php echo e($lolos ? 'background:#f0fdf4' : ''); ?>">
                        <td>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         width:28px;height:28px;border-radius:50%;font-weight:700;font-size:0.78rem;
                                         background:<?php echo e($lolos ? '#16a34a' : 'var(--surface)'); ?>;
                                         color:<?php echo e($lolos ? '#fff' : 'var(--text-muted)'); ?>">
                                <?php echo e($h->ranking_prodi); ?>

                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.82rem"><?php echo e($h->ranking); ?></td>
                        <td><code style="font-size:0.8rem"><?php echo e($h->calon->nim ?? '-'); ?></code></td>
                        <td style="font-weight:600"><?php echo e($h->calon->nama ?? '-'); ?></td>
                        <td>
                            <strong style="color:var(--primary-light);font-family:monospace;font-size:0.85rem">
                                <?php echo e(number_format($h->skor_moora, 6)); ?>

                            </strong>
                        </td>
                        <td>
                            <span style="font-size:0.7rem;font-weight:700;padding:3px 9px;border-radius:6px;
                                         background:<?php echo e($selStyle['bg']); ?>;color:<?php echo e($selStyle['fg']); ?>">
                                <?php echo e(strtoupper($sel)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:0.85rem">
                Belum dihitung
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php elseif($calons->isNotEmpty()): ?>
    
    <div class="card">
        <div class="card-header">
            <h3>Preview Calon Per Prodi (Belum Dihitung)</h3>
            <span class="badge b-warning">Belum Dihitung</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px">
                <?php $__empty_1 = true; $__currentLoopData = $calonPerProdi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="border:1px solid var(--border);border-radius:10px;padding:14px">
                    <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:4px">Program Studi</div>
                    <div style="font-weight:700;font-size:0.85rem;margin-bottom:6px">
                        <?php echo e($group['prodi']?->nama ?? 'Prodi Tidak Diketahui'); ?>

                    </div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--primary-light)">
                        <?php echo e($group['total']); ?>

                    </div>
                    <div style="font-size:0.72rem;color:var(--text-muted)">calon</div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="color:var(--text-muted);font-size:0.85rem">Tidak ada data calon per prodi.</div>
                <?php endif; ?>
            </div>
            <div style="margin-top:14px;padding:12px;background:#eff6ff;border-radius:8px;font-size:0.82rem;color:#1e40af">
                <i class="fas fa-info-circle"></i>
                Klik <strong>Hitung MOORA</strong> di atas untuk memulai perhitungan.
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/moora/index.blade.php ENDPATH**/ ?>