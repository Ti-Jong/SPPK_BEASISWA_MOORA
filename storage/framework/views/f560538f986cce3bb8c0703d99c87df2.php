<?php $__env->startSection('title','Dashboard BAPSI'); ?>
<?php $__env->startSection('page-title','Dashboard BAPSI'); ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="stat-val"><?php echo e($stats['total']); ?></div>
            <div class="stat-label">Total Calon Masuk</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="stat-val"><?php echo e($stats['terverifikasi']); ?></div>
            <div class="stat-label">Terverifikasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-orange">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="stat-val"><?php echo e($stats['terkirim']); ?></div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-gold">
            <i class="fas fa-trophy"></i>
        </div>
        <div>
            <div class="stat-val"><?php echo e($hasilTerbaru->count()); ?></div>
            <div class="stat-label">Hasil MOORA</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    
    <div class="card">
        <div class="card-header">
            <h3>Top Ranking MOORA</h3>
            <a href="<?php echo e(route('bapsi.moora.index')); ?>" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>

        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $hasilTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div class="rank-card">
                    <div class="rank-num r<?php echo e($i < 3 ? $i+1 : 'n'); ?>">
                        <?php echo e($i+1); ?>

                    </div>

                    <div style="flex:1">
                        <div style="font-weight:700;font-size:0.88rem">
                            <?php echo e($h->calon->nama); ?>

                        </div>

                        <div style="font-size:0.74rem;color:var(--text-muted)">
                            <?php echo e($h->calon->prodi->nama_prodi ?? '-'); ?>

                        </div>
                    </div>

                    <div style="text-align:right">
                        <div style="font-size:1rem;font-weight:800;color:var(--primary-light)">
                            <?php echo e(number_format($h->skor_moora,4)); ?>

                        </div>

                        <div style="font-size:0.7rem;color:var(--text-muted)">
                            Skor MOORA
                        </div>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:20px">
                    Belum ada hasil MOORA.
                    <a href="<?php echo e(route('bapsi.moora.index')); ?>">Hitung sekarang</a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>Calon Menunggu Verifikasi</h3>
            <a href="<?php echo e(route('bapsi.verifikasi.index')); ?>" class="btn btn-outline btn-sm">Semua</a>
        </div>

        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $calonsMenunggu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    
                    <div>
                        <div style="font-weight:600;font-size:0.84rem">
                            <?php echo e($c->nama); ?>

                        </div>

                        <div style="font-size:0.73rem;color:var(--text-muted)">
                            <?php echo e($c->prodi->nama_prodi ?? '-'); ?>

                        </div>
                    </div>

                    <?php
                    $statusMap = [
                        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => '#f4f1f0', 'bg' => '#068f04'],
                        'lulus_verifikasi'    => ['label' => 'Lulus Verifikasi',    'color' => '#15803d', 'bg' => '#f0fdf4'],
                        'ditolak'             => ['label' => 'Ditolak',             'color' => '#b91c1c', 'bg' => '#fef2f2'],
                        'draft'               => ['label' => 'Draft',               'color' => '#475569', 'bg' => '#f1f5f9'],
                        'ditetapkan'          => ['label' => 'Ditetapkan',          'color' => '#1d4ed8', 'bg' => '#eff6ff'],
                    ];
                    $st = $statusMap[$c->status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $c->status)),
                        'color' => '#475569',
                        'bg'    => '#f1f5f9',
                    ];
                ?>
                <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['color']); ?>;padding:4px 11px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;letter-spacing:.02em">
                    <?php echo e($st['label']); ?>

                </span>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:20px">
                    Semua terverifikasi ✓
                </p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/dashboard/index.blade.php ENDPATH**/ ?>