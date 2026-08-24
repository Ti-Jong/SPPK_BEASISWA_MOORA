@extends('layouts.app')
@section('title', 'Hitung MOORA')
@section('page-title', 'Hitung MOORA')

@section('content')
@php
    $statistik       = $statistik       ?? null;
    $rankingPerProdi = $rankingPerProdi ?? [];
    $calonPerProdi   = $calonPerProdi   ?? collect();
    $stats           = $stats           ?? ['total_lulus' => 0];

    $totalLolosCount = \App\Models\CalonMahasiswa::where('tahun_akademik', $tahun)
        ->where('status_seleksi', 'lolos')->count();
    $totalDitolakCount = \App\Models\CalonMahasiswa::where('tahun_akademik', $tahun)
        ->where('status_seleksi', 'ditolak')->count();
@endphp

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- Filter Tahun & Prodi --}}
<form method="GET" action="{{ route('bapsi.moora.index') }}"
      style="display:flex;align-items:center;gap:8px;margin-bottom:20px;flex-wrap:wrap">

    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Periode:</label>
    <select name="tahun"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:160px"
            onchange="this.form.submit()">
        @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>

    @if($hasilAda && $prodis->isNotEmpty())
    <label style="font-size:0.8rem;font-weight:600;color:var(--text-muted)">Prodi:</label>
    <select name="prodi_id"
            style="border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:200px"
            onchange="this.form.submit()">
        <option value="">— Semua Prodi —</option>
        @foreach($prodis as $p)
            <option value="{{ $p->id }}" {{ $p->id == $prodiFilter ? 'selected' : '' }}>
                {{ $p->nama }}
            </option>
        @endforeach
    </select>
    @endif

    <span style="font-size:0.78rem;color:var(--text-muted)">{{ $calons->count() }} calon lulus verifikasi</span>
</form>

{{-- Stats Bar (jika sudah dihitung) --}}
@if($hasilAda && $statistik)
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val">{{ $statistik['total_calon'] }}</div>
            <div class="stat-label">Total Calon Diranking</div>
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
        <div class="stat-icon si-gold"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="stat-val">{{ number_format($statistik['skor_avg'], 4) }}</div>
            <div class="stat-label">Rata-rata Skor</div>
        </div>
    </div>
</div>
@endif

