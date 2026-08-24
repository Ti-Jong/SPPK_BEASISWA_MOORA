

<?php $__env->startSection('title','Dokumen'); ?>
<?php $__env->startSection('page-title','Manajemen Dokumen Persyaratan'); ?>

<?php $__env->startSection('content'); ?>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Pastikan semua dokumen sudah diupload sebelum mengirim data ke BAPSI.
</div>

<?php if(session('success')): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger">
    <i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<?php $__empty_1 = true; $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card" style="margin-bottom:16px;border-radius:10px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden">

    
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;
                                     padding:12px 18px;background:#fff;border-bottom:1px solid #f0f0f0">
        <div>
            <h3 style="margin:0;font-size:1rem;font-weight:700"><?php echo e($c->nama); ?></h3>
            <span style="font-size:0.78rem;color:var(--text-muted)">
                <?php echo e($c->nim); ?> · <?php echo e($c->prodi->nama_prodi ?? '-'); ?>

            </span>
        </div>
        <span class="badge <?php echo e($c->isDokumenLengkap() ? 'b-success' : 'b-warning'); ?>"
              style="padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700">
            <?php echo e($c->isDokumenLengkap() ? '✓ Lengkap' : '⚠ Kurang'); ?>

        </span>
    </div>

    
    <div class="card-body" style="padding:16px 18px">

        
        <form method="POST"
              action="<?php echo e(route('baa.dokumen.upload', $c)); ?>"
              enctype="multipart/form-data"
              style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;
                     margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f5f5f5">
            <?php echo csrf_field(); ?>

            <div class="f-group" style="display:flex;flex-direction:column;gap:4px">
                <label style="font-size:.78rem;font-weight:600;color:#555">Jenis Dokumen</label>
                <select name="jenis" required
                        style="border:1px solid #ddd;border-radius:7px;padding:6px 10px;
                               font-size:.82rem;min-width:200px">
                    <option value="surat_rekomendasi">Surat Rekomendasi</option>
                    <option value="rapor_semester_3">Rapor Semester 3</option>
                    <option value="rapor_semester_4">Rapor Semester 4</option>
                    <option value="piagam">Piagam Penghargaan</option>
                    <option value="kartu_keluarga">Kartu Keluarga</option>
                    <option value="foto_rumah">Foto Rumah</option>
                    <option value="sktm">SKTM</option>
                    <option value="tagihan_listrik">Tagihan Listrik</option>
                    <option value="surat_penghasilan">Surat Penghasilan Orang Tua</option>
                </select>
            </div>

            <div class="f-group" style="display:flex;flex-direction:column;gap:4px">
                <label style="font-size:.78rem;font-weight:600;color:#555">
                    File (PDF/JPG/PNG, max 5MB)
                </label>
                <input type="file"
                       name="file"
                       accept=".pdf,.jpg,.jpeg,.png"
                       required
                       style="border:1px solid #ddd;border-radius:7px;padding:5px 8px;
                              font-size:.82rem">
            </div>

            <button type="submit"
                    class="btn btn-primary btn-sm"
                    style="display:inline-flex;align-items:center;gap:6px;
                           padding:7px 16px;border-radius:7px;font-size:.82rem;
                           white-space:nowrap;align-self:flex-end">
                <i class="fas fa-upload"></i> Upload
            </button>
        </form>

        
        <?php if($c->dokumen->isNotEmpty()): ?>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:.82rem">
                <thead>
                    <tr style="background:#f8f9fc">
                        <th style="padding:8px 12px;text-align:left;border-bottom:2px solid #eee;
                                   font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;
                                   color:#6c757d;white-space:nowrap">
                            Jenis Dokumen
                        </th>
                        <th style="padding:8px 12px;text-align:left;border-bottom:2px solid #eee;
                                   font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;
                                   color:#6c757d">
                            Nama File
                        </th>
                        <th style="padding:8px 12px;text-align:center;border-bottom:2px solid #eee;
                                   font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;
                                   color:#6c757d;white-space:nowrap">
                            Status
                        </th>
                        <th style="padding:8px 12px;text-align:center;border-bottom:2px solid #eee;
                                   font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;
                                   color:#6c757d">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $c->dokumen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #f5f5f5;transition:background .1s"
                        onmouseover="this.style.background='#fafbff'"
                        onmouseout="this.style.background='transparent'">

                        
                        <td style="padding:10px 12px;white-space:nowrap">
                            <?php
                                $jenisLabel = match($dok->jenis) {
                                    'surat_rekomendasi' => 'Surat Rekomendasi',
                                    'rapor_semester_3'  => 'Rapor Semester 3',
                                    'rapor_semester_4'  => 'Rapor Semester 4',
                                    'piagam'            => 'Piagam Penghargaan',
                                    'kartu_keluarga'    => 'Kartu Keluarga',
                                    'foto_rumah'        => 'Foto Rumah',
                                    'sktm'              => 'SKTM',
                                    'tagihan_listrik'   => 'Tagihan Listrik',
                                    'surat_penghasilan' => 'Surat Penghasilan OT',
                                    default             => ucfirst(str_replace('_', ' ', $dok->jenis)),
                                };
                            ?>
                            <span style="font-weight:600;color:#333"><?php echo e($jenisLabel); ?></span>
                        </td>

                        
                        <td style="padding:10px 12px;max-width:250px;
                                   overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                            title="<?php echo e($dok->file_name); ?>">
                            <i class="fas fa-file" style="color:#94a3b8;margin-right:5px;font-size:.75rem"></i>
                            <?php echo e($dok->file_name); ?>

                        </td>

                        
                        <td style="padding:10px 12px;text-align:center">
                            <?php
                                $statusStyle = match($dok->status) {
                                    'valid'   => 'background:#e8f8f2;color:#1a9e6f',
                                    'invalid' => 'background:#fdf0ee;color:#e74a3b',
                                    default   => 'background:#f0f0f0;color:#666',
                                };
                                $statusIcon = match($dok->status) {
                                    'valid'   => 'fa-check-circle',
                                    'invalid' => 'fa-times-circle',
                                    default   => 'fa-clock',
                                };
                            ?>
                            <span style="display:inline-flex;align-items:center;gap:4px;
                                         padding:3px 10px;border-radius:20px;
                                         font-size:.72rem;font-weight:700;<?php echo e($statusStyle); ?>">
                                <i class="fas <?php echo e($statusIcon); ?>" style="font-size:.65rem"></i>
                                <?php echo e(ucfirst($dok->status)); ?>

                            </span>
                        </td>

                        
                        <td style="padding:10px 12px;text-align:center;white-space:nowrap">

                            
                            <a href="<?php echo e(asset('storage/' . $dok->file_path)); ?>"
                               target="_blank"
                               title="Lihat file"
                               style="display:inline-flex;align-items:center;justify-content:center;
                                      width:30px;height:30px;border-radius:6px;
                                      border:1.5px solid #3498db;color:#3498db;
                                      text-decoration:none;transition:all .15s;margin-right:4px"
                               onmouseover="this.style.background='#3498db';this.style.color='#fff'"
                               onmouseout="this.style.background='transparent';this.style.color='#3498db'">
                                <i class="fas fa-eye" style="font-size:.75rem"></i>
                            </a>

                            
                            <form method="POST"
                                  action="<?php echo e(route('baa.dokumen.destroy', $dok->id)); ?>"
                                  style="display:inline"
                                  onsubmit="return confirm('Hapus dokumen <?php echo e(addslashes($dok->file_name)); ?>?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        title="Hapus"
                                        style="display:inline-flex;align-items:center;justify-content:center;
                                               width:30px;height:30px;border-radius:6px;
                                               border:1.5px solid #e74a3b;color:#e74a3b;
                                               background:transparent;cursor:pointer;
                                               transition:all .15s"
                                        onmouseover="this.style.background='#e74a3b';this.style.color='#fff'"
                                        onmouseout="this.style.background='transparent';this.style.color='#e74a3b'">
                                    <i class="fas fa-trash" style="font-size:.75rem"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:24px;color:#aaa">
            <i class="fas fa-folder-open"
               style="font-size:2rem;display:block;margin-bottom:8px;opacity:.35"></i>
            <div style="font-size:.83rem">Belum ada dokumen diupload untuk calon ini.</div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> Belum ada data calon mahasiswa.
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/baa/dokumen/index.blade.php ENDPATH**/ ?>