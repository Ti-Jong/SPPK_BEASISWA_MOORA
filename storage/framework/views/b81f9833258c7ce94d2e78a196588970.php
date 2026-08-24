


<?php $__env->startSection('title', 'Import Data Calon — SPK Beasiswa'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; }
:root {
    --font   : 'Plus Jakarta Sans', sans-serif;
    --mono   : 'JetBrains Mono', monospace;
    --ink    : #0f172a;
    --ink-2  : #334155;
    --ink-3  : #64748b;
    --ink-4  : #94a3b8;
    --line   : #e2e8f0;
    --surface: #f8fafc;
    --white  : #ffffff;
    --blue   : #3b82f6;
    --blue-dk: #1d4ed8;
    --blue-lt: #eff6ff;
    --green  : #16a34a;
    --green-lt:#f0fdf4;
    --red    : #dc2626;
    --red-lt : #fef2f2;
    --yellow : #f59e0b;
    --yellow-lt:#fffbeb;
    --shadow : 0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.04);
    --shadow-md: 0 4px 20px rgba(0,0,0,.1);
}
body { font-family: var(--font); color: var(--ink); background: var(--surface); }

/* ── Page header ── */
.page-header {
    background: var(--white);
    border-bottom: 1px solid var(--line);
    padding: 22px 32px;
}
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--ink-3); margin-bottom: 10px;
}
.breadcrumb a { color: var(--ink-3); text-decoration: none; }
.breadcrumb a:hover { color: var(--blue); }
.breadcrumb span { color: #cbd5e1; }
.page-title { font-size: 20px; font-weight: 800; color: var(--ink); margin: 0 0 3px; }
.page-sub   { font-size: 13px; color: var(--ink-3); margin: 0; }

/* ── Layout 2 kolom ── */
.import-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    padding: 28px 32px 48px;
    max-width: 1200px;
    margin: 0 auto;
}

/* ── Card ── */
.card {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 14px;
    box-shadow: var(--shadow);
    overflow: hidden;
}
.card-header {
    padding: 18px 22px 16px;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; gap: 10px;
}
.card-header-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.card-header-icon svg { width: 18px; height: 18px; }
.card-header-icon.blue   { background: var(--blue-lt);  color: var(--blue); }
.card-header-icon.green  { background: var(--green-lt); color: var(--green); }
.card-header-icon.yellow { background: var(--yellow-lt);color: var(--yellow); }
.card-title { font-size: 14px; font-weight: 800; color: var(--ink); }
.card-sub   { font-size: 12px; color: var(--ink-3); margin-top: 1px; }
.card-body  { padding: 22px; }

/* ── Dropzone ── */
.dropzone {
    border: 2px dashed var(--line);
    border-radius: 12px;
    padding: 40px 24px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: var(--surface);
    position: relative;
}
.dropzone:hover, .dropzone.drag-over {
    border-color: var(--blue);
    background: var(--blue-lt);
}
.dropzone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.dropzone-icon {
    width: 52px; height: 52px; margin: 0 auto 14px;
    background: var(--blue-lt); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}
.dropzone-icon svg { width: 26px; height: 26px; color: var(--blue); }
.dropzone-title { font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 5px; }
.dropzone-sub   { font-size: 13px; color: var(--ink-3); margin-bottom: 12px; }
.dropzone-badge {
    display: inline-flex; gap: 6px; align-items: center;
    font-size: 11.5px; font-weight: 600; color: var(--ink-3);
}
.file-tag {
    padding: 3px 8px; border-radius: 5px;
    background: var(--line); font-weight: 700; font-size: 11px;
    font-family: var(--mono); letter-spacing: .05em;
}

/* File preview setelah dipilih */
.file-preview {
    display: none;
    margin-top: 14px;
    background: var(--green-lt);
    border: 1px solid #bbf7d0;
    border-radius: 9px;
    padding: 12px 14px;
    text-align: left;
    align-items: center;
    gap: 10px;
}
.file-preview.show { display: flex; }
.file-preview-icon { color: var(--green); flex-shrink: 0; }
.file-preview-icon svg { width: 20px; height: 20px; }
.file-preview-name { font-size: 13px; font-weight: 700; color: var(--green); flex: 1; word-break: break-all; }
.file-preview-size { font-size: 11.5px; color: var(--ink-3); }
.file-preview-remove {
    background: none; border: none; cursor: pointer;
    color: var(--ink-3); padding: 2px;
    flex-shrink: 0;
}
.file-preview-remove:hover { color: var(--red); }
.file-preview-remove svg { width: 16px; height: 16px; }