{{-- Panel Aksi --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3><i class="fas fa-calculator" style="color:var(--primary-light)"></i> Perhitungan MOORA</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <form method="POST" action="{{ route('bapsi.moora.hitung') }}" style="display:inline"
                  onsubmit="return confirm('Hitung ulang MOORA untuk periode {{ $tahun }}?\nData ranking lama akan ditimpa.')">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button class="btn btn-primary" {{ $calons->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-play"></i>
                    {{ $hasilAda ? 'Hitung Ulang' : 'Hitung MOORA' }}
                </button>
            </form>

            @if($hasilAda)
            <form method="POST" action="{{ route('bapsi.moora.kirim') }}" style="display:inline">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kirim ke LKM
                </button>
            </form>

            <form method="POST" action="{{ route('bapsi.moora.reset') }}" style="display:inline"
                  onsubmit="return confirm('Reset semua data MOORA periode {{ $tahun }}?\nTindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button class="btn btn-danger">
                    <i class="fas fa-trash"></i> Reset
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Info Kriteria --}}
    <div class="card-body">
        <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:12px;font-weight:600">
            <i class="fas fa-info-circle"></i> Kriteria & Bobot MOORA
        </p>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            @foreach(\App\Services\MooraService::kriteriaList() as $key => $krit)
            <div style="background:{{ $krit['type'] === 'benefit' ? '#f0fdf4' : '#fff7ed' }};
                        border:1px solid {{ $krit['type'] === 'benefit' ? '#86efac' : '#fed7aa' }};
                        border-radius:8px;padding:10px 14px;min-width:160px">
                <div style="font-size:0.7rem;font-weight:700;
                            color:{{ $krit['type'] === 'benefit' ? '#15803d' : '#c2410c' }};
                            text-transform:uppercase;letter-spacing:.05em">
                    {{ strtoupper($krit['type']) }}
                </div>
                <div style="font-size:0.85rem;font-weight:700;margin:3px 0">{{ $krit['label'] }}</div>
                <div style="font-size:0.78rem;color:var(--text-muted)">
                    Bobot: <strong>{{ $krit['bobot'] }}</strong>
                </div>
            </div>
            @endforeach
        </div>

        @if($calons->isEmpty())
        <div class="alert alert-warning" style="margin-top:14px">
            <i class="fas fa-exclamation-triangle"></i>
            Tidak ada calon yang lulus verifikasi untuk periode <strong>{{ $tahun }}</strong>.
            Pastikan Admin BAA sudah menginput data dan Admin BAPSI sudah memverifikasi.
        </div>
        @endif
    </div>
</div>

{{-- Statistik Hasil Seleksi — single-stage (Lolos / Ditolak) --}}
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-user-check"></i></div>
        <div>
            <div class="stat-val">{{ $stats['total_lulus'] }}</div>
            <div class="stat-label">Total Lulus Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-val" style="color:#16a34a">{{ $totalLolosCount }}</div>
            <div class="stat-label">Lolos</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="stat-val" style="color:#dc2626">{{ $totalDitolakCount }}</div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>
</div>

{{-- Tabel Ranking Per Prodi (setelah dihitung) --}}
@if($hasilAda)
    @foreach($prodis as $prodi)
    @php
        $kuota    = $prodi->kuota_beasiswa ?? 5;
        $dihitung = $prodi->sudah_dihitung ?? 0;
        $ada      = isset($rankingPerProdi[$prodi->id]) && $rankingPerProdi[$prodi->id]->isNotEmpty();
    @endphp
    <div class="card" style="margin-bottom:18px">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
            <h3 style="display:flex;align-items:center;gap:8px">
                <i class="fas fa-building-columns" style="color:var(--primary-light)"></i>
                {{ $prodi->nama }}
            </h3>
            <div style="display:flex;gap:8px">
                <span style="background:#eef0fb;color:#1d4ed8;font-size:0.72rem;font-weight:700;
                             padding:4px 10px;border-radius:6px">
                    Kuota: {{ $kuota }}
                </span>
                <span style="background:var(--surface);color:var(--text-muted);font-size:0.72rem;font-weight:700;
                             padding:4px 10px;border-radius:6px;border:1px solid var(--border)">
                    Dihitung: {{ $dihitung }}
                </span>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            @if($ada)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Rank Prodi</th>
                            <th>Rank Global</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Skor MOORA</th>
                            <th>Status Seleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rankingPerProdi[$prodi->id] as $h)
                    @php
                        $sel   = $h->calon->status_seleksi ?? '-';
                        $lolos = $sel === 'lolos';
                        $selStyle = match($sel) {
                            'lolos'   => ['bg' => '#dcfce7', 'fg' => '#16a34a'],
                            'ditolak' => ['bg' => '#fef2f2', 'fg' => '#dc2626'],
                            default   => ['bg' => '#f1f5f9', 'fg' => '#64748b'],
                        };
                    @endphp
                    <tr style="{{ $lolos ? 'background:#f0fdf4' : '' }}">
                        <td>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         width:28px;height:28px;border-radius:50%;font-weight:700;font-size:0.78rem;
                                         background:{{ $lolos ? '#16a34a' : 'var(--surface)' }};
                                         color:{{ $lolos ? '#fff' : 'var(--text-muted)' }}">
                                {{ $h->ranking_prodi }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.82rem">{{ $h->ranking }}</td>
                        <td><code style="font-size:0.8rem">{{ $h->calon->nim ?? '-' }}</code></td>
                        <td style="font-weight:600">{{ $h->calon->nama ?? '-' }}</td>
                        <td>
                            <strong style="color:var(--primary-light);font-family:monospace;font-size:0.85rem">
                                {{ number_format($h->skor_moora, 6) }}
                            </strong>
                        </td>
                        <td>
                            <span style="font-size:0.7rem;font-weight:700;padding:3px 9px;border-radius:6px;
                                         background:{{ $selStyle['bg'] }};color:{{ $selStyle['fg'] }}">
                                {{ strtoupper($sel) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:0.85rem">
                Belum dihitung
            </div>
            @endif
        </div>
    </div>
    @endforeach

@elseif($calons->isNotEmpty())
    {{-- Preview calon sebelum dihitung --}}
    <div class="card">
        <div class="card-header">
            <h3>Preview Calon Per Prodi (Belum Dihitung)</h3>
            <span class="badge b-warning">Belum Dihitung</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px">
                @forelse($calonPerProdi as $group)
                <div style="border:1px solid var(--border);border-radius:10px;padding:14px">
                    <div style="font-size:0.72rem;color:var(--text-muted);margin-bottom:4px">Program Studi</div>
                    <div style="font-weight:700;font-size:0.85rem;margin-bottom:6px">
                        {{ $group['prodi']?->nama ?? 'Prodi Tidak Diketahui' }}
                    </div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--primary-light)">
                        {{ $group['total'] }}
                    </div>
                    <div style="font-size:0.72rem;color:var(--text-muted)">calon</div>
                </div>
                @empty
                <div style="color:var(--text-muted);font-size:0.85rem">Tidak ada data calon per prodi.</div>
                @endforelse
            </div>
            <div style="margin-top:14px;padding:12px;background:#eff6ff;border-radius:8px;font-size:0.82rem;color:#1e40af">
                <i class="fas fa-info-circle"></i>
                Klik <strong>Hitung MOORA</strong> di atas untuk memulai perhitungan.
            </div>
        </div>
    </div>
@endif

@endsection