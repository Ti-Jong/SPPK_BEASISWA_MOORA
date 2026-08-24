


<?php $__env->startSection('title', 'Dashboard BAA — SPK Beasiswa Cerdas Humanis'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════
   DESIGN SYSTEM — BAA Dashboard
   Tone: Institutional-modern, clean authority
   ════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }

:root {
    --font-main : 'Plus Jakarta Sans', sans-serif;
    --font-mono : 'JetBrains Mono', monospace;

    /* Core palette */
    --ink       : #0f172a;
    --ink-2     : #334155;
    --ink-3     : #64748b;
    --ink-4     : #94a3b8;
    --line      : #e2e8f0;
    --surface   : #f8fafc;
    --white     : #ffffff;

    /* Accent */
    --blue      : #3b82f6;
    --blue-dk   : #1d4ed8;
    --blue-lt   : #eff6ff;
    --blue-ring : rgba(59,130,246,.15);

    /* Status */
    --yellow    : #f59e0b;
    --yellow-lt : #fffbeb;
    --green     : #16a34a;
    --green-lt  : #f0fdf4;
    --red       : #dc2626;
    --red-lt    : #fef2f2;
    --cyan      : #0891b2;
    --cyan-lt   : #ecfeff;
    --purple    : #7c3aed;
    --purple-lt : #f5f3ff;
    --orange    : #ea580c;
    --orange-lt : #fff7ed;

    /* Shadows */
    --shadow-sm : 0 1px 2px rgba(0,0,0,.05);
    --shadow    : 0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.04);
    --shadow-md : 0 4px 16px rgba(0,0,0,.09), 0 2px 6px rgba(0,0,0,.06);
}

body { font-family: var(--font-main); color: var(--ink); background: var(--surface); }

/* ── Topbar ── */
.dash-topbar {
    background: var(--white);
    border-bottom: 1px solid var(--line);
    padding: 20px 32px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.dash-title  { font-size: 20px; font-weight: 800; color: var(--ink); margin: 0 0 3px; letter-spacing: -.02em; }
.dash-sub    { font-size: 13px; color: var(--ink-3); margin: 0; }
.topbar-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.topbar-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600; letter-spacing: .01em;
}
.topbar-pill.date  { background: var(--blue-lt);   color: var(--blue-dk); }
.topbar-pill.user  { background: var(--surface);    color: var(--ink-2); border: 1px solid var(--line); }
.topbar-pill svg   { width: 13px; height: 13px; }

/* ── Stat Cards ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    padding: 24px 0 0;
}
.stat-card {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
}
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
}
.stat-card.total    ::before { background: var(--blue);   }
.stat-card.draft    ::before { background: var(--ink-4);  }
.stat-card.menunggu ::before { background: var(--yellow); }
.stat-card.lulus    ::before { background: var(--green);  }
.stat-card.ditolak  ::before { background: var(--red);    }

.stat-icon {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
}
.stat-icon svg { width: 19px; height: 19px; }
.stat-icon.total    { background: var(--blue-lt);   color: var(--blue); }
.stat-icon.draft    { background: var(--surface);   color: var(--ink-3); }
.stat-icon.menunggu { background: var(--yellow-lt); color: var(--yellow); }
.stat-icon.lulus    { background: var(--green-lt);  color: var(--green); }
.stat-icon.ditolak  { background: var(--red-lt);    color: var(--red); }

.stat-num   { font-size: 28px; font-weight: 800; color: var(--ink); line-height: 1; letter-spacing: -.03em; }
.stat-label { font-size: 11.5px; font-weight: 600; color: var(--ink-3); text-transform: uppercase; letter-spacing: .07em; }

/* ── Notif Penolakan ── */
.notif-box {
    margin: 20px 0 0;
    background: var(--red-lt);
    border: 1px solid #fca5a5;
    border-radius: 12px;
    overflow: hidden;
}
.notif-header {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px;
    background: rgba(220,38,38,.06);
    border-bottom: 1px solid #fca5a5;
    cursor: pointer;
    user-select: none;
}
.notif-header-title {
    font-size: 13.5px; font-weight: 700; color: var(--red); flex: 1;
}
.notif-header svg { width: 17px; height: 17px; color: var(--red); flex-shrink: 0; }
.notif-badge {
    background: var(--red); color: white;
    font-size: 11px; font-weight: 700;
    padding: 2px 8px; border-radius: 10px;
    animation: pulse-red 2s ease-in-out infinite;
}
@keyframes pulse-red {
    0%,100% { box-shadow: 0 0 0 0 rgba(220,38,38,.4); }
    50%      { box-shadow: 0 0 0 5px rgba(220,38,38,0); }
}
.notif-chevron {
    transition: transform .25s;
    color: var(--red);
}
.notif-chevron.open { transform: rotate(180deg); }

