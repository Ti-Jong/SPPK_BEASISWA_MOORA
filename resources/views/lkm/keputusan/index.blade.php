@extends('layouts.app')
@section('title', 'Tetapkan Penerima')
@section('page-title', 'Penetapan Penerima Beasiswa')

@section('content')

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- ── Filter Tahun ── --}}
<form method="GET" action="{{ route('lkm.keputusan.index') }}"
      style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap">
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Periode:</label>
    <select name="tahun"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem"
            onchange="this.form.submit()">
        @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>
</form>

{{-- ── Panel Aksi ── --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div>
            <h3>Penetapan Penerima — Periode {{ $tahun }}</h3>
            <span style="font-size:0.78rem;color:var(--text-muted)">
                Berdasarkan ranking MOORA per prodi dan kuota yang tersedia
            </span>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            {{-- Tetapkan / Tetapkan Ulang --}}
            <form method="POST" action="{{ route('lkm.keputusan.tetapkan') }}"
                  onsubmit="return confirm('{{ $sudahDitetapkan
                      ? 'Tetapkan ulang? Keputusan sebelumnya akan ditimpa.'
                      : 'Tetapkan penerima beasiswa berdasarkan ranking dan kuota per prodi?' }}')">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button class="btn btn-primary">
                    <i class="fas fa-{{ $sudahDitetapkan ? 'sync' : 'check-double' }}"></i>
                    {{ $sudahDitetapkan ? 'Tetapkan Ulang' : 'Tetapkan Penerima' }}
                </button>
            </form>

            @if($sudahDitetapkan)
            {{-- Export CSV --}}
            <a href="{{ route('lkm.keputusan.export', ['tahun' => $tahun, 'format' => 'csv']) }}"
               class="btn btn-outline">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            {{-- Export HTML/Print --}}
            <a href="{{ route('lkm.keputusan.export', ['tahun' => $tahun, 'format' => 'html']) }}"
               target="_blank" class="btn btn-outline">
                <i class="fas fa-print"></i> Cetak / PDF
            </a>
            {{-- Finalisasi --}}
            <form method="POST" action="{{ route('lkm.keputusan.finalize') }}" style="display:inline">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button class="btn btn-accent">
                    <i class="fas fa-lock"></i> Finalisasi
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Ringkasan --}}
    @if($sudahDitetapkan)
    <div class="card-body" style="padding:14px 20px">
        <div style="display:flex;gap:16px;flex-wrap:wrap;align-items:center">
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:3px;background:#86efac"></div>
                <span style="font-size:0.8rem;color:var(--text-muted)">
                    Total penerima: <strong style="color:var(--success)">{{ $totalPenerima }} mahasiswa</strong>
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:3px;background:#1e3a5c"></div>
                <span style="font-size:0.8rem;color:var(--text-muted)">
                    Dari {{ $rankingPerProdi->count() }} program studi
                </span>
            </div>
        </div>
    </div>
    @else
    <div class="card-body">
        <div class="alert alert-info" style="margin:0">
            <i class="fas fa-info-circle"></i>
            Klik <strong>Tetapkan Penerima</strong> untuk menentukan penerima beasiswa secara otomatis
            berdasarkan ranking MOORA dan kuota tiap prodi.
        </div>
    </div>
    @endif
</div>

{{-- ── Tabel Per Prodi ── --}}
@forelse($rankingPerProdi as $group)
@php
    $prodi       = $group['prodi'];
    $items       = $group['items'];
    $kuota       = $group['kuota'];
    $sudahDitap  = $group['sudah_ditap'];
    $sisaKuota   = $group['sisa_kuota'];
@endphp

