@extends('layouts.dasar')

@section('title', 'Tambah Pelatihan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Data Pelatihan</h5>

                    <a href="{{ route('pelatihan.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-3">

                    <form action="{{ route('pelatihan.store') }}" method="POST">
                        @csrf

                        {{-- ===================== ROW 1: Pegawai & Skala ===================== --}}
                        <div class="row">
                            {{-- PEGAWAI --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pegawai <span class="text-danger">*</span></label>

                                <select name="nip" id="nip" class="form-control select2" @if($NamalevelUser=='Pegawai' )
                                    disabled @endif>

                                    @if($NamalevelUser==='Pegawai')
                                    <option value="{{ $nip }}" selected>
                                        {{ Auth::user()->pegawai->nama }} ({{ $nip }})
                                    </option>
                                    @else
                                    <option value="">Pilih Pegawai</option>
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->nip }}" {{ old('nip')==$pegawai->nip ? 'selected':'' }}>
                                        {{ $pegawai->nama }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>

                                @if($NamalevelUser==='Pegawai')
                                <input type="hidden" name="nip" value="{{ $nip }}">
                                @endif

                                @error('nip')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SKALA --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Skala Pelatihan</label>
                                <select name="skala_id" id="skala_id" class="form-control select2">
                                    <option value="">Pilih Skala</option>
                                    @foreach ($skalas as $skala)
                                    <option value="{{ $skala->skala_id }}" {{ old('skala_id')==$skala->
                                        skala_id?'selected':'' }}>
                                        {{ $skala->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('skala_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- ===================== ROW 2: Bentuk, Kategori, Jenis ===================== --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Bentuk Pelatihan</label>
                                <select name="bentuk_id" id="bentuk_id" class="form-control select2">
                                    <option value="">Pilih Bentuk</option>
                                    @foreach ($bentuks as $bentuk)
                                    <option value="{{ $bentuk->bentuk_id }}" {{ old('bentuk_id')==$bentuk->
                                        bentuk_id?'selected':'' }}>
                                        {{ $bentuk->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bentuk_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori Pelatihan</label>
                                <select name="kategori_id" id="kategori_id" class="form-control select2">
                                    <option value="">Pilih Kategori</option>
                                </select>
                                @error('kategori_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jenis Pelatihan</label>
                                <select name="jenis_id" id="jenis_id" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                </select>
                                @error('jenis_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- ===================== ROW 3: TNA & Nama Pelatihan ===================== --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    TNA (Opsional)
                                    <span class="text-muted small">(kosongkan jika bukan dari TNA)</span>
                                </label>
                                <select name="tna_id" id="tna_id" class="form-control select2">
                                    <option value="">Pilih TNA</option>
                                </select>
                                @error('tna_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Pelatihan</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                                @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- ===================== ROW 4: Tanggal & Durasi ===================== --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai') }}">
                                @error('tgl_mulai') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control"
                                    value="{{ old('tgl_selesai') }}">
                                @error('tgl_selesai') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Durasi</label>
                                <input type="text" name="durasi" class="form-control" placeholder="01.00"
                                    value="{{ old('durasi') }}">
                                @error('durasi') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- ===================== ROW 5: Instansi & Peserta & Rank ===================== --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Instansi</label>
                                <select name="instansi_id" id="instansi_id" class="form-control select2">
                                    <option value="">Pilih Instansi</option>
                                    @foreach($instansi_gabung_universitas as $instansi)
                                    <option value="{{ $instansi->instansi_id }}" {{ old('instansi_id')==$instansi->
                                        instansi_id?'selected':'' }}>
                                        {{ $instansi->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('instansi_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jumlah Peserta</label>
                                <input type="number" name="jumlah_peserta" class="form-control"
                                    value="{{ old('jumlah_peserta') }}">
                                @error('jumlah_peserta') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Rangking</label>
                                <input type="number" name="rangking" class="form-control" value="{{ old('rangking') }}">
                                @error('rangking') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- ===================== ROW 6: Bukti & Sertifikat ===================== --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Link Bukti Dukung</label>
                                <input type="text" name="link_bukti_dukung"
                                    class="form-control @error('link_bukti_dukung') is-invalid @enderror"
                                    value="{{ old('link_bukti_dukung') }}">
                                @error('link_bukti_dukung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control"
                                    value="{{ old('nomor_sertifikat') }}">
                                @error('nomor_sertifikat') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pelatihan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection