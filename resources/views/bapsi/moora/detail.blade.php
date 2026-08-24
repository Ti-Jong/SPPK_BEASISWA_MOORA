@extends('layouts.app')
@section('title', 'Detail MOORA')
@section('page-title', 'Detail Perhitungan MOORA')

@push('styles')
<style>
:root {
    --blue:#4e73df; --blue-lt:#eef0fb;
    --green:#1cc88a; --green-lt:#e8f8f2;
    --red:#e74a3b; --red-lt:#fff5f5;
    --ink:#2d3748; --ink-3:#718096;
    --border:#e2e8f0; --surface:#f8fafc;
}
.v-card {
    background:#fff; border:1px solid var(--border);
    border-radius:10px; overflow:hidden;
    margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,.05);
}
.v-card-header {
    display:flex; align-items:center; gap:10px;
    padding:13px 18px; border-bottom:1px solid var(--border);
    font-weight:700; font-size:13.5px;
}
.detail-table { width:100%; border-collapse:collapse; font-size:13px; }
.detail-table th {
    background:var(--surface); padding:10px 14px;
    border-bottom:1px solid var(--border);
    font-size:11px; font-weight:700;
    text-transform:uppercase; letter-spacing:.06em;
    color:var(--ink-3); text-align:left;
}
.detail-table th.center { text-align:center; }
.detail-table td {
    padding:11px 14px; border-bottom:1px solid #f1f5f9;
    vertical-align:middle;
}
.detail-table tr:last-child td { border-bottom:none; }
.detail-table tr:hover td { background:#fafbff; }
.badge-type {
    display:inline-block; font-size:10px; font-weight:700;
    padding:2px 8px; border-radius:4px;
}
.badge-benefit { background:var(--green-lt); color:#15803d; }
.badge-cost    { background:var(--red-lt);   color:#dc2626; }
.info-grid {
    display:grid; grid-template-columns:1fr 1fr;
    gap:16px; padding:18px;
}
.info-item label {
    display:block; font-size:11px; font-weight:700;
    text-transform:uppercase; letter-spacing:.06em;
    color:var(--ink-3); margin-bottom:4px;
}
.info-item span {
    font-size:14px; font-weight:600; color:var(--ink);
}
.skor-big {
    font-size:32px; font-weight:800;
    color:var(--blue); letter-spacing:-.5px;
}
.rank-circle {
    display:inline-flex; align-items:center; justify-content:center;
    width:40px; height:40px; border-radius:50%;
    font-weight:800; font-size:16px;
    background:#fef9c3; color:#713f12;
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div style="margin-bottom:16px;font-size:13px;color:var(--ink-3)">
    <a href="{{ route('bapsi.moora.index') }}" style="color:var(--blue);text-decoration:none">
        Perhitungan MOORA
    </a>
    <span style="margin:0 6px">/</span>
    <span>Detail — {{ $hasil->calon->nama }}</span>
</div>

{{-- Info Calon --}}
<div class="v-card">
    <div class="v-card-header">
        <i class="fas fa-user" style="color:var(--blue)"></i>
        Informasi Calon
    </div>
    <div class="info-grid">
        <div class="info-item">
            <label>NIM</label>
            <span style="font-family:monospace">{{ $hasil->calon->nim }}</span>
        </div>
        <div class="info-item">
            <label>Nama</label>
            <span>{{ $hasil->calon->nama }}</span>
        </div>
        <div class="info-item">
            <label>Program Studi</label>
            <span>{{ $hasil->calon->prodi?->nama ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Periode</label>
            <span>{{ $hasil->periode_tahun }}</span>
        </div>
        <div class="info-item">
            <label>Skor MOORA (Yi)</label>
            <span class="skor-big">{{ number_format($hasil->skor_moora, 6) }}</span>
        </div>
        <div class="info-item">
            <label>Ranking</label>
            <span class="rank-circle">{{ $hasil->ranking }}</span>
        </div>
    </div>
</div>

 
{{-- ── Tabel Detail Perhitungan ── --}}
<div class="v-card">
    <div class="v-card-header">
        <i class="fas fa-table" style="color:var(--blue)"></i>
        Detail Perhitungan MOORA
    </div>
    <div style="overflow-x:auto">
        <table class="moora-table">
            <thead>
                <tr>
                    <th class="left" style="min-width:140px">Kriteria</th>
                    <th style="min-width:80px">Tipe</th>
                    <th style="min-width:100px">Bobot</th>
                    <th style="min-width:130px">Nilai Asli</th>
                    <th style="min-width:80px">Nilai Skor</th>
                    <th style="min-width:110px">Normalisasi</th>
                    <th style="min-width:110px">Terbobot</th>
                    <th style="min-width:110px">Kontribusi Yi</th>
                </tr>
            </thead>
            <tbody>
                @php $yi = 0; @endphp
 
                @foreach($detail as $key => $n)
                @php
                    $label     = $kriteriaInfo[$key]['label'] ?? ucfirst(str_replace('_', ' ', $key));
                    $tipe      = $n['type'] ?? 'benefit';
                    $bobot     = $n['bobot'] ?? 0;
                    $nilaiAsli = $n['nilai'] ?? null;
                    $nilaiSkor = $n['nilai_skor'] ?? $nilaiAsli ?? 0;
                    $norm      = $n['normalized'] ?? 0;
                    $weighted  = $n['weighted']   ?? 0;
                    $kontrib   = $tipe === 'benefit' ? $weighted : -$weighted;
                    $yi       += $kontrib;
                @endphp
                <tr>
                    <td style="text-align:left;font-weight:600">{{ $label }}</td>
 
                    {{-- Tipe: benefit / cost --}}
                    <td>
                        @if($tipe === 'benefit')
                            <span style="font-size:11px;font-weight:700;padding:2px 8px;
                                         border-radius:4px;background:#e8f8f2;color:#15803d">
                                + Benefit
                            </span>
                        @else
                            <span style="font-size:11px;font-weight:700;padding:2px 8px;
                                         border-radius:4px;background:#fff5f5;color:#dc2626">
                                − Cost
                            </span>
                        @endif
                    </td>
 
                    {{-- Bobot --}}
                    <td style="font-weight:600">
                        {{ number_format($bobot * 100, 0) }}%
                    </td>
 
                    {{-- Nilai Asli (rupiah atau label) --}}
                    <td style="font-size:12px;color:#555">
                        @if($nilaiAsli === null)
                            <span class="text-muted">—</span>
                        @elseif(in_array($key, ['penghasilan_ortu', 'tagihan_listrik']))
                            Rp {{ number_format((float)$nilaiAsli, 0, ',', '.') }}
                        @elseif($key === 'kondisi_ortu')
                            {{ $nilaiAsli ?? '—' }}
                        @else
                            {{ $nilaiAsli }}
                        @endif
                    </td>
 
                    {{-- Nilai Skor (yang dipakai di MOORA) --}}
                    <td>
                        <span style="font-weight:700;font-size:13px">
                            {{ is_numeric($nilaiSkor) ? number_format((float)$nilaiSkor, 0) : ($nilaiSkor ?? '—') }}
                        </span>
                    </td>
 
                    {{-- Normalisasi --}}
                    <td style="font-family:monospace;font-size:12px">
                        {{ number_format((float)$norm, 6) }}
                    </td>
 
                    {{-- Terbobot --}}
                    <td style="font-family:monospace;font-size:12px">
                        {{ number_format((float)$weighted, 6) }}
                    </td>
 
                    {{-- Kontribusi ke Yi --}}
                    <td style="font-weight:700;font-size:12px;
                        {{ $tipe === 'benefit' ? 'color:#15803d' : 'color:#dc2626' }}">
                        {{ $tipe === 'benefit' ? '+' : '−' }}{{ number_format(abs($kontrib), 6) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
 
            {{-- Total Yi --}}
            <tfoot>
                <tr style="background:#eef0fb">
                    <td colspan="7"
                        style="text-align:right;font-weight:700;font-size:13px;
                               padding:10px 12px;color:#2d3748">
                        Total Skor MOORA (Yi)
                    </td>
                    <td style="font-weight:800;font-size:14px;
                               color:{{ $yi >= 0 ? '#1d4ed8' : '#dc2626' }};
                               font-family:monospace;padding:10px 12px">
                        {{ number_format($yi, 6) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Tombol Kembali --}}
<a href="{{ route('bapsi.moora.index') }}"
   style="display:inline-flex;align-items:center;gap:6px;
          padding:9px 20px;border-radius:8px;
          border:1.5px solid var(--border);color:var(--ink-3);
          font-size:13px;font-weight:600;text-decoration:none;
          transition:all .15s"
   onmouseover="this.style.borderColor='#4e73df';this.style.color='#4e73df'"
   onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--ink-3)'">
    <i class="fas fa-arrow-left"></i> Kembali ke MOORA
</a>

@endsection