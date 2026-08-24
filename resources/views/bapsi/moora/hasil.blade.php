@extends("layouts.app")
@section("title","Hasil MOORA")
@section("page-title","Hasil Perhitungan MOORA")
@section("content")
<div class="card"><div class="card-header">
<h3>Ranking MOORA Tahun {{ $tahun }}</h3>
<span class="badge badge-info">{{ $hasil->count() }} calon</span>
</div><div class="card-body">
<div class="table-wrap"><table><thead><tr><th>Rank</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>Yi Score</th><th>Aksi</th></tr></thead><tbody>
@foreach($hasil as $h)
<tr>
<td><span class="badge {{ $h->ranking<=3 ? "badge-gold" : "badge-gray" }}">#{{ $h->ranking }}</span></td>
<td><code>{{ $h->calon->nim }}</code></td>
<td><strong>{{ $h->calon->nama }}</strong></td>
<td style="font-size:.78rem">{{ $h->prodi->nama }}</td>
<td><strong style="color:var(--primary-light);font-size:.95rem">{{ number_format($h->yi_score,4) }}</strong></td>
<td><a href="{{ route("bapsi.moora.detail",$h->calon) }}" class="btn btn-outline btn-sm"><i class="fas fa-search"></i> Detail</a></td>
</tr>
@endforeach
</tbody></table></div>
</div></div>
@endsection