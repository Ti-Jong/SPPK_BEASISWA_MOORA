<?php $__env->startSection('title', 'Input Calon Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Layout & Card ──────────────────────────────── */
.page-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    overflow: visible;
    margin-bottom: 16px;
}

/* ── Notifikasi Penolakan ───────────────────────── */
.notif-card {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(231,74,59,.15);
    border: none;
}
.notif-header {
    background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
    color: #fff;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.notif-header .title {
    font-weight: 700;
    font-size: .85rem;
    display: flex;
    align-items: center;
    gap: 9px;
}
.notif-bell-wrap { position: relative; display: inline-flex; }
.notif-bell-wrap i { font-size: .9rem; }
.notif-pulse {
    position: absolute; top: -2px; right: -2px;
    width: 8px; height: 8px; background: #fff; border-radius: 50%;
    animation: bellPulse 1.5s ease-in-out infinite;
}
@keyframes bellPulse {
    0%,100% { transform: scale(1); opacity: 1; }
    50%      { transform: scale(1.5); opacity: .5; }
}
.notif-toggle-btn {
    width: 28px; height: 28px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.notif-toggle-btn:hover { background: rgba(255,255,255,.25); }
.notif-toggle-btn i { font-size: .75rem; transition: transform .3s ease; }
.notif-toggle-btn i.rotated { transform: rotate(180deg); }
.notif-banner {
    background: #fff5f5; padding: 8px 18px; font-size: .78rem; color: #c0392b;
    border-bottom: 1px solid #fde8e6; display: flex; align-items: center; gap: 7px;
}
.notif-body { overflow: hidden; max-height: 600px; transition: max-height .35s ease, opacity .3s ease; opacity: 1; }
.notif-body.collapsed { max-height: 0; opacity: 0; }
.notif-table { width: 100%; border-collapse: collapse; font-size: .78rem; }
.notif-table th {
    background: #fdf0ee; padding: 8px 12px; font-weight: 700; color: #c0392b;
    border-bottom: 1px solid #fde8e6; white-space: nowrap;
    font-size: .72rem; text-transform: uppercase; letter-spacing: .04em;
}
.notif-table td { padding: 10px 12px; border-bottom: 1px solid #fef5f4; vertical-align: middle; }
.notif-table tr:last-child td { border-bottom: none; }
.notif-table tr:hover td { background: #fffafa; }
.alasan-box {
    display: flex; align-items: flex-start; gap: 6px;
    background: #fff5f5; border: 1px solid #fcd5d0;
    border-left: 3px solid #e74a3b; border-radius: 6px;
    padding: 6px 10px; color: #c0392b; font-size: .75rem; line-height: 1.4;
}

/* ── Filter pills ───────────────────────────────── */
.filter-pill {
    padding: 3px 13px; border-radius: 20px; font-size: .75rem; font-weight: 500;
    border: 1.5px solid #ddd; color: #666; text-decoration: none;
    transition: all .15s; white-space: nowrap; position: relative;
}
.filter-pill:hover { border-color: #4e73df; color: #4e73df; }
.filter-pill.active { background: #4e73df; border-color: #4e73df; color: #fff; }
.filter-pill.active-danger { background: #e74a3b; border-color: #e74a3b; color: #fff; }
.filter-pill .f-count {
    position: absolute; top: -7px; right: -7px;
    background: #e74a3b; color: #fff; border-radius: 50%;
    width: 17px; height: 17px; font-size: .6rem;
    display: flex; align-items: center; justify-content: center; font-weight: 700;
}

/* ── Search Bar ─────────────────────────────────── */
.search-wrap {
    position: relative; display: flex; align-items: center;
}
.search-wrap .search-icon {
    position: absolute; left: 10px; color: #aaa;
    font-size: .75rem; pointer-events: none;
}
.search-input {
    padding: 6px 34px 6px 32px;
    border: 1.5px solid #ddd; border-radius: 8px;
    font-size: .78rem; width: 240px; outline: none;
    transition: border-color .15s; background: #fff;
}
.search-input:focus { border-color: #4e73df; }
.search-clear {
    position: absolute; right: 8px; color: #aaa;
    text-decoration: none; font-size: .8rem; line-height: 1;
}
.search-clear:hover { color: #e74a3b; }
.btn-search {
    padding: 6px 14px; background: #4e73df; color: #fff;
    border: none; border-radius: 8px; font-size: .78rem;
    font-weight: 600; cursor: pointer; transition: background .15s;
    display: inline-flex; align-items: center; gap: 5px;
}
.btn-search:hover { background: #3756c0; }

/* ── Tabel Utama ────────────────────────────────── */
.main-table { width: 100%; border-collapse: collapse; font-size: .75rem; }
.main-table th {
    background: #f8f9fc; padding: 7px 9px;
    font-weight: 600; color: #6c757d;
    border-bottom: 2px solid #eee; white-space: nowrap;
    position: sticky; top: 0; z-index: 1;
}
.main-table td { padding: 6px 9px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
.main-table tr:last-child td { border-bottom: none; }
.main-table tr.row-ditolak td { background: #fff8f8; }
.main-table tr:hover td { background: #fafbff; }
.main-table tr.row-ditolak:hover td { background: #fff0ef; }

/* ── Badge Status ───────────────────────────────── */
.bs { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: .7rem; font-weight: 600; white-space: nowrap; }
.bs-draft        { background: #f0f0f0; color: #666; }
.bs-diajukan     { background: #fef9ec; color: #d4a017; }
.bs-diverifikasi { background: #e8f8f2; color: #1a9e6f; }
.bs-ditolak      { background: #fdf0ee; color: #e74a3b; }
.bs-ditetapkan   { background: #eef0fb; color: #4e73df; }
.badge-rapor     { background: #e3f2fd; color: #1565c0; padding: 2px 7px; border-radius: 12px; font-size: .72rem; font-weight: 600; }
.badge-dok-lengkap  { background: #e8f8f2; color: #1a9e6f; padding: 2px 7px; border-radius: 12px; font-size: .7rem; font-weight: 600; }
.badge-dok-sebagian { background: #fef9ec; color: #d4a017; padding: 2px 7px; border-radius: 12px; font-size: .7rem; font-weight: 600; }
.badge-dok-belum    { background: #f0f0f0; color: #888;    padding: 2px 7px; border-radius: 12px; font-size: .7rem; font-weight: 600; }

/* ── Kondisi OT chip ────────────────────────────── */
.kondisi-chip { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: .68rem; font-weight: 600; white-space: nowrap; }
.kc-yatim-piatu { background: #3d0000; color: #fff; }
.kc-yatim       { background: #6d1a00; color: #fff; }
.kc-piatu       { background: #5c2900; color: #fff; }
.kc-cerai       { background: #6b4c00; color: #fff; }
.kc-lengkap     { background: #e8f8f2; color: #1a9e6f; }
.kc-default     { background: #f0f0f0; color: #555; }

/* ── Btn Icon ───────────────────────────────────── */
.btn-icon { width: 26px; height: 26px; border-radius: 5px; display: inline-flex; align-items: center; justify-content: center; font-size: .75rem; border: 1px solid; cursor: pointer; text-decoration: none; transition: all .12s; }
.btn-icon.view  { color: #3498db; border-color: #3498db; }
.btn-icon.view:hover  { background: #3498db; color: #fff; }
.btn-icon.edit  { color: #f6c23e; border-color: #f6c23e; }
.btn-icon.edit:hover  { background: #f6c23e; color: #fff; }
.btn-icon.del   { color: #e74a3b; border-color: #e74a3b; }
.btn-icon.del:hover   { background: #e74a3b; color: #fff; }
.btn-icon.send  { color: #1a9e6f; border-color: #1a9e6f; }
.btn-icon.send:hover  { background: #1a9e6f; color: #fff; }

/* ── Buttons Header ─────────────────────────────── */
.btn-primary-sm {
    background: #4e73df; color: #fff; border: none;
    border-radius: 7px; padding: 6px 14px; font-size: .78rem;
    font-weight: 600; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
}
.btn-primary-sm:hover { background: #3756c0; color: #fff; }
.btn-template-sm {
    background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0;
    border-radius: 7px; padding: 6px 14px; font-size: .78rem;
    font-weight: 600; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
    transition: background .18s, color .18s, border-color .18s;
}
.btn-template-sm:hover { background: #16a34a; color: #fff; border-color: #16a34a; }
.btn-import-sm {
    background: #eff6ff; color: #2563eb; border: 1.5px solid #bfdbfe;
    border-radius: 7px; padding: 6px 14px; font-size: .78rem;
    font-weight: 600; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
    transition: background .18s, color .18s, border-color .18s;
}
.btn-import-sm:hover { background: #2563eb; color: #fff; border-color: #2563eb; }

/* ── Pagination ─────────────────────────────────── */
.pagi-wrap {
    padding: 12px 18px; border-top: 1px solid #f0f0f0;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px;
}
.pagi-info { font-size: .75rem; color: #888; }
.pagi-info strong { color: #444; }

/* ── Misc ───────────────────────────────────────── */
.alasan-snippet { font-size: .67rem; color: #e74a3b; margin-top: 3px; max-width: 120px; line-height: 1.2; }
</style>

<div class="container-fluid px-3">

    
<div style="display:flex;justify-content:space-between;align-items:flex-start;
            flex-wrap:wrap;gap:12px;margin-bottom:16px;">

    
    <div>
        <h5 style="margin:0 0 3px;font-size:1.05rem;font-weight:800;color:#0f172a;letter-spacing:-.01em">
            Input Calon Mahasiswa
        </h5>
        <p style="margin:0;font-size:.78rem;color:#64748b">
            Daftar calon penerima beasiswa BCH
        </p>
    </div>

    
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">

        
        <form method="GET" action="<?php echo e(route('baa.calon.index')); ?>"
              style="display:flex;align-items:center;gap:6px;" id="formTahun">
            <label style="font-size:.75rem;font-weight:600;color:#64748b;white-space:nowrap">
                <i class="fas fa-calendar-alt" style="color:#3b82f6;margin-right:3px"></i> TA:
            </label>
            <input type="text" name="tahun" value="<?php echo e($tahunAktif); ?>"
                   placeholder="2023/2024" onchange="this.form.submit()"
                   style="width:95px;border:1.5px solid #e2e8f0;border-radius:7px;
                          padding:5px 9px;font-size:.8rem;outline:none;
                          transition:border-color .15s;font-weight:600;color:#0f172a"
                   onfocus="this.style.borderColor='#3b82f6'"
                   onblur="this.style.borderColor='#e2e8f0'">
            <?php if(request('status')): ?>
                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
            <?php endif; ?>
        </form>

        
        <div style="width:1px;height:28px;background:#e2e8f0"></div>

        
        <a href="<?php echo e(route('baa.calon.import.template')); ?>" class="btn-template-sm">
            <i class="fas fa-download"></i> Template CSV
        </a>

        
        <a href="<?php echo e(route('baa.calon.import.index')); ?>" class="btn-import-sm">
            <i class="fas fa-file-excel"></i> Import Excel/CSV
        </a>

        
        <form method="POST" action="<?php echo e(route('baa.calon.kirim.semua')); ?>"
              style="display:inline"
              onsubmit="return confirm('Kirim semua data Draft & Ditolak ke BAPSI sekaligus?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-template-sm"
                    style="background:#f0fdf4;color:#16a34a;border-color:#bbf7d0">
                <i class="fas fa-paper-plane"></i> Kirim Semua ke BAPSI
            </button>
        </form>

        
        <a href="<?php echo e(route('baa.calon.create')); ?>" class="btn-primary-sm">
            <i class="fas fa-plus"></i> Tambah Manual
        </a>

    </div>
</div>

    
<form method="POST" action="<?php echo e(route('baa.calon.kirim.semua')); ?>"
      style="display:inline"
      onsubmit="return confirm('Apakah yakin ingin mengirim semua data Draft & Ditolak ke BAPSI?\n\nSeluruh data yang belum terkirim akan dikirim sekaligus.')">
    <?php echo csrf_field(); ?>
    <button type="submit"
            style="background:#f0fdf4;color:#16a34a;border:1.5px solid #bbf7d0;
                   border-radius:7px;padding:6px 14px;font-size:.78rem;
                   font-weight:600;cursor:pointer;
                   display:inline-flex;align-items:center;gap:5px;
                   transition:background .18s,color .18s,border-color .18s;"
            onmouseover="this.style.background='#16a34a';this.style.color='#fff';this.style.borderColor='#16a34a'"
            onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a';this.style.borderColor='#bbf7d0'">
        <i class="fas fa-paper-plane"></i> Kirim Semua ke BAPSI
    </button>
</form>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show py-2 small">
        <i class="fas fa-check-circle me-1"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2 small">
        <i class="fas fa-times-circle me-1"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <?php if($totalDitolak > 0): ?>
    <div class="notif-card" id="notifCard">
        <div class="notif-header" onclick="toggleNotifPanel()" style="cursor:pointer;user-select:none">
            <div class="title">
                <div class="notif-bell-wrap">
                    <i class="fas fa-bell"></i>
                    <span class="notif-pulse"></span>
                </div>
                <span><?php echo e($totalDitolak); ?> Data Ditolak BAPSI</span>
                <span style="background:rgba(255,255,255,.25);padding:1px 9px;border-radius:20px;font-size:.72rem;font-weight:700;">
                    Perlu Diperbaiki
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <a href="<?php echo e(route('baa.calon.index', ['tahun' => $tahunAktif, 'status' => 'ditolak'])); ?>"
                   onclick="event.stopPropagation()"
                   style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);
                          color:#fff;border-radius:6px;padding:4px 12px;font-size:.73rem;
                          font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                    <i class="fas fa-filter"></i> Filter Ditolak
                </a>
                <div class="notif-toggle-btn" id="notifToggleBtn">
                    <i class="fas fa-chevron-up" id="notifChevron"></i>
                </div>
            </div>
        </div>
        <div class="notif-banner">
            <i class="fas fa-info-circle"></i>
            BAPSI menolak data berikut. Klik <strong>Perbaiki</strong> untuk memperbaiki dan kirim ulang.
        </div>
        <div class="notif-body" id="notifBody">
            <div class="table-responsive" style="overflow-x:auto;border-radius:10px;">
                <table class="notif-table">
                    <thead>
                        <tr>
                            <th style="width:32px">#</th>
                            <th>NIM</th>
                            <th>Nama / Prodi</th>
                            <th>Ditolak Oleh</th>
                            <th>Waktu</th>
                            <th>Alasan Penolakan dari BAPSI</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $calonDitolakList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="color:#aaa;font-size:.72rem;text-align:center"><?php echo e($i + 1); ?></td>
                            <td><strong style="font-family:monospace;font-size:.78rem;letter-spacing:.03em"><?php echo e($c->nim); ?></strong></td>
                            <td>
                                <div style="font-weight:600;font-size:.82rem"><?php echo e($c->nama); ?></div>
                                <div style="font-size:.71rem;color:#888;margin-top:1px"><?php echo e($c->prodi?->nama ?? '-'); ?></div>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f5f5f5;border:1px solid #e8e8e8;padding:3px 9px;border-radius:20px;font-size:.71rem;font-weight:600;color:#555">
                                    <i class="fas fa-user-check" style="color:#e74a3b;font-size:.65rem"></i>
                                    <?php echo e($c->verifikasi_by ?? 'Admin BAPSI'); ?>

                                </span>
                            </td>
                            <td style="white-space:nowrap;font-size:.73rem;color:#888">
                                <?php if($c->verifikasi_at): ?>
                                <div style="font-weight:600;color:#555"><?php echo e(\Carbon\Carbon::parse($c->verifikasi_at)->format('d/m/Y')); ?></div>
                                <div><?php echo e(\Carbon\Carbon::parse($c->verifikasi_at)->format('H:i')); ?></div>
                                <?php else: ?> —
                                <?php endif; ?>
                            </td>
                            <td style="max-width:260px">
                                <?php if($c->alasan_penolakan): ?>
                                <div class="alasan-box">
                                    <i class="fas fa-exclamation-triangle" style="margin-right:5px;flex-shrink:0"></i>
                                    <span><?php echo e($c->alasan_penolakan); ?></span>
                                </div>
                                <?php elseif($c->catatan): ?>
                                <div class="alasan-box"><?php echo e($c->catatan); ?></div>
                                <?php else: ?>
                                <span style="color:#bbb;font-style:italic;font-size:.75rem">Tidak ada keterangan</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;white-space:nowrap">
                                <div style="display:flex;flex-direction:column;gap:5px;align-items:center">
                                    <a href="<?php echo e(route('baa.calon.edit', $c->id)); ?>"
                                       style="display:inline-flex;align-items:center;gap:5px;background:#e74a3b;color:#fff;border-radius:7px;padding:5px 13px;font-size:.73rem;font-weight:700;text-decoration:none;transition:background .15s;width:100%;justify-content:center"
                                       onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74a3b'">
                                        <i class="fas fa-wrench"></i> Perbaiki
                                    </a>
                                    <a href="<?php echo e(route('baa.calon.show', $c->id)); ?>"
                                       style="display:inline-flex;align-items:center;gap:5px;color:#3498db;border:1.5px solid #3498db;border-radius:7px;padding:4px 13px;font-size:.72rem;font-weight:600;text-decoration:none;transition:all .15s;width:100%;justify-content:center"
                                       onmouseover="this.style.background='#3498db';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#3498db'">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php
    $filters = [
        ''             => 'Semua',
        'draft'        => 'Draft',
        'diajukan'     => 'Diajukan',
        'diverifikasi' => 'Diverifikasi',
        'ditolak'      => 'Ditolak',
        'ditetapkan'   => 'Ditetapkan',
    ];
    $statusAktif = request('status', '');
    ?>

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:10px;">

        
        <div style="display:flex;gap:7px;flex-wrap:wrap;align-items:center;">
            <span style="font-size:.75rem;color:#888;font-weight:600;">Filter:</span>
            <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('baa.calon.index', ['tahun' => $tahunAktif, 'status' => $val, 'search' => request('search')])); ?>"
               class="filter-pill <?php echo e($statusAktif === $val && !($val === '' && $statusAktif !== '') ? ($val === 'ditolak' ? 'active-danger' : 'active') : ''); ?>

                      <?php echo e($val === '' && $statusAktif === '' ? 'active' : ''); ?>">
                <?php echo e($label); ?>

                <?php if($val === 'ditolak' && $totalDitolak > 0 && $statusAktif !== 'ditolak'): ?>
                <span class="f-count"><?php echo e($totalDitolak); ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <form method="GET" action="<?php echo e(route('baa.calon.index')); ?>"
              style="display:flex;align-items:center;gap:6px;">
            <input type="hidden" name="tahun" value="<?php echo e($tahunAktif); ?>">
            <?php if(request('status')): ?>
            <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
            <?php endif; ?>
            <div class="search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="search-input"
                       value="<?php echo e(request('search')); ?>"
                       placeholder="Cari NIM atau nama...">
                <?php if(request('search')): ?>
                <a href="<?php echo e(route('baa.calon.index', ['tahun' => $tahunAktif, 'status' => request('status')])); ?>"
                   class="search-clear" title="Hapus pencarian">
                    <i class="fas fa-times"></i>
                </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>

    </div>

    
    <?php if(request('search')): ?>
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;
                padding:7px 14px;margin-bottom:10px;font-size:.78rem;color:#1d4ed8;
                display:flex;align-items:center;gap:8px;">
        <i class="fas fa-search"></i>
        Hasil pencarian untuk <strong>"<?php echo e(request('search')); ?>"</strong>
        — ditemukan <strong><?php echo e($calons->total()); ?></strong> data
        <a href="<?php echo e(route('baa.calon.index', ['tahun' => $tahunAktif, 'status' => request('status')])); ?>"
           style="margin-left:auto;color:#1d4ed8;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:4px;">
            <i class="fas fa-times-circle"></i> Hapus filter
        </a>
    </div>
    <?php endif; ?>

    
    <div class="page-card">

        
        <div style="padding:9px 18px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:.78rem;color:#666;">
                Total <strong style="color:#333;"><?php echo e($calons->total()); ?></strong> calon
                <?php if(request('search')): ?> &nbsp;·&nbsp; Pencarian: <strong>"<?php echo e(request('search')); ?>"</strong> <?php endif; ?>
            </span>
        </div>

        <div class="table-responsive">
            <table class="main-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th style="text-align:center">Rapor S3</th>
                        <th style="text-align:center">Rapor S4</th>
                        <th>Program Studi</th>
                        <th>Kondisi OT</th>
                        <th style="text-align:right">Penghasilan OT</th>
                        <th style="text-align:right">Tagihan Listrik</th>
                        <th style="text-align:center">Tang. Anak</th>
                        <th style="text-align:center">Dokumen</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $rowStyle  = $c->status === 'ditolak' ? 'row-ditolak' : '';
                        $statusDok = method_exists($c, 'statusDokumen') ? $c->statusDokumen() : null;

                        $kondisiChipClass = 'kc-default';
                        if (str_contains($c->kondisi_ortu ?? '', 'yatim_piatu'))     $kondisiChipClass = 'kc-yatim-piatu';
                        elseif (str_contains($c->kondisi_ortu ?? '', 'yatim'))       $kondisiChipClass = 'kc-yatim';
                        elseif (str_contains($c->kondisi_ortu ?? '', 'piatu'))       $kondisiChipClass = 'kc-piatu';
                        elseif (str_contains($c->kondisi_ortu ?? '', 'cerai'))       $kondisiChipClass = 'kc-cerai';
                        elseif (str_contains($c->kondisi_ortu ?? '', 'lengkap'))     $kondisiChipClass = 'kc-lengkap';

                        $kondisiSingkat = match(true) {
                            str_contains($c->kondisi_ortu ?? '', 'yatim_piatu')              => 'Yatim Piatu',
                            str_contains($c->kondisi_ortu ?? '', 'yatim')                    => 'Yatim',
                            str_contains($c->kondisi_ortu ?? '', 'piatu')                    => 'Piatu',
                            str_contains($c->kondisi_ortu ?? '', 'cerai')                    => 'Cerai',
                            str_contains($c->kondisi_ortu ?? '', 'lengkap_keduanya_bekerja') => 'Lengkap (kerja)',
                            str_contains($c->kondisi_ortu ?? '', 'lengkap_salah_satu')       => 'Lengkap (1 kerja)',
                            str_contains($c->kondisi_ortu ?? '', 'lengkap_tidak')            => 'Lengkap (tdk kerja)',
                            default => $c->kondisi_ortu ?? '-',
                        };
                    ?>
                    <tr class="<?php echo e($rowStyle); ?>">
                        <td class="text-muted"><?php echo e($calons->firstItem() + $i); ?></td>
                        <td><strong style="font-family:monospace;font-size:.77rem;"><?php echo e($c->nim); ?></strong></td>
                        <td>
                            <div style="max-width:150px;"><?php echo e($c->nama); ?></div>
                            <?php if($c->status === 'ditolak'): ?>
                            <span class="bs bs-ditolak" style="font-size:.62rem;">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo e($c->asal_sekolah); ?>">
                            <?php echo e($c->asal_sekolah ?? '-'); ?>

                        </td>
                        <td style="text-align:center">
                            <?php if($c->nilai_rapor_sem3): ?>
                                <span class="badge-rapor"><?php echo e(number_format($c->nilai_rapor_sem3, 1)); ?></span>
                            <?php else: ?> <span class="text-muted">-</span> <?php endif; ?>
                        </td>
                        <td style="text-align:center">
                            <?php if($c->nilai_rapor_sem4): ?>
                                <span class="badge-rapor"><?php echo e(number_format($c->nilai_rapor_sem4, 1)); ?></span>
                            <?php else: ?> <span class="text-muted">-</span> <?php endif; ?>
                        </td>
                        <td style="max-width:160px;font-size:.75rem;"><?php echo e($c->prodi->nama ?? '-'); ?></td>
                        <td><span class="kondisi-chip <?php echo e($kondisiChipClass); ?>"><?php echo e($kondisiSingkat); ?></span></td>
                        <td style="text-align:right;white-space:nowrap;">
                            Rp <?php echo e($c->penghasilan_ortu ? number_format($c->penghasilan_ortu, 0, ',', '.') : '-'); ?>

                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            Rp <?php echo e($c->tagihan_listrik ? number_format($c->tagihan_listrik, 0, ',', '.') : '-'); ?>

                        </td>
                        <td style="text-align:center"><?php echo e($c->tanggungan_anak ?? '-'); ?></td>
                        <td style="text-align:center">
                            <?php if($statusDok === 'lengkap'): ?>
                                <span class="badge-dok-lengkap">Lengkap</span>
                            <?php elseif($statusDok === 'sebagian'): ?>
                                <span class="badge-dok-sebagian">Sebagian</span>
                            <?php else: ?>
                                <span class="badge-dok-belum">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center">
                            <?php
                            $bsMap = ['draft'=>'bs-draft','diajukan'=>'bs-diajukan','diverifikasi'=>'bs-diverifikasi','ditolak'=>'bs-ditolak','ditetapkan'=>'bs-ditetapkan'];
                            ?>
                            <span class="bs <?php echo e($bsMap[$c->status] ?? 'bs-draft'); ?>"><?php echo e(ucfirst($c->status)); ?></span>
                            <?php if($c->status === 'ditolak' && $c->alasan_penolakan): ?>
                            <div class="alasan-snippet"><?php echo e(Str::limit($c->alasan_penolakan, 45)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;white-space:nowrap;">
                            <?php if(in_array($c->status, ['draft', 'ditolak'])): ?>
                                <form action="<?php echo e(route('baa.calon.kirim', $c->id)); ?>" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Kirim data <?php echo e(addslashes($c->nama)); ?> ke Admin BAPSI?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-icon send ms-1" title="Kirim ke BAPSI">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                                <a href="<?php echo e(route('baa.calon.edit', $c->id)); ?>" class="btn-icon edit ms-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('baa.calon.destroy', $c->id)); ?>" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus data <?php echo e(addslashes($c->nama)); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-icon del ms-1" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('baa.calon.show', $c->id)); ?>" class="btn-icon view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="14" style="text-align:center;padding:48px;color:#aaa;">
                            <i class="fas fa-inbox fa-2x" style="display:block;margin-bottom:10px;"></i>
                            <?php if(request('search')): ?>
                                Tidak ada data yang cocok dengan pencarian <strong>"<?php echo e(request('search')); ?>"</strong>
                            <?php else: ?>
                                Tidak ada data
                                <?php if(request('status')): ?> dengan status <strong><?php echo e(request('status')); ?></strong> <?php endif; ?>
                                untuk TA <?php echo e($tahunAktif); ?>.
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($calons->hasPages()): ?>
        <div class="pagi-wrap">
            <div class="pagi-info">
                Menampilkan <strong><?php echo e($calons->firstItem()); ?></strong>–<strong><?php echo e($calons->lastItem()); ?></strong>
                dari <strong><?php echo e($calons->total()); ?></strong> data
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <?php if($calons->onFirstPage()): ?>
                    <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:7px;border:1.5px solid #eee;background:#f8f9fc;color:#bbb;font-size:.78rem;font-weight:600;cursor:default">
                        <i class="fas fa-chevron-left" style="font-size:.65rem"></i> Previous
                    </span>
                <?php else: ?>
                    <a href="<?php echo e($calons->previousPageUrl()); ?>&<?php echo e(http_build_query(request()->except('page'))); ?>"
                       style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:7px;border:1.5px solid #e2e8f0;background:#fff;color:#4e73df;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .15s"
                       onmouseover="this.style.background='#4e73df';this.style.color='#fff';this.style.borderColor='#4e73df'"
                       onmouseout="this.style.background='#fff';this.style.color='#4e73df';this.style.borderColor='#e2e8f0'">
                        <i class="fas fa-chevron-left" style="font-size:.65rem"></i> Previous
                    </a>
                <?php endif; ?>

                <?php
                    $current = $calons->currentPage();
                    $last    = $calons->lastPage();
                    $start   = max(1, $current - 2);
                    $end     = min($last, $current + 2);
                ?>

                <?php if($start > 1): ?>
                    <a href="<?php echo e($calons->url(1)); ?>&<?php echo e(http_build_query(request()->except('page'))); ?>"
                       style="display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 6px;border-radius:7px;border:1.5px solid #e2e8f0;background:#fff;color:#4e73df;font-size:.78rem;font-weight:600;text-decoration:none">1</a>
                    <?php if($start > 2): ?><span style="color:#bbb;font-size:.78rem;padding:0 2px">…</span><?php endif; ?>
                <?php endif; ?>

                <?php for($p = $start; $p <= $end; $p++): ?>
                    <?php if($p == $current): ?>
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 6px;border-radius:7px;border:1.5px solid #4e73df;background:#4e73df;color:#fff;font-size:.78rem;font-weight:700"><?php echo e($p); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($calons->url($p)); ?>&<?php echo e(http_build_query(request()->except('page'))); ?>"
                           style="display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 6px;border-radius:7px;border:1.5px solid #e2e8f0;background:#fff;color:#4e73df;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .15s"
                           onmouseover="this.style.background='#4e73df';this.style.color='#fff'"
                           onmouseout="this.style.background='#fff';this.style.color='#4e73df'"><?php echo e($p); ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($end < $last): ?>
                    <?php if($end < $last - 1): ?><span style="color:#bbb;font-size:.78rem;padding:0 2px">…</span><?php endif; ?>
                    <a href="<?php echo e($calons->url($last)); ?>&<?php echo e(http_build_query(request()->except('page'))); ?>"
                       style="display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 6px;border-radius:7px;border:1.5px solid #e2e8f0;background:#fff;color:#4e73df;font-size:.78rem;font-weight:600;text-decoration:none"><?php echo e($last); ?></a>
                <?php endif; ?>

                <?php if($calons->hasMorePages()): ?>
                    <a href="<?php echo e($calons->nextPageUrl()); ?>&<?php echo e(http_build_query(request()->except('page'))); ?>"
                       style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:7px;border:1.5px solid #e2e8f0;background:#fff;color:#4e73df;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .15s"
                       onmouseover="this.style.background='#4e73df';this.style.color='#fff';this.style.borderColor='#4e73df'"
                       onmouseout="this.style.background='#fff';this.style.color='#4e73df';this.style.borderColor='#e2e8f0'">
                        Next <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
                    </a>
                <?php else: ?>
                    <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:7px;border:1.5px solid #eee;background:#f8f9fc;color:#bbb;font-size:.78rem;font-weight:600;cursor:default">
                        Next <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleNotifPanel() {
    const body    = document.getElementById('notifBody');
    const chevron = document.getElementById('notifChevron');
    if (!body) return;
    const isOpen = !body.classList.contains('collapsed');
    if (isOpen) {
        body.classList.add('collapsed');
        chevron.classList.add('rotated');
    } else {
        body.classList.remove('collapsed');
        chevron.classList.remove('rotated');
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/baa/calon/index.blade.php ENDPATH**/ ?>