<div class="card" style="margin-bottom:18px">
    {{-- Header Prodi --}}
    <div class="card-header"
         style="background:linear-gradient(135deg,#1a3a5c,#2563a8);border-radius:13px 13px 0 0">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.15);
                        display:flex;align-items:center;justify-content:center;font-size:1.1rem">🎓</div>
            <div>
                <h3 style="color:#fff;font-size:0.92rem;margin:0;font-weight:700">
                    {{ $prodi?->nama ?? 'Prodi Tidak Diketahui' }}
                </h3>
                <span style="color:rgba(255,255,255,.65);font-size:0.72rem">
                    {{ $prodi?->jenjang ?? '' }} &nbsp;·&nbsp; {{ $group['total'] }} calon
                </span>
            </div>
        </div>
        {{-- Kuota Info --}}
        <div style="display:flex;gap:8px">
            <div style="background:rgba(255,255,255,.15);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fff;font-weight:800;font-size:1.1rem;line-height:1">{{ $kuota }}</div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Kuota</div>
            </div>
            <div style="background:rgba(5,150,105,.3);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#6ee7b7;font-weight:800;font-size:1.1rem;line-height:1">{{ $sudahDitap }}</div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Ditetapkan</div>
            </div>
            @if($sisaKuota > 0)
            <div style="background:rgba(217,119,6,.3);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fcd34d;font-weight:800;font-size:1.1rem;line-height:1">{{ $sisaKuota }}</div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Sisa</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Rank Prodi</th>
                        <th>Rank Global</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Semester</th>
                        <th>Skor MOORA</th>
                        <th>Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $hasil)
                @php
                    $rankProdi  = $hasil->ranking_prodi ?? ($loop->iteration);
                    $rankGlobal = $hasil->ranking ?? '-';
                    $skor       = (float)($hasil->skor_moora ?? $hasil->yi_score ?? 0);

                    // ── FIX: cocokkan Keputusan lewat calon_id, BUKAN hasil_moora_id ──
                    // Satu calon bisa punya beberapa baris HasilMoora (tahap P1/P2/P3),
                    // tapi Keputusan finalnya cuma satu per tahun. $hasil->keputusan
                    // (relasi via hasil_moora_id) bisa null kalau baris HasilMoora yang
                    // sedang ditampilkan bukan yang terhubung ke Keputusan tersimpan.
                    $keputusan  = $keputusanByCalon->get($hasil->calon_id);

                    $lolos      = $rankProdi <= $kuota;
                    $status     = $keputusan?->status ?? null;
                @endphp
                <tr style="{{ $lolos ? 'background:#f0fdf4' : '' }}">
                    {{-- Rank Prodi --}}
                    <td>
                        @if($rankProdi == 1) <span style="font-size:1.2rem">🥇</span>
                        @elseif($rankProdi == 2) <span style="font-size:1.2rem">🥈</span>
                        @elseif($rankProdi == 3) <span style="font-size:1.2rem">🥉</span>
                        @else
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         width:26px;height:26px;border-radius:6px;background:#f1f5f9;
                                         font-weight:700;font-size:0.78rem;color:var(--text-muted)">
                                {{ $rankProdi }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size:0.75rem;color:var(--text-muted)">#{{ $rankGlobal }}</span>
                    </td>
                    <td><code style="font-size:0.75rem">{{ $hasil->calon->nim ?? '-' }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:0.84rem">{{ $hasil->calon->nama ?? '-' }}</div>
                        @if($keputusan?->keterangan)
                        <div style="font-size:0.68rem;color:var(--text-muted);margin-top:1px">
                            {{ $keputusan->keterangan }}
                        </div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem">{{ $hasil->calon->semester ?? '-' }}</td>
                    <td>
                        <span style="font-weight:700;font-size:0.85rem;
                                     color:{{ $skor >= 0 ? '#059669' : '#dc2626' }}">
                            {{ number_format($skor, 4) }}
                        </span>
                    </td>
                    {{-- Keputusan --}}
                    <td>
                        @if($status === 'penerima')
                            <span class="badge b-success">✓ Penerima</span>
                        @elseif($status === 'cadangan')
                            <span class="badge b-warning">⏳ Cadangan</span>
                        @elseif($status === 'tidak_lolos')
                            <span class="badge b-gray">✗ Tidak Lolos</span>
                        @elseif($lolos)
                            <span class="badge b-info">Akan Diterima</span>
                        @else
                            <span class="badge b-gray" style="opacity:.6">Tidak Lolos</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer prodi --}}
        <div style="padding:10px 16px;border-top:1px solid var(--border);background:#f8fafc;
                    border-radius:0 0 13px 13px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
            <span style="font-size:0.75rem;color:var(--text-muted)">
                <span style="color:var(--success);font-weight:700">{{ min($kuota, $group['total']) }}</span>
                diterima &nbsp;·&nbsp;
                <span style="color:var(--text-muted)">{{ max(0, $group['total'] - $kuota) }}</span>
                tidak lolos
                @if($sisaKuota > 0)
                    &nbsp;·&nbsp;
                    <span style="color:var(--warning);font-weight:600">⚠ {{ $sisaKuota }} kuota tidak terisi</span>
                @endif
            </span>
        </div>
    </div>
</div>

@empty
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px">
        <i class="fas fa-trophy" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:12px"></i>
        <p style="color:var(--text-muted);font-size:0.9rem">
            Belum ada data ranking untuk periode <strong>{{ $tahun }}</strong>.
        </p>
        <p style="color:var(--text-muted);font-size:0.8rem;margin-top:6px">
            Minta Admin BAPSI untuk menjalankan perhitungan MOORA terlebih dahulu.
        </p>
    </div>
</div>
@endforelse

@endsection