.notif-list { padding: 6px 0; display: none; }
.notif-list.open { display: block; }

.notif-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 14px;
    align-items: start;
    padding: 13px 18px;
    border-bottom: 1px solid rgba(220,38,38,.1);
}
.notif-item:last-child { border-bottom: none; }
.notif-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--red); color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; flex-shrink: 0;
}
.notif-nama  { font-size: 13.5px; font-weight: 700; color: var(--ink); }
.notif-nim   { font-size: 11.5px; color: var(--ink-3); font-family: var(--font-mono); }
.notif-alasan {
    font-size: 12.5px; color: #7f1d1d;
    margin-top: 5px; line-height: 1.5;
    background: rgba(220,38,38,.07);
    padding: 6px 10px; border-radius: 6px;
    border-left: 3px solid var(--red);
}
.notif-meta { font-size: 11px; color: var(--ink-4); margin-top: 4px; }
.btn-perbaiki {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 13px; border-radius: 7px;
    background: var(--red); color: white;
    font-size: 12px; font-weight: 700;
    text-decoration: none;
    transition: background .18s;
    white-space: nowrap;
    align-self: center;
}
.btn-perbaiki:hover { background: #b91c1c; }
.btn-perbaiki svg { width: 13px; height: 13px; }

/* ── Section Header ── */
.section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px 0 12px;
    flex-wrap: wrap; gap: 12px;
}
.section-title {
    font-size: 15px; font-weight: 800; color: var(--ink); letter-spacing: -.01em;
}
.section-sub { font-size: 12.5px; color: var(--ink-3); margin-top: 2px; }
.btn-add {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 8px;
    background: var(--blue); color: white;
    font-size: 13px; font-weight: 700;
    text-decoration: none;
    transition: background .18s;
}
.btn-add:hover { background: var(--blue-dk); }
.btn-add svg { width: 15px; height: 15px; }

/* ── Table ── */
.table-wrap {
    margin: 0 0 40px;
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
}
.table-toolbar {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 16px;
    border-bottom: 1px solid var(--line);
    background: #fafbff;
    flex-wrap: wrap;
}
.table-count {
    font-size: 12.5px; color: var(--ink-3); flex: 1;
}
.tbl-search {
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 7px;
    font-size: 13px; font-family: var(--font-main);
    color: var(--ink);
    background: var(--white);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    width: 220px;
}
.tbl-search:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-ring); }

/* Scrollable table */
.table-scroll { overflow-x: auto; }
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1200px; /* pastikan kolom tidak terpotong */
}
thead th {
    padding: 10px 12px;
    background: var(--surface);
    border-bottom: 1px solid var(--line);
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--ink-3);
    white-space: nowrap;
    text-align: left;
    position: sticky; top: 0; z-index: 1;
}
tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fafbff; }
tbody td { padding: 12px 12px; vertical-align: middle; }

