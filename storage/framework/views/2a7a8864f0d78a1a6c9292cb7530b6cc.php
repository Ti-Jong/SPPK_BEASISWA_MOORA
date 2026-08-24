<?php $__env->startSection('title','Verifikasi Dokumen'); ?>
<?php $__env->startSection('page-title','Verifikasi Kelengkapan Dokumen'); ?>
<?php $__env->startSection('content'); ?>

<?php
$statusMap = [
    'menunggu_verifikasi' => ['label' => 'Menunggu',  'badge' => 'b-warning'],
    'diproses'            => ['label' => 'Diproses',  'badge' => 'b-info'],
    'lulus_verifikasi'    => ['label' => 'Lulus',     'badge' => 'b-success'],
    'ditolak'             => ['label' => 'Ditolak',   'badge' => 'b-danger'],
];
?>


<form method="GET" action="<?php echo e(route('bapsi.verifikasi.index')); ?>"
      style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Tahun Akademik:</label>
    <input type="number" name="tahun" value="<?php echo e($tahun); ?>"
           min="2000" max="2100"
           style="width:80px;border:1px solid var(--border);border-radius:7px;padding:5px 9px;font-size:0.82rem">
    <button type="submit" class="btn btn-outline btn-sm">Tampilkan</button>
</form>

<div class="card">
    <div class="card-header">
    <h3>Data Calon &amp; Status Proses</h3>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span style="font-size:0.78rem;color:var(--text-muted)">
            <?php echo e($calons->total()); ?> data ditemukan
        </span>
        
        <form method="POST" action="<?php echo e(route('bapsi.verifikasi.approve.all')); ?>"
              style="display:inline"
              onsubmit="return confirm('Apakah yakin ingin memproses semua data?\n\nSeluruh data Menunggu & Diproses akan disetujui sekaligus.')">
            <?php echo csrf_field(); ?>
            <?php if($tahun): ?>
                <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-check-double"></i> Proses Semua
            </button>
        </form>
    </div>
</div>
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $st = $statusMap[$c->status] ?? ['label' => ucfirst($c->status), 'badge' => 'b-gray'];
                ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:0.75rem">
                        <?php echo e($calons->firstItem() + $i); ?>

                    </td>
                    <td>
                        <code style="font-size:0.76rem"><?php echo e($c->nim); ?></code>
                    </td>
                    <td>
                        <strong><?php echo e($c->nama); ?></strong>
                        <?php if($c->status === 'ditolak' && $c->alasan_penolakan): ?>
                        <div style="font-size:0.7rem;color:var(--danger);margin-top:2px">
                            ⚠ <?php echo e(Str::limit($c->alasan_penolakan, 40)); ?>

                        </div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.8rem">
                        <?php echo e($c->prodi?->nama ?? '-'); ?>

                    </td>
                    <td>
                        <?php if(method_exists($c, 'isDokumenLengkap')): ?>
                            <span class="badge <?php echo e($c->isDokumenLengkap() ? 'b-success' : 'b-warning'); ?>">
                                <?php echo e($c->isDokumenLengkap() ? '✓ Lengkap' : '⚠ Kurang'); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge b-gray">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo e($st['badge']); ?>"><?php echo e($st['label']); ?></span>
                    </td>
                    <td style="white-space:nowrap">
                        
                        <a href="<?php echo e(route('bapsi.verifikasi.show', $c->id)); ?>"
                           class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        
                        <?php if(in_array($c->status, ['menunggu_verifikasi', 'diproses'])): ?>
                        <form method="POST"
                              action="<?php echo e(route('bapsi.verifikasi.approve', $c->id)); ?>"
                              style="display:inline">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-success btn-sm"
                                    onclick="return confirm('Verifikasi data <?php echo e(addslashes($c->nama)); ?>?')">
                                <i class="fas fa-check"></i> Verifikasi
                            </button>
                        </form>

                        
                        <button class="btn btn-danger btn-sm"
                                onclick="showTolakModal(<?php echo e($c->id); ?>, '<?php echo e(addslashes($c->nama)); ?>')">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted)">
                        <i class="fas fa-inbox fa-2x" style="display:block;margin-bottom:10px;opacity:.3"></i>
                        Tidak ada data calon untuk tahun <strong><?php echo e($tahun); ?></strong>.<br>
                        <small>Pastikan BAA sudah mengirim data ke BAPSI.</small>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

     
