<?php $__env->startSection('title', 'Detail Verifikasi — ' . $calon->nama); ?>
<?php $__env->startSection('page-title', 'Detail Calon'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ════════════════════════════════════
   BAPSI — Detail Verifikasi Calon
   ════════════════════════════════════ */
:root {
    --blue   : #4e73df; --blue-lt : #eef0fb;
    --green  : #1cc88a; --green-lt: #e8f8f2;
    --red    : #e74a3b; --red-lt  : #fff5f5;
    --yellow : #f6c23e; --yellow-lt:#fef9ec;
    --cyan   : #36b9cc; --cyan-lt : #e8f8fb;
    --ink    : #2d3748; --ink-2   : #4a5568; --ink-3: #718096;
    --border : #e2e8f0; --surface : #f8fafc;
}

/* ── Page Header ── */
.v-header {
    background: #fff;
    border-bottom: 1px solid var(--border);
    padding: 18px 28px;
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px;
    flex-wrap: wrap; margin: -24px -24px 24px;
}
.v-header-left h1 {
    font-size: 18px; font-weight: 800;
    color: var(--ink); margin: 0 0 4px;
}
.v-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--ink-3);
}
.v-breadcrumb a { color: var(--ink-3); text-decoration: none; }
.v-breadcrumb a:hover { color: var(--blue); }
.v-breadcrumb span { color: #cbd5e1; }
.v-header-actions { display: flex; gap: 8px; flex-shrink: 0; }

/* ── Status Badge ── */
.v-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 13px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
}
.v-status::before {
    content: ''; width: 7px; height: 7px;
    border-radius: 50%; background: currentColor;
}
.vs-menunggu { background: var(--yellow-lt); color: #b45309; }
.vs-diproses  { background: var(--cyan-lt);   color: #0e7490; }
.vs-lulus     { background: var(--green-lt);  color: #15803d; }
.vs-ditolak   { background: var(--red-lt);    color: var(--red); }

/* ── Cards ── */
.v-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.v-card-header {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px;
    border-bottom: 1px solid var(--border);
    font-weight: 700; font-size: 13.5px;
}
.v-card-header .ico {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.ico-blue   { background: var(--blue-lt);   color: var(--blue); }
.ico-green  { background: var(--green-lt);  color: var(--green); }
.ico-cyan   { background: var(--cyan-lt);   color: var(--cyan); }
.ico-yellow { background: var(--yellow-lt); color: #d97706; }
.ico-red    { background: var(--red-lt);    color: var(--red); }
.v-card-body { padding: 18px; }

/* ── Info Grid ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0;
}
.info-item {
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    border-right: 1px solid var(--border);
}
.info-item:nth-child(even) { border-right: none; }
.info-item:nth-last-child(-n+2) { border-bottom: none; }
.info-item.full {
    grid-column: 1 / -1;
    border-right: none;
}
.info-label {
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--ink-3); margin-bottom: 4px;
}
.info-value {
    font-size: 13.5px; font-weight: 600; color: var(--ink);
    line-height: 1.4;
}
.info-value.mono { font-family: 'Courier New', monospace; letter-spacing: .04em; }
.info-value.muted { color: var(--ink-3); font-weight: 400; }

/* ── Nilai Rapor ── */
.rapor-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 8px;
    font-size: 15px; font-weight: 800;
}
.rb-high { background: #dcfce7; color: #15803d; }
.rb-mid  { background: #fef9c3; color: #854d0e; }
.rb-low  { background: #fee2e2; color: #991b1b; }

/* ── Kondisi OT chip ── */
.kondisi-chip {
    display: inline-block; padding: 4px 11px;
    border-radius: 6px; font-size: 12px; font-weight: 700;
}
.kc-10 { background: #450a0a; color: #fca5a5; }
.kc-5  { background: #7c2d12; color: #fed7aa; }
.kc-0  { background: var(--green-lt); color: #15803d; }

/* ── Dokumen List ── */
.dok-list { padding: 0; }
.dok-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 18px; border-bottom: 1px solid var(--border);
    gap: 12px;
}
.dok-item:last-child { border-bottom: none; }
.dok-left { display: flex; align-items: center; gap: 10px; }
.dok-icon {
    width: 32px; height: 32px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.dok-icon.ok  { background: var(--green-lt); color: var(--green); }
.dok-icon.emp { background: var(--surface);  color: #cbd5e1; }
.dok-name  { font-size: 13px; font-weight: 600; color: var(--ink); }
.dok-size  { font-size: 11px; color: var(--ink-3); margin-top: 1px; }
.btn-preview {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 6px;
    border: 1.5px solid var(--cyan); color: var(--cyan);
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: all .15s;
}
.btn-preview:hover { background: var(--cyan); color: #fff; }

/* ── Penolakan Card ── */
.penolakan-card {
    border: 2px solid var(--red);
    border-radius: 12px; overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 4px 16px rgba(231,74,59,.12);
}
.penolakan-header {
    background: var(--red); color: #fff;
    padding: 12px 18px;
    display: flex; align-items: center; gap: 10px;
    font-weight: 700; font-size: 14px;
}
.penolakan-body { padding: 18px; background: var(--red-lt); }
.alasan-box {
    background: #fff0ef; border: 1px solid #fcd5d0;
    border-left: 4px solid var(--red);
    border-radius: 8px; padding: 13px 16px;
    color: #c0392b; font-size: 13.5px; line-height: 1.6;
    margin-bottom: 12px;
}
.penolakan-meta {
    display: flex; align-items: center; gap: 16px;
    flex-wrap: wrap; font-size: 12px; color: var(--ink-3);
}
.penolakan-meta i { color: var(--red); }

/* ── Lulus Card ── */
.lulus-card {
    border: 2px solid var(--green);
    border-radius: 12px; overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 4px 16px rgba(28,200,138,.1);
}
.lulus-header {
    background: var(--green); color: #fff;
    padding: 12px 18px;
    display: flex; align-items: center; gap: 10px;
    font-weight: 700; font-size: 14px;
}
.lulus-body {
    padding: 16px 18px; background: var(--green-lt);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
}
.lulus-meta { font-size: 12.5px; color: #166534; }

/* ── Aksi Verifikasi ── */
.aksi-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.aksi-header {
    background: var(--surface);
    padding: 13px 18px; border-bottom: 1px solid var(--border);
    font-weight: 700; font-size: 13.5px; color: var(--ink);
    display: flex; align-items: center; gap: 8px;
}
.aksi-body { padding: 18px; }

.btn-verifikasi {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 8px;
    font-size: 13.5px; font-weight: 700; cursor: pointer;
    border: none; transition: all .18s; text-decoration: none;
}
.btn-setujui { background: var(--green); color: #fff; }
.btn-setujui:hover { background: #17a673; color: #fff; }
.btn-tolak   { background: var(--red);   color: #fff; }
.btn-tolak:hover { background: #c0392b; color: #fff; }
.btn-back    {
    background: #fff; color: var(--ink-3);
    border: 1.5px solid var(--border);
    padding: 10px 18px; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    transition: all .15s;
}
.btn-back:hover { background: var(--surface); color: var(--ink); }

/* ── Textarea Tolak ── */
.tolak-form { display: none; margin-top: 18px; }
.tolak-form.show { display: block; }
.tolak-form textarea {
    width: 100%; padding: 10px 13px;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-size: 13px; font-family: inherit;
    color: var(--ink); resize: vertical; min-height: 90px;
    transition: border-color .15s;
}
.tolak-form textarea:focus {
    outline: none; border-color: var(--red);
    box-shadow: 0 0 0 3px rgba(231,74,59,.1);
}
.tolak-form label {
    display: block; font-size: 12px; font-weight: 700;
    color: var(--red); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .05em;
}

/* ── Layout 2 kolom ── */
.v-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 1024px) { .v-layout { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="v-header">
    <div class="v-header-left">
        <div class="v-breadcrumb">
            <a href="<?php echo e(route('bapsi.dashboard')); ?>">Dashboard</a>
            <span>›</span>
            <a href="<?php echo e(route('bapsi.verifikasi.index')); ?>">Verifikasi</a>
            <span>›</span>
            <span><?php echo e($calon->nim); ?></span>
        </div>
        <h1>Detail Calon Mahasiswa</h1>
        <div style="margin-top:6px">
            <?php
            $stMap = [
                'menunggu_verifikasi' => ['Menunggu Verifikasi', 'vs-menunggu'],
                'diproses'            => ['Sedang Diproses',     'vs-diproses'],
                'lulus_verifikasi'    => ['Lulus Verifikasi',    'vs-lulus'],
                'ditolak'             => ['Ditolak',             'vs-ditolak'],
            ];
            [$stLabel, $stClass] = $stMap[$calon->status] ?? [ucfirst($calon->status), 'vs-menunggu'];
            ?>
            <span class="v-status <?php echo e($stClass); ?>"><?php echo e($stLabel); ?></span>
        </div>
    </div>
    <div class="v-header-actions">
        <a href="<?php echo e(route('bapsi.verifikasi.index')); ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>


<?php if(session('success')): ?>
<div style="background:var(--green-lt);border:1px solid #86efac;border-radius:9px;padding:11px 16px;
            font-size:13px;color:#15803d;display:flex;align-items:center;gap:8px;margin-bottom:18px">
    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div style="background:var(--red-lt);border:1px solid #fca5a5;border-radius:9px;padding:11px 16px;
            font-size:13px;color:#b91c1c;display:flex;align-items:center;gap:8px;margin-bottom:18px">
    <i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?>

</div>
<?php endif; ?>


<?php if($calon->status === 'ditolak' && $calon->alasan_penolakan): ?>
<div class="penolakan-card">
    <div class="penolakan-header">
        <i class="fas fa-times-circle"></i>
        Data Ini Telah Ditolak
    </div>
    <div class="penolakan-body">
        <div style="font-size:11.5px;font-weight:700;color:var(--red);text-transform:uppercase;letter-spacing:.05em;margin-bottom:7px">
            Alasan Penolakan:
        </div>
        <div class="alasan-box"><?php echo e($calon->alasan_penolakan); ?></div>
        <?php if($calon->catatan): ?>
        <div style="font-size:12px;color:var(--ink-3);padding:8px 12px;background:#fff;border-radius:7px;border:1px solid var(--border)">
            <strong>Catatan:</strong> <?php echo e($calon->catatan); ?>

        </div>
        <?php endif; ?>
        <div class="penolakan-meta" style="margin-top:10px">
            <span><i class="fas fa-user me-1"></i>Oleh: <strong><?php echo e($calon->verifikasi_by ?? 'Admin BAPSI'); ?></strong></span>
            <?php if($calon->verifikasi_at): ?>
            <span><i class="fas fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($calon->verifikasi_at)->format('d M Y, H:i')); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($calon->status === 'lulus_verifikasi'): ?>
<div class="lulus-card">
    <div class="lulus-header">
        <i class="fas fa-check-circle"></i>
        Data Telah Lulus Verifikasi
    </div>
    <div class="lulus-body">
        <div class="lulus-meta">
            <div style="font-size:13px;font-weight:700;margin-bottom:3px">✓ Terverifikasi oleh BAPSI</div>
            <?php if($calon->verifikasi_by): ?>
            <div>Oleh: <strong><?php echo e($calon->verifikasi_by); ?></strong>
                <?php if($calon->verifikasi_at): ?>
                · <?php echo e(\Carbon\Carbon::parse($calon->verifikasi_at)->format('d M Y, H:i')); ?>

                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if($calon->skor_moora): ?>
        <div style="display:flex;gap:20px;text-align:center">
            <div>
                <div style="font-size:11px;color:#166534;font-weight:600;text-transform:uppercase">Skor MOORA</div>
                <div style="font-size:22px;font-weight:800;color:#15803d"><?php echo e(number_format($calon->skor_moora, 4)); ?></div>
            </div>
            <div>
                <div style="font-size:11px;color:#166534;font-weight:600;text-transform:uppercase">Peringkat</div>
                <div style="font-size:22px;font-weight:800;color:#15803d">#<?php echo e($calon->ranking); ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<div class="v-layout">

    
    <div>

        
        <div class="v-card">
            <div class="v-card-header">
                <span class="ico ico-blue"><i class="fas fa-graduation-cap"></i></span>
                Data Akademik
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">NIM</div>
                    <div class="info-value mono"><?php echo e($calon->nim); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tahun Akademik</div>
                    <div class="info-value"><?php echo e($calon->tahun_akademik); ?></div>
                </div>
                <div class="info-item full">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value"><?php echo e($calon->nama); ?></div>
                </div>
                <div class="info-item full">
                    <div class="info-label">Program Studi</div>
                    <div class="info-value"><?php echo e($calon->prodi?->nama ?? '-'); ?>

                        <?php if($calon->prodi?->jenjang): ?>
                        <span style="font-size:11px;color:var(--ink-3);font-weight:400"> · <?php echo e($calon->prodi->jenjang); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item full">
                    <div class="info-label">Asal Sekolah</div>
                    <div class="info-value <?php echo e($calon->asal_sekolah ? '' : 'muted'); ?>">
                        <?php echo e($calon->asal_sekolah ?? 'Tidak diisi'); ?>

                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rapor Semester 3</div>
                    <div class="info-value">
                        <?php $r3 = $calon->nilai_rapor_sem3; $c3 = $r3 >= 85 ? 'rb-high' : ($r3 >= 75 ? 'rb-mid' : 'rb-low'); ?>
                        <?php if($r3): ?>
                        <span class="rapor-badge <?php echo e($c3); ?>"><?php echo e(number_format($r3, 2)); ?></span>
                        <?php else: ?> <span class="muted">—</span> <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rapor Semester 4</div>
                    <div class="info-value">
                        <?php $r4 = $calon->nilai_rapor_sem4; $c4 = $r4 >= 85 ? 'rb-high' : ($r4 >= 75 ? 'rb-mid' : 'rb-low'); ?>
                        <?php if($r4): ?>
                        <span class="rapor-badge <?php echo e($c4); ?>"><?php echo e(number_format($r4, 2)); ?></span>
                        <?php else: ?> <span class="muted">—</span> <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="v-card">
            <div class="v-card-header">
                <span class="ico ico-green"><i class="fas fa-wallet"></i></span>
                Data Ekonomi Orang Tua
            </div>
            <?php
            // Mapping 11 kondisi → label + skor
            $kondisiMap = [
                'tidak_punya_ayah_ibu'         => ['Tidak mempunyai ayah dan ibu', 10],
                'hanya_ayah_sakit'             => ['Hanya ada ayah (ayah sakit)', 10],
                'hanya_ibu_sakit'              => ['Hanya ada ibu (ibu sakit)', 10],
                'ayah_ibu_sakit_tidak_bekerja' => ['Ayah & Ibu keduanya sakit, tidak bekerja', 10],
                'hanya_ayah_sehat'             => ['Hanya ada ayah (ayah sehat)', 5],
                'hanya_ibu_sehat'              => ['Hanya ada ibu (ibu sehat)', 5],
                'ada_ayah_sakit_tidak_bekerja' => ['Ada ayah & ibu, ayah sakit tidak bekerja', 5],
                'ada_ibu_sakit_tidak_bekerja'  => ['Ada ayah & ibu, ibu sakit tidak bekerja', 5],
                'ada_ayah_sakit_pensiun'       => ['Ada ayah & ibu, ayah sakit pensiunan', 0],
                'ada_ibu_sakit_pensiun'        => ['Ada ayah & ibu, ibu sakit pensiunan', 0],
                'ada_ayah_ibu_sehat'           => ['Ada ayah & ibu (keduanya sehat)', 0],
                // Fallback kode lama
                'lengkap'     => ['Orang Tua Lengkap', 0],
                'yatim'       => ['Yatim', 5],
                'piatu'       => ['Piatu', 5],
                'yatim_piatu' => ['Yatim Piatu', 10],
                'cerai'       => ['Cerai', 5],
            ];
            $kode = $calon->kondisi_ortu ?? '';
            [$kondisiLabel, $kondisiSkor] = $kondisiMap[$kode] ?? [ucfirst($kode) ?: '-', 0];
            $kondisiClass = $kondisiSkor >= 10 ? 'kc-10' : ($kondisiSkor >= 5 ? 'kc-5' : 'kc-0');
            ?>
            <div class="info-grid">
                <div class="info-item full">
                    <div class="info-label">Kondisi Orang Tua</div>
                    <div class="info-value" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                        <span class="kondisi-chip <?php echo e($kondisiClass); ?>"><?php echo e($kondisiLabel); ?></span>
                        <span style="font-size:11.5px;color:var(--ink-3)">
                            Skor MOORA:
                            <strong style="color:<?php echo e($kondisiSkor >= 10 ? '#dc2626' : ($kondisiSkor >= 5 ? '#d97706' : '#16a34a')); ?>">
                                <?php echo e($kondisiSkor); ?>

                            </strong>
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Penghasilan OT / Bulan</div>
                    <div class="info-value">
                        Rp <?php echo e(number_format($calon->penghasilan_ortu ?? 0, 0, ',', '.')); ?>

                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tagihan Listrik / Bulan</div>
                    <div class="info-value">
                        Rp <?php echo e(number_format($calon->tagihan_listrik ?? 0, 0, ',', '.')); ?>

                    </div>
                </div>
                <div class="info-item full">
                    <div class="info-label">Tanggungan Anak</div>
                    <div class="info-value" style="display:flex;align-items:center;gap:8px">
                        <span><?php echo e($calon->tanggungan_anak ?? 0); ?> anak</span>
                        <span style="display:flex;gap:3px">
                            <?php for($t = 1; $t <= min($calon->tanggungan_anak ?? 0, 6); $t++): ?>
                            <span style="width:8px;height:8px;border-radius:50%;background:#4e73df;display:inline-block"></span>
                            <?php endfor; ?>
                            <?php for($t = ($calon->tanggungan_anak ?? 0) + 1; $t <= 6; $t++): ?>
                            <span style="width:8px;height:8px;border-radius:50%;background:#e2e8f0;display:inline-block"></span>
                            <?php endfor; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="v-card">
            <div class="v-card-header">
                <span class="ico ico-cyan"><i class="fas fa-paperclip"></i></span>
                Dokumen Pendukung
                <span style="margin-left:auto;font-size:12px;font-weight:400;color:var(--ink-3)">
                    <?php echo e($calon->dokumen->count()); ?> file diunggah
                </span>
            </div>
            <?php if($calon->dokumen->count()): ?>
            <div class="dok-list">
                <?php $__currentLoopData = $calon->dokumen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="dok-item">
                    <div class="dok-left">
                        <div class="dok-icon ok">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div class="dok-name"><?php echo e($dok->label_jenis ?? $dok->jenis); ?></div>
                            <div class="dok-size">
                                <?php echo e($dok->file_name ?? '-'); ?>

                                <?php if($dok->file_size): ?>
                                · <?php echo e(round($dok->file_size / 1024)); ?> KB
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(asset('storage/' . $dok->file_path)); ?>" target="_blank" class="btn-preview">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div style="text-align:center;padding:36px 20px;color:var(--ink-3)">
                <i class="fas fa-folder-open" style="font-size:32px;opacity:.3;display:block;margin-bottom:10px"></i>
                <div style="font-size:13px">Belum ada dokumen diunggah</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div>

        
        <?php if(in_array($calon->status, ['menunggu_verifikasi', 'diproses'])): ?>
        <div class="aksi-card">
            <div class="aksi-header">
                <i class="fas fa-clipboard-check" style="color:var(--blue)"></i>
                Tindakan Verifikasi
            </div>
            <div class="aksi-body">
                
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:11.5px;font-weight:700;color:var(--ink-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">
                        Catatan (opsional)
                    </label>
                    <textarea id="catatanBapsi" rows="3"
                              placeholder="Catatan tambahan untuk BAA..."
                              style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;
                                     font-size:13px;font-family:inherit;resize:vertical;
                                     transition:border-color .15s;outline:none"
                              onfocus="this.style.borderColor='#4e73df'"
                              onblur="this.style.borderColor='#e2e8f0'"></textarea>
                </div>

                
                <form method="POST" action="<?php echo e(route('bapsi.verifikasi.approve', $calon->id)); ?>"
                      id="formSetujui"
                      onsubmit="return konfirmasiSetujui()">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="catatan" id="catatanSetujui">
                    <button type="submit" class="btn-verifikasi btn-setujui" style="width:100%;justify-content:center;margin-bottom:10px">
                        <i class="fas fa-check-circle"></i> Setujui — Lulus Verifikasi
                    </button>
                </form>

                
                <button type="button" class="btn-verifikasi btn-tolak"
                        style="width:100%;justify-content:center"
                        onclick="toggleTolakForm()">
                    <i class="fas fa-times-circle"></i> Tolak Data
                </button>

                
                <div class="tolak-form" id="tolakForm">
                    <form method="POST" action="<?php echo e(route('bapsi.verifikasi.reject', $calon->id)); ?>"
                          onsubmit="return konfirmasiTolak()">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="catatan" id="catatanTolak">
                        <label>Alasan Penolakan <span style="color:var(--red)">*</span></label>
                        <textarea name="alasan_penolakan" id="alasanInput" rows="4" required minlength="10"
                                  placeholder="Jelaskan alasan penolakan secara detail (minimal 10 karakter)..."></textarea>
                        <div style="display:flex;gap:8px;margin-top:10px">
                            <button type="button" onclick="toggleTolakForm()"
                                    style="flex:1;padding:9px;border-radius:7px;border:1.5px solid var(--border);
                                           background:#fff;color:var(--ink-3);font-size:13px;font-weight:600;cursor:pointer">
                                Batal
                            </button>
                            <button type="submit"
                                    style="flex:2;padding:9px;border-radius:7px;background:var(--red);
                                           color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer">
                                <i class="fas fa-times"></i> Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="v-card">
            <div class="v-card-header">
                <span class="ico ico-yellow"><i class="fas fa-info"></i></span>
                Informasi
            </div>
            <div class="v-card-body" style="padding:14px 18px">
                <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                    <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border)">
                        <span style="color:var(--ink-3)">Status</span>
                        <span class="v-status <?php echo e($stClass); ?>" style="padding:2px 10px;font-size:11.5px"><?php echo e($stLabel); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border)">
                        <span style="color:var(--ink-3)">Dokumen</span>
                        <span style="font-weight:700;color:<?php echo e($calon->isDokumenLengkap() ? '#15803d' : '#d97706'); ?>">
                            <?php echo e($calon->isDokumenLengkap() ? '✓ Lengkap' : '⚠ Belum Lengkap'); ?>

                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border)">
                        <span style="color:var(--ink-3)">Semester</span>
                        <span style="font-weight:700"><?php echo e($calon->semester ?? '-'); ?></span>
                    </div>
                    <?php if($calon->skor_moora): ?>
                    <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border)">
                        <span style="color:var(--ink-3)">Skor MOORA</span>
                        <span style="font-weight:700;color:var(--blue)"><?php echo e(number_format($calon->skor_moora, 4)); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--ink-3)">Peringkat</span>
                        <span style="font-weight:700;color:var(--green)">#<?php echo e($calon->ranking); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php
        $jenisDokumenWajib = \App\Models\CalonMahasiswa::jenisDokumenWajib();
        $uploadedJenis = $calon->dokumen->pluck('jenis')->toArray();
        $jenisLabel = [
            'surat_rekomendasi' => 'Surat Rekomendasi',
            'rapor_semester_3'  => 'Rapor Semester 3',
            'rapor_semester_4'  => 'Rapor Semester 4',
            'piagam'            => 'Piagam Penghargaan',
            'kartu_keluarga'    => 'Kartu Keluarga',
            'foto_rumah'        => 'Foto Rumah',
            'sktm'              => 'SKTM',
            'tagihan_listrik'   => 'Tagihan Listrik',
            'surat_penghasilan' => 'Surat Penghasilan OT',
        ];
        ?>
        <div class="v-card">
            <div class="v-card-header">
                <span class="ico ico-cyan"><i class="fas fa-tasks"></i></span>
                Checklist Dokumen
            </div>
            <div style="padding:6px 0">
                <?php $__currentLoopData = $jenisDokumenWajib; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $ada = in_array($jenis, $uploadedJenis); ?>
                <div style="display:flex;align-items:center;gap:10px;padding:9px 16px;
                            border-bottom:1px solid var(--border);font-size:12.5px">
                    <span style="width:20px;height:20px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;
                                 background:<?php echo e($ada ? '#dcfce7' : '#fee2e2'); ?>;
                                 color:<?php echo e($ada ? '#15803d' : '#dc2626'); ?>">
                        <i class="fas fa-<?php echo e($ada ? 'check' : 'times'); ?>"></i>
                    </span>
                    <span style="color:<?php echo e($ada ? 'var(--ink)' : 'var(--ink-3)'); ?>;
                                 <?php echo e(!$ada ? 'text-decoration:none' : ''); ?>">
                        <?php echo e($jenisLabel[$jenis] ?? $jenis); ?>

                    </span>
                    <?php if(!$ada): ?>
                    <span style="margin-left:auto;font-size:10.5px;color:#dc2626;font-weight:600">Belum</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleTolakForm() {
    const form = document.getElementById('tolakForm');
    form.classList.toggle('show');
    if (form.classList.contains('show')) {
        document.getElementById('alasanInput').focus();
    }
}

function konfirmasiSetujui() {
    const catatan = document.getElementById('catatanBapsi').value;
    document.getElementById('catatanSetujui').value = catatan;
    return confirm('Setujui data <?php echo e(addslashes($calon->nama)); ?>?\nData akan berstatus Lulus Verifikasi.');
}

function konfirmasiTolak() {
    const alasan = document.getElementById('alasanInput').value.trim();
    if (alasan.length < 10) {
        alert('Alasan penolakan minimal 10 karakter.');
        return false;
    }
    const catatan = document.getElementById('catatanBapsi').value;
    document.getElementById('catatanTolak').value = catatan;
    return confirm('Tolak data <?php echo e(addslashes($calon->nama)); ?>?\nBAA akan mendapat notifikasi untuk memperbaiki data.');
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/bapsi/verifikasi/show.blade.php ENDPATH**/ ?>