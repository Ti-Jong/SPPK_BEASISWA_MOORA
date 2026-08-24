<?php $__env->startSection('title','Kuota Prodi'); ?>
<?php $__env->startSection('page-title','Kuota Beasiswa Per Program Studi'); ?>
<?php $__env->startSection('content'); ?>
<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Sistem akan otomatis membatasi penerima sesuai kuota yang ditetapkan per program studi.</div>
<div class="card">
    <div class="card-header"><h3>Pengaturan Kuota</h3></div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('bapsi.kuota.update')); ?>">
            <?php echo csrf_field(); ?>
            <div class="table-wrap"><table>
                <thead><tr><th>Kode</th><th>Program Studi</th><th>Jenjang</th><th>Kuota Beasiswa</th><th>Status</th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><code style="font-size:0.76rem"><?php echo e($p->kode); ?></code></td>
                    <td><strong><?php echo e($p->nama); ?></strong></td>
                    <td><span class="badge b-info"><?php echo e($p->jenjang); ?></span></td>
                    <td><input type="number" name="kuota[<?php echo e($p->id); ?>]" value="<?php echo e($p->kuota_beasiswa); ?>" min="0" max="50" style="width:70px;padding:6px 8px;border:1px solid var(--border);border-radius:6px;font-family:inherit;text-align:center"></td>
                    <td><span class="badge <?php echo e($p->is_active ? 'b-success' : 'b-gray'); ?>"><?php echo e($p->is_active ? 'Aktif' : 'Non-aktif'); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table></div>
            <div style="margin-top:16px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Kuota</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/kuota/index.blade.php ENDPATH**/ ?>