<?php if($calons->hasPages()): ?>
<div style="padding:12px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
    
    
    <span style="font-size:0.78rem;color:var(--text-muted)">
        Showing <?php echo e($calons->firstItem()); ?>–<?php echo e($calons->lastItem()); ?> of <?php echo e($calons->total()); ?> results
    </span>

    
    <div style="display:flex;align-items:center;gap:4px">

        
        <?php if($calons->onFirstPage()): ?>
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:#ccc;font-size:0.8rem;cursor:default">‹</span>
        <?php else: ?>
            <a href="<?php echo e($calons->previousPageUrl()); ?>&tahun=<?php echo e($tahun); ?>"
               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.8rem;text-decoration:none;background:#fff"
               onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
               onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'">‹</a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $calons->getUrlRange(1, $calons->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page == $calons->currentPage()): ?>
                <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;background:var(--primary,#1e3a5f);color:#fff;font-size:0.78rem;font-weight:600;border:1px solid var(--primary,#1e3a5f)"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>&tahun=<?php echo e($tahun); ?>"
                   style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.78rem;text-decoration:none;background:#fff"
                   onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
                   onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($calons->hasMorePages()): ?>
            <a href="<?php echo e($calons->nextPageUrl()); ?>&tahun=<?php echo e($tahun); ?>"
               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.8rem;text-decoration:none;background:#fff"
               onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
               onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'">›</a>
        <?php else: ?>
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:#ccc;font-size:0.8rem;cursor:default">›</span>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>


<div id="modalTolak"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px;width:100%;max-width:480px;margin:20px">
        <h3 style="font-size:1rem;margin-bottom:6px;color:var(--danger)">
            <i class="fas fa-times-circle"></i> Tolak Data Calon
        </h3>
        <p id="modalTolakNama" style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px"></p>
        <form id="formTolak" method="POST">
            <?php echo csrf_field(); ?>
            <div class="f-group" style="margin-bottom:14px">
                <label>Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                <textarea name="alasan_penolakan" rows="4" required minlength="10"
                          placeholder="Jelaskan alasan penolakan secara detail (min. 10 karakter)..."
                          style="resize:vertical"></textarea>
            </div>
            <div class="f-group" style="margin-bottom:20px">
                <label>Catatan Tambahan (opsional)</label>
                <textarea name="catatan" rows="2"
                          placeholder="Catatan tambahan untuk BAA..."
                          style="resize:vertical"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
/* Fix ukuran pagination */
nav[aria-label="Pagination Navigation"],
.pagination,
[role="navigation"] {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}

/* Semua link/button pagination */
nav[aria-label="Pagination Navigation"] a,
nav[aria-label="Pagination Navigation"] span,
nav[aria-label="Pagination Navigation"] button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
    padding: 0 8px;
    font-size: 0.78rem;
    border-radius: 6px;
    border: 1px solid var(--border, #e2e8f0);
    background: #fff;
    color: var(--text, #334155);
    text-decoration: none;
    line-height: 1;
    cursor: pointer;
}

/* Ikon SVG di dalam pagination (Previous/Next arrow) */
nav[aria-label="Pagination Navigation"] svg {
    width: 12px !important;
    height: 12px !important;
}

/* Halaman aktif */
nav[aria-label="Pagination Navigation"] span[aria-current="page"] > span {
    background: var(--primary, #1e3a5f);
    color: #fff;
    border-color: var(--primary, #1e3a5f);
    font-weight: 600;
}

/* Disabled (ellipsis) */
nav[aria-label="Pagination Navigation"] span[aria-disabled="true"] span {
    color: var(--text-muted, #94a3b8);
    cursor: default;
    border-color: transparent;
    background: transparent;
}

/* Hover */
nav[aria-label="Pagination Navigation"] a:hover {
    background: var(--primary, #1e3a5f);
    color: #fff;
    border-color: var(--primary, #1e3a5f);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showTolakModal(id, nama) {
    document.getElementById('modalTolakNama').textContent = 'Calon: ' + nama;
    document.getElementById('formTolak').action = '/bapsi/verifikasi/' + id + '/reject';
    document.getElementById('modalTolak').style.display = 'flex';
    document.querySelector('#formTolak textarea[name="alasan_penolakan"]').value = '';
    document.querySelector('#formTolak textarea[name="catatan"]').value = '';
}
function closeTolakModal() {
    document.getElementById('modalTolak').style.display = 'none';
}
// Tutup modal jika klik di luar
document.getElementById('modalTolak').addEventListener('click', function(e) {
    if (e.target === this) closeTolakModal();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/verifikasi/index.blade.php ENDPATH**/ ?>