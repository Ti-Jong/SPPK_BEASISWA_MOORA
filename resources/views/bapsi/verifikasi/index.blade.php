@extends('layouts.app')
@section('title','Verifikasi Dokumen')
@section('page-title','Verifikasi Kelengkapan Dokumen')
@section('content')

@php
$statusMap = [
    'menunggu_verifikasi' => ['label' => 'Menunggu',  'badge' => 'b-warning'],
    'diproses'            => ['label' => 'Diproses',  'badge' => 'b-info'],
    'lulus_verifikasi'    => ['label' => 'Lulus',     'badge' => 'b-success'],
    'ditolak'             => ['label' => 'Ditolak',   'badge' => 'b-danger'],
];
@endphp

{{-- Filter Tahun --}}
<form method="GET" action="{{ route('bapsi.verifikasi.index') }}"
      style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Tahun Akademik:</label>
    <input type="number" name="tahun" value="{{ $tahun }}"
           min="2000" max="2100"
           style="width:80px;border:1px solid var(--border);border-radius:7px;padding:5px 9px;font-size:0.82rem">
    <button type="submit" class="btn btn-outline btn-sm">Tampilkan</button>
</form>

<div class="card">
    <div class="card-header">
    <h3>Data Calon &amp; Status Proses</h3>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span style="font-size:0.78rem;color:var(--text-muted)">
            {{ $calons->total() }} data ditemukan
        </span>
        {{-- Tombol Proses Semua --}}
        <form method="POST" action="{{ route('bapsi.verifikasi.approve.all') }}"
              style="display:inline"
              onsubmit="return confirm('Apakah yakin ingin memproses semua data?\n\nSeluruh data Menunggu & Diproses akan disetujui sekaligus.')">
            @csrf
            @if($tahun)
                <input type="hidden" name="tahun" value="{{ $tahun }}">
            @endif
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-check-double"></i> Proses Semua
            </button>
        </form>
    </div>
</div>
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($calons as $i => $c)
                @php
                    $st = $statusMap[$c->status] ?? ['label' => ucfirst($c->status), 'badge' => 'b-gray'];
                @endphp
                <tr>
                    <td style="color:var(--text-muted);font-size:0.75rem">
                        {{ $calons->firstItem() + $i }}
                    </td>
                    <td>
                        <code style="font-size:0.76rem">{{ $c->nim }}</code>
                    </td>
                    <td>
                        <strong>{{ $c->nama }}</strong>
                        @if($c->status === 'ditolak' && $c->alasan_penolakan)
                        <div style="font-size:0.7rem;color:var(--danger);margin-top:2px">
                            ⚠ {{ Str::limit($c->alasan_penolakan, 40) }}
                        </div>
                        @endif
                    </td>
                    <td style="font-size:0.8rem">
                        {{ $c->prodi?->nama ?? '-' }}
                    </td>
                    <td>
                        @if(method_exists($c, 'isDokumenLengkap'))
                            <span class="badge {{ $c->isDokumenLengkap() ? 'b-success' : 'b-warning' }}">
                                {{ $c->isDokumenLengkap() ? '✓ Lengkap' : '⚠ Kurang' }}
                            </span>
                        @else
                            <span class="badge b-gray">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $st['badge'] }}">{{ $st['label'] }}</span>
                    </td>
                    <td style="white-space:nowrap">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('bapsi.verifikasi.show', $c->id) }}"
                           class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        {{-- Tombol Verifikasi — hanya untuk menunggu/diproses --}}
                        @if(in_array($c->status, ['menunggu_verifikasi', 'diproses']))
                        <form method="POST"
                              action="{{ route('bapsi.verifikasi.approve', $c->id) }}"
                              style="display:inline">
                            @csrf
                            <button class="btn btn-success btn-sm"
                                    onclick="return confirm('Verifikasi data {{ addslashes($c->nama) }}?')">
                                <i class="fas fa-check"></i> Verifikasi
                            </button>
                        </form>

                        {{-- Tombol Tolak --}}
                        <button class="btn btn-danger btn-sm"
                                onclick="showTolakModal({{ $c->id }}, '{{ addslashes($c->nama) }}')">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted)">
                        <i class="fas fa-inbox fa-2x" style="display:block;margin-bottom:10px;opacity:.3"></i>
                        Tidak ada data calon untuk tahun <strong>{{ $tahun }}</strong>.<br>
                        <small>Pastikan BAA sudah mengirim data ke BAPSI.</small>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

     {{-- Pagination Custom --}}