/* ── Cell Styles ── */
.cell-no    { color: var(--ink-4); font-size: 11.5px; text-align: center; width: 36px; }
.cell-nim   { font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; color: var(--ink-3); white-space: nowrap; }
.cell-nama  { font-weight: 700; color: var(--ink); white-space: nowrap; }
.cell-sekolah { font-size: 12px; color: var(--ink-3); max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cell-prodi {
    font-size: 11.5px; color: var(--ink-2);
    background: var(--blue-lt); color: var(--blue-dk);
    padding: 3px 8px; border-radius: 5px;
    display: inline-block; white-space: nowrap; font-weight: 600;
}
.cell-rapor {
    font-family: var(--font-mono); font-size: 13px; font-weight: 700;
    text-align: center;
}
.cell-rapor.high { color: var(--green); }
.cell-rapor.mid  { color: var(--yellow); }
.cell-rapor.low  { color: var(--red); }

/* ── Ekonomi Cells ── */
.cell-penghasilan {
    font-family: var(--font-mono); font-size: 12px; font-weight: 600;
    white-space: nowrap;
}
.cell-penghasilan.rendah  { color: var(--red); }
.cell-penghasilan.sedang  { color: var(--yellow); }
.cell-penghasilan.tinggi  { color: var(--green); }

.cell-listrik {
    font-family: var(--font-mono); font-size: 12px; font-weight: 600;
    white-space: nowrap;
    color: var(--cyan);
}

.cell-tanggungan {
    text-align: center;
    font-size: 13px; font-weight: 700; color: var(--purple);
}
.tang-bar {
    display: flex; gap: 2px; justify-content: center; margin-top: 3px;
}
.tang-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--purple); opacity: .25;
}
.tang-dot.filled { opacity: 1; }

