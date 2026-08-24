@extends('layouts.app')

@section('title', 'Detail Calon — ' . $calon->nama)

@section('content')
<style>
.detail-section {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    overflow: hidden;
    margin-bottom: 16px;
}
.detail-section-header {
    padding: 10px 16px;
    display: flex; align-items: center; justify-content: space-between;
    font-weight: 600; font-size: .83rem;
}
.dsh-blue   { background: #4e73df; color: #fff; }
.dsh-green  { background: #1cc88a; color: #fff; }
.dsh-info   { background: #36b9cc; color: #fff; }
.dsh-danger { background: #e74a3b; color: #fff; }
.dsh-gold   { background: #f6c23e; color: #fff; }

.detail-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.detail-table th { width: 42%; padding: 9px 16px; color: #888; font-weight: 500; border-bottom: 1px solid #f5f5f5; vertical-align: top; }
.detail-table td { padding: 9px 16px; color: #2d3748; border-bottom: 1px solid #f5f5f5; }
.detail-table tr:last-child th,
.detail-table tr:last-child td { border-bottom: none; }

/* Badge status */
.bs { display:inline-block; padding:3px 11px; border-radius:20px; font-size:.72rem; font-weight:600; }
.bs-draft        { background:#f0f0f0; color:#666; }
.bs-diajukan     { background:#fef9ec; color:#d4a017; }
.bs-diverifikasi { background:#e8f8f2; color:#1a9e6f; }
.bs-ditolak      { background:#fdf0ee; color:#e74a3b; }
.bs-ditetapkan   { background:#eef0fb; color:#4e73df; }

/* Kotak penolakan */
.penolakan-card {
    border: 2px solid #e74a3b;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 2px 10px rgba(231,74,59,.15);
}
.penolakan-header {
    background: #e74a3b; color: #fff;
    padding: 10px 16px;
    display: flex; align-items: center; gap: 8px;
    font-weight: 600;
}
.penolakan-body { padding: 16px; background: #fffafa; }
.alasan-box {
    background: #fff0ef; border: 1px solid #fcd5d0;
    border-radius: 8px; padding: 12px 14px;
    color: #c0392b; font-size: .85rem; line-height: 1.5;
    margin-bottom: 12px;
}
.catatan-box {
    background: #f8f9fa; border: 1px solid #e9ecef;
    border-radius: 8px; padding: 10px 14px;
    color: #555; font-size: .82rem;
}

/* Dokumen list */
.dok-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; border-bottom: 1px solid #f5f5f5;
    font-size: .82rem;
}
.dok-item:last-child { border-bottom: none; }
.dok-jenis { display: flex; align-items: center; gap: 8px; }
.dok-icon { width: 30px; height: 30px; border-radius: 6px; background: #e3f2fd; color: #1565c0; display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
.dok-status { padding: 2px 8px; border-radius: 12px; font-size: .68rem; font-weight: 600; }
.dok-status-pending { background: #fef9ec; color: #d4a017; }
.dok-status-valid   { background: #e8f8f2; color: #1a9e6f; }
.dok-status-invalid { background: #fdf0ee; color: #e74a3b; }
.dok-actions { display: flex; align-items: center; gap: 6px; }

/* Btn */
.btn-sm-outline {
    padding: 4px 12px; border-radius: 6px; font-size: .76rem; font-weight: 600;
    border: 1.5px solid; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px; transition: all .12s;
}
.btn-sm-primary { background: #4e73df; color: #fff; border-color: #4e73df; }
.btn-sm-primary:hover { background: #3756c0; color: #fff; }
.btn-sm-danger  { background: #e74a3b; color: #fff; border-color: #e74a3b; }
.btn-sm-danger:hover  { background: #c0392b; color: #fff; }
.btn-sm-info    { color: #36b9cc; border-color: #36b9cc; }
.btn-sm-info:hover    { background: #36b9cc; color: #fff; }
.btn-sm-del     { color: #e74a3b; border-color: #e74a3b; }
.btn-sm-del:hover     { background: #e74a3b; color: #fff; }
.btn-sm-sec     { color: #666; border-color: #ccc; }
.btn-sm-sec:hover     { background: #f0f0f0; }
.btn-sm-warn    { color: #d4a017; border-color: #f6c23e; }
.btn-sm-warn:hover    { background: #fef9ec; }

/* Upload area di modal */
.upload-drop {
    border: 2px dashed #d0d5dd;
    border-radius: 10px;
    padding: 24px;
    text-align: center;
    color: #888;
    font-size: .83rem;
    cursor: pointer;
    transition: all .15s;
}
.upload-drop:hover { border-color: #4e73df; color: #4e73df; background: #f7f9ff; }
.upload-drop input[type=file] { display: none; }

/* Rapor badge */
.rapor-badge { background: #e3f2fd; color: #1565c0; padding: 3px 10px; border-radius: 12px; font-weight: 700; font-size: .85rem; }

/* Info row */
.info-meta { font-size: .75rem; color: #888; display: flex; align-items: center; gap: 5px; }
</style>

<div class="container-fluid px-3">

    {{-- ===== HEADER ===== --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <div>
            <h5 class="mb-1 fw-bold">Detail Calon Mahasiswa</h5>
            <nav style="font-size:.78rem;">
                <a href="{{ route('baa.dashboard') }}" style="color:#4e73df;text-decoration:none;">Dashboard</a>
                <span class="mx-1 text-muted">/</span>
                <a href="{{ route('baa.calon.index') }}" style="color:#4e73df;text-decoration:none;">Input Calon</a>
                <span class="mx-1 text-muted">/</span>
                <span class="text-muted">{{ $calon->nim }}</span>
            </nav>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            @if(in_array($calon->status, ['draft', 'ditolak']))
            <a href="{{ route('baa.calon.edit', $calon->id) }}" class="btn-sm-outline btn-sm-warn">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            @endif
            <a href="{{ route('baa.calon.index') }}" class="btn-sm-outline btn-sm-sec">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 small mb-3">
        <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ===== KOTAK PENOLAKAN ===== --}}
    @if($calon->status === 'ditolak')
    <div class="penolakan-card">
        <div class="penolakan-header">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>Data Ditolak oleh BAPSI</span>
        </div>
        <div class="penolakan-body">
            @if($calon->alasan_penolakan)
            <div class="mb-2" style="font-size:.78rem;font-weight:600;color:#c0392b;">
                <i class="fas fa-exclamation-triangle me-1"></i>Alasan Penolakan:
            </div>
            <div class="alasan-box">{{ $calon->alasan_penolakan }}</div>
            @endif

            @if($calon->catatan)
            <div class="mb-2" style="font-size:.78rem;font-weight:600;color:#666;">
                <i class="fas fa-sticky-note me-1"></i>Catatan Tambahan:
            </div>
            <div class="catatan-box mb-3">{{ $calon->catatan }}</div>
            @endif

            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                <div class="info-meta">
                    <i class="fas fa-user"></i>
                    Ditolak oleh: <strong>{{ $calon->verifikasiBy->name ?? '-' }}</strong>
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock"></i>
                    {{ $calon->verifikasi_at?->format('d M Y, H:i') ?? '-' }}
                </div>
                <a href="{{ route('baa.calon.edit', $calon->id) }}" class="btn-sm-outline btn-sm-danger">
                    <i class="fas fa-wrench"></i> Perbaiki Data Sekarang
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-3">

        {{-- ===== DATA AKADEMIK ===== --}}
        <div class="col-lg-6">
            <div class="detail-section">
                <div class="detail-section-header dsh-blue">
                    <span><i class="fas fa-graduation-cap me-2"></i>Data Akademik</span>
                </div>
                <table class="detail-table">
                    <tr><th>NIM</th><td><strong style="font-family:monospace;">{{ $calon->nim }}</strong></td></tr>
                    <tr><th>Nama Lengkap</th><td>{{ $calon->nama }}</td></tr>
                    <tr><th>Asal Sekolah</th><td>{{ $calon->asal_sekolah ?? '<span style="color:#aaa">-</span>' }}</td></tr>
                    <tr><th>Program Studi</th><td>{{ $calon->prodiAsal->nama ?? $calon->prodi->nama ?? '-' }}</td></tr>

                    <tr><th>Tahun Akademik</th><td>{{ $calon->tahun_akademik }}</td></tr>
                    <tr>
                        <th>Rapor Semester 3</th>
                        <td>
                            @if($calon->nilai_rapor_sem3)
                                <span class="rapor-badge">{{ number_format($calon->nilai_rapor_sem3, 2) }}</span>
                            @else <span style="color:#aaa">Belum diisi</span> @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Rapor Semester 4</th>
                        <td>
                            @if($calon->nilai_rapor_sem4)
                                <span class="rapor-badge">{{ number_format($calon->nilai_rapor_sem4, 2) }}</span>
                            @else <span style="color:#aaa">Belum diisi</span> @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php $bsMap=['draft'=>'bs-draft','diajukan'=>'bs-diajukan','diverifikasi'=>'bs-diverifikasi','ditolak'=>'bs-ditolak','ditetapkan'=>'bs-ditetapkan']; @endphp
                            <span class="bs {{ $bsMap[$calon->status] ?? 'bs-draft' }}">{{ ucfirst($calon->status) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ===== DATA EKONOMI ===== --}}
        <div class="col-lg-6">
            <div class="detail-section">
                <div class="detail-section-header dsh-green">
                    <span><i class="fas fa-wallet me-2"></i>Data Ekonomi</span>
                </div>
                <table class="detail-table">
                    <tr>
                        <th>Kondisi Orang Tua</th>
                        <td>{{ $calon->kondisi_ortu_label ?? $calon->kondisi_ortu ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Penghasilan OT</th>
                        <td>Rp {{ number_format($calon->penghasilan_ortu ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tagihan Listrik</th>
                        <td>Rp {{ number_format($calon->tagihan_listrik ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggungan Anak</th>
                        <td>{{ $calon->tanggungan_anak ?? '-' }} anak</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ===== SKOR MOORA ===== --}}
        @if($calon->skor_moora)
        <div class="col-12">
            <div class="detail-section">
                <div class="detail-section-header dsh-gold">
                    <span><i class="fas fa-calculator me-2"></i>Hasil Kalkulasi MOORA</span>
                </div>
                <div style="padding:16px;display:flex;gap:32px;">
                    <div style="text-align:center;">
                        <div style="font-size:.75rem;color:#888;margin-bottom:4px;">Skor MOORA</div>
                        <div style="font-size:1.6rem;font-weight:700;color:#4e73df;">{{ number_format($calon->skor_moora, 4) }}</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:.75rem;color:#888;margin-bottom:4px;">Peringkat</div>
                        <div style="font-size:1.6rem;font-weight:700;color:#1cc88a;">#{{ $calon->ranking }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== DOKUMEN PENDUKUNG ===== --}}
        <div class="col-12">
            <div class="detail-section">
                <div class="detail-section-header dsh-info">
                    <span><i class="fas fa-paperclip me-2"></i>Dokumen Pendukung</span>
                    @if(in_array($calon->status, ['draft', 'ditolak']))
                    {{-- Tombol Upload — trigger modal, BUKAN link GET --}}
                    <button type="button" class="btn-sm-outline"
                            style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.5);color:#fff;font-size:.75rem;padding:4px 12px;"
                            data-bs-toggle="modal" data-bs-target="#modalUpload">
                        <i class="fas fa-upload me-1"></i>Upload Dokumen
                    </button>
                    @endif
                </div>

                @php
                    $jenisWajib = \App\Models\CalonMahasiswa::jenisDokumenWajib();
                    $uploaded   = $calon->dokumen->keyBy('jenis');
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
                @endphp

                @if($calon->dokumen->count() > 0 || in_array($calon->status, ['draft','ditolak']))

                {{-- Progress bar dokumen --}}
                @php
                    $total    = count($jenisWajib);
                    $uploaded_count = $calon->dokumen->count();
                    $pct      = $total > 0 ? min(100, round($uploaded_count / $total * 100)) : 0;
                @endphp
                <div style="padding:10px 16px;border-bottom:1px solid #f5f5f5;background:#fafbff;">
                    <div style="display:flex;justify-content:space-between;font-size:.75rem;color:#666;margin-bottom:5px;">
                        <span><i class="fas fa-file-check me-1"></i>{{ $uploaded_count }} / {{ $total }} dokumen wajib diunggah</span>
                        <span style="font-weight:600;color:{{ $pct == 100 ? '#1a9e6f' : '#d4a017' }}">{{ $pct }}%</span>
                    </div>
                    <div style="height:6px;background:#f0f0f0;border-radius:10px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct == 100 ? '#1cc88a' : '#f6c23e' }};border-radius:10px;transition:width .3s;"></div>
                    </div>
                </div>

                {{-- List dokumen wajib --}}
                @foreach($jenisWajib as $jenis)
                @php $dok = $uploaded->get($jenis); @endphp
                <div class="dok-item">
                    <div class="dok-jenis">
                        <div class="dok-icon" style="{{ $dok ? 'background:#e8f8f2;color:#1a9e6f;' : 'background:#f0f0f0;color:#aaa;' }}">
                            <i class="fas fa-{{ $dok ? 'file-check' : 'file' }}"></i>
                        </div>
                        <div>
                            <div style="font-weight:500;">{{ $jenisLabel[$jenis] ?? $jenis }}</div>
                            @if($dok)
                            <div style="font-size:.7rem;color:#888;">
                                {{ $dok->file_name }} · {{ $dok->file_size_human ?? round($dok->file_size/1024).'KB' }}
                            </div>
                            @else
                            <div style="font-size:.7rem;color:#aaa;">Belum diunggah</div>
                            @endif
                        </div>
                    </div>
                    <div class="dok-actions">
                        @if($dok)
                            @php
                                $stClass = match($dok->status){
                                    'valid'   => 'dok-status-valid',
                                    'invalid' => 'dok-status-invalid',
                                    default   => 'dok-status-pending'
                                };
                            @endphp
                            <span class="dok-status {{ $stClass }}">{{ ucfirst($dok->status) }}</span>
                            <a href="{{ $dok->url }}" target="_blank" class="btn-sm-outline btn-sm-info" style="padding:3px 9px;font-size:.72rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array($calon->status, ['draft','ditolak']))
                            <form action="{{ route('baa.dokumen.destroy', $dok->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus dokumen ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm-outline btn-sm-del" style="padding:3px 9px;font-size:.72rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        @else
                            <span style="font-size:.72rem;color:#aaa;">—</span>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Dokumen tambahan (non-wajib) --}}
                @foreach($calon->dokumen->whereNotIn('jenis', $jenisWajib) as $dok)
                <div class="dok-item" style="background:#fafafa;">
                    <div class="dok-jenis">
                        <div class="dok-icon"><i class="fas fa-file"></i></div>
                        <div>
                            <div style="font-weight:500;">{{ $dok->label_jenis }}</div>
                            <div style="font-size:.7rem;color:#888;">{{ $dok->file_name }}</div>
                        </div>
                    </div>
                    <div class="dok-actions">
                        <a href="{{ $dok->url }}" target="_blank" class="btn-sm-outline btn-sm-info" style="padding:3px 9px;font-size:.72rem;">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(in_array($calon->status, ['draft','ditolak']))
                        <form action="{{ route('baa.dokumen.destroy', $dok->id) }}" method="POST"
                              onsubmit="return confirm('Hapus?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm-outline btn-sm-del" style="padding:3px 9px;font-size:.72rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach

                @else
                <div style="text-align:center;padding:32px;color:#aaa;">
                    <i class="fas fa-folder-open fa-2x" style="display:block;margin-bottom:8px;"></i>
                    Belum ada dokumen diunggah.
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ===== TOMBOL AJUKAN KE BAPSI ===== --}}
    @if($calon->status === 'draft')
    <div style="display:flex;justify-content:flex-end;margin-top:16px;margin-bottom:32px;">
        <form action="{{ route('baa.calon.kirim', $calon->id) }}" method="POST"
              onsubmit="return confirm('Ajukan data {{ addslashes($calon->nama) }} ke BAPSI?')">
            @csrf
            <button type="submit" class="btn-sm-outline btn-sm-primary" style="padding:8px 28px;font-size:.85rem;">
                <i class="fas fa-paper-plane me-2"></i>Ajukan ke BAPSI
            </button>
        </form>
    </div>
    @endif

</div>

{{-- ===== MODAL UPLOAD DOKUMEN ===== --}}
@if(in_array($calon->status, ['draft', 'ditolak']))
<div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="modalUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header" style="background:#36b9cc;color:#fff;border:none;">
                <h6 class="modal-title fw-bold" id="modalUploadLabel">
                    <i class="fas fa-upload me-2"></i>Upload Dokumen
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('baa.dokumen.upload', $calon->id) }}" method="POST"
                  enctype="multipart/form-data" id="formUpload">
                @csrf
                <div class="modal-body" style="padding:20px;">

                    {{-- Pilih Jenis --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Jenis Dokumen <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select form-select-sm" required id="selectJenis">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="surat_rekomendasi">Surat Rekomendasi</option>
                            <option value="rapor_semester_3">Rapor Semester 3</option>
                            <option value="rapor_semester_4">Rapor Semester 4</option>
                            <option value="piagam">Piagam Penghargaan</option>
                            <option value="kartu_keluarga">Kartu Keluarga</option>
                            <option value="foto_rumah">Foto Rumah</option>
                            <option value="sktm">SKTM</option>
                            <option value="tagihan_listrik">Tagihan Listrik</option>
                            <option value="surat_penghasilan">Surat Penghasilan OT</option>
                        </select>
                    </div>

                    {{-- Upload File --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">File <span class="text-danger">*</span></label>
                        <div class="upload-drop" id="dropArea" onclick="document.getElementById('fileInput').click()">
                            <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required
                                   onchange="updateFileLabel(this)">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="display:block;"></i>
                            <div id="fileLabel">Klik untuk pilih file</div>
                            <div style="font-size:.72rem;color:#aaa;margin-top:4px;">PDF, JPG, PNG — maksimal 5MB</div>
                        </div>
                    </div>

                    {{-- Info overwrite --}}
                    <div style="background:#fef9ec;border:1px solid #fde8a8;border-radius:8px;padding:8px 12px;font-size:.76rem;color:#856404;">
                        <i class="fas fa-info-circle me-1"></i>
                        Jika jenis dokumen sudah ada, file lama akan diganti otomatis.
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:12px 20px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm" style="background:#36b9cc;color:#fff;font-weight:600;">
                        <i class="fas fa-upload me-1"></i>Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    if (input.files && input.files[0]) {
        const f = input.files[0];
        const size = f.size > 1048576
            ? (f.size/1048576).toFixed(1)+' MB'
            : Math.round(f.size/1024)+' KB';
        label.innerHTML = `<strong>${f.name}</strong> <span style="color:#888;">(${size})</span>`;
        document.getElementById('dropArea').style.borderColor = '#1cc88a';
        document.getElementById('dropArea').style.color = '#1a9e6f';
    }
}
</script>
@endpush

@endsection