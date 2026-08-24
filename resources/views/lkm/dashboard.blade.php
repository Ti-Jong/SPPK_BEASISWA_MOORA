@extends('layouts.app')
@section('title','Dashboard LKM')
@section('page-title','Dashboard LKM')
@section('page-subtitle','Lembaga Kesejahteraan Mahasiswa')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-list-ol"></i></div><div><div class="stat-val">{{ $stats['total_ranking'] }}</div><div class="stat-label">Kandidat Terperingkat</div></div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="fas fa-award"></i></div><div><div class="stat-val">{{ $stats['penerima'] }}</div><div class="stat-label">Penerima Ditetapkan</div></div></div>
    <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-university"></i></div><div><div class="stat-val">{{ $stats['total_prodi'] }}</div><div class="stat-label">Program Studi</div></div></div>
    <div class="stat-card"><div class="stat-icon gold"><i class="fas fa-star"></i></div><div><div class="stat-val">{{ $stats['top_score'] ? number_format($stats['top_score'],3) : '-' }}</div><div class="stat-label">Skor Yi Tertinggi</div></div></div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h3><i class="fas fa-trophy" style="color:var(--accent)"></i> Ranking MOORA — Tahun {{ $tahun }}</h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('lkm.ranking.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            <a href="{{ route('lkm.keputusan.index') }}" class="btn btn-accent btn-sm"><i class="fas fa-check-double"></i> Tetapkan Penerima</a>
        </div>
    </div>
    <div class="card-body">
        @forelse($topRanking as $i => $r)
            <div class="rank-card">
                <div class="rank-num r{{ $i<3?$i+1:'n' }}">{{ $i+1 }}</div>
                <div style="flex:1">
                    <div style="font-weight:700;font-size:.88rem">{{ $r->calon->nama }} <code style="font-size:.72rem">{{ $r->calon->nim }}</code></div>
                    <div class="text-sm text-muted">{{ $r->calon->prodi->nama }} · Sem. {{ $r->calon->semester }}</div>
                    <div class="progress-bar"><div class="progress-fill" style="width:{{ max(15,($r->yi_score+0.5)*70) }}%"></div></div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:1.05rem;font-weight:800;color:var(--primary-light)">{{ number_format($r->yi_score,4) }}</div>
                    <div class="text-sm text-muted">Yi Score</div>
                </div>
            </div>
        @empty
            <p class="text-muted text-sm" style="text-align:center;padding:30px">Belum ada hasil MOORA. Tunggu proses dari BAPSI.</p>
        @endforelse
    </div>
</div>
@endsection
