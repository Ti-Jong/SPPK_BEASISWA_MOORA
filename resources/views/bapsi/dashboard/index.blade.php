@extends('layouts.app')
@section('title','Dashboard BAPSI')
@section('page-title','Dashboard BAPSI')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Calon Masuk</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['terverifikasi'] }}</div>
            <div class="stat-label">Terverifikasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-orange">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['terkirim'] }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon si-gold">
            <i class="fas fa-trophy"></i>
        </div>
        <div>
            <div class="stat-val">{{ $hasilTerbaru->count() }}</div>
            <div class="stat-label">Hasil MOORA</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    {{-- CARD 1 --}}
    <div class="card">
        <div class="card-header">
            <h3>Top Ranking MOORA</h3>
            <a href="{{ route('bapsi.moora.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>

        <div class="card-body">
            @forelse($hasilTerbaru as $i => $h)

                <div class="rank-card">
                    <div class="rank-num r{{ $i < 3 ? $i+1 : 'n' }}">
                        {{ $i+1 }}
                    </div>

                    <div style="flex:1">
                        <div style="font-weight:700;font-size:0.88rem">
                            {{ $h->calon->nama }}
                        </div>

                        <div style="font-size:0.74rem;color:var(--text-muted)">
                            {{ $h->calon->prodi->nama_prodi ?? '-' }}
                        </div>
                    </div>

                    <div style="text-align:right">
                        <div style="font-size:1rem;font-weight:800;color:var(--primary-light)">
                            {{ number_format($h->skor_moora,4) }}
                        </div>

                        <div style="font-size:0.7rem;color:var(--text-muted)">
                            Skor MOORA
                        </div>
                    </div>
                </div>

            @empty
                <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:20px">
                    Belum ada hasil MOORA.
                    <a href="{{ route('bapsi.moora.index') }}">Hitung sekarang</a>
                </p>
            @endforelse
        </div>
    </div>

    {{-- CARD 2 --}}
    <div class="card">
        <div class="card-header">
            <h3>Calon Menunggu Verifikasi</h3>
            <a href="{{ route('bapsi.verifikasi.index') }}" class="btn btn-outline btn-sm">Semua</a>
        </div>

        <div class="card-body">
            @forelse($calonsMenunggu as $c)

                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    
                    <div>
                        <div style="font-weight:600;font-size:0.84rem">
                            {{ $c->nama }}
                        </div>

                        <div style="font-size:0.73rem;color:var(--text-muted)">
                            {{ $c->prodi->nama_prodi ?? '-' }}
                        </div>
                    </div>

                    @php
                    $statusMap = [
                        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => '#f4f1f0', 'bg' => '#068f04'],
                        'lulus_verifikasi'    => ['label' => 'Lulus Verifikasi',    'color' => '#15803d', 'bg' => '#f0fdf4'],
                        'ditolak'             => ['label' => 'Ditolak',             'color' => '#b91c1c', 'bg' => '#fef2f2'],
                        'draft'               => ['label' => 'Draft',               'color' => '#475569', 'bg' => '#f1f5f9'],
                        'ditetapkan'          => ['label' => 'Ditetapkan',          'color' => '#1d4ed8', 'bg' => '#eff6ff'],
                    ];
                    $st = $statusMap[$c->status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $c->status)),
                        'color' => '#475569',
                        'bg'    => '#f1f5f9',
                    ];
                @endphp
                <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};padding:4px 11px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;letter-spacing:.02em">
                    {{ $st['label'] }}
                </span>

                </div>

            @empty
                <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:20px">
                    Semua terverifikasi ✓
                </p>
            @endforelse
        </div>
    </div>

</div>

@endsection