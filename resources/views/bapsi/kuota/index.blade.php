@extends('layouts.app')
@section('title','Kuota Prodi')
@section('page-title','Kuota Beasiswa Per Program Studi')
@section('content')
<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Sistem akan otomatis membatasi penerima sesuai kuota yang ditetapkan per program studi.</div>
<div class="card">
    <div class="card-header"><h3>Pengaturan Kuota</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('bapsi.kuota.update') }}">
            @csrf
            <div class="table-wrap"><table>
                <thead><tr><th>Kode</th><th>Program Studi</th><th>Jenjang</th><th>Kuota Beasiswa</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($prodis as $p)
                <tr>
                    <td><code style="font-size:0.76rem">{{ $p->kode }}</code></td>
                    <td><strong>{{ $p->nama }}</strong></td>
                    <td><span class="badge b-info">{{ $p->jenjang }}</span></td>
                    <td><input type="number" name="kuota[{{ $p->id }}]" value="{{ $p->kuota_beasiswa }}" min="0" max="50" style="width:70px;padding:6px 8px;border:1px solid var(--border);border-radius:6px;font-family:inherit;text-align:center"></td>
                    <td><span class="badge {{ $p->is_active ? 'b-success' : 'b-gray' }}">{{ $p->is_active ? 'Aktif' : 'Non-aktif' }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table></div>
            <div style="margin-top:16px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Kuota</button>
            </div>
        </form>
    </div>
</div>
@endsection
