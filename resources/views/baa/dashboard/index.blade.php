@extends('layouts.app')
@section('title', 'Dashboard BAA')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Badan Administratif Akademik')

@push('styles')
<style>
.dok-bar { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
.dok-bar-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }
.dok-chip { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
.dok-lengkap { background: #dcfce7; color: #15803d; }
.dok-kurang  { background: #fef9c3; color: #b45309; }
.dok-kosong  { background: #f1f5f9; color: #64748b; }
</style>
@endpush

@section('content')

{{-- STATS GRID --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $stats['total'] }}</div><div class="stat-label">Total Calon Diinput</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div><div class="stat-val">{{ $stats['terkirim'] }}</div><div class="stat-label">Menunggu Verifikasi</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val">{{ $stats['terverifikasi'] }}</div><div class="stat-label">Terverifikasi BAPSI</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div><div class="stat-val">{{ $stats['ditolak'] }}</div><div class="stat-label">Ditolak</div></div>
    </div>
</div>

{{-- RINGKASAN DOKUMEN --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
    <div class="card" style="margin:0">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:16px">
            <div style="width:44px;height:44px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-folder-open" style="color:#15803d;font-size:1.2rem"></i>
            </div>
            <div style="flex:1">
                <div style="font-size:1.6rem;font-weight:800;color:#15803d;line-height:1">{{ $stats['dok_lengkap'] }}</div>
                <div style="font-size:0.78rem;color:#64748b;font-weight:600">Dokumen Lengkap</div>
                @if($stats['total'] > 0)
                <div class="dok-bar">
                    <div class="dok-bar-fill" style="width:{{ round($stats['dok_lengkap']/$stats['total']*100) }}%;background:#22c55e"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card" style="margin:0">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:16px">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef9c3;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-folder" style="color:#b45309;font-size:1.2rem"></i>
            </div>
            <div style="flex:1">
                <div style="font-size:1.6rem;font-weight:800;color:#b45309;line-height:1">{{ $stats['dok_kurang'] }}</div>
                <div style="font-size:0.78rem;color:#64748b;font-weight:600">Dokumen Kurang</div>
                @if($stats['total'] > 0)
                <div class="dok-bar">
                    <div class="dok-bar-fill" style="width:{{ round($stats['dok_kurang']/$stats['total']*100) }}%;background:#f59e0b"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-shield-alt"></i>
    <strong>Catatan:</strong> Sebagai BAA, Anda tidak dapat melihat skor dan hasil perhitungan MOORA. Data yang Anda input akan diproses oleh BAPSI.
</div>

{{-- TABEL DATA CALON --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color:var(--primary-light)"></i> Data Calon Terbaru</h3>
        <a href="{{ route('baa.calon.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Input Calon Baru
        </a>
    </div>
    <div class="card-body" style="padding:0">
        @if($calons->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--text-muted)">
                <i class="fas fa-inbox" style="font-size:2.5rem;margin-bottom:12px;display:block;opacity:.4"></i>
                Belum ada data calon. Klik "Input Calon Baru" untuk memulai.
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Prodi</th>
                            <th>Semester</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($calons as $c)
                    @php
                        $jenisDiperlukan = [
                            'surat_rekomendasi','rapor_semester_3','rapor_semester_4',
                            'kartu_keluarga','sktm','tagihan_listrik','surat_penghasilan',
                        ];
                        $totalDiperlukan = count($jenisDiperlukan);
                        $uploaded        = $c->dokumen->pluck('jenis')->toArray();
                        $jumlahAda       = count(array_intersect($jenisDiperlukan, $uploaded));
                        $lengkap         = $jumlahAda === $totalDiperlukan;
                    @endphp
                    <tr>
                        <td><code style="font-size:0.76rem">{{ $c->nim }}</code></td>
                        <td><strong>{{ $c->nama }}</strong></td>
                        <td style="font-size:0.82rem">{{ $c->prodi->nama }}</td>
                        <td>Sem. {{ $c->semester }}</td>
                        <td>
                            @if($jumlahAda === 0)
                                {{-- Belum ada dokumen sama sekali --}}
                                <span class="dok-chip dok-kosong">
                                    <i class="fas fa-minus-circle"></i> Belum Ada
                                </span>
                            @elseif($lengkap)
                                {{-- Semua dokumen sudah lengkap --}}
                                <span class="dok-chip dok-lengkap">
                                    <i class="fas fa-check-circle"></i> Lengkap
                                </span>
                            @else
                                {{-- Sebagian dokumen sudah ada tapi belum lengkap --}}
                                <span class="dok-chip dok-kurang">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Kurang ({{ $jumlahAda }}/{{ $totalDiperlukan }})
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge b-{{ match($c->status) {
                                'terverifikasi' => 'success',
                                'terkirim'      => 'info',
                                'ditolak'       => 'danger',
                                default         => 'gray'
                            } }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('baa.calon.show', $c) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:14px">
                <a href="{{ route('baa.calon.index') }}" class="btn btn-outline">
                    <i class="fas fa-list"></i> Lihat Semua Data
                </a>
            </div>
        @endif
    </div>
</div>

@endsection