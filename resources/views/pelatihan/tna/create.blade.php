@extends('layouts.dasar')

@section('title', 'Buat TNA Baru')

@section('content')
{{-- Library Select2 CSS dan JS diasumsikan sudah dimuat di layouts.dasar. --}}

<div class="container-fluid py-4">
    <div class="row">
        {{-- MENGGUNAKAN STRUKTUR ASLI: col-lg-10 mx-auto --}}
        <div class="col-lg-10 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3">
                    <h5 class="mb-0">Tambah Data Training Needs Analysis (TNA)</h5>
                </div>
                {{-- MENGGUNAKAN CARD BODY ASLI: p-3 --}}
                <div class="card-body p-3">

                    {{-- Catatan Penting: Pastikan variabel $pegawais, $sifatTnas, $NamalevelUser, dan $nip di-pass dari
                    controller --}}

                    <form action="{{ route('pelatihan.tna.store') }}" method="POST">
                        @csrf

                        {{-- CONTAINER ROW UNTUK PEGAWAI DAN NAMA PELATIHAN --}}
                        <div class="row">
                            {{-- 1. INPUT PEGAWAI (Menggunakan Select2 dan Logika Level User) --}}
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label fw-bold">Pegawai <span
                                        class="text-danger">*</span></label>
                                {{-- PERBAIKAN: Hapus 'required' di HTML agar validasi dikendalikan penuh oleh Laravel
                                --}}
                                <select class="form-control" id="nip" name="nip" {{-- Jika level Pegawai, field ini
                                    di-disabled secara visual --}} @if (isset($NamalevelUser) &&
                                    $NamalevelUser==='Pegawai' ) disabled @endif>

                                    @if (isset($NamalevelUser) && $NamalevelUser === 'Pegawai')
                                    {{-- Jika level user Pegawai, otomatis nip dari user yang login (asumsi $nip dan
                                    Auth::user()->pegawai->nama tersedia di Controller) --}}
                                    <option value="{{ $nip ?? (Auth::user()->nip ?? '') }}" selected>
                                        {{ Auth::user()->pegawai->nama ?? 'Nama Pegawai' }} ({{ $nip ??
                                        (Auth::user()->nip ?? '') }})
                                    </option>
                                    @else
                                    <option value="">--- Pilih NIP / Nama Pegawai ---</option>
                                    {{-- Data Pegawai untuk Admin/Atasan --}}
                                    @isset($pegawais)
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->nip }}" {{ old('nip')==$pegawai->nip ? 'selected' : ''
                                        }}>
                                        {{ $pegawai->nama }} ({{ $pegawai->nip }})
                                    </option>
                                    @endforeach
                                    @endisset
                                    @endif
                                </select>

                                {{-- Jika field disabled, kirim nilai nip menggunakan hidden input agar tetap terkirim
                                saat submit --}}
                                @if (isset($NamalevelUser) && $NamalevelUser === 'Pegawai')
                                <input type="hidden" name="nip" value="{{ $nip ?? (Auth::user()->nip ?? '') }}">
                                @endif

                                @error('nip')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 2. KEBUTUHAN PELATIHAN --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Pelatihan (Kebutuhan) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                                    placeholder="Contoh: Pelatihan Keterampilan Digital Marketing" required>
                                @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- CONTAINER ROW UNTUK TAHUN DAN SIFAT TNA --}}
                        <div class="row">
                            {{-- 3. TAHUN --}}
                            <div class="col-md-6 mb-3">
                                <label for="tahun" class="form-label fw-bold">Tahun Pelaksanaan <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahun" name="tahun"
                                    value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y') + 5 }}" required>
                                @error('tahun')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 4. INPUT SIFAT TNA (Menggunakan Select2) --}}
                            <div class="col-md-6 mb-3">
                                <label for="sifat_tna_id" class="form-label fw-bold">Sifat TNA / Prioritas <span
                                        class="text-danger">*</span></label>
                                {{-- PERBAIKAN: Hapus 'required' di HTML agar validasi dikendalikan penuh oleh Laravel
                                --}}
                                <select class="form-control" id="sifat_tna_id" name="sifat_tna_id">
                                    {{-- FIX: Tambahkan disabled selected agar placeholder Select2 berfungsi --}}
                                    <option value="" disabled selected>--- Pilih Sifat TNA / Prioritas ---</option>
                                    {{-- Data Sifat TNA --}}
                                    @isset($sifatTnas)
                                    @foreach ($sifatTnas as $sifatTna)
                                    {{-- Menggunakan $sifat->sifat_tna_id sesuai konfirmasi --}}
                                    <option value="{{ $sifatTna->sifat_tna_id }}" {{ old('sifat_tna_id')==$sifatTna->
                                        sifat_tna_id ? 'selected' : '' }}>{{ $sifatTna->nama }} {{
                                        $sifatTna->sifat_tna_id }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                @error('sifat_tna_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi pelatihan</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Deskripsi kebutuhan pelatihan ini.">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- STATUS TNA (HIDDEN - Default 0) --}}
                        <input type="hidden" name="status_tna" value="0">


                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pelatihan.tna.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Data TNA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // INISIALISASI PEGAWAI
        // Select2 hanya perlu diinisialisasi jika field tersebut tidak disabled.
        $('#nip').select2({
            placeholder: "Pilih Pegawai", 
            allowClear: true,
        });
        
        // INISIALISASI SIFAT TNA
        $('#sifat_tna_id').select2({
            placeholder: "Pilih Prioritas TNA",
            allowClear: true,
        });
    });
</script>
@endpush