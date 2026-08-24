<?php $__env->startSection('title','Master Kriteria'); ?>
<?php $__env->startSection('page-title','Master Kriteria & Skor'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.active { display:flex; }
.modal-box { background:#fff; border-radius:16px; padding:28px; width:500px; max-width:94vw; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation:modalIn .2s ease; }
@keyframes modalIn { from{opacity:0;transform:scale(0.95)} to{opacity:1;transform:scale(1)} }
.modal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
.modal-header h3 { font-size:1rem; font-weight:700; }
.modal-close { background:none; border:none; font-size:1.3rem; cursor:pointer; color:var(--text-muted); padding:4px 8px; border-radius:6px; }
.modal-close:hover { background:var(--bg); }
.modal-footer { display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
.sub-edit-row { display:none; background:#f8fafc; }
.sub-edit-row.active { display:table-row; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?></div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <i class="fas fa-times-circle"></i>
    <ul style="margin:4px 0 0 18px">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($e); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>

<div class="alert alert-info"><i class="fas fa-info-circle"></i> Kelola kriteria dan sub kriteria (range skor) yang digunakan dalam perhitungan MOORA.</div>


<?php
    $totalBobotAktif = $kriterias->where('is_active', true)->sum('bobot');
    $sisaBobot = max(0, 1 - $totalBobotAktif);
?>

<div class="alert <?php echo e($totalBobotAktif == 1 ? 'alert-info' : 'alert-warning'); ?>" style="margin-bottom:12px">
    <i class="fas fa-balance-scale"></i>
    Total bobot kriteria aktif saat ini: <strong><?php echo e(round($totalBobotAktif * 100, 1)); ?>%</strong>
    <?php if($sisaBobot > 0): ?>
        — sisa yang bisa dipakai: <strong><?php echo e(round($sisaBobot * 100, 1)); ?>%</strong>
    <?php elseif($totalBobotAktif > 1): ?>
        — <strong style="color:#dc2626">melebihi 100%! segera perbaiki.</strong>
    <?php endif; ?>
</div>


<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
    <button class="btn btn-primary" onclick="openModal('modal-tambah-kriteria')">
        <i class="fas fa-plus"></i> Tambah Kriteria
    </button>
</div>

<?php $__currentLoopData = $kriterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card" style="margin-bottom:20px">

    
    <div class="card-header" style="cursor:pointer"
         onclick="toggleCard('card-body-<?php echo e($k->id); ?>', 'chevron-<?php echo e($k->id); ?>')">
        <div>
            <h3>
                <span style="color:var(--primary-light)"><?php echo e($k->kode); ?></span> — <?php echo e($k->nama); ?>

            </h3>
            <span style="font-size:0.78rem;color:var(--text-muted)">
                Bobot: <strong><?php echo e($k->bobot*100); ?>%</strong> &nbsp;|&nbsp;
                Tipe: <span class="badge <?php echo e($k->tipe==='benefit'?'b-success':'b-danger'); ?>"><?php echo e(strtoupper($k->tipe)); ?></span>
                &nbsp;|&nbsp; <?php echo e($k->subKriterias->count()); ?> sub kriteria
            </span>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <button class="btn btn-outline btn-sm"
                    onclick="event.stopPropagation(); openEditKriteria(<?php echo e($k->id); ?>, '<?php echo e($k->kode); ?>', '<?php echo e(addslashes($k->nama)); ?>', '<?php echo e($k->tipe); ?>', <?php echo e($k->bobot); ?>, <?php echo e($k->urutan); ?>)">
                <i class="fas fa-edit"></i> Edit
            </button>

            
            <form method="POST" action="<?php echo e(route('bapsi.kriteria.destroy', $k)); ?>"
                  style="display:inline" onclick="event.stopPropagation()">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="confirmHapusKriteria(this, '<?php echo e($k->kode); ?>', '<?php echo e(addslashes($k->nama)); ?>')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            

            <i id="chevron-<?php echo e($k->id); ?>" class="fas fa-chevron-down"
               style="color:var(--text-muted);font-size:0.85rem;transition:transform .3s;
                      transform:<?php echo e($loop->first ? 'rotate(0deg)' : 'rotate(-90deg)'); ?>"></i>
        </div>
    </div>

    
    <div id="card-body-<?php echo e($k->id); ?>" style="<?php echo e($loop->first ? '' : 'display:none'); ?>">
        <div class="card-body" style="padding:0">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Label / Range</th>
                            <th>Nilai String</th>
                            <th>Range Min (Rp)</th>
                            <th>Range Max (Rp)</th>
                            <th>Skor</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $k->subKriterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="row-<?php echo e($s->id); ?>">
                        <td><strong><?php echo e($s->label); ?></strong></td>
                        <td><?php echo e($s->nilai_string ?? '-'); ?></td>
                        <td><?php echo e($s->range_min !== null ? 'Rp '.number_format($s->range_min,0,',','.') : '-'); ?></td>
                        <td><?php echo e($s->range_max !== null ? 'Rp '.number_format($s->range_max,0,',','.') : '-'); ?></td>
                        <td>
                            <span style="background:var(--primary);color:#fff;border-radius:6px;padding:3px 10px;font-weight:700">
                                <?php echo e($s->skor); ?>

                            </span>
                        </td>
                        <td style="text-align:center;white-space:nowrap">
                            <button class="btn btn-outline btn-sm" onclick="toggleEditSub(<?php echo e($s->id); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" action="<?php echo e(route('bapsi.sub-kriteria.destroy', $s)); ?>" style="display:inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus sub kriteria ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr class="sub-edit-row" id="edit-row-<?php echo e($s->id); ?>">
                        <td colspan="6" style="padding:12px 16px;background:#f0f4f8">
                            <form method="POST" action="<?php echo e(route('bapsi.sub-kriteria.update', $s)); ?>"
                                  style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="f-group" style="min-width:150px">
                                    <label>Label</label>
                                    <input type="text" name="label" value="<?php echo e($s->label); ?>" required>
                                </div>
                                <div class="f-group" style="min-width:120px">
                                    <label>Nilai String</label>
                                    <input type="text" name="nilai_string" value="<?php echo e($s->nilai_string); ?>">
                                </div>
                                <div class="f-group" style="min-width:120px">
                                    <label>Range Min</label>
                                    <input type="number" name="range_min" value="<?php echo e($s->range_min); ?>">
                                </div>
                                <div class="f-group" style="min-width:120px">
                                    <label>Range Max</label>
                                    <input type="number" name="range_max" value="<?php echo e($s->range_max); ?>">
                                </div>
                                <div class="f-group" style="min-width:80px">
                                    <label>Skor (1-10)</label>
                                    <input type="number" name="skor" value="<?php echo e($s->skor); ?>" min="0" max="10" required>
                                </div>
                                <div style="display:flex;gap:6px">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-outline btn-sm"
                                            onclick="toggleEditSub(<?php echo e($s->id); ?>)">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--text-muted);padding:16px">
                            Belum ada sub kriteria. Tambahkan di bawah.
                        </td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div style="padding:16px;border-top:1px solid var(--border);background:#fafbfc">
                <p style="font-size:0.78rem;font-weight:700;color:var(--text-muted);margin-bottom:10px;text-transform:uppercase">
                    <i class="fas fa-plus-circle" style="color:var(--primary-light)"></i>
                    Tambah Sub Kriteria untuk <?php echo e($k->kode); ?>

                </p>
                <form method="POST" action="<?php echo e(route('bapsi.sub-kriteria.store', $k)); ?>"
                      style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                    <?php echo csrf_field(); ?>
                    <div class="f-group" style="min-width:150px">
                        <label>Label *</label>
                        <input type="text" name="label" placeholder="misal: ≤ Rp 1jt" required>
                    </div>
                    <div class="f-group" style="min-width:120px">
                        <label>Nilai String</label>
                        <input type="text" name="nilai_string" placeholder="mis: yatim">
                    </div>
                    <div class="f-group" style="min-width:120px">
                        <label>Range Min</label>
                        <input type="number" name="range_min" placeholder="0">
                    </div>
                    <div class="f-group" style="min-width:120px">
                        <label>Range Max</label>
                        <input type="number" name="range_max" placeholder="1000000">
                    </div>
                    <div class="f-group" style="min-width:80px">
                        <label>Skor (1-10) *</label>
                        <input type="number" name="skor" min="0" max="10" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal-overlay" id="modal-tambah-kriteria" onclick="closeModalOutside(event, 'modal-tambah-kriteria')">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="color:var(--primary-light)"></i> Tambah Kriteria Baru</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah-kriteria')">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('bapsi.kriteria.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-grid" style="gap:14px">
                <div class="f-group">
                    <label>Kode Kriteria * <span style="font-size:0.72rem;color:var(--text-muted)">(misal: C5)</span></label>
                    <input type="text" name="kode" placeholder="C5" maxlength="5" required style="text-transform:uppercase">
                </div>
                <div class="f-group">
                    <label>Urutan *</label>
                    <input type="number" name="urutan" placeholder="5" min="1" required>
                </div>
                <div class="f-group" style="grid-column:1/-1">
                    <label>Nama Kriteria *</label>
                    <input type="text" name="nama" placeholder="misal: IPK Semester Terakhir" required>
                </div>
                <div class="f-group">
                    <label>Tipe *</label>
                    <select name="tipe" required>
                        <option value="benefit">Benefit (semakin tinggi semakin baik)</option>
                        <option value="cost">Cost (semakin rendah semakin baik)</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Bobot * <span style="font-size:0.72rem;color:var(--text-muted)">(0.01 – <?php echo e(round($sisaBobot,2)); ?> tersisa)</span></label>
                    <input type="number" name="bobot" placeholder="0.10" min="0.01" max="1" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modal-tambah-kriteria')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Kriteria</button>
            </div>
        </form>
    </div>
</div>


<div class="modal-overlay" id="modal-edit-kriteria" onclick="closeModalOutside(event, 'modal-edit-kriteria')">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:var(--primary-light)"></i> Edit Kriteria</h3>
            <button class="modal-close" onclick="closeModal('modal-edit-kriteria')">&times;</button>
        </div>
        <form method="POST" id="form-edit-kriteria">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-grid" style="gap:14px">
                <div class="f-group">
                    <label>Kode Kriteria *</label>
                    <input type="text" id="edit-kode" name="kode" maxlength="5" required style="text-transform:uppercase">
                </div>
                <div class="f-group">
                    <label>Urutan *</label>
                    <input type="number" id="edit-urutan" name="urutan" min="1" required>
                </div>
                <div class="f-group" style="grid-column:1/-1">
                    <label>Nama Kriteria *</label>
                    <input type="text" id="edit-nama" name="nama" required>
                </div>
                <div class="f-group">
                    <label>Tipe *</label>
                    <select id="edit-tipe" name="tipe" required>
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Bobot * <span style="font-size:0.72rem;color:var(--text-muted)">(0.01 – 1.00)</span></label>
                    <input type="number" id="edit-bobot" name="bobot" min="0.01" max="1" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-kriteria')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Kriteria</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleCard(bodyId, chevronId) {
    const body    = document.getElementById(bodyId);
    const chevron = document.getElementById(chevronId);
    const isHidden = body.style.display === 'none';
    body.style.display      = isHidden ? '' : 'none';
    chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
}
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}
function closeModalOutside(e, id) {
    if (e.target.id === id) closeModal(id);
}
function openEditKriteria(id, kode, nama, tipe, bobot, urutan) {
    document.getElementById('edit-kode').value   = kode;
    document.getElementById('edit-nama').value   = nama;
    document.getElementById('edit-tipe').value   = tipe;
    document.getElementById('edit-bobot').value  = bobot;
    document.getElementById('edit-urutan').value = urutan;
    document.getElementById('form-edit-kriteria').action =
        '<?php echo e(url("bapsi/kriteria")); ?>/' + id;
    openModal('modal-edit-kriteria');
}
function toggleEditSub(id) {
    const editRow = document.getElementById('edit-row-' + id);
    const isActive = editRow.classList.contains('active');
    document.querySelectorAll('.sub-edit-row.active').forEach(r => r.classList.remove('active'));
    if (!isActive) {
        editRow.classList.add('active');
        editRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// ▼▼▼ KONFIRMASI HAPUS KRITERIA — ketik ulang kode, bukan cuma confirm() biasa ▼▼▼
function confirmHapusKriteria(btn, kode, nama) {
    const input = prompt(
        `PERHATIAN: Anda akan menghapus kriteria [${kode}] "${nama}".\n` +
        `Ini akan menghapus SEMUA sub kriteria di dalamnya.\n\n` +
        `Ketik "${kode}" untuk konfirmasi:`
    );
    if (input === kode) {
        btn.closest('form').submit();
    } else if (input !== null) {
        alert('Kode tidak cocok. Penghapusan dibatalkan.');
    }
}
// ▲▲▲ SAMPAI SINI ▲▲▲

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => {
            m.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/kriteria/index.blade.php ENDPATH**/ ?>