/* Kondisi OT chips — solid seperti di halaman index calon */
.chip-kondisi {
    display: inline-flex; align-items: center;
    padding: 4px 10px; border-radius: 6px;
    font-size: 12px; font-weight: 700;
    white-space: nowrap; letter-spacing: .01em;
}
.chip-kondisi.lengkap    { background: #dcfce7; color: #166534; }
.chip-kondisi.yatim      { background: #7f1d1d; color: #fff; }
.chip-kondisi.piatu      { background: #ea580c; color: #fff; }
.chip-kondisi.yatim_piatu{ background: #450a0a; color: #fca5a5; }
.chip-kondisi.cerai      { background: #ede9fe; color: #5b21b6; }

/* Status badge */
.badge-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 700; white-space: nowrap;
}
.badge-status::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: currentColor; flex-shrink: 0;
}
.badge-status.draft               { background: #f1f5f9; color: var(--ink-3); }
.badge-status.menunggu_verifikasi { background: var(--yellow-lt); color: #b45309; }
.badge-status.diproses            { background: var(--cyan-lt);   color: #0e7490; }
.badge-status.lulus_verifikasi    { background: var(--green-lt);  color: #15803d; }
.badge-status.ditolak             { background: var(--red-lt);    color: var(--red); }

/* Action */
.cell-action {
    display: flex; gap: 4px; align-items: center;
    flex-wrap: wrap;
}
.btn-sm {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 10px; border-radius: 6px;
    font-size: 11.5px; font-weight: 700; font-family: var(--font-main);
    text-decoration: none; border: none; cursor: pointer;
    transition: all .15s; white-space: nowrap;
}
.btn-sm.view  { background: var(--blue-lt);  color: var(--blue-dk); }
.btn-sm.view:hover  { background: var(--blue); color: white; }
.btn-sm.edit  { background: var(--surface);  color: var(--ink-2); border: 1px solid var(--line); }
.btn-sm.edit:hover  { background: var(--line); }
.btn-sm.kirim { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.btn-sm.kirim:hover { background: var(--green); color: white; border-color: var(--green); }
.btn-sm.hapus { background: var(--red-lt); color: var(--red); border: 1px solid #fca5a5; }
.btn-sm.hapus:hover { background: var(--red); color: white; border-color: var(--red); }
.btn-sm svg { width: 12px; height: 12px; }

/* ── Responsive ── */
@media (max-width: 1100px) {
    .stats-grid { grid-template-columns: repeat(3, 1fr); }
    .dash-topbar { padding: 16px 20px; }
}
@media (max-width: 640px) {
    .stats-grid  { grid-template-columns: 1fr 1fr; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="dash-topbar">
    <div>
        <h1 class="dash-title">Dashboard BAA</h1>
        <p class="dash-sub">Biro Administrasi Akademik — SPK Beasiswa Cerdas Humanis</p>
    </div>
    <div class="topbar-meta">
        <span class="topbar-pill date">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
            <?php echo e(now()->translatedFormat('l, d F Y')); ?>

        </span>
        <span class="topbar-pill user">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
            <?php echo e(Auth::user()->name); ?>

        </span>
    </div>
</div>


<div class="stats-grid">
    
    <div class="stat-card total">
        <div class="stat-icon total">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
        </div>
        <div class="stat-num"><?php echo e($stats['total']); ?></div>
        <div class="stat-label">Total Calon</div>
    </div>
    
    <div class="stat-card draft">
        <div class="stat-icon draft">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
        </div>
        <div class="stat-num"><?php echo e($stats['draft']); ?></div>
        <div class="stat-label">Draft</div>
    </div>
    
    <div class="stat-card menunggu">
        <div class="stat-icon menunggu">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
        </div>
        <div class="stat-num"><?php echo e($stats['menunggu_verifikasi']); ?></div>
        <div class="stat-label">Menunggu Verifikasi</div>
    </div>
    
    <div class="stat-card lulus">
        <div class="stat-icon lulus">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        </div>
        <div class="stat-num"><?php echo e($stats['lulus_verifikasi']); ?></div>
        <div class="stat-label">Lulus Verifikasi</div>
    </div>
    
    <div class="stat-card ditolak">
        <div class="stat-icon ditolak">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        </div>
        <div class="stat-num"><?php echo e($stats['ditolak']); ?></div>
        <div class="stat-label">Ditolak BAPSI</div>
    </div>
</div>


<?php if($ditolakList->isNotEmpty()): ?>
<div class="notif-box" style="margin-top:20px">
    <div class="notif-header" onclick="toggleNotif()">
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span class="notif-header-title">
            Data Ditolak BAPSI — Perlu Diperbaiki
        </span>
        <span class="notif-badge"><?php echo e($ditolakList->count()); ?></span>
        <svg class="notif-chevron open" id="notifChevron" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div class="notif-list open" id="notifList">
        <?php $__currentLoopData = $ditolakList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dtl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="notif-item">
            <div class="notif-avatar"><?php echo e(strtoupper(substr($dtl->nama, 0, 1))); ?></div>
            <div>
                <div class="notif-nama"><?php echo e($dtl->nama); ?></div>
                <div class="notif-nim"><?php echo e($dtl->nim); ?> · <?php echo e($dtl->prodi?->nama ?? '-'); ?></div>
                <div class="notif-alasan">
                    <strong>Alasan:</strong> <?php echo e($dtl->alasan_penolakan); ?>

                </div>
                <?php if($dtl->verifikasi_by && $dtl->verifikasi_at): ?>
                <div class="notif-meta">
                    Ditolak oleh <?php echo e($dtl->verifikasi_by); ?>

                    · <?php echo e(\Carbon\Carbon::parse($dtl->verifikasi_at)->diffForHumans()); ?>

                </div>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('baa.calon.edit', $dtl->id)); ?>" class="btn-perbaiki">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                Perbaiki
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="section-header">
    <div>
        <div class="section-title">Data Calon Penerima Beasiswa</div>
        <div class="section-sub">Menampilkan semua calon yang sudah dikirim ke BAPSI</div>
    </div>
    <a href="<?php echo e(route('baa.calon.create')); ?>" class="btn-add">
        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Tambah Calon
    </a>
</div>

    <div class="table-wrap">
    
   
<div class="table-toolbar">
    <div class="table-count">
        Total <strong><?php echo e($calons->count()); ?></strong> calon terdaftar
    </div>
    <input type="text"
           class="tbl-search"
           placeholder="Cari NIM atau nama..."
           id="tableSearch"
           onkeyup="cariData(this.value)">
</div>

    
    <div class="table-scroll">
        <table id="mainTable">
            <thead>
                <tr>
                    <th style="width:36px;text-align:center">#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Asal Sekolah</th>
                    <th style="text-align:center">Rapor S3</th>
                    <th style="text-align:center">Rapor S4</th>
                    <th>Program Studi</th>
                    <th>Kondisi OT</th>
                    <th>Penghasilan OT</th>
                    <th>Tagihan Listrik</th>
                    <th style="text-align:center">Tang. Anak</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php $__empty_1 = true; $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // Warna nilai rapor
                    $r3Class = $c->nilai_rapor_sem3 >= 85 ? 'high' : ($c->nilai_rapor_sem3 >= 75 ? 'mid' : 'low');
                    $r4Class = $c->nilai_rapor_sem4 >= 85 ? 'high' : ($c->nilai_rapor_sem4 >= 75 ? 'mid' : 'low');

                    // Warna penghasilan (< 1jt = rendah, 1-3jt = sedang, >3jt = tinggi)
                    $phClass = $c->penghasilan_ortu < 1000000 ? 'rendah'
                             : ($c->penghasilan_ortu <= 3000000 ? 'sedang' : 'tinggi');

                    // Kondisi OT
                    $kondisiMap = [
                        'lengkap'     => ['label' => 'Lengkap',     'class' => 'lengkap'],
                        'yatim'       => ['label' => 'Yatim',       'class' => 'yatim'],
                        'piatu'       => ['label' => 'Piatu',       'class' => 'piatu'],
                        'yatim_piatu' => ['label' => 'Yatim Piatu', 'class' => 'yatim_piatu'],
                        'cerai'       => ['label' => 'Cerai/Pisah', 'class' => 'cerai'],
                    ];
                    $kondisi = $kondisiMap[$c->kondisi_ortu] ?? ['label' => ucfirst($c->kondisi_ortu ?? '-'), 'class' => 'lengkap'];

                    // Status label
                    $statusLabels = [
                        'draft'               => 'Draft',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diproses'            => 'Diproses',
                        'lulus_verifikasi'    => 'Lulus Verifikasi',
                        'ditolak'             => 'Ditolak',
                    ];
                ?>
                <tr data-search="<?php echo e(strtolower($c->nim . ' ' . $c->nama)); ?>">

                    
                    <td class="cell-no"><?php echo e($i + 1); ?></td>

                    
                    <td class="cell-nim"><?php echo e($c->nim); ?></td>

                    
                    <td>
                        <div class="cell-nama"><?php echo e($c->nama); ?></div>
                        <?php if($c->status === 'ditolak'): ?>
                        <div style="margin-top:3px">
                            <span class="badge-status ditolak" style="font-size:10px;padding:2px 7px">Ditolak</span>
                        </div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <div class="cell-sekolah" title="<?php echo e($c->asal_sekolah); ?>">
                            <?php echo e($c->asal_sekolah); ?>

                        </div>
                    </td>

                    
                    <td class="cell-rapor <?php echo e($r3Class); ?>" style="text-align:center">
                        <?php echo e(number_format($c->nilai_rapor_sem3, 1)); ?>

                    </td>

                    
                    <td class="cell-rapor <?php echo e($r4Class); ?>" style="text-align:center">
                        <?php echo e(number_format($c->nilai_rapor_sem4, 1)); ?>

                    </td>

                    
                    <td>
                        <div style="font-size:13px;color:var(--ink-2);line-height:1.4">
                            <?php echo e($c->prodi?->nama ?? '-'); ?>

                        </div>
                        <?php if($c->prodi?->jenjang): ?>
                        <div style="font-size:10.5px;color:var(--ink-4);margin-top:1px"><?php echo e($c->prodi->jenjang); ?></div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <span class="chip-kondisi <?php echo e($kondisi['class']); ?>">
                            <?php echo e($kondisi['label']); ?>

                        </span>
                    </td>

                    
                    <td>
                        <div class="cell-penghasilan <?php echo e($phClass); ?>">
                            Rp <?php echo e(number_format($c->penghasilan_ortu, 0, ',', '.')); ?>

                        </div>
                    </td>

                    
                    <td>
                        <div class="cell-listrik">
                            Rp <?php echo e(number_format($c->tagihan_listrik, 0, ',', '.')); ?>

                        </div>
                    </td>

                    
                    <td style="text-align:center">
                        <div style="font-size:15px;font-weight:800;color:var(--purple);line-height:1">
                            <?php echo e($c->tanggungan_anak); ?>

                        </div>
                        <div class="tang-bar">
                            <?php for($t = 1; $t <= max(min($c->tanggungan_anak, 6), 1); $t++): ?>
                            <div class="tang-dot <?php echo e($t <= $c->tanggungan_anak ? 'filled' : ''); ?>"></div>
                            <?php endfor; ?>
                            <?php for($t = max($c->tanggungan_anak, 1) + 1; $t <= 6; $t++): ?>
                            <div class="tang-dot"></div>
                            <?php endfor; ?>
                        </div>
                    </td>

                    
                    <td>
                        <span class="badge-status <?php echo e($c->status); ?>">
                            <?php echo e($statusLabels[$c->status] ?? ucfirst($c->status)); ?>

                        </span>
                        <?php if($c->status === 'ditolak' && $c->alasan_penolakan): ?>
                        <div style="font-size:10px;color:var(--red);margin-top:4px;max-width:130px;
                                    overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                             title="<?php echo e($c->alasan_penolakan); ?>">
                            ⚠ <?php echo e(Str::limit($c->alasan_penolakan, 30)); ?>

                        </div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <div class="cell-action">
                            <?php if(in_array($c->status, ['draft', 'ditolak'])): ?>
                                

                                
                                <form method="POST"
                                      action="<?php echo e(route('baa.calon.kirim', $c->id)); ?>"
                                      onsubmit="return confirm('Kirim data <?php echo e(addslashes($c->nama)); ?> ke Admin BAPSI?')"
                                      style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-sm kirim">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l.003-.002 4.252-1.68a1 1 0 00.57-.97V8.5a1 1 0 112 0v6.81a1 1 0 00.57.97l4.252 1.68.003.002a1 1 0 001.17-1.409l-7-14z"/>
                                        </svg>
                                        Kirim
                                    </button>
                                </form>

                                
                                <a href="<?php echo e(route('baa.calon.edit', $c->id)); ?>" class="btn-sm edit">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    Edit
                                </a>

                                
                                <form method="POST"
                                      action="<?php echo e(route('baa.calon.destroy', $c->id)); ?>"
                                      onsubmit="return confirm('Hapus data <?php echo e(addslashes($c->nama)); ?>? Tindakan ini tidak dapat dibatalkan.')"
                                      style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-sm hapus">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>

                            <?php else: ?>
                                
                                <a href="<?php echo e(route('baa.calon.show', $c->id)); ?>" class="btn-sm view">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Detail
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="13" style="text-align:center;padding:60px 20px;color:var(--ink-3)">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="width:48px;height:48px;opacity:.2;margin:0 auto 12px;display:block">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <div style="font-size:15px;font-weight:700;color:var(--ink);margin-bottom:6px">Belum ada data calon</div>
                        <div style="font-size:13px">Tambahkan calon pertama melalui tombol <strong>Tambah Calon</strong> di atas.</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo e(asset('js/baa-dashboard.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/baa/dashboard.blade.php ENDPATH**/ ?>