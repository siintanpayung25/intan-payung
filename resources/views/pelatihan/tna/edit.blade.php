@extends('layouts.dasar')

@section('title', 'Edit TNA')

@section('content')
{{-- Library Select2 CSS dan JS diasumsikan sudah dimuat di layouts.dasar. --}}

<div class="container-fluid py-4">
    <div class="row">
        {{-- MENGGUNAKAN STRUKTUR ASLI: col-lg-10 mx-auto --}}
        <div class="col-lg-10 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3">
                    <h5 class="mb-0">Ubah Data Training Needs Analysis (TNA)</h5>
                </div>
                {{-- MENGGUNAKAN CARD BODY ASLI: p-3 --}}
                <div class="card-body p-3">

                    <form action="{{ route('pelatihan.tna.update', $pelatihan_tna->tna_id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib menggunakan metode PUT untuk UPDATE --}}

                        Berikut adalah kode yang sudah disesuaikan:

                        <div class="row">
                            {{-- Baris 1: Pegawai dan Kebutuhan Pelatihan --}}
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label fw-bold">Pegawai <span
                                        class="text-danger">*</span></label>

                                {{-- JIKA PEGAWAI, SELECT DI-DISABLED --}}
                                <select class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" {{
                                    isset($NamalevelUser) && $NamalevelUser==='Pegawai' ? 'disabled' : '' }}>

                                    @if (isset($NamalevelUser) && $NamalevelUser === 'Pegawai')
                                    {{-- JIKA PEGAWAI: Tampilkan data diri sendiri --}}
                                    @php
                                    $pegawaiNamaAman = isset($pelatihan_tna->pegawai) ? $pelatihan_tna->pegawai->nama :
                                    'Nama Pegawai Tidak Ditemukan';
                                    @endphp

                                    <option value="{{ $pelatihan_tna->nip }}" selected>
                                        {{ $pegawaiNamaAman }} ({{ $pelatihan_tna->nip }})
                                    </option>
                                    @else
                                    {{-- JIKA ADMIN/ATASAN: Tampilkan semua opsi --}}
                                    <option value="{{ old('nip', $pelatihan_tna->nip) }}">--- Pilih NIP / Nama Pegawai
                                        ---</option>
                                    @isset($pegawais)
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->nip }}" {{-- Pastikan nip yang sesuai terpilih --}} {{
                                        old('nip', $pelatihan_tna->nip) == $pegawai->nip ? 'selected' : '' }}>
                                        {{ $pegawai->nama }} ({{ $pegawai->nip }})
                                    </option>
                                    @endforeach
                                    @endisset
                                    @endif
                                </select>

                                {{-- Jika field disabled (Pegawai), kirim nilai nip menggunakan hidden input agar tetap
                                terkirim saat submit --}}
                                @if (isset($NamalevelUser) && $NamalevelUser === 'Pegawai')
                                <input type="hidden" name="nip" value="{{ $pelatihan_tna->nip }}">
                                @endif

                                @error('nip')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 2. KEBUTUHAN/Nama PELATIHAN --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Pelatihan (Kebutuhan) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama', $pelatihan_tna->nama) }}"
                                    placeholder="Masukkan nama kebutuhan pelatihan" required>
                                @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Baris 3: Tahun dan Skala Prioritas --}}
                            <div class="row">
                                {{-- Kolom Kiri: Tahun --}}
                                <div class="col-md-6 mb-3">
                                    <label for="tahun" class="form-label fw-bold">Tahun Pelaksanaan <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                        id="tahun" name="tahun" value="{{ old('tahun', $pelatihan_tna->tahun) }}"
                                        min="2000" max="{{ date('Y') + 5 }}" required>
                                    @error('tahun')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Baris 4 Kolom Kanan: Sifat TNA (Skala Prioritas) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="sifat_tna_id" class="form-label fw-bold">Sifat TNA / Prioritas <span
                                            class="text-danger">*</span></label>
                                    {{-- Menggunakan form-control untuk Select2 --}}
                                    <select class="form-control @error('sifat_tna_id') is-invalid @enderror"
                                        id="sifat_tna_id" name="sifat_tna_id">
                                        <option value="" disabled>--- Pilih Sifat TNA / Prioritas ---</option>
                                        @foreach ($sifatTnas as $sifatTna)
                                        <option value="{{ $sifatTna->sifat_tna_id }}" {{-- Memastikan data lama terpilih
                                            --}} {{ (old('sifat_tna_id', $pelatihan_tna->sifat_tna_id) ==
                                            $sifatTna->sifat_tna_id) ? 'selected' : '' }}>
                                            {{ $sifatTna->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('sifat_tna_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 5 Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="3"
                                placeholder="Jelaskan secara singkat alasan kebutuhan pelatihan ini.">{{ old('deskripsi', $pelatihan_tna->deskripsi) }}</textarea>
                            @error('deskripsi')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- PERBAIKAN: Input Hidden dengan Value dari Database --}}
                        {{-- status_tna di-hide tapi nilainya diambil dari data lama --}}
                        <input type="hidden" name="status_tna" value="{{ $pelatihan_tna->status_tna }}">
                        {{-- kode_tna di-hide tapi nilainya diambil dari data lama (Kode Urut TNA) --}}
                        <input type="hidden" name="kode_tna" value="{{ $pelatihan_tna->kode_tna }}">



                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pelatihan.tna.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
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
        $('#nip').select2({
            placeholder: "Pilih Pegawai", 
            allowClear: true,
            // Tambahkan dropdownParent untuk Select2 di dalam card/modal
            dropdownParent: $('#nip').closest('.card-body') 
        });
        
        // INISIALISASI SIFAT TNA
        $('#sifat_tna_id').select2({
            placeholder: "Pilih Prioritas TNA",
            allowClear: true,
            dropdownParent: $('#sifat_tna_id').closest('.card-body') 
        });
    });
</script>
@endpush