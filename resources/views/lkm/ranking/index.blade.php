@extends('layouts.app')
@section('title', 'Ranking MOORA')
@section('page-title', 'Ranking MOORA')

@section('content')

{{-- ── Filter ── --}}
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap">
    <form method="GET" action="{{ route('lkm.ranking.index') }}"
          style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">

        <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Periode:</label>
        <select name="tahun"
                style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem"
                onchange="this.form.submit()">
            @foreach($tahunList as $t)
                <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>

        <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Prodi:</label>
        <select name="prodi_id"
                style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:180px"
                onchange="this.form.submit()">
            <option value="">— Semua Prodi —</option>
            @foreach($prodis as $p)
                <option value="{{ $p->id }}" {{ $p->id == $prodiFilter ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- ── Stats ── --}}
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val">{{ $statistik['total_calon'] }}</div>
            <div class="stat-label">Total Calon</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <div class="stat-val">{{ $statistik['total_prodi'] }}</div>
            <div class="stat-label">Program Studi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="stat-val">{{ number_format($statistik['skor_max'], 4) }}</div>
            <div class="stat-label">Skor Tertinggi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-orange"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="stat-val">{{ number_format($statistik['skor_min'], 4) }}</div>
            <div class="stat-label">Skor Terendah</div>
        </div>
    </div>
</div>

{{-- ── Ranking Per Prodi ── --}}
@forelse($rankingPerProdi as $group)
@php
    $prodi  = $group['prodi'];
    $items  = $group['items'];
    $kuota  = $prodi?->kuota_beasiswa ?? 0;
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
        {{-- Kuota badge --}}
        <div style="display:flex;gap:8px;align-items:center">
            <div style="background:rgba(255,255,255,.15);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fff;font-weight:800;font-size:1.1rem;line-height:1">{{ $kuota }}</div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Kuota Prodi</div>
            </div>
            <div style="background:rgba(232,160,32,.25);border-radius:8px;padding:7px 14px;text-align:center">
                <div style="color:#fcd34d;font-weight:800;font-size:1.1rem;line-height:1">
                    {{ min($kuota, $group['total']) }}
                </div>
                <div style="color:rgba(255,255,255,.65);font-size:0.68rem;margin-top:2px">Akan Diterima</div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:70px">Rank Prodi</th>
                        <th style="width:70px">Rank Global</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Skor MOORA</th>
                        <th>Visualisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $hasil)
                @php
                    $rankProdi  = $hasil->ranking_prodi ?? ($loop->iteration);
                    $rankGlobal = $hasil->ranking ?? '-';
                    $skor       = (float)($hasil->skor_moora ?? 0);
                    // FIX: status_seleksi sekarang single-stage — hanya 'lolos' / 'ditolak'
                    // (sebelumnya cek in_array(['lolos_p1','lolos_p2']), sudah tidak berlaku
                    // sejak migration simplify_status_seleksi_enum).
                    $lolos      = ($hasil->calon->status_seleksi ?? '') === 'lolos';
                    $localMax   = $items->max(fn($h) => (float)($h->skor_moora ?? 0)) ?: 1;
                    $pct        = $localMax > 0 ? round(abs($skor / $localMax) * 100, 1) : 0;
                @endphp
                <tr style="{{ $lolos ? 'background:#f0fdf4' : '' }}">
                    {{-- Rank Prodi --}}
                    <td>
                        @if($rankProdi == 1)
                            <span style="font-size:1.3rem">🥇</span>
                        @elseif($rankProdi == 2)
                            <span style="font-size:1.3rem">🥈</span>
                        @elseif($rankProdi == 3)
                            <span style="font-size:1.3rem">🥉</span>
                        @else
                            <div style="display:inline-flex;align-items:center;justify-content:center;
                                        width:28px;height:28px;border-radius:7px;
                                        background:var(--bg);font-weight:700;font-size:0.8rem;
                                        color:var(--text-muted)">
                                {{ $rankProdi }}
                            </div>
                        @endif
                    </td>
                    {{-- Rank Global --}}
                    <td>
                        <span style="font-size:0.75rem;color:var(--text-muted);
                                     background:#f1f5f9;padding:2px 7px;border-radius:5px">
                            #{{ $rankGlobal }}
                        </span>
                    </td>
                    <td><code style="font-size:0.75rem">{{ $hasil->calon->nim ?? '-' }}</code></td>
                    <td>
                       <div style="font-weight:600;font-size:0.84rem">
                            {{ $hasil->calon->nama ?? '-' }}
                        </div>
                        <div style="font-size:0.7rem;color:var(--text-muted)">
                            Semester {{ $hasil->calon->semester ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:700;font-size:0.88rem;
                                     color:{{ $skor >= 0 ? '#059669' : '#dc2626' }}">
                            {{ number_format($skor, 4) }}
                        </span>
                    </td>
                    {{-- Progress --}}
                    <td style="min-width:110px">
                        <div style="font-size:0.68rem;color:var(--text-muted);margin-bottom:3px">
                            {{ $pct }}% dari maks
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $pct }}%;
                                 background:{{ $lolos ? 'linear-gradient(90deg,#059669,#34d399)' : 'linear-gradient(90deg,#94a3b8,#cbd5e1)' }}">
                            </div>
                        </div>
                    </td>
                    {{-- Status --}}
                    <td>
                        @if($lolos)
                            <span class="badge b-success" style="font-size:0.72rem">
                                ✓ Masuk Kuota
                            </span>
                        @else
                            <span class="badge b-gray" style="font-size:0.72rem">
                                Tidak Lolos
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer info kuota --}}
        <div style="padding:10px 16px;border-top:1px solid var(--border);background:#f8fafc;
                    border-radius:0 0 13px 13px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:6px">
                <div style="width:10px;height:10px;border-radius:3px;background:#86efac"></div>
                <span style="font-size:0.75rem;color:var(--text-muted)">
                    Diterima: <strong>{{ min($kuota, $group['total']) }}</strong> mahasiswa
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <div style="width:10px;height:10px;border-radius:3px;background:#e2e8f0"></div>
                <span style="font-size:0.75rem;color:var(--text-muted)">
                    Tidak lolos: <strong>{{ max(0, $group['total'] - $kuota) }}</strong> mahasiswa
                </span>
            </div>
            @if($group['total'] < $kuota)
            <div style="display:flex;align-items:center;gap:6px">
                <div style="width:10px;height:10px;border-radius:3px;background:#fde68a"></div>
                <span style="font-size:0.75rem;color:#92400e">
                    ⚠ Sisa kuota: <strong>{{ $kuota - $group['total'] }}</strong> tidak terisi
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

@empty
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px">
        <i class="fas fa-calculator" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:12px"></i>
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