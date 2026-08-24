<?php $__env->startSection('title','Dashboard LKM'); ?>
<?php $__env->startSection('page-title','Dashboard LKM'); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('content'); ?>



<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;
            padding:14px 20px;display:flex;align-items:center;gap:12px;
            flex-wrap:wrap;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,.07)">
 
    <div style="display:flex;align-items:center;gap:7px;flex-shrink:0">
        <i class="fas fa-calendar-alt" style="color:#3b82f6"></i>
        <span style="font-size:13px;font-weight:700;color:#334155">Tahun Akademik:</span>
    </div>
 
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        
        
        <?php $__empty_1 = true; $__currentLoopData = $tahunList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('lkm.dashboard', ['tahun' => $thn])); ?>"
               style="display:inline-flex;align-items:center;padding:6px 18px;
                      border-radius:20px;font-size:13px;font-weight:700;
                      text-decoration:none;transition:all .15s;
                      <?php echo e(($tahunAktif ?? '') == $thn
                         ? 'background:#3b82f6;color:#fff;border:1px solid #3b82f6;box-shadow:0 2px 8px rgba(59,130,246,.35)'
                         : 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe'); ?>">
                <?php echo e($thn); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <span style="font-size:13px;color:#94a3b8;font-style:italic">
                Belum ada data periode
            </span>
        <?php endif; ?>
    </div>
 
    <div style="margin-left:auto;font-size:12px;color:#64748b">
        Menampilkan: <strong style="color:#1d4ed8"><?php echo e($tahunAktif ?? '-'); ?></strong>
    </div>
</div>
 

<?php if(($hasilMoora ?? collect())->isEmpty()): ?>
    <div style="background:#fef9ec;border:1px solid #fde68a;border-radius:10px;
                padding:14px 18px;display:flex;align-items:flex-start;gap:10px;
                font-size:13px;color:#92400e;margin-bottom:20px">
        <i class="fas fa-exclamation-triangle" style="flex-shrink:0;margin-top:2px"></i>
        <div>
            <strong>Belum ada hasil ranking MOORA untuk periode <?php echo e($tahunAktif ?? '-'); ?>.</strong><br>
            Pastikan Admin BAPSI sudah menjalankan perhitungan MOORA terlebih dahulu.
        </div>
    </div>
<?php endif; ?>


<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon si-blue"><i class="fas fa-list-ol"></i></div><div><div class="stat-val"><?php echo e($stats['total_ranking']); ?></div><div class="stat-label">Total Kandidat Terperingkat</div></div></div>
    <div class="stat-card"><div class="stat-icon si-green"><i class="fas fa-award"></i></div><div><div class="stat-val"><?php echo e($stats['penerima']); ?></div><div class="stat-label">Penerima Ditetapkan</div></div></div>
    <div class="stat-card"><div class="stat-icon si-orange"><i class="fas fa-university"></i></div><div><div class="stat-val"><?php echo e($stats['total_prodi']); ?></div><div class="stat-label">Program Studi Aktif</div></div></div>
    <div class="stat-card"><div class="stat-icon si-gold"><i class="fas fa-star"></i></div><div><div class="stat-val"><?php echo e($stats['skor_tertinggi'] ? number_format($stats['skor_tertinggi'],4) : '-'); ?></div><div class="stat-label">Skor Yi Tertinggi</div></div></div>
</div>
<div class="card">
    <div class="card-header"><h3><i class="fas fa-trophy" style="color:var(--accent)"></i> Top 5 Ranking MOORA — <?php echo e($tahun); ?></h3>
        <a href="<?php echo e(route('lkm.keputusan.index')); ?>" class="btn btn-accent btn-sm"><i class="fas fa-check-double"></i> Tetapkan Penerima</a>
    </div>
    <div class="card-body">
    <?php $__empty_1 = true; $__currentLoopData = $topRanking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rank-card">
            <div class="rank-num r<?php echo e($i < 3 ? $i+1 : 'n'); ?>"><?php echo e($i+1); ?></div>
            <div style="flex:1">
                <div style="font-weight:700;font-size:0.9rem"><?php echo e($h->calon->nama); ?> <code style="font-size:0.72rem;background:#f1f5f9;padding:2px 6px;border-radius:4px"><?php echo e($h->calon->nim); ?></code></div>
                <div style="font-size:0.75rem;color:var(--text-muted)"><?php echo e($h->prodi->nama); ?></div>
                <div class="progress-bar"><div class="progress-fill" style="width:<?php echo e(max(15, ($h->skor_moora + 0.5) * 80)); ?>%"></div></div>
            </div>
            <div style="text-align:right">
                <div style="font-size:1.1rem;font-weight:800;color:var(--primary-light)"><?php echo e(number_format($h->skor_moora,4)); ?></div>
                <div style="font-size:0.7rem;color:var(--text-muted)">Yi Score</div>
                <?php if($h->keputusan): ?><span class="badge b-success" style="margin-top:4px">✓ Penerima</span><?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:20px">Belum ada hasil MOORA. Tunggu proses dari BAPSI.</p>
    <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/lkm/dashboard/index.blade.php ENDPATH**/ ?>