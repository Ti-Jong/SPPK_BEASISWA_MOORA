<?php $__env->startSection('title', 'Tetapkan Penerima'); ?>
<?php $__env->startSection('page-title', 'Penetapan Penerima Beasiswa'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?></div>
<?php endif; ?>


<form method="GET" action="<?php echo e(route('lkm.keputusan.index')); ?>"
      style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap">
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Periode:</label>
    <select name="tahun"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem"
            onchange="this.form.submit()">
        <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($t); ?>" <?php echo e($t == $tahun ? 'selected' : ''); ?>><?php echo e($t); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</form>


<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div>
            <h3>Penetapan Penerima — Periode <?php echo e($tahun); ?></h3>
            <span style="font-size:0.78rem;color:var(--text-muted)">
                Berdasarkan ranking MOORA per prodi dan kuota yang tersedia
            </span>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            
            <form method="POST" action="<?php echo e(route('lkm.keputusan.tetapkan')); ?>"
                  onsubmit="return confirm('<?php echo e($sudahDitetapkan
                      ? 'Tetapkan ulang? Keputusan sebelumnya akan ditimpa.'
                      : 'Tetapkan penerima beasiswa berdasarkan ranking dan kuota per prodi?'); ?>')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                <button class="btn btn-primary">
                    <i class="fas fa-<?php echo e($sudahDitetapkan ? 'sync' : 'check-double'); ?>"></i>
                    <?php echo e($sudahDitetapkan ? 'Tetapkan Ulang' : 'Tetapkan Penerima'); ?>

                </button>
            </form>

            <?php if($sudahDitetapkan): ?>
            
            <a href="<?php echo e(route('lkm.keputusan.export', ['tahun' => $tahun, 'format' => 'csv'])); ?>"
               class="btn btn-outline">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            
            <a href="<?php echo e(route('lkm.keputusan.export', ['tahun' => $tahun, 'format' => 'html'])); ?>"
               target="_blank" class="btn btn-outline">
                <i class="fas fa-print"></i> Cetak / PDF
            </a>
            
            <form method="POST" action="<?php echo e(route('lkm.keputusan.finalize')); ?>" style="display:inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                <button class="btn btn-accent">
                    <i class="fas fa-lock"></i> Finalisasi
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if($sudahDitetapkan): ?>
    <div class="card-body" style="padding:14px 20px">
        <div style="display:flex;gap:16px;flex-wrap:wrap;align-items:center">
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:3px;background:#86efac"></div>
                <span style="font-size:0.8rem;color:var(--text-muted)">
                    Total penerima: <strong style="color:var(--success)"><?php echo e($totalPenerima); ?> mahasiswa</strong>
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:3px;background:#1e3a5c"></div>
                <span style="font-size:0.8rem;color:var(--text-muted)">
                    Dari <?php echo e($rankingPerProdi->count()); ?> program studi
                </span>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card-body">
        <div class="alert alert-info" style="margin:0">
            <i class="fas fa-info-circle"></i>
            Klik <strong>Tetapkan Penerima</strong> untuk menentukan penerima beasiswa secara otomatis
            berdasarkan ranking MOORA dan kuota tiap prodi.
        </div>
    </div>
    <?php endif; ?>
</div>


<?php $__empty_1 = true; $__currentLoopData = $rankingPerProdi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    $prodi       = $group['prodi'];
    $items       = $group['items'];
    $kuota       = $group['kuota'];
    $sudahDitap  = $group['sudah_ditap'];
    $sisaKuota   = $group['sisa_kuota'];
?>

