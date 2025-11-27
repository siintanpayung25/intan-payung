@extends('layouts.dasar')

@section('title', 'MySDM-Tambah Pelatihan')

@section('content')
<!-- Header dengan judul form dan tombol kembali -->
<div class="form-header">
    <h2>Tambah Pelatihan</h2> <!-- Judul Form di sebelah kiri -->
    <a id="back-btn" href="{{ route('pelatihan.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a> <!-- Tombol Kembali di sebelah kanan -->
</div>

<!-- Menampilkan pesan kesalahan di atas form -->
<!-- @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif -->

<!-- Formulir Tambah Pelatihan, diberi kotak dan styling -->
<form action="{{ route('pelatihan.store') }}" method="POST" class="center-form">
    @csrf

    <!-- NIP Pegawai -->
    <div class="form-group">
        <label for="nip">Nama Pegawai</label>
        <select name="nip" class="form-control select2" id="nip">
            @if ($NamalevelUser === 'Pegawai')
            <!-- Jika level user Pegawai, otomatis nip dari user yang login -->
            <option value="{{ $nip }}" selected>{{ Auth::user()->pegawai->nama }}</option>
            @else
            <option value="">Pilih Pegawai</option>
            @foreach ($pegawais as $pegawai)
            <option value="{{ $pegawai->nip }}" {{ old('nip')==$pegawai->nip ? 'selected' : '' }}>
                {{ $pegawai->nama }}
            </option>
            @endforeach
            @endif
        </select>
        @error('nip')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Skala Pelatihan -->
    <div class="form-group">
        <label for="skala_id">Skala Pelatihan</label>
        <select name="skala_id" class="form-control select2" id="skala_id">
            <option value="">Pilih Skala</option>
            @foreach($skalas as $skala)
            <option value="{{ $skala->skala_id }}" {{ old('skala_id')==$skala->skala_id ? 'selected' : '' }}>
                {{ $skala->nama }}
            </option>
            @endforeach
        </select>
        @error('skala_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Bentuk Pelatihan -->
    <div class="form-group">
        <label for="bentuk_id">Bentuk Pelatihan</label>
        <select name="bentuk_id" class="form-control select2" id="bentuk_id">
            <option value="">Pilih Bentuk</option>
            @foreach($bentuks as $bentuk)
            <option value="{{ $bentuk->bentuk_id }}" {{ old('bentuk_id')==$bentuk->bentuk_id ? 'selected' : '' }}>
                {{ $bentuk->nama }}
            </option>
            @endforeach
        </select>
        @error('bentuk_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Kategori Pelatihan -->
    <div class="form-group">
        <label for="kategori_id">Kategori Pelatihan</label>
        <select name="kategori_id" class="form-control select2" id="kategori_id">
            <option value="">Pilih Kategori</option>
            @foreach($kategoris as $kategori)
            <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id')==$kategori->kategori_id ? 'selected' : ''
                }}>
                {{ $kategori->nama }}
            </option>
            @endforeach
        </select>
        @error('kategori_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Jenis Pelatihan -->
    <div class="form-group">
        <label for="jenis_id">Jenis Pelatihan</label>
        <select name="jenis_id" class="form-control select2" id="jenis_id">
            <option value="">Pilih Jenis</option>
            @foreach($jeniss as $jenis)
            <option value="{{ $jenis->jenis_id }}" {{ old('jenis_id')==$jenis->jenis_id ? 'selected' : '' }}>
                {{ $jenis->nama }}
            </option>
            @endforeach
        </select>
        @error('jenis_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Training Need Analysis (TNA) -->
    <div class="form-group">
        <label for="tna_id">
            Pilih TNA jika pelatihan dari TNA (<i style="color: green; font-style: italic;">kosongkan jika bukan dari
                TNA</i>)
        </label>
        <select name="tna_id" class="form-control select2" id="tna_id">
            <option value="">Pilih TNA</option>
            @foreach($tnas as $tna)
            <option value="{{ $tna->tna_id }}" {{ old('tna_id')==$tna->tna_id ? 'selected' : '' }}>
                {{ $tna->nama }}
            </option>
            @endforeach
        </select>
        @error('tna_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Nama Pelatihan -->
    <div class="form-group">
        <label for="nama">Nama Pelatihan</label>
        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}">
        @error('nama')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tanggal Mulai -->
    <div class="form-group">
        <label for="tgl_mulai">Tanggal Mulai</label>
        <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="{{ old('tgl_mulai') }}">
        @error('tgl_mulai')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tanggal Selesai -->
    <div class="form-group">
        <label for="tgl_selesai">Tanggal Selesai</label>
        <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" value="{{ old('tgl_selesai') }}">
        @error('tgl_selesai')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Durasi -->
    <div class="form-group">
        <label for="durasi">Durasi</label>
        <input type="text" class="form-control" id="durasi" name="durasi" value="{{ old('durasi') ?? '' }}"
            placeholder="Contoh: 01.00" maxlength="6">
        @error('durasi')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Instansi -->
    <div class="form-group">
        <label for="instansi_id">Instansi</label>
        <select name="instansi_id" class="form-control select2" id="instansi_id">
            <option value="">Pilih Instansi</option>
            @foreach($instansi_gabung_universitas as $instansi)
            <option value="{{ $instansi->instansi_id }}" {{ old('instansi_id')==$instansi->instansi_id ? 'selected' : ''
                }}>
                {{ $instansi->nama }}
            </option>
            @endforeach
        </select>
        @error('instansi_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Jumlah peserta -->
    <div class="form-group">
        <label for="jumlah_peserta">Jumlah peserta</label>
        <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta"
            value="{{ old('jumlah_peserta') }}">
        @error('jumlah_peserta')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Rangking -->
    <div class="form-group">
        <label for="rangking">Rangking</label>
        <input type="number" class="form-control" id="rangking" name="rangking" value="{{ old('rangking') }}">
        @error('rangking')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Link Bukti Dukung -->
    <div class="form-group">
        <label for="link_bukti_dukung">Link Bukti Dukung</label>
        <input type="text" class="form-control @error('link_bukti_dukung') is-invalid @enderror" id="link_bukti_dukung"
            name="link_bukti_dukung" value="{{ old('link_bukti_dukung') }}">
        @error('link_bukti_dukung')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Nomor Sertifikat -->
    <div class="form-group">
        <label for="nama">Nomor sertifikat</label>
        <input type="text" class="form-control" id="nomor_sertifikat" name="nomor_sertifikat"
            value="{{ old('nomor_sertifikat') }}">
        @error('nomor_sertifikat')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk dropdown
        $('#nip').select2({
            placeholder: "Pilih Pegawai", // Placeholder untuk NIP Pegawai
            allowClear: true // Tombol clear (hapus)
        });

        $('#skala_id').select2({
            placeholder: "Pilih Skala Pelatihan...", // Placeholder untuk Skala Pelatihan
            allowClear: true
        });

        $('#bentuk_id').select2({
            placeholder: "Pilih Bentuk Pelatihan...",
            allowClear: true
        });

        $('#kategori_id').select2({
            placeholder: "Pilih Kategori Pelatihan...",
            allowClear: true
        });

        $('#jenis_id').select2({
            placeholder: "Pilih Jenis Pelatihan...", // Placeholder untuk Jenis Pelatihan
            allowClear: true
        });

        $('#tna_id').select2({
            placeholder: "Pilih TNA Pelatihan...", // Placeholder untuk TNA Pelatihan
            allowClear: true
        });

        $('#instansi_id').select2({
            placeholder: "Pilih Instansi...", // Placeholder untuk Instansi
            allowClear: true
        });

        // Event listener untuk perubahan pada dropdown Bentuk
        $('#bentuk_id').change(function() {
            var bentuk_id = $(this).val(); // Ambil nilai bentuk_id yang dipilih

            // Jika bentuk_id dipilih, maka lakukan request AJAX untuk mendapatkan kategori
            if (bentuk_id) {
                $.ajax({
                    url: '/get-kategoris-by-bentuk/' + bentuk_id, // Endpoint untuk ambil kategori berdasarkan bentuk_id
                    type: 'GET',
                    success: function(data) {
                        // Kosongkan dropdown kategori
                        $('#kategori_id').empty();
                        $('#kategori_id').append('<option value="">Pilih Kategori</option>');

                        // Tambahkan opsi kategori baru berdasarkan data yang diterima
                        $.each(data, function(key, value) {
                            $('#kategori_id').append('<option value="' + value.kategori_id + '">' + value.nama + '</option>');
                        });

                        // Kosongkan dropdown jenis
                        $('#jenis_id').empty();
                        $('#jenis_id').append('<option value="">Pilih Jenis</option>');
                    }
                });
            } else {
                // Jika tidak ada bentuk yang dipilih, kosongkan dropdown kategori dan jenis
                $('#kategori_id').empty();
                $('#kategori_id').append('<option value="">Pilih Kategori</option>');
                $('#jenis_id').empty();
                $('#jenis_id').append('<option value="">Pilih Jenis</option>');
            }
        });

        // Event listener untuk perubahan pada dropdown Kategori
        $('#kategori_id').change(function() {
            var kategori_id = $(this).val(); // Ambil nilai kategori_id yang dipilih

            // Jika kategori_id dipilih, maka lakukan request AJAX untuk mendapatkan jenis
            if (kategori_id) {
                $.ajax({
                    url: '/get-jenis-by-kategori/' + kategori_id, // Endpoint untuk ambil jenis berdasarkan kategori_id
                    type: 'GET',
                    success: function(data) {
                        // Kosongkan dropdown jenis
                        $('#jenis_id').empty();
                        $('#jenis_id').append('<option value="">Pilih Jenis</option>');

                        // Tambahkan opsi jenis baru berdasarkan data yang diterima
                        $.each(data, function(key, value) {
                            $('#jenis_id').append('<option value="' + value.jenis_id + '">' + value.nama + '</option>');
                        });
                    }
                });
            } else {
                // Jika tidak ada kategori yang dipilih, kosongkan dropdown jenis
                $('#jenis_id').empty();
                $('#jenis_id').append('<option value="">Pilih Jenis</option>');
            }
        });

        // Event listener untuk perubahan pada dropdown TNA
        // Fungsi untuk mengambil data TNA berdasarkan NIP
    function fetchTnaData(nip) {
        $.ajax({
            url: '/pelatihan-tna/ambil-tna-berdasarkan-nip/' + nip, // Endpoint untuk ambil TNA berdasarkan Nip
            type: 'GET',
            success: function(data) {
                // Jika data TNA belum ada (kosong), baru kosongkan dropdown dan isi dengan data baru
                if ($('#tna_id').val() === null || $('#tna_id').val() === '') {
                    $('#tna_id').empty();
                    $('#tna_id').append('<option value="">Pilih TNA</option>'); // Tambahkan opsi default
                }

                // Cek apakah data TNA kosong
                if (data.length === 0) {
                    // Jika tidak ada data, tambahkan opsi dengan pesan
                    $('#tna_id').append('<option value="" disabled>TNA tidak ditemukan</option>');
                } else {
                    // Isi dropdown TNA dengan data yang diterima
                    $.each(data, function(key, value) {
                        // Cek apakah status TNA adalah 1 (Sudah Dilaksanakan)
                        if (value.status_tna == 1) {
                            // Tambahkan keterangan (Sudah Dilaksanakan) di dalam kurung dan disable option
                            $('#tna_id').append('<option value="' + value.tna_id +
                                '" disabled>' + value.nama +
                                ' <span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span></option>'
                            );
                        } else {
                            // TNA yang belum dilaksanakan, bisa dipilih
                            $('#tna_id').append('<option value="' + value.tna_id +
                                '">' + value.nama + '</option>');
                        }
                    });
                }

                // Re-inisialisasi select2 setelah dropdown TNA di-update
                $('#tna_id').select2({
                    placeholder: "Pilih TNA Pelatihan...", // Placeholder untuk TNA Pelatihan
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Tidak ada TNA ditemukan"; // Pesan custom saat tidak ada hasil
                        }
                    },
                    templateResult: function(state) {
                        var $state = $('<span>' + state.text + '</span>');

                        // Jika TNA sudah dilaksanakan, ubah tampilannya untuk bagian dalam kurung
                        if ($(state.element).prop('disabled')) {
                            var text = $state.html();
                            // Mencari teks yang ada dalam kurung dan memberi gaya merah dan miring
                            $state.html(text.replace(/\(Sudah Dilaksanakan\)/g,
                                '<span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span>'
                            ));
                        }
                        return $state;
                    }
                });

                // Pastikan nilai yang dipilih sebelumnya tetap ada setelah error
                var selectedTna = '{{ old('tna_id') }}'; // Ambil nilai yang dipilih sebelumnya
                if (selectedTna) {
                    $('#tna_id').val(selectedTna).trigger('change');
                }
            },
            error: function() {
                console.error('Gagal memuat data TNA');
            }
        });
    }

    // Ketika NIP berubah, lakukan request AJAX untuk mendapatkan data TNA yang sesuai
    $('#nip').change(function() {
        var nip = $(this).val(); // Ambil nilai NIP yang dipilih
        if (nip) {
            fetchTnaData(nip); // Panggil fungsi untuk ambil data TNA berdasarkan NIP
        } else {
            // Jika tidak ada NIP yang dipilih, kosongkan dropdown TNA
            $('#tna_id').empty();
            $('#tna_id').append('<option value="">Pilih TNA</option>');
        }
    });

    // Jika halaman pertama kali dimuat (edit mode), ambil data TNA jika NIP sudah terisi
    var nip = $('#nip').val(); // Ambil nilai NIP yang sudah terisi
    if (nip) {
        fetchTnaData(nip); // Panggil fungsi untuk ambil data TNA berdasarkan NIP
    }

    });
</script>
@endpush