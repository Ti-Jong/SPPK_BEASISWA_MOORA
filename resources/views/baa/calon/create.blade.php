@extends('layouts.app')
@section('title','Tambah Calon')
@section('page-title','Input Calon Mahasiswa Baru')

@push('styles')
<style>
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.active{display:flex}
.modal-box{background:#fff;border-radius:14px;padding:26px;width:460px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,0.25);animation:mIn .2s ease}
@keyframes mIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.m-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.m-header h3{font-size:0.95rem;font-weight:700;display:flex;align-items:center;gap:8px}
.m-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:#94a3b8;padding:2px 8px;border-radius:6px;line-height:1}
.m-close:hover{background:#f1f5f9}
.m-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:18px;padding-top:14px;border-top:1px solid #e2e8f0}
.spinner{width:16px;height:16px;border:2px solid #e2e8f0;border-top-color:#6366f1;border-radius:50%;animation:spin .6s linear infinite;display:none}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus" style="color:var(--primary-light)"></i> Form Input Calon Mahasiswa</h3>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ $errors->first() }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('baa.calon.store') }}">
            @csrf

            {{-- DATA MAHASISWA --}}
            <h4 style="font-size:0.82rem;font-weight:700;color:var(--text-muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em">
                <i class="fas fa-id-card"></i> Data Mahasiswa
            </h4>
            <div class="form-grid" style="margin-bottom:20px">

                <div class="f-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap mahasiswa" required>
                </div>

                <div class="f-group">
                    <label>NIM / No. Pendaftaran *</label>
                    <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Nomor Induk / Pendaftaran" required>
                </div>

                {{-- Program Studi full-width agar tombol Tambah Prodi tidak tertutup --}}
                <div class="f-group" style="grid-column:1/-1">
                    <label>Program Studi *</label>
                    <select name="prodi_id" id="selectProdi" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id')==$p->id?'selected':'' }}>
                            {{ $p->nama }} ({{ $p->jenjang }})
                        </option>
                        @endforeach
                    </select>
                    <button type="button" onclick="bukaModalProdi()"
                        style="margin-top:6px;background:none;border:none;color:#1d4ed8;font-size:0.78rem;font-weight:600;cursor:pointer;padding:0;display:flex;align-items:center;gap:4px">
                        <i class="fas fa-plus-circle"></i> Prodi belum ada? Tambah di sini
                    </button>
                </div>

                {{-- Asal Sekolah full-width --}}
                <div class="f-group" style="grid-column:1/-1">
                    <label>Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                           placeholder="contoh: SMA N 1 Yogyakarta">
                </div>

                <div class="f-group">
                    <label>Nilai Rapor Semester 3</label>
                    <input type="number" name="nilai_rapor_sem3" value="{{ old('nilai_rapor_sem3') }}"
                           placeholder="contoh: 88.50" min="0" max="100" step="0.01">
                </div>

                <div class="f-group">
                    <label>Nilai Rapor Semester 4</label>
                    <input type="number" name="nilai_rapor_sem4" value="{{ old('nilai_rapor_sem4') }}"
                           placeholder="contoh: 90.25" min="0" max="100" step="0.01">
                </div>

                <div class="f-group">
                    <label>Tahun Akademik *</label>
                    <select name="tahun_akademik"
                            style="width:140px;font-size:1rem;font-weight:600">
                        @foreach(['2023/2024', '2024/2025', '2025/2026'] as $tahun)
                            <option value="{{ $tahun }}"
                                {{ old('tahun_akademik', $tahun ?? '2025/2026') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                           required>
                </div>

                {{-- Semester tersimpan sebagai hidden --}}
                <input type="hidden" name="semester" value="1">

            </div>

            {{-- DATA EKONOMI --}}
            <h4 style="font-size:0.82rem;font-weight:700;color:var(--text-muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em">
                <i class="fas fa-wallet"></i> Data Ekonomi
            </h4>
            <div class="form-grid" style="margin-bottom:24px">

               {{-- Kondisi Orang Tua --}}
<div class="f-group" style="grid-column:1/-1">
    <label>Kondisi Orang Tua *</label>
    <select name="kondisi_ortu" required>
        <option value="">-- Pilih Kondisi --</option>
        <option value="tidak_punya_ayah_ibu" {{ old('kondisi_ortu')=='tidak_punya_ayah_ibu'?'selected':'' }}>
            Tidak mempunyai ayah dan ibu
        </option>
        <option value="hanya_ayah_sehat" {{ old('kondisi_ortu')=='hanya_ayah_sehat'?'selected':'' }}>
            Hanya ada ayah dalam Keluarga kami (ayah sehat)
        </option>
        <option value="hanya_ibu_sehat" {{ old('kondisi_ortu')=='hanya_ibu_sehat'?'selected':'' }}>
            Hanya ada ibu dalam Keluarga kami (ibu sehat)
        </option>
        <option value="ada_ayah_ibu_sehat" {{ old('kondisi_ortu')=='ada_ayah_ibu_sehat'?'selected':'' }}>
            Ada ayah dan ibu dalam keluarga kami (keduanya sehat)
        </option>
        <option value="ada_ayah_sakit_tidak_bekerja" {{ old('kondisi_ortu')=='ada_ayah_sakit_tidak_bekerja'?'selected':'' }}>
            Ada ayah dan ibu tetapi ayah sakit dan tidak bekerja
        </option>
        <option value="ada_ayah_sakit_pensiun" {{ old('kondisi_ortu')=='ada_ayah_sakit_pensiun'?'selected':'' }}>
            Ada ayah dan ibu tetapi ayah sakit dan Pensiunan
        </option>
        <option value="ada_ibu_sakit_tidak_bekerja" {{ old('kondisi_ortu')=='ada_ibu_sakit_tidak_bekerja'?'selected':'' }}>
            Ada ayah dan ibu tetapi Ibu sakit dan tidak bekerja
        </option>
        <option value="ada_ibu_sakit_pensiun" {{ old('kondisi_ortu')=='ada_ibu_sakit_pensiun'?'selected':'' }}>
            Ada ayah dan ibu tetapi Ibu sakit dan Pensiunan
        </option>
        <option value="ayah_ibu_sakit_tidak_bekerja" {{ old('kondisi_ortu')=='ayah_ibu_sakit_tidak_bekerja'?'selected':'' }}>
            Ayah dan Ibu keduanya sakit dan tidak bekerja
        </option>
        <option value="hanya_ayah_sakit" {{ old('kondisi_ortu')=='hanya_ayah_sakit'?'selected':'' }}>
            Hanya ada ayah dalam Keluarga kami (ayah sakit)
        </option>
        <option value="hanya_ibu_sakit" {{ old('kondisi_ortu')=='hanya_ibu_sakit'?'selected':'' }}>
            Hanya ada ibu dalam Keluarga kami (ibu sakit)
        </option>
    </select>
</div>

                {{-- Penghasilan Orang Tua --}}
                <div class="f-group">
                    <label>Penghasilan Orang Tua *</label>
                    <select name="penghasilan_ortu" required>
                        <option value="">-- Pilih Range --</option>
                        <option value="500000"  {{ old('penghasilan_ortu')=='500000'?'selected':'' }}>lebih kecil ato sama dengan 1.000.000</option>
                        <option value="1500000" {{ old('penghasilan_ortu')=='1500000'?'selected':'' }}>Lebih besar dari 1 juta ato sama dengan 2 juta</option>
                        <option value="2750000" {{ old('penghasilan_ortu')=='2750000'?'selected':'' }}>Lebih besar dari 2 juta atau sama dengan 3,5 juta</option>
                        <option value="4000000" {{ old('penghasilan_ortu')=='4000000'?'selected':'' }}>Lebih besar dari 3,5 juta</option>
                    </select>
                </div>

                {{-- Tagihan Listrik --}}
                <div class="f-group">
                    <label>Tagihan Listrik *</label>
                    <select name="tagihan_listrik" required>
                        <option value="">-- Pilih Range --</option>
                        <option value="75000"  {{ old('tagihan_listrik')=='75000'?'selected':'' }}>Lebih kecil ato sama dengan 100.000</option>
                        <option value="200000" {{ old('tagihan_listrik')=='200000'?'selected':'' }}>Lebih besar dari 100.000 ato sama dengan 300.000</option>
                        <option value="400000" {{ old('tagihan_listrik')=='400000'?'selected':'' }}>Lebih besar dari 300.000</option>
                    </select>
                </div>

                {{-- Tanggungan Anak --}}
                <div class="f-group">
                    <label>Tanggungan Anak *</label>
                    <select name="tanggungan_anak" required>
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ old('tanggungan_anak')=='1'?'selected':'' }}>Satu anak</option>
                        <option value="2" {{ old('tanggungan_anak')=='2'?'selected':'' }}>Dua anak</option>
                        <option value="3" {{ old('tanggungan_anak')=='3'?'selected':'' }}>Tiga anak atau lebih</option>
                    </select>
                </div>

            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                <a href="{{ route('baa.calon.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- ====================== MODAL TAMBAH PRODI ====================== --}}
<div class="modal-overlay" id="modalProdi" onclick="if(event.target.id==='modalProdi')tutupModalProdi()">
    <div class="modal-box">
        <div class="m-header">
            <h3>
                <i class="fas fa-plus-circle" style="color:#1d4ed8"></i>
                Tambah Program Studi Baru
            </h3>
            <button class="m-close" onclick="tutupModalProdi()">×</button>
        </div>

        <div id="prodiAlert" style="display:none;margin-bottom:12px;padding:10px 14px;border-radius:8px;font-size:0.82rem"></div>

        <div class="f-group" style="margin-bottom:14px">
            <label>Kode Prodi * <span style="font-size:0.72rem;color:#94a3b8">(misal: S1-TI, D3-MI, S1-PSI)</span></label>
            <input type="text" id="prodiKode" placeholder="S1-TI" maxlength="20"
                style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
        </div>

        <div class="f-group" style="margin-bottom:14px">
            <label>Nama Program Studi *</label>
            <input type="text" id="prodiNama" placeholder="misal: Teknik Informatika">
        </div>

        <div class="form-grid" style="gap:12px;margin-bottom:14px">
            <div class="f-group">
                <label>Jenjang *</label>
                <select id="prodiJenjang">
                    <option value="Sarjana">Sarjana (S1)</option>
                    <option value="D3">Diploma 3 (D3)</option>
                    <option value="Sarjana Terapan">Sarjana Terapan (D4)</option>
                </select>
            </div>
            <div class="f-group">
                <label>Kuota Beasiswa</label>
                <input type="number" id="prodiKuota" value="1" min="0" max="100">
            </div>
        </div>

        <div class="m-footer">
            <button class="btn btn-outline" onclick="tutupModalProdi()">Batal</button>
            <button class="btn btn-primary" id="btnSimpanProdi" onclick="simpanProdi()">
                <span class="spinner" id="prodiSpinner"></span>
                <span id="btnSimpanLabel"><i class="fas fa-save"></i> Simpan & Pilih</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const PRODI_URL  = '{{ route("baa.prodi.quick-store") }}';
const CSRF_TOKEN = '{{ csrf_token() }}';

function bukaModalProdi(){
    document.getElementById('modalProdi').classList.add('active');
    document.body.style.overflow = 'hidden';
    document.getElementById('prodiKode').focus();
    resetModalProdi();
}

function tutupModalProdi(){
    document.getElementById('modalProdi').classList.remove('active');
    document.body.style.overflow = '';
}

function resetModalProdi(){
    document.getElementById('prodiAlert').style.display = 'none';
    document.getElementById('prodiKode').value  = '';
    document.getElementById('prodiNama').value  = '';
    document.getElementById('prodiKuota').value = '1';
    document.getElementById('prodiJenjang').value = 'Sarjana';
    setLoading(false);
}

document.addEventListener('keydown', e => {
    if(e.key === 'Escape') tutupModalProdi();
    if(e.key === 'Enter' && document.getElementById('modalProdi').classList.contains('active')){
        e.preventDefault();
        simpanProdi();
    }
});

function setLoading(on){
    document.getElementById('prodiSpinner').style.display = on ? 'inline-block' : 'none';
    document.getElementById('btnSimpanLabel').style.display = on ? 'none' : 'inline';
    document.getElementById('btnSimpanProdi').disabled = on;
}

function showAlert(msg, type){
    const el = document.getElementById('prodiAlert');
    el.style.display = '';
    el.style.background = type==='success' ? '#dcfce7' : '#fee2e2';
    el.style.color      = type==='success' ? '#15803d' : '#b91c1c';
    el.style.border     = '1px solid '+(type==='success'?'#86efac':'#fca5a5');
    el.innerHTML = (type==='success'?'<i class="fas fa-check-circle"></i> ':'<i class="fas fa-times-circle"></i> ') + msg;
}

async function simpanProdi(){
    const kode   = document.getElementById('prodiKode').value.trim().toUpperCase();
    const nama   = document.getElementById('prodiNama').value.trim();
    const jenjang= document.getElementById('prodiJenjang').value;
    const kuota  = parseInt(document.getElementById('prodiKuota').value)||1;

    if(!kode){ showAlert('Kode prodi wajib diisi.','error'); document.getElementById('prodiKode').focus(); return; }
    if(!nama){ showAlert('Nama prodi wajib diisi.','error'); document.getElementById('prodiNama').focus(); return; }

    setLoading(true);

    try {
        const res = await fetch(PRODI_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ kode, nama, jenjang, kuota_beasiswa: kuota }),
        });

        const data = await res.json();

        if(res.ok && data.success){
            showAlert('Prodi <strong>' + data.prodi.nama + '</strong> berhasil ditambahkan!', 'success');
            const select = document.getElementById('selectProdi');
            const opt    = document.createElement('option');
            opt.value    = data.prodi.id;
            opt.text     = data.prodi.label;
            opt.selected = true;
            select.appendChild(opt);
            setTimeout(() => tutupModalProdi(), 1200);
        } else {
            showAlert(data.message || data.errors?.kode?.[0] || 'Gagal menyimpan prodi.', 'error');
        }
    } catch(e) {
        showAlert('Error koneksi. Coba lagi.', 'error');
    } finally {
        setLoading(false);
    }
}
</script>
@endpush

@endsection