<div class="card" style="margin-bottom:18px">
    
    <div class="card-header"
         style="background:linear-gradient(135deg,#1a3a5c,#2563a8);border-radius:13px 13px 0 0">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.15);
                        display:flex;align-items:center;justify-content:center;font-size:1.1rem">🎓</div>
            <div>
                <h3 style="color:#fff;font-size:0.92rem;margin:0;font-weight:700">
                    <?php echo e($prodi?->nama ?? 'Prodi Tidak Diketahui'); ?>

                </h3>
                <span style="color:rgba(255,255,255,.65);font-size:0.72rem">
                    <?php echo e($prodi?->jenjang ?? ''); ?> &nbsp;·&nbsp; <?php echo e($group['total']); ?> calon
                </span>
            </div>
        </div>
        
        <div style="display:flex;gap:8px">
            <div style="background:rgba(255,255,255,.15);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fff;font-weight:800;font-size:1.1rem;line-height:1"><?php echo e($kuota); ?></div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Kuota</div>
            </div>
            <div style="background:rgba(5,150,105,.3);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#6ee7b7;font-weight:800;font-size:1.1rem;line-height:1"><?php echo e($sudahDitap); ?></div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Ditetapkan</div>
            </div>
            <?php if($sisaKuota > 0): ?>
            <div style="background:rgba(217,119,6,.3);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fcd34d;font-weight:800;font-size:1.1rem;line-height:1"><?php echo e($sisaKuota); ?></div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Sisa</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Rank Prodi</th>
                        <th>Rank Global</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Semester</th>
                        <th>Skor MOORA</th>
                        <th>Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hasil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $rankProdi  = $hasil->ranking_prodi ?? ($loop->iteration);
                    $rankGlobal = $hasil->ranking ?? '-';
                    $skor       = (float)($hasil->skor_moora ?? $hasil->yi_score ?? 0);

                    // ── FIX: cocokkan Keputusan lewat calon_id, BUKAN hasil_moora_id ──
                    // Satu calon bisa punya beberapa baris HasilMoora (tahap P1/P2/P3),
                    // tapi Keputusan finalnya cuma satu per tahun. $hasil->keputusan
                    // (relasi via hasil_moora_id) bisa null kalau baris HasilMoora yang
                    // sedang ditampilkan bukan yang terhubung ke Keputusan tersimpan.
                    $keputusan  = $keputusanByCalon->get($hasil->calon_id);

                    $lolos      = $rankProdi <= $kuota;
                    $status     = $keputusan?->status ?? null;
                ?>
                <tr style="<?php echo e($lolos ? 'background:#f0fdf4' : ''); ?>">
                    
                    <td>
                        <?php if($rankProdi == 1): ?> <span style="font-size:1.2rem">🥇</span>
                        <?php elseif($rankProdi == 2): ?> <span style="font-size:1.2rem">🥈</span>
                        <?php elseif($rankProdi == 3): ?> <span style="font-size:1.2rem">🥉</span>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         width:26px;height:26px;border-radius:6px;background:#f1f5f9;
                                         font-weight:700;font-size:0.78rem;color:var(--text-muted)">
                                <?php echo e($rankProdi); ?>

                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-size:0.75rem;color:var(--text-muted)">#<?php echo e($rankGlobal); ?></span>
                    </td>
                    <td><code style="font-size:0.75rem"><?php echo e($hasil->calon->nim ?? '-'); ?></code></td>
                    <td>
                        <div style="font-weight:600;font-size:0.84rem"><?php echo e($hasil->calon->nama ?? '-'); ?></div>
                        <?php if($keputusan?->keterangan): ?>
                        <div style="font-size:0.68rem;color:var(--text-muted);margin-top:1px">
                            <?php echo e($keputusan->keterangan); ?>

                        </div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.82rem"><?php echo e($hasil->calon->semester ?? '-'); ?></td>
                    <td>
                        <span style="font-weight:700;font-size:0.85rem;
                                     color:<?php echo e($skor >= 0 ? '#059669' : '#dc2626'); ?>">
                            <?php echo e(number_format($skor, 4)); ?>

                        </span>
                    </td>
                    
                    <td>
                        <?php if($status === 'penerima'): ?>
                            <span class="badge b-success">✓ Penerima</span>
                        <?php elseif($status === 'cadangan'): ?>
                            <span class="badge b-warning">⏳ Cadangan</span>
                        <?php elseif($status === 'tidak_lolos'): ?>
                            <span class="badge b-gray">✗ Tidak Lolos</span>
                        <?php elseif($lolos): ?>
                            <span class="badge b-info">Akan Diterima</span>
                        <?php else: ?>
                            <span class="badge b-gray" style="opacity:.6">Tidak Lolos</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div style="padding:10px 16px;border-top:1px solid var(--border);background:#f8fafc;
                    border-radius:0 0 13px 13px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
            <span style="font-size:0.75rem;color:var(--text-muted)">
                <span style="color:var(--success);font-weight:700"><?php echo e(min($kuota, $group['total'])); ?></span>
                diterima &nbsp;·&nbsp;
                <span style="color:var(--text-muted)"><?php echo e(max(0, $group['total'] - $kuota)); ?></span>
                tidak lolos
                <?php if($sisaKuota > 0): ?>
                    &nbsp;·&nbsp;
                    <span style="color:var(--warning);font-weight:600">⚠ <?php echo e($sisaKuota); ?> kuota tidak terisi</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px">
        <i class="fas fa-trophy" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="color:var(--text-muted);font-size:0.9rem">
            Belum ada data ranking untuk periode <strong><?php echo e($tahun); ?></strong>.
        </p>
        <p style="color:var(--text-muted);font-size:0.8rem;margin-top:6px">
            Minta Admin BAPSI untuk menjalankan perhitungan MOORA terlebih dahulu.
        </p>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/lkm/keputusan/index.blade.php ENDPATH**/ ?>