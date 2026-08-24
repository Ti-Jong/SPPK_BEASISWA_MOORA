@extends('layouts.app')

@section('title', 'Edit Data Calon Mahasiswa')

@section('content')
<style>
/* ── Form Sections ──────────────────────────────── */
.form-section {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    overflow: hidden;
    margin-bottom: 16px;
}
.form-section-header {
    padding: 11px 18px;
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    font-weight: 600; font-size: .83rem;
}
.fsh-blue  { background: #4e73df; color: #fff; }
.fsh-green { background: #1cc88a; color: #fff; }
.fsh-info  { background: #36b9cc; color: #fff; }

.form-section-body { padding: 20px 18px; }

/* ── Labels & Inputs ────────────────────────────── */
.f-label {
    display: block; font-size: .78rem; font-weight: 600;
    color: #444; margin-bottom: 5px;
}
.f-label .req { color: #e74a3b; margin-left: 2px; }
.f-label .opt { color: #aaa; font-weight: 400; font-size: .72rem; margin-left: 4px; }

.f-input, .f-select {
    width: 100%; padding: 8px 12px;
    border: 1.5px solid #dde1e9; border-radius: 7px;
    font-size: .82rem; color: #2d3748; background: #fff;
    transition: border-color .15s, box-shadow .15s; appearance: none;
}
.f-input:focus, .f-select:focus {
    outline: none; border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78,115,223,.12);
}
.f-input.is-invalid, .f-select.is-invalid { border-color: #e74a3b; }
.f-error { font-size: .72rem; color: #e74a3b; margin-top: 4px; }
.f-hint  { font-size: .71rem; color: #999; margin-top: 3px; }

.f-select-wrap { position: relative; }
.f-select-wrap::after {
    content: '\f107'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #999; pointer-events: none; font-size: .8rem;
}
.f-select { padding-right: 30px; }

.input-addon-group { display: flex; gap: 8px; }
.input-addon-group .f-select { border-radius: 7px; }
.btn-addon {
    flex-shrink: 0; padding: 8px 14px;
    background: #f8f9fc; border: 1.5px solid #dde1e9; border-radius: 7px;
    font-size: .78rem; font-weight: 600; color: #4e73df;
    cursor: pointer; white-space: nowrap; transition: all .12s;
    display: flex; align-items: center; gap: 5px;
}
.btn-addon:hover { background: #eef0fb; border-color: #4e73df; }

/* ── Grid ───────────────────────────────────────── */
.f-grid { display: grid; gap: 14px; }
.f-grid-3   { grid-template-columns: repeat(3, 1fr); }
.f-grid-2   { grid-template-columns: repeat(2, 1fr); }
.f-grid-142 { grid-template-columns: 1fr 2fr; }
@media (max-width: 640px) {
    .f-grid-3, .f-grid-2, .f-grid-142 { grid-template-columns: 1fr; }
}

/* ── Penolakan ──────────────────────────────────── */
.penolakan-card {
    border: 2px solid #e74a3b; border-radius: 10px;
    overflow: hidden; margin-bottom: 16px;
    box-shadow: 0 2px 10px rgba(231,74,59,.15);
}
.penolakan-header {
    background: #e74a3b; color: #fff; padding: 10px 16px;
    display: flex; align-items: center; gap: 8px;
    font-weight: 600; font-size: .88rem;
}
.penolakan-body { padding: 16px; background: #fffafa; }
.alasan-box {
    background: #fff0ef; border: 1px solid #fcd5d0; border-radius: 8px;
    padding: 12px 14px; color: #c0392b; font-size: .83rem;
    line-height: 1.5; margin-bottom: 10px;
}
.catatan-box {
    background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px;
    padding: 10px 14px; color: #555; font-size: .81rem;
}
.info-tip {
    display: flex; align-items: flex-start; gap: 7px;
    background: #fef9ec; border: 1px solid #fde8a8; border-radius: 8px;
    padding: 9px 13px; font-size: .77rem; color: #856404; margin-top: 10px;
}

/* ── Error Summary ──────────────────────────────── */
.error-summary {
    background: #fff5f5; border: 1.5px solid #fcd5d0;
    border-radius: 8px; padding: 12px 16px; margin-bottom: 14px;
}
.error-summary .err-title { font-weight: 600; color: #c0392b; font-size: .82rem; margin-bottom: 6px; }
.error-summary ul { margin: 0; padding-left: 18px; }
.error-summary li { font-size: .78rem; color: #c0392b; }

/* ── Skor kondisi OT ── */
.skor-kondisi-info {
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: 5px; font-size: .72rem; color: #64748b;
}
.skor-badge {
    display: inline-block; padding: 1px 8px; border-radius: 10px;
    font-weight: 700; font-size: .72rem;
}
.skor-10 { background: #fef2f2; color: #dc2626; }
.skor-5  { background: #fffbeb; color: #d97706; }
.skor-0  { background: #f0fdf4; color: #16a34a; }

/* ── Dokumen Section ────────────────────────────── */
.dok-progress-wrap {
    padding: 12px 18px; background: #f8fbff;
    border-bottom: 1px solid #eef2ff;
}
.dok-progress-bar-bg {
    height: 7px; background: #e9ecef; border-radius: 10px; overflow: hidden;
}
.dok-progress-bar-fill {
    height: 100%; border-radius: 10px; transition: width .4s ease;
}

.dok-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 18px; border-bottom: 1px solid #f5f5f5; gap: 12px;
}
.dok-item:last-child { border-bottom: none; }
.dok-left { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
.dok-icon {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.dok-icon-uploaded { background: #e8f8f2; color: #1a9e6f; }
.dok-icon-empty    { background: #f0f0f0; color: #bbb; }
.dok-info { min-width: 0; }
.dok-name { font-size: .8rem; font-weight: 600; color: #2d3748; }
.dok-meta { font-size: .7rem; color: #aaa; margin-top: 1px; }
.dok-meta-ok { color: #1a9e6f; }

.dok-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
.dok-status-badge {
    padding: 2px 9px; border-radius: 12px; font-size: .68rem; font-weight: 600;
}
.dsb-pending { background: #fef9ec; color: #d4a017; }
.dsb-valid   { background: #e8f8f2; color: #1a9e6f; }
.dsb-invalid { background: #fdf0ee; color: #e74a3b; }

.upload-inline { display: flex; align-items: center; gap: 6px; }
.upload-inline-input {
    font-size: .72rem; color: #555;
    border: 1.5px solid #dde1e9; border-radius: 6px;
    padding: 4px 8px; background: #fff; cursor: pointer;
    max-width: 180px;
}
.upload-inline-input::-webkit-file-upload-button {
    background: #4e73df; color: #fff; border: none;
    border-radius: 4px; padding: 3px 8px; font-size: .7rem;
    cursor: pointer; margin-right: 6px;
}
.btn-upload-submit {
    padding: 4px 12px; border-radius: 6px; background: #36b9cc;
    color: #fff; border: none; font-size: .72rem; font-weight: 600;
    cursor: pointer; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 4px;
    transition: background .12s;
}
.btn-upload-submit:hover { background: #2a9caf; }
.btn-preview {
    padding: 4px 10px; border-radius: 6px; border: 1.5px solid #36b9cc;
    color: #36b9cc; font-size: .72rem; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
    transition: all .12s;
}
.btn-preview:hover { background: #36b9cc; color: #fff; }
.btn-del-dok {
    padding: 4px 9px; border-radius: 6px; border: 1.5px solid #e74a3b;
    color: #e74a3b; font-size: .72rem; background: transparent;
    cursor: pointer; display: inline-flex; align-items: center; gap: 3px;
    transition: all .12s;
}
.btn-del-dok:hover { background: #e74a3b; color: #fff; }

/* ── Action Bar ─────────────────────────────────── */
.action-bar {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 14px 0 32px;
}
.btn-batal {
    padding: 9px 20px; border-radius: 7px; font-size: .82rem;
    font-weight: 600; color: #666; border: 1.5px solid #ccc;
    background: #fff; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px; transition: all .12s;
}
.btn-batal:hover { background: #f5f5f5; }
.btn-simpan {
    padding: 9px 28px; border-radius: 7px; font-size: .82rem;
    font-weight: 600; color: #fff; border: none; background: #4e73df;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: all .12s;
}
.btn-simpan:hover { background: #3756c0; }
.btn-simpan.perbaiki { background: #e74a3b; }
.btn-simpan.perbaiki:hover { background: #c0392b; }
</style>

<div class="container-fluid px-3">

    {{-- ===== HEADER ===== --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <div>
            <h5 class="mb-1 fw-bold">Edit Data Calon Mahasiswa</h5>
            <nav style="font-size:.78rem;">
                <a href="{{ route('baa.dashboard') }}" style="color:#4e73df;text-decoration:none;">Dashboard</a>
                <span class="mx-1 text-muted">/</span>
                <a href="{{ route('baa.calon.index') }}" style="color:#4e73df;text-decoration:none;">Input Calon</a>
                <span class="mx-1 text-muted">/</span>
                <span class="text-muted">Edit — {{ $calon->nim }}</span>
            </nav>
        </div>
        <a href="{{ route('baa.calon.index') }}"
           style="padding:6px 14px;border-radius:7px;border:1.5px solid #ccc;color:#666;font-size:.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ===== KOTAK PENOLAKAN ===== --}}
    @if($calon->status === 'ditolak')
    <div class="penolakan-card">
        <div class="penolakan-header">
            <i class="fas fa-exclamation-circle"></i>
            Data Ini Ditolak oleh BAPSI — Silakan Perbaiki
        </div>
        <div class="penolakan-body">
            @if($calon->alasan_penolakan)
            <div style="font-size:.76rem;font-weight:600;color:#c0392b;margin-bottom:5px;">
                <i class="fas fa-exclamation-triangle me-1"></i>Alasan Penolakan:
            </div>
            <div class="alasan-box">{{ $calon->alasan_penolakan }}</div>
            @endif
            @if($calon->catatan)
            <div style="font-size:.76rem;font-weight:600;color:#666;margin-bottom:5px;">
                <i class="fas fa-sticky-note me-1"></i>Catatan Tambahan:
            </div>
            <div class="catatan-box">{{ $calon->catatan }}</div>
            @endif
            <div class="info-tip">
                <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>Perbaiki data dan lengkapi dokumen di bawah, lalu klik <strong>Simpan Perbaikan</strong>. Status otomatis kembali ke <em>Draft</em>.</span>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== ERROR SUMMARY ===== --}}
    @if($errors->any())
    <div class="error-summary">
        <div class="err-title"><i class="fas fa-exclamation-triangle me-1"></i>Periksa Input Berikut:</div>
        <ul>
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div style="background:#e8f8f2;border:1.5px solid #b2dfdb;border-radius:8px;padding:10px 14px;font-size:.8rem;color:#1a9e6f;margin-bottom:14px;display:flex;align-items:center;gap:7px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ===== FORM DATA ===== --}}
    <form action="{{ route('baa.calon.update', $calon->id) }}" method="POST" id="formEdit">
        @csrf
        @method('PUT')
        <input type="hidden" name="semester" value="1">

        {{-- SEKSI 1: DATA AKADEMIK --}}
        <div class="form-section">
            <div class="form-section-header fsh-blue">
                <span><i class="fas fa-graduation-cap me-2"></i>Data Akademik</span>
            </div>
            <div class="form-section-body">
                <div class="f-grid f-grid-142" style="margin-bottom:14px;">
                    <div>
                        <label class="f-label">NIM <span class="req">*</span></label>
                        <input type="text" name="nim"
                               class="f-input {{ $errors->has('nim') ? 'is-invalid' : '' }}"
                               value="{{ old('nim', $calon->nim) }}" required>
                        @error('nim')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="f-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="nama"
                               class="f-input {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                               value="{{ old('nama', $calon->nama) }}" required>
                        @error('nama')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="margin-bottom:14px;">
                    <label class="f-label">Asal Sekolah <span class="opt">(opsional)</span></label>
                    <input type="text" name="asal_sekolah"
                           class="f-input {{ $errors->has('asal_sekolah') ? 'is-invalid' : '' }}"
                           value="{{ old('asal_sekolah', $calon->asal_sekolah) }}"
                           placeholder="Contoh: SMA N 1 Kupang">
                    @error('asal_sekolah')<div class="f-error">{{ $message }}</div>@enderror
                </div>

                <div style="margin-bottom:14px;">
                    <label class="f-label">Program Studi <span class="req">*</span></label>
                    <div class="input-addon-group">
                        <div class="f-select-wrap" style="flex:1;">
                            <select name="prodi_id" id="prodiSelect"
                                    class="f-select {{ $errors->has('prodi_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}"
                                    {{ old('prodi_id', $calon->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}{{ $prodi->jenjang ? ' ('.$prodi->jenjang.')' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn-addon"
                                data-bs-toggle="modal" data-bs-target="#modalTambahProdi">
                            <i class="fas fa-plus"></i> Tambah Prodi
                        </button>
                    </div>
                    @error('prodi_id')<div class="f-error">{{ $message }}</div>@enderror
                </div>

                <div class="f-grid f-grid-3">
                    <div>
                        <label class="f-label">Tahun Akademik <span class="req">*</span></label>
                        <select name="tahun_akademik"
                            class="f-input {{ $errors->has('tahun_akademik') ? 'is-invalid' : '' }}"
                            required>
                        @foreach(['2023/2024', '2024/2025', '2025/2026'] as $tahun)
                            <option value="{{ $tahun }}"
                                {{ old('tahun_akademik', $calon->tahun_akademik) == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                        @error('tahun_akademik')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="f-label">Rapor Semester 3 <span class="opt">(opsional)</span></label>
                        <input type="number" name="nilai_rapor_sem3"
                               class="f-input {{ $errors->has('nilai_rapor_sem3') ? 'is-invalid' : '' }}"
                               value="{{ old('nilai_rapor_sem3', $calon->nilai_rapor_sem3) }}"
                               step="0.01" min="0" max="100" placeholder="0.00 – 100">
                        @error('nilai_rapor_sem3')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="f-label">Rapor Semester 4 <span class="opt">(opsional)</span></label>
                        <input type="number" name="nilai_rapor_sem4"
                               class="f-input {{ $errors->has('nilai_rapor_sem4') ? 'is-invalid' : '' }}"
                               value="{{ old('nilai_rapor_sem4', $calon->nilai_rapor_sem4) }}"
                               step="0.01" min="0" max="100" placeholder="0.00 – 100">
                        @error('nilai_rapor_sem4')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SEKSI 2: DATA EKONOMI --}}
        <div class="form-section">
            <div class="form-section-header fsh-green">
                <span><i class="fas fa-wallet me-2"></i>Data Ekonomi</span>
            </div>
            <div class="form-section-body">

                {{-- ✅ KONDISI OT — 11 kondisi sesuai sub-kriteria LKM --}}
                <div style="margin-bottom:14px;">
                    <label class="f-label">Kondisi Orang Tua <span class="req">*</span></label>
                    <div class="f-select-wrap">
                        <select name="kondisi_ortu" id="kondisiSelect"
                                class="f-select {{ $errors->has('kondisi_ortu') ? 'is-invalid' : '' }}"
                                onchange="updateSkorKondisi(this.value)"
                                required>
                            <option value="">-- Pilih Kondisi Orang Tua --</option>

                            {{-- Skor 10 --}}
                            <optgroup label="━━ Skor 10 — Paling Membutuhkan ━━">
                                <option value="tidak_punya_ayah_ibu"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'tidak_punya_ayah_ibu' ? 'selected' : '' }}>
                                    Tidak mempunyai ayah dan ibu
                                </option>
                                <option value="hanya_ayah_sakit"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'hanya_ayah_sakit' ? 'selected' : '' }}>
                                    Hanya ada ayah dalam Keluarga kami (ayah sakit)
                                </option>
                                <option value="hanya_ibu_sakit"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'hanya_ibu_sakit' ? 'selected' : '' }}>
                                    Hanya ada ibu dalam Keluarga kami (ibu sakit)
                                </option>
                                <option value="ayah_ibu_sakit_tidak_bekerja"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ayah_ibu_sakit_tidak_bekerja' ? 'selected' : '' }}>
                                    Ayah dan Ibu keduanya sakit dan tidak bekerja
                                </option>
                            </optgroup>

                            {{-- Skor 5 --}}
                            <optgroup label="━━ Skor 5 ━━">
                                <option value="hanya_ayah_sehat"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'hanya_ayah_sehat' ? 'selected' : '' }}>
                                    Hanya ada ayah dalam Keluarga kami (ayah sehat)
                                </option>
                                <option value="hanya_ibu_sehat"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'hanya_ibu_sehat' ? 'selected' : '' }}>
                                    Hanya ada ibu dalam Keluarga kami (ibu sehat)
                                </option>
                                <option value="ada_ayah_sakit_tidak_bekerja"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ada_ayah_sakit_tidak_bekerja' ? 'selected' : '' }}>
                                    Ada ayah dan ibu tetapi ayah sakit dan tidak bekerja
                                </option>
                                <option value="ada_ibu_sakit_tidak_bekerja"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ada_ibu_sakit_tidak_bekerja' ? 'selected' : '' }}>
                                    Ada ayah dan ibu tetapi Ibu sakit dan tidak bekerja
                                </option>
                            </optgroup>

                            {{-- Skor 0 --}}
                            <optgroup label="━━ Skor 0 ━━">
                                <option value="ada_ayah_sakit_pensiun"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ada_ayah_sakit_pensiun' ? 'selected' : '' }}>
                                    Ada ayah dan ibu tetapi ayah sakit dan Pensiunan
                                </option>
                                <option value="ada_ibu_sakit_pensiun"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ada_ibu_sakit_pensiun' ? 'selected' : '' }}>
                                    Ada ayah dan ibu tetapi Ibu sakit dan Pensiunan
                                </option>
                                <option value="ada_ayah_ibu_sehat"
                                    {{ old('kondisi_ortu', $calon->kondisi_ortu) === 'ada_ayah_ibu_sehat' ? 'selected' : '' }}>
                                    Ada ayah dan ibu dalam keluarga kami (keduanya sehat)
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    {{-- Info skor real-time --}}
                    <div class="skor-kondisi-info" id="skorKondisiInfo" style="display:none">
                        Skor MOORA kondisi OT:
                        <span class="skor-badge" id="skorKondisiBadge">—</span>
                    </div>
                    @error('kondisi_ortu')<div class="f-error">{{ $message }}</div>@enderror
                </div>

                <div class="f-grid f-grid-3">
                    <div>
                        <label class="f-label">Penghasilan Orang Tua <span class="req">*</span></label>
                        <div class="f-select-wrap">
                            <select name="penghasilan_ortu"
                                    class="f-select {{ $errors->has('penghasilan_ortu') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="500000"  {{ old('penghasilan_ortu', $calon->penghasilan_ortu) == 500000  ? 'selected' : '' }}>≤ Rp 1.000.000</option>
                                <option value="1500000" {{ old('penghasilan_ortu', $calon->penghasilan_ortu) == 1500000 ? 'selected' : '' }}>Rp 1.000.001 – Rp 2.000.000</option>
                                <option value="2750000" {{ old('penghasilan_ortu', $calon->penghasilan_ortu) == 2750000 ? 'selected' : '' }}>Rp 2.000.001 – Rp 3.500.000</option>
                                <option value="4000000" {{ old('penghasilan_ortu', $calon->penghasilan_ortu) == 4000000 ? 'selected' : '' }}>> Rp 3.500.000</option>
                            </select>
                        </div>
                        @error('penghasilan_ortu')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="f-label">Tagihan Listrik / Bulan <span class="req">*</span></label>
                        <div class="f-select-wrap">
                            <select name="tagihan_listrik"
                                    class="f-select {{ $errors->has('tagihan_listrik') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="75000"  {{ old('tagihan_listrik', $calon->tagihan_listrik) == 75000  ? 'selected' : '' }}>≤ Rp 100.000</option>
                                <option value="200000" {{ old('tagihan_listrik', $calon->tagihan_listrik) == 200000 ? 'selected' : '' }}>Rp 100.001 – Rp 300.000</option>
                                <option value="400000" {{ old('tagihan_listrik', $calon->tagihan_listrik) == 400000 ? 'selected' : '' }}>> Rp 300.000</option>
                            </select>
                        </div>
                        @error('tagihan_listrik')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="f-label">Tanggungan Anak <span class="req">*</span></label>
                        <div class="f-select-wrap">
                            <select name="tanggungan_anak"
                                    class="f-select {{ $errors->has('tanggungan_anak') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="1" {{ old('tanggungan_anak', $calon->tanggungan_anak) == 1 ? 'selected' : '' }}>1 anak</option>
                                <option value="2" {{ old('tanggungan_anak', $calon->tanggungan_anak) == 2 ? 'selected' : '' }}>2 anak</option>
                                <option value="3" {{ old('tanggungan_anak', $calon->tanggungan_anak) == 3 ? 'selected' : '' }}>3 anak atau lebih</option>
                            </select>
                        </div>
                        @error('tanggungan_anak')<div class="f-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TOMBOL SIMPAN ===== --}}
        <div class="action-bar" style="padding-bottom:8px;">
            <a href="{{ route('baa.calon.index') }}" class="btn-batal">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn-simpan {{ $calon->status === 'ditolak' ? 'perbaiki' : '' }}">
                @if($calon->status === 'ditolak')
                    <i class="fas fa-wrench"></i> Simpan Perbaikan
                @else
                    <i class="fas fa-save"></i> Simpan Perubahan
                @endif
            </button>
        </div>
    </form>

    {{-- ===== SEKSI 3: DOKUMEN PENDUKUNG ===== --}}
    @php
        $jenisWajib  = \App\Models\CalonMahasiswa::jenisDokumenWajib();
        $uploadedDok = $calon->dokumen->keyBy('jenis');
        $jenisLabel  = [
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
        $totalWajib   = count($jenisWajib);
        $jumlahUpload = $calon->dokumen->count();
        $pct          = $totalWajib > 0 ? min(100, round($jumlahUpload / $totalWajib * 100)) : 0;
        $warnaDok     = $pct == 100 ? '#1cc88a' : ($pct > 0 ? '#f6c23e' : '#e74a3b');
    @endphp

    <div class="form-section" style="margin-bottom:32px;">
        <div class="form-section-header fsh-info">
            <span><i class="fas fa-paperclip me-2"></i>Dokumen Pendukung</span>
            <span style="font-size:.75rem;font-weight:400;opacity:.9;">
                {{ $jumlahUpload }}/{{ $totalWajib }} diunggah
            </span>
        </div>

        <div class="dok-progress-wrap">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <span style="font-size:.75rem;color:#555;">
                    <i class="fas fa-file-upload me-1" style="color:#36b9cc;"></i>
                    Kelengkapan Dokumen
                </span>
                <span style="font-size:.75rem;font-weight:700;color:{{ $warnaDok }};">{{ $pct }}%</span>
            </div>
            <div class="dok-progress-bar-bg">
                <div class="dok-progress-bar-fill" style="width:{{ $pct }}%;background:{{ $warnaDok }};"></div>
            </div>
            @if($pct < 100)
            <div style="font-size:.72rem;color:#d4a017;margin-top:5px;">
                <i class="fas fa-exclamation-triangle me-1"></i>
                {{ $totalWajib - $jumlahUpload }} dokumen belum diunggah.
            </div>
            @else
            <div style="font-size:.72rem;color:#1a9e6f;margin-top:5px;">
                <i class="fas fa-check-circle me-1"></i>Semua dokumen wajib sudah lengkap.
            </div>
            @endif
        </div>

        @foreach($jenisWajib as $jenis)
        @php $dok = $uploadedDok->get($jenis); @endphp
        <div class="dok-item">
            <div class="dok-left">
                <div class="dok-icon {{ $dok ? 'dok-icon-uploaded' : 'dok-icon-empty' }}">
                    <i class="fas fa-{{ $dok ? 'file-check' : 'file' }}"></i>
                </div>
                <div class="dok-info">
                    <div class="dok-name">{{ $jenisLabel[$jenis] ?? $jenis }}</div>
                    @if($dok)
                        <div class="dok-meta dok-meta-ok">
                            <i class="fas fa-check me-1"></i>
                            {{ $dok->file_name }}
                            · {{ isset($dok->file_size) ? (round($dok->file_size/1024) . ' KB') : '-' }}
                        </div>
                    @else
                        <div class="dok-meta">Belum diunggah</div>
                    @endif
                </div>
            </div>
            <div class="dok-right">
                @if($dok)
                    @php
                        $stClass = match($dok->status ?? 'pending'){
                            'valid'   => 'dsb-valid',
                            'invalid' => 'dsb-invalid',
                            default   => 'dsb-pending',
                        };
                    @endphp
                    <span class="dok-status-badge {{ $stClass }}">{{ ucfirst($dok->status ?? 'pending') }}</span>
                    <a href="{{ $dok->url }}" target="_blank" class="btn-preview">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form action="{{ route('baa.dokumen.destroy', $dok->id) }}" method="POST"
                          onsubmit="return confirm('Hapus dokumen {{ $jenisLabel[$jenis] ?? $jenis }}?')"
                          style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del-dok" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
                <form action="{{ route('baa.dokumen.upload', $calon->id) }}" method="POST"
                      enctype="multipart/form-data" class="upload-inline"
                      id="form-upload-{{ $jenis }}">
                    @csrf
                    <input type="hidden" name="jenis" value="{{ $jenis }}">
                    <input type="file" name="file"
                           class="upload-inline-input"
                           id="file-{{ $jenis }}"
                           accept=".pdf,.jpg,.jpeg,.png"
                           onchange="autoSubmitUpload('{{ $jenis }}')">
                    <button type="submit" class="btn-upload-submit" id="btn-{{ $jenis }}" style="display:none;">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        <div style="padding:10px 18px;background:#f8f9fc;border-top:1px solid #f0f0f0;">
            <p style="font-size:.72rem;color:#888;margin:0;">
                <i class="fas fa-info-circle me-1 text-info"></i>
                Format: PDF, JPG, PNG — Maksimal 5 MB per file.
            </p>
        </div>
    </div>

</div>

{{-- ===== MODAL TAMBAH PRODI ===== --}}
<div class="modal fade" id="modalTambahProdi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;border:none;">
            <div class="modal-header" style="background:#4e73df;color:#fff;border:none;padding:12px 18px;">
                <h6 class="modal-title fw-bold mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Program Studi Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <div id="alertProdi"></div>
                <div style="margin-bottom:14px;">
                    <label class="f-label">Nama Program Studi <span class="req">*</span></label>
                    <input type="text" id="namaProdiInput" class="f-input" placeholder="Contoh: Teknik Informatika">
                </div>
                <div>
                    <label class="f-label">Jenjang</label>
                    <div class="f-select-wrap">
                        <select id="jenjangProdiInput" class="f-select">
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1" selected>S1</option>
                            <option value="S2">S2</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:12px 18px;">
                <button type="button"
                        style="padding:7px 16px;border-radius:7px;border:1.5px solid #ccc;background:#fff;color:#666;font-size:.8rem;font-weight:600;cursor:pointer;"
                        data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanProdi"
                        style="padding:7px 18px;border-radius:7px;background:#4e73df;color:#fff;border:none;font-size:.8rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                    <i class="fas fa-save"></i> Simpan Prodi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Skor kondisi OT real-time ──────────────────────
const skorMap = {
    'tidak_punya_ayah_ibu'        : 10,
    'hanya_ayah_sakit'            : 10,
    'hanya_ibu_sakit'             : 10,
    'ayah_ibu_sakit_tidak_bekerja': 10,
    'hanya_ayah_sehat'            : 5,
    'hanya_ibu_sehat'             : 5,
    'ada_ayah_sakit_tidak_bekerja': 5,
    'ada_ibu_sakit_tidak_bekerja' : 5,
    'ada_ayah_sakit_pensiun'      : 0,
    'ada_ibu_sakit_pensiun'       : 0,
    'ada_ayah_ibu_sehat'          : 0,
};

function updateSkorKondisi(val) {
    const info  = document.getElementById('skorKondisiInfo');
    const badge = document.getElementById('skorKondisiBadge');
    if (!val || skorMap[val] === undefined) { info.style.display = 'none'; return; }
    const skor = skorMap[val];
    badge.textContent = skor;
    badge.className   = 'skor-badge skor-' + skor;
    info.style.display = 'inline-flex';
}

// Trigger saat halaman load (untuk edit mode — nilai sudah terpilih)
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('kondisiSelect');
    if (sel && sel.value) updateSkorKondisi(sel.value);
});

// ── Auto-submit upload ─────────────────────────────
function autoSubmitUpload(jenis) {
    const input = document.getElementById('file-' + jenis);
    const btn   = document.getElementById('btn-' + jenis);
    const form  = document.getElementById('form-upload-' + jenis);
    if (!input.files || !input.files[0]) return;
    if (input.files[0].size > 5 * 1024 * 1024) {
        alert('File terlalu besar! Maksimal 5 MB.');
        input.value = ''; return;
    }
    btn.style.display = 'inline-flex';
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
    btn.disabled = true;
    form.submit();
}

// ── Tambah Prodi via AJAX ─────────────────────────
document.getElementById('btnSimpanProdi').addEventListener('click', function () {
    const nama    = document.getElementById('namaProdiInput').value.trim();
    const jenjang = document.getElementById('jenjangProdiInput').value;
    const alertEl = document.getElementById('alertProdi');
    const btn     = this;
    if (!nama) {
        alertEl.innerHTML = '<div style="background:#fff5f5;border:1px solid #fcd5d0;border-radius:7px;padding:8px 12px;color:#c0392b;font-size:.78rem;margin-bottom:10px;">Nama prodi tidak boleh kosong.</div>';
        return;
    }
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    fetch('{{ route("baa.prodi.quick-store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ nama, jenjang })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('prodiSelect');
            select.add(new Option(`${data.prodi.nama} (${data.prodi.jenjang})`, data.prodi.id, true, true));
            alertEl.innerHTML = '<div style="background:#e8f8f2;border:1px solid #b2dfdb;border-radius:7px;padding:8px 12px;color:#1a9e6f;font-size:.78rem;margin-bottom:10px;">Prodi berhasil ditambahkan!</div>';
            document.getElementById('namaProdiInput').value = '';
            setTimeout(() => { bootstrap.Modal.getInstance(document.getElementById('modalTambahProdi')).hide(); alertEl.innerHTML = ''; }, 1200);
        } else {
            alertEl.innerHTML = `<div style="background:#fff5f5;border:1px solid #fcd5d0;border-radius:7px;padding:8px 12px;color:#c0392b;font-size:.78rem;margin-bottom:10px;">${data.message || 'Gagal menyimpan.'}</div>`;
        }
    })
    .catch(() => {
        alertEl.innerHTML = '<div style="background:#fff5f5;border:1px solid #fcd5d0;border-radius:7px;padding:8px 12px;color:#c0392b;font-size:.78rem;margin-bottom:10px;">Terjadi kesalahan koneksi.</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Prodi';
    });
});
</script>
@endpush

@endsection