/* ── Form fields ── */
.form-group { margin-bottom: 18px; }
.form-group:last-child { margin-bottom: 0; }
.form-label {
    display: block; font-size: 11.5px; font-weight: 700;
    color: var(--ink-2); text-transform: uppercase;
    letter-spacing: .07em; margin-bottom: 7px;
}
.form-label .req { color: var(--red); margin-left: 2px; }
.form-input, .form-select {
    width: 100%; padding: 10px 13px;
    border: 1px solid var(--line); border-radius: 8px;
    font-size: 13.5px; font-family: var(--font);
    color: var(--ink); background: var(--surface);
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.form-input:focus, .form-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    background: var(--white);
}

/* ── Btn Submit ── */
.btn-import {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 13px;
    background: var(--blue); color: white;
    font-size: 14px; font-weight: 700; font-family: var(--font);
    border: none; border-radius: 9px; cursor: pointer;
    transition: background .18s, transform .1s;
    margin-top: 22px;
}
.btn-import:hover { background: var(--blue-dk); }
.btn-import:active { transform: scale(.99); }
.btn-import svg { width: 18px; height: 18px; }
.btn-import:disabled { opacity: .6; cursor: not-allowed; }

/* ── Sidebar kanan ── */
.sidebar-right { display: flex; flex-direction: column; gap: 20px; }

/* Template download card */
.template-card {
    background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);
    border-radius: 14px;
    padding: 22px;
    color: white;
}
.template-card-title {
    font-size: 15px; font-weight: 800; margin-bottom: 6px;
}
.template-card-sub {
    font-size: 12.5px; opacity: .75; margin-bottom: 18px; line-height: 1.5;
}
.btn-download {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px; border-radius: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: white; font-size: 13px; font-weight: 700;
    text-decoration: none; cursor: pointer;
    font-family: var(--font);
    transition: background .18s;
    width: 100%; justify-content: center;
}
.btn-download:hover { background: rgba(255,255,255,.25); }
.btn-download svg { width: 16px; height: 16px; }

/* ── Panduan kolom ── */
.kolom-list { list-style: none; padding: 0; margin: 0; }
.kolom-list li {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 9px 0; border-bottom: 1px solid var(--line);
    font-size: 12.5px;
}
.kolom-list li:last-child { border-bottom: none; }
.kolom-name {
    font-family: var(--mono); font-size: 11px; font-weight: 600;
    background: var(--blue-lt); color: var(--blue-dk);
    padding: 2px 7px; border-radius: 4px;
    white-space: nowrap; flex-shrink: 0;
}
.kolom-desc { color: var(--ink-3); line-height: 1.45; }
.kolom-req  { color: var(--red); font-size: 10px; font-weight: 700; margin-left: 3px; }