@if($calons->hasPages())
<div style="padding:12px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
    
    {{-- Info --}}
    <span style="font-size:0.78rem;color:var(--text-muted)">
        Showing {{ $calons->firstItem() }}–{{ $calons->lastItem() }} of {{ $calons->total() }} results
    </span>

    {{-- Tombol Pagination --}}
    <div style="display:flex;align-items:center;gap:4px">

        {{-- Previous --}}
        @if($calons->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:#ccc;font-size:0.8rem;cursor:default">‹</span>
        @else
            <a href="{{ $calons->previousPageUrl() }}&tahun={{ $tahun }}"
               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.8rem;text-decoration:none;background:#fff"
               onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
               onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'">‹</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach($calons->getUrlRange(1, $calons->lastPage()) as $page => $url)
            @if($page == $calons->currentPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;background:var(--primary,#1e3a5f);color:#fff;font-size:0.78rem;font-weight:600;border:1px solid var(--primary,#1e3a5f)">{{ $page }}</span>
            @else
                <a href="{{ $url }}&tahun={{ $tahun }}"
                   style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.78rem;text-decoration:none;background:#fff"
                   onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
                   onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($calons->hasMorePages())
            <a href="{{ $calons->nextPageUrl() }}&tahun={{ $tahun }}"
               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:var(--text,#334155);font-size:0.8rem;text-decoration:none;background:#fff"
               onmouseover="this.style.background='var(--primary,#1e3a5f)';this.style.color='#fff'"
               onmouseout="this.style.background='#fff';this.style.color='var(--text,#334155)'">›</a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:6px;border:1px solid var(--border,#e2e8f0);color:#ccc;font-size:0.8rem;cursor:default">›</span>
        @endif

    </div>
</div>
@endif

{{-- Modal Tolak --}}
<div id="modalTolak"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px;width:100%;max-width:480px;margin:20px">
        <h3 style="font-size:1rem;margin-bottom:6px;color:var(--danger)">
            <i class="fas fa-times-circle"></i> Tolak Data Calon
        </h3>
        <p id="modalTolakNama" style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px"></p>
        <form id="formTolak" method="POST">
            @csrf
            <div class="f-group" style="margin-bottom:14px">
                <label>Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                <textarea name="alasan_penolakan" rows="4" required minlength="10"
                          placeholder="Jelaskan alasan penolakan secara detail (min. 10 karakter)..."
                          style="resize:vertical"></textarea>
            </div>
            <div class="f-group" style="margin-bottom:20px">
                <label>Catatan Tambahan (opsional)</label>
                <textarea name="catatan" rows="2"
                          placeholder="Catatan tambahan untuk BAA..."
                          style="resize:vertical"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* Fix ukuran pagination */
nav[aria-label="Pagination Navigation"],
.pagination,
[role="navigation"] {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}

/* Semua link/button pagination */
nav[aria-label="Pagination Navigation"] a,
nav[aria-label="Pagination Navigation"] span,
nav[aria-label="Pagination Navigation"] button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
    padding: 0 8px;
    font-size: 0.78rem;
    border-radius: 6px;
    border: 1px solid var(--border, #e2e8f0);
    background: #fff;
    color: var(--text, #334155);
    text-decoration: none;
    line-height: 1;
    cursor: pointer;
}

/* Ikon SVG di dalam pagination (Previous/Next arrow) */
nav[aria-label="Pagination Navigation"] svg {
    width: 12px !important;
    height: 12px !important;
}

/* Halaman aktif */
nav[aria-label="Pagination Navigation"] span[aria-current="page"] > span {
    background: var(--primary, #1e3a5f);
    color: #fff;
    border-color: var(--primary, #1e3a5f);
    font-weight: 600;
}

/* Disabled (ellipsis) */
nav[aria-label="Pagination Navigation"] span[aria-disabled="true"] span {
    color: var(--text-muted, #94a3b8);
    cursor: default;
    border-color: transparent;
    background: transparent;
}

/* Hover */
nav[aria-label="Pagination Navigation"] a:hover {
    background: var(--primary, #1e3a5f);
    color: #fff;
    border-color: var(--primary, #1e3a5f);
}
</style>
@endpush

@endsection

@push('scripts')
<script>
function showTolakModal(id, nama) {
    document.getElementById('modalTolakNama').textContent = 'Calon: ' + nama;
    document.getElementById('formTolak').action = '/bapsi/verifikasi/' + id + '/reject';
    document.getElementById('modalTolak').style.display = 'flex';
    document.querySelector('#formTolak textarea[name="alasan_penolakan"]').value = '';
    document.querySelector('#formTolak textarea[name="catatan"]').value = '';
}
function closeTolakModal() {
    document.getElementById('modalTolak').style.display = 'none';
}
// Tutup modal jika klik di luar
document.getElementById('modalTolak').addEventListener('click', function(e) {
    if (e.target === this) closeTolakModal();
});
</script>
@endpush
