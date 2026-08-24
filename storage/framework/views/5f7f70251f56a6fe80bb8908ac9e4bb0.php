<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penerima Beasiswa <?php echo e($tahun); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            padding: 20mm 20mm 20mm 25mm;
        }

        /* ── Header Surat ── */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .kop-logo-placeholder {
            width: 70px;
            height: 70px;
            border: 2px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            text-align: center;
            color: #555;
            flex-shrink: 0;
        }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .instansi {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-text .sub-instansi {
            font-size: 10pt;
            margin-top: 2px;
        }
        .kop-text .alamat {
            font-size: 8.5pt;
            color: #333;
            margin-top: 3px;
        }

        /* ── Judul Dokumen ── */
        .judul-wrapper {
            text-align: center;
            margin: 18px 0 14px;
        }
        .judul {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .sub-judul {
            font-size: 10pt;
            margin-top: 4px;
            color: #222;
        }

        /* ── Info Meta ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 24px;
            margin-bottom: 16px;
            font-size: 10pt;
        }
        .meta-item { display: flex; gap: 4px; }
        .meta-label { min-width: 110px; }
        .meta-value { font-weight: bold; }

        /* ── Tabel Per Prodi ── */
                    .prodi-section { margin-bottom: 20px; }
            .prodi-header { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tbody tr { page-break-inside: avoid; }

        .prodi-header {
            background: #1a3a5c;
            color: #fff;
            padding: 6px 10px;
            font-size: 10pt;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .prodi-header .badge {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        thead tr th {
            background: #e8edf2;
            border: 1px solid #aab;
            padding: 5px 7px;
            text-align: center;
            font-weight: bold;
        }
        tbody tr td {
            border: 1px solid #ccd;
            padding: 4px 7px;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) td { background: #f7f9fb; }
        tbody tr.penerima td { background: #f0fdf4; }
        .rank-cell { text-align: center; font-weight: bold; }
        .skor-cell { text-align: right; font-family: 'Courier New', monospace; font-size: 8.5pt; }
        .nim-cell  { font-family: 'Courier New', monospace; font-size: 8.5pt; }
        .badge-status {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-penerima   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-tidak      { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Ringkasan ── */
        .ringkasan {
            margin: 18px 0;
            border: 1px solid #ccd;
            border-radius: 4px;
            overflow: hidden;
        }
        .ringkasan-header {
            background: #f1f5f9;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 10pt;
            border-bottom: 1px solid #ccd;
        }
        .ringkasan-body {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
        }
        .ringkasan-item {
            padding: 10px 16px;
            border-right: 1px solid #ccd;
            text-align: center;
        }
        .ringkasan-item:last-child { border-right: none; }
        .ringkasan-item .angka {
            font-size: 20pt;
            font-weight: bold;
            color: #1a3a5c;
            display: block;
        }
        .ringkasan-item .label {
            font-size: 8.5pt;
            color: #555;
            margin-top: 2px;
        }

        /* ── TTD ── */
        .ttd-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
        }
        .ttd-box { text-align: center; }
        .ttd-space { height: 60px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }

        /* ── Footer ── */
        .doc-footer {
            margin-top: 20px;
            border-top: 1px solid #ccd;
            padding-top: 6px;
            font-size: 8pt;
            color: #888;
            display: flex;
            justify-content: space-between;
        }

        /* ── Print ── */
        @media print {
            .prodi-section { margin-bottom: 20px; }
            .prodi-header { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tbody tr { page-break-inside: avoid; }
        }

        /* ── Tombol Cetak (tidak ikut print) ── */
        .print-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #1a3a5c;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }
        .print-bar span { font-size: 0.9rem; }
        .print-bar .btn-group { display: flex; gap: 8px; }
        .btn-print {
            background: #fff;
            color: #1a3a5c;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .btn-back {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255,255,255,.4);
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
        }
        .content-wrapper { margin-top: 50px; }
        @media print { .print-bar { display: none; } .content-wrapper { margin-top: 0; } }
    </style>
</head>
<body>


<div class="print-bar no-print">
    <span>📄 Dokumen Penerima Beasiswa <?php echo e($tahun); ?></span>
    <div class="btn-group">
        <a href="<?php echo e(url()->previous()); ?>" class="btn-back">← Kembali</a>
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>
</div>

<div class="content-wrapper">


<div class="kop">
    <div class="kop-logo-placeholder">
        <img src="<?php echo e(asset('images/logo-usd.png')); ?>" alt="Logo USD" style="width:80px; height:80px; object-fit:contain;">
    </div>
    <div class="kop-text">
        <div class="instansi">Universitas Sanata Dharma</div>
        <div class="sub-instansi">Lembaga Kemahasiswaan (LKM)</div>
        <div class="alamat">Jl. Affandi, Mrican, Caturtunggal, Depok, Sleman, Yogyakarta 55281</div>
    </div>
    <div class="kop-logo-placeholder">
        <img src="<?php echo e(asset('images/logo-usd.png')); ?>" alt="Logo USD" style="width:80px; height:80px; object-fit:contain;">
    </div>
</div>


<div class="judul-wrapper">
    <div class="judul">Daftar Penerima Beasiswa</div>
    <div class="sub-judul">Periode Akademik <?php echo e($tahun); ?> &nbsp;·&nbsp; Berdasarkan Metode MOORA</div>
</div>


<?php
    $totalProdi    = $byProdi->count();
    $totalPenerima = $keputusans->count();
?>

<div class="ringkasan">
    <div class="ringkasan-header">Ringkasan</div>
    <div class="ringkasan-body">
        <div class="ringkasan-item">
            <span class="angka"><?php echo e($totalPenerima); ?></span>
            <div class="label">Total Penerima</div>
        </div>
        <div class="ringkasan-item">
            <span class="angka"><?php echo e($totalProdi); ?></span>
            <div class="label">Program Studi</div>
        </div>
        <div class="ringkasan-item">
            <span class="angka"><?php echo e($tahun); ?></span>
            <div class="label">Periode</div>
        </div>
    </div>
</div>


<?php $__empty_1 = true; $__currentLoopData = $byProdi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodiId => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php $prodi = $items->first()->prodi; ?>
<div class="prodi-section">
    <div class="prodi-header">
        <span><?php echo e($prodi?->nama ?? 'Prodi #'.$prodiId); ?></span>
        <span class="badge"><?php echo e($items->count()); ?> penerima &nbsp;·&nbsp; Kuota: <?php echo e($prodi?->kuota_beasiswa ?? '-'); ?></span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th style="width:90px">NIM</th>
                <th>Nama Mahasiswa</th>
                <th style="width:55px">Tahap</th>
                <th style="width:55px">Rank Prodi</th>
                <th style="width:80px">Skor MOORA</th>
                <th style="width:80px">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $items->sortBy('ranking_prodi'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($k->status === 'penerima' ? 'penerima' : ''); ?>">
            <td class="rank-cell"><?php echo e($i + 1); ?></td>
            <td class="nim-cell"><?php echo e($k->calon?->nim ?? '-'); ?></td>
            <td><?php echo e($k->calon?->nama ?? '-'); ?></td>
            <td class="rank-cell">P<?php echo e($k->tahap_diterima ?? '-'); ?></td>
            <td class="rank-cell"><?php echo e($k->ranking_prodi ?? '-'); ?></td>
            <td class="skor-cell"><?php echo e(number_format((float)($k->skor_moora ?? 0), 6)); ?></td>
            <td style="text-align:center">
                <?php if($k->status === 'penerima'): ?>
                    <span class="badge-status badge-penerima">✓ Penerima</span>
                <?php else: ?>
                    <span class="badge-status badge-tidak">✗ Tidak Lolos</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<p style="text-align:center;padding:40px;color:#888">Tidak ada data penerima untuk periode <?php echo e($tahun); ?>.</p>
<?php endif; ?>


<div class="ttd-section">
    <div class="ttd-box">
        <div>Mengetahui,</div>
        <div>Ketua LKM</div>
        <div class="ttd-space"></div>
        <div class="ttd-nama">( _________________________ )</div>
    </div>
    <div class="ttd-box">
        <div>Yogyakarta, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></div>
        <div>Dibuat oleh,</div>
        <div class="ttd-space"></div>
        <div class="ttd-nama">( _________________________ )</div>
    </div>
</div>


<div class="doc-footer">
    <span>Dicetak: <?php echo e(\Carbon\Carbon::now()->format('d/m/Y H:i')); ?></span>
    <span>Sistem Pendukung Keputusan Beasiswa — Metode MOORA</span>
    <span>Periode <?php echo e($tahun); ?></span>
</div>

</div>
</body>
</html><?php /**PATH F:\laragon\www\spk-beasiswav3\resources\views/lkm/keputusan/print.blade.php ENDPATH**/ ?>