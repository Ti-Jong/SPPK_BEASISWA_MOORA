@extends('layouts.app')
@section('title','Dashboard BAPSI')
@section('page-title','Dashboard BAPSI')
@section('page-subtitle','Pusat Administrasi dan Sistem Informasi')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-users"></i></div><div><div class="stat-val">{{ $stats['total_calon'] }}</div><div class="stat-label">Total Calon Masuk</div></div></div>
    <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-clock"></i></div><div><div class="stat-val">{{ $stats['menunggu'] }}</div><div class="stat-label">Menunggu Verifikasi</div></div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div><div class="stat-val">{{ $stats['terverifikasi'] }}</div><div class="stat-label">Terverifikasi</div></div></div>
    <div class="stat-card"><div class="stat-icon gold"><i class="fas fa-trophy"></i></div><div><div class="stat-val">{{ $stats['hasil_moora'] }}</div><div class="stat-label">Hasil MOORA {{ $tahun }}</div></div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-trophy" style="color:var(--accent)"></i> Top Ranking MOORA {{ $tahun }}</h3>
            <a href="{{ route('bapsi.moora.hasil') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body">
            @forelse($topRanking as $i => $r)
                <div class="rank-card">
                    <div class="rank-num r{{ $i < 3 ? $i+1 : 'n' }}">{{ $i+1 }}</div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:.88rem">{{ $r->calon->nama }}</div>
                        <div class="text-sm text-muted">{{ $r->calon->prodi->nama }}</div>
                        <div class="progress-bar"><div class="progress-fill" style="width:{{ max(20, ($r->yi_score + 0.5) * 75) }}%"></div></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:1rem;font-weight:800;color:var(--primary-light)">{{ number_format($r->yi_score,4) }}</div>
                        <div class="text-sm text-muted">Yi Score</div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-sm" style="text-align:center;padding:20px">Belum ada hasil MOORA. Jalankan perhitungan terlebih dahulu.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Aksi Cepat</h3></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
            <a href="{{ route('bapsi.verifikasi.index') }}" class="btn btn-primary">
                <i class="fas fa-clipboard-check"></i> Verifikasi Dokumen ({{ $stats['menunggu'] }} pending)
            </a>
            <a href="{{ route('bapsi.moora.index') }}" class="btn btn-accent">
                <i class="fas fa-calculator"></i> Perhitungan MOORA
            </a>
            <a href="{{ route('bapsi.kriteria.index') }}" class="btn btn-outline">
                <i class="fas fa-sliders-h"></i> Master Kriteria & Skor
            </a>
            <a href="{{ route('bapsi.kuota.index') }}" class="btn btn-outline">
                <i class="fas fa-chart-pie"></i> Kelola Kuota Prodi
            </a>
        </div>
    </div>
</div>
@endsection