/* ── Kondisi OT chips kecil ── */
.kondisi-chips { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 4px; }
.kondisi-chip {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 4px; font-family: var(--mono);
}
.kondisi-chip.a { background: #dcfce7; color: #166534; }
.kondisi-chip.b { background: #fee2e2; color: #991b1b; }
.kondisi-chip.c { background: #ffedd5; color: #9a3412; }
.kondisi-chip.d { background: #fef9c3; color: #854d0e; }
.kondisi-chip.e { background: #ede9fe; color: #5b21b6; }

/* ── Alert / Result box ── */
.alert {
    border-radius: 10px; padding: 14px 16px;
    font-size: 13.5px; margin-bottom: 24px;
    display: flex; gap: 12px; align-items: flex-start;
}
.alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
.alert.success { background: var(--green-lt); border: 1px solid #bbf7d0; color: #14532d; }
.alert.error   { background: var(--red-lt);   border: 1px solid #fca5a5; color: #7f1d1d; }
.alert.warning { background: var(--yellow-lt);border: 1px solid #fde68a; color: #78350f; }

/* ── Hasil import table ── */
.result-card {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 14px;
    box-shadow: var(--shadow);
    margin-bottom: 24px;
    overflow: hidden;
}
.result-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.result-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12.5px; font-weight: 700;
}
.result-badge.ok  { background: var(--green-lt); color: var(--green); }
.result-badge.err { background: var(--red-lt);   color: var(--red); }
.result-badge.dup { background: var(--yellow-lt); color: var(--yellow); }
.result-badge svg { width: 14px; height: 14px; }

.result-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.result-table th {
    padding: 9px 14px; background: var(--surface);
    border-bottom: 1px solid var(--line);
    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--ink-3); text-align: left;
}
.result-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
.result-table tr:last-child td { border-bottom: none; }
.err-list { margin: 0; padding: 0 0 0 14px; color: var(--red); }
.err-list li { margin-bottom: 2px; }

/* ── Progress bar ── */
.import-progress {
    display: none;
    margin-top: 12px;
}
.import-progress.show { display: block; }
.progress-bar-wrap {
    height: 6px; background: var(--line); border-radius: 3px; overflow: hidden;
}
.progress-bar-fill {
    height: 100%; background: var(--blue); border-radius: 3px;
    animation: progressAnim 1.5s ease-in-out infinite;
}
@keyframes progressAnim {
    0%   { width: 10%; }
    50%  { width: 80%; }
    100% { width: 95%; }
}
.progress-label { font-size: 12px; color: var(--ink-3); margin-top: 6px; text-align: center; }

@media (max-width: 900px) {
    .import-layout { grid-template-columns: 1fr; }
    .import-layout, .page-header { padding-left: 20px; padding-right: 20px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header">
    <div class="breadcrumb">
        <a href="<?php echo e(route('baa.dashboard')); ?>">Dashboard</a>
        <span>›</span>
        <a href="<?php echo e(route('baa.calon.index')); ?>">Input Calon</a>
        <span>›</span>
        <span>Import Excel / CSV</span>
    </div>
    <h1 class="page-title">Import Data Calon Beasiswa</h1>
    <p class="page-sub">Upload file Excel atau CSV untuk menambahkan banyak data sekaligus</p>
</div>

<div class="import-layout">

    
    <div>

        
        <?php if(session('import_result')): ?>
        <?php $res = session('import_result'); ?>

        <div class="result-card">
            <div class="result-header">
                <span style="font-size:14px;font-weight:800;color:var(--ink)">Hasil Import</span>
                <span class="result-badge ok">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <?php echo e($res['berhasil']); ?> Berhasil
                </span>
                <?php if(count($res['duplikat']) > 0): ?>
                <span class="result-badge dup">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <?php echo e(count($res['duplikat'])); ?> NIM Duplikat
                </span>
                <?php endif; ?>
                <?php if(count($res['gagal']) > 0): ?>
                <span class="result-badge err">
                    <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <?php echo e(count($res['gagal'])); ?> Gagal
                </span>
                <?php endif; ?>

                <?php if($res['berhasil'] > 0): ?>
                <a href="<?php echo e(route('baa.calon.index')); ?>" style="margin-left:auto"
                   class="btn-download" style="width:auto;padding:7px 14px;font-size:12px">
                    Lihat Data →
                </a>
                <?php endif; ?>
            </div>

            
            <?php if(count($res['duplikat']) > 0): ?>
            <div style="padding:14px 20px;border-bottom:1px solid var(--line)">
                <div style="font-size:12px;font-weight:700;color:var(--yellow);margin-bottom:8px">
                    ⚠ NIM Duplikat (sudah ada di database, dilewati)
                </div>
                <table class="result-table">
                    <thead><tr><th>Baris</th><th>NIM</th><th>Nama</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $res['duplikat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="color:var(--ink-3)"><?php echo e($d['baris']); ?></td>
                            <td style="font-family:var(--mono);font-weight:600"><?php echo e($d['nim']); ?></td>
                            <td><?php echo e($d['nama']); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            
            <?php if(count($res['gagal']) > 0): ?>
            <div style="padding:14px 20px">
                <div style="font-size:12px;font-weight:700;color:var(--red);margin-bottom:8px">
                    ✕ Baris Gagal Diimport (perbaiki file lalu upload ulang)
                </div>
                <table class="result-table">
                    <thead><tr><th>Baris</th><th>NIM</th><th>Alasan Gagal</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $res['gagal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="color:var(--ink-3)"><?php echo e($g['baris']); ?></td>
                            <td style="font-family:var(--mono);font-weight:600"><?php echo e($g['nim']); ?></td>
                            <td>
                                <ul class="err-list">
                                    <?php $__currentLoopData = $g['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($err); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <?php if(session('error')): ?>
        <div class="alert error">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div><?php echo session('error'); ?></div>
        </div>
        <?php endif; ?>

        
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon blue">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Upload File</div>
                    <div class="card-sub">CSV atau Excel (.xlsx) maksimal 5MB</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST"
                      action="<?php echo e(route('baa.calon.import.store')); ?>"
                      enctype="multipart/form-data"
                      id="importForm">
                    <?php echo csrf_field(); ?>

                    
                    <div class="dropzone" id="dropzone">
                        <input type="file"
                               name="file"
                               id="fileInput"
                               accept=".csv,.xlsx,.xls,.txt"
                               onchange="onFileSelected(this)">
                        <div class="dropzone-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="dropzone-title">Drag & drop file di sini</div>
                        <div class="dropzone-sub">atau klik untuk memilih file</div>
                        <div class="dropzone-badge">
                            <span class="file-tag">CSV</span>
                            <span class="file-tag">XLSX</span>
                            <span class="file-tag">XLS</span>
                            <span style="margin-left:2px">· Maks 5MB</span>
                        </div>
                    </div>

                    
                    <div class="file-preview" id="filePreview">
                        <div class="file-preview-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <div class="file-preview-name" id="fileName">—</div>
                            <div class="file-preview-size" id="fileSize">—</div>
                        </div>
                        <button type="button" class="file-preview-remove" onclick="removeFile()">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    
                    <div class="form-group" style="margin-top:20px">
                        <label class="form-label">
                            Tahun Akademik Default
                            <span style="font-size:10.5px;font-weight:400;color:var(--ink-3);text-transform:none;letter-spacing:0">
                                (dipakai jika kolom tahun_akademik kosong di file)
                            </span>
                        </label>
                        <input type="text"
                               name="tahun_akademik_default"
                               class="form-input"
                               placeholder="contoh: 2024/2025"
                               value="<?php echo e(date('Y') . '/' . (date('Y')+1)); ?>">
                    </div>

                    
                    <div class="import-progress" id="importProgress">
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill"></div>
                        </div>
                        <div class="progress-label">Memproses file, harap tunggu...</div>
                    </div>

                    <button type="submit" class="btn-import" id="btnImport">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Import Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="sidebar-right">

        
        <div class="template-card">
            <div style="font-size:28px;margin-bottom:10px">📄</div>
            <div class="template-card-title">Download Template</div>
            <div class="template-card-sub">
                Gunakan template ini agar format kolom sesuai dan import berjalan lancar.
            </div>
            <a href="<?php echo e(route('baa.calon.import.template')); ?>" class="btn-download">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Download Template CSV
            </a>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon yellow">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Panduan Kolom</div>
                    <div class="card-sub">Wajib ada di baris pertama (header)</div>
                </div>
            </div>
            <div class="card-body" style="padding:0">
                <ul class="kolom-list" style="padding:0 16px">
                    <li>
                        <span class="kolom-name">nim</span>
                        <span class="kolom-desc">NIM mahasiswa (unik, tidak boleh duplikat)</span>
                    </li>
                    <li>
                        <span class="kolom-name">nama</span>
                        <span class="kolom-desc">Nama lengkap mahasiswa</span>
                    </li>
                    <li>
                        <span class="kolom-name">asal_sekolah</span>
                        <span class="kolom-desc">Nama SMA/SMK asal</span>
                    </li>
                    <li>
                        <span class="kolom-name">nilai_rapor_sem3</span>
                        <span class="kolom-desc">Nilai rata-rata rapor semester 3 (0–100)</span>
                    </li>
                    <li>
                        <span class="kolom-name">nilai_rapor_sem4</span>
                        <span class="kolom-desc">Nilai rata-rata rapor semester 4 (0–100)</span>
                    </li>
                    <li>
                        <span class="kolom-name">prodi_kode</span>
                        <span class="kolom-desc">
                            Kode prodi (lihat tabel di bawah)
                            <?php if(isset($prodis) && $prodis->count()): ?>
                            <div style="margin-top:5px;display:flex;flex-wrap:wrap;gap:4px">
                                <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span style="font-size:10px;font-family:var(--mono);background:var(--blue-lt);color:var(--blue-dk);padding:1px 6px;border-radius:3px;font-weight:700">
                                    <?php echo e($p->kode); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li>
                        <span class="kolom-name">semester</span>
                        <span class="kolom-desc">Semester aktif mahasiswa (angka, misal: 3)</span>
                    </li>
                    <li>
                        <span class="kolom-name">tahun_akademik</span>
                        <span class="kolom-desc">Misal: 2024/2025 (boleh kosong, pakai default)</span>
                    </li>
                    <li>
                        <span class="kolom-name">penghasilan_ortu</span>
                        <span class="kolom-desc">Penghasilan orang tua per bulan (angka, tanpa titik/koma)</span>
                    </li>
                    <li>
                        <span class="kolom-name">tagihan_listrik</span>
                        <span class="kolom-desc">Tagihan listrik per bulan (angka)</span>
                    </li>
                    <li>
                        <span class="kolom-name">tanggungan_anak</span>
                        <span class="kolom-desc">Jumlah tanggungan anak (angka)</span>
                    </li>
                    <li style="border-bottom:none">
                        <span class="kolom-name">kondisi_ortu</span>
                        <span class="kolom-desc">
                            Status orang tua:
                            <div class="kondisi-chips">
                                <span class="kondisi-chip a">lengkap</span>
                                <span class="kondisi-chip b">yatim</span>
                                <span class="kondisi-chip c">piatu</span>
                                <span class="kondisi-chip d">yatim_piatu</span>
                                <span class="kondisi-chip e">cerai</span>
                            </div>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon green">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="card-title">Tips Import</div>
                </div>
            </div>
            <div class="card-body">
                <ul style="margin:0;padding:0 0 0 16px;font-size:12.5px;color:var(--ink-3);line-height:1.8">
                    <li>Baris pertama <strong style="color:var(--ink)">harus header</strong> persis seperti panduan</li>
                    <li>NIM yang sudah ada di database akan <strong style="color:var(--yellow)">dilewati</strong> (tidak error)</li>
                    <li>Angka ditulis tanpa titik/koma: <code style="background:var(--line);padding:1px 5px;border-radius:3px">1500000</code> bukan <code style="background:var(--line);padding:1px 5px;border-radius:3px">1.500.000</code></li>
                    <li>Data berhasil diimport masuk sebagai <strong style="color:var(--ink)">Draft</strong></li>
                    <li>Setelah import, kirim data ke BAPSI dari halaman Input Calon</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Dropzone drag & drop ──
const dropzone = document.getElementById('dropzone');

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.classList.add('drag-over');
});
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('fileInput').files = e.dataTransfer.files;
        showFilePreview(file);
    }
});

function onFileSelected(input) {
    if (input.files[0]) showFilePreview(input.files[0]);
}

function showFilePreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatBytes(file.size);
    document.getElementById('filePreview').classList.add('show');
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').classList.remove('show');
}

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// ── Submit: tampilkan progress ──
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files[0]) {
        e.preventDefault();
        alert('Pilih file terlebih dahulu.');
        return;
    }
    document.getElementById('btnImport').disabled = true;
    document.getElementById('btnImport').textContent = 'Memproses...';
    document.getElementById('importProgress').classList.add('show');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/baa/calon/import.blade.php ENDPATH**/ ?>