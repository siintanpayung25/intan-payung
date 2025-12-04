@extends('layouts.dasar')

@section('title', 'MySDM-Ubah Pelatihan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header pb-0 p-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ubah Pelatihan</h5>

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

                <div class="card-body p-3">

                    <!-- Formulir Edit Pelatihan -->
                    <form action="{{ route('pelatihan.update', $pelatihan->pelatihan_id) }}" method="POST" @csrf
                        @method('PUT') {{-- ROW 1 --}} <div class="row">
                        <div class="col-md-6 mb-3">
                            <!-- NIP Pegawai -->
                            <label for="nip">Nama Pegawai</label>
                            <select name="nip" class="form-control select2" id="nip">
                                @if (session('NamalevelUser') === 'Pegawai')
                                <!-- Jika level user Pegawai, otomatis nip dari user yang login -->
                                <option value="{{ Auth::user()->pegawai->nip }}" selected>{{
                                    Auth::user()->pegawai->nama
                                    }}
                                </option>
                                @else
                                <option value="">Pilih Pegawai</option>
                                @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->nip }}" {{ old('nip', $pelatihan->nip) == $pegawai->nip
                                    ?
                                    'selected' : '' }}>
                                    {{ $pegawai->nama }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            @error('nip')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <!-- Skala Pelatihan -->
                            <div class="form-group">
                                <label for="skala_id">Skala Pelatihan</label>
                                <select name="skala_id" class="form-control" id="skala_id">
                                    <option value="">Pilih Skala</option>
                                    @foreach($skalas as $skala)
                                    <option value="{{ $skala->skala_id }}" {{ old('skala_id', $pelatihan->skala_id)
                                        ==
                                        $skala->skala_id ?
                                        'selected' : '' }}>
                                        {{ $skala->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('skala_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ROW 2 --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <!-- Bentuk Pelatihan -->
                                <label for="bentuk_id">Bentuk Pelatihan</label>
                                <select name="bentuk_id" class="form-control" id="bentuk_id">
                                    <option value="">Pilih Bentuk</option>
                                    @foreach($bentuks as $bentuk)
                                    <option value="{{ $bentuk->bentuk_id }}" {{ old('bentuk_id', $pelatihan->
                                        bentuk_id)
                                        ==
                                        $bentuk->bentuk_id ?
                                        'selected' : '' }}>
                                        {{ $bentuk->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bentuk_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Kategori Pelatihan -->
                                <label for="kategori_id">Kategori Pelatihan</label>
                                <select name="kategori_id" class="form-control" id="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id', $pelatihan->
                                        kategori_id)
                                        ==
                                        $kategori->kategori_id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Jenis Pelatihan -->
                                <label for="jenis_id">Jenis Pelatihan</label>
                                <select name="jenis_id" class="form-control" id="jenis_id">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jeniss as $jenis)
                                    <option value="{{ $jenis->jenis_id }}" {{ old('jenis_id', $pelatihan->
                                        jenis_id)
                                        ==
                                        $jenis->jenis_id ?
                                        'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jenis_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ROW 3 --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <!-- Training Need Analysis (TNA) -->
                                <label for="tna_id">
                                    Pilih TNA (<i style="color: green; font-style: italic;">kosongkan jika bukan dari
                                        TNA</i>)
                                </label>
                                <select name="tna_id" class="form-control select2" id="tna_id">
                                    <option value="">Pilih TNA</option>
                                    @foreach($tnas as $tna)
                                    <option value="{{ $tna->tna_id }}" {{ old('tna_id', $pelatihan->tna_id) ==
                                        $tna->tna_id
                                        ?
                                        'selected' : ''
                                        }}>
                                        {{ $tna->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tna_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <!-- Nama Pelatihan -->
                                <label for="nama">Nama Pelatihan</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama', $pelatihan->nama) }}">
                                @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ROW 4 --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <!-- Tanggal Mulai -->
                                <label for="tgl_mulai">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai"
                                    value="{{ old('tgl_mulai', $pelatihan->tgl_mulai) }}">
                                @error('tgl_mulai')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Tanggal Selesai -->
                                <label for="tgl_selesai">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                    value="{{ old('tgl_selesai', $pelatihan->tgl_selesai) }}">
                                @error('tgl_selesai')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Durasi -->
                                <label for="durasi">Durasi</label>
                                <input type="text" class="form-control" id="durasi" name="durasi"
                                    value="{{ old('durasi', $pelatihan->durasi ? substr(str_replace(':', '.', $pelatihan->durasi), 0, 5) : '') }}"
                                    placeholder="HH.MM" maxlength="5">
                                @error('durasi')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ROW 5 --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <!-- Instansi -->
                                <label for="instansi_id">Instansi</label>
                                <select name="instansi_id" class="form-control" id="instansi_id">
                                    <option value="">Pilih Instansi</option>
                                    @foreach($instansi_gabung_universitas as $instansi)
                                    <option value="{{ $instansi->instansi_id }}" {{ old('instansi_id', $pelatihan->
                                        instansi_id)
                                        ==
                                        $instansi->instansi_id ? 'selected' : '' }}>
                                        {{ $instansi->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('instansi_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Jumlah peserta -->
                                <label for="jumlah_peserta">Jumlah peserta</label>
                                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta"
                                    value="{{ old('jumlah_peserta', $pelatihan->jumlah_peserta) }}">
                                @error('jumlah_peserta')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <!-- Rangking -->
                                <label for="rangking">Rangking</label>
                                <input type="number" class="form-control" id="rangking" name="rangking"
                                    value="{{ old('rangking', $pelatihan->rangking) }}">
                                @error('rangking')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ROW 6 --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <!-- Link Bukti Dukung -->
                                <label for="link_bukti_dukung">Link Bukti Dukung</label>
                                <input type="text" class="form-control @error('link_bukti_dukung') is-invalid @enderror"
                                    id="link_bukti_dukung" name="link_bukti_dukung"
                                    value="{{ old('link_bukti_dukung', $pelatihan->link_bukti_dukung) }}">
                                @error('link_bukti_dukung')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <!-- Nomor Sertifikat -->
                                <label for="nomor_sertifikat">Nomor Sertifikat</label>
                                <input type="text" class="form-control" id="nomor_sertifikat" name="nomor_sertifikat"
                                    value="{{ old('nomor_sertifikat', $pelatihan->nomor_sertifikat) }}">
                                @error('nomor_sertifikat')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pelatihan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update
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
    // // Fungsi filterTNA yang digunakan untuk mendapatkan data TNA berdasarkan NIP
    // function filterTNA(nip) {
    //     // Kosongkan dropdown TNA sebelum mengisi
    //     $('#tna_id').empty();
    //     $('#tna_id').append('<option value="">Pilih TNA</option>');

    //     $.ajax({
    //         url: '{{ url('pelatihan-tna/ambil-tna-berdasarkan-nip') }}/' + nip, // Endpoint untuk ambil TNA berdasarkan NIP
    //         type: 'GET',
    //         success: function(data) {
    //             // Cek apakah data TNA kosong
    //             if (data.length === 0) {
    //                 // Jika tidak ada data, tambahkan opsi dengan pesan
    //                 $('#tna_id').append('<option value="" disabled>TNA tidak ditemukan</option>');
    //             } else {
    //                 // Isi dropdown TNA dengan data yang diterima
    //                 $.each(data, function(key, value) {
    //                     // Cek apakah status TNA adalah 1 (Sudah Dilaksanakan)
    //                     if (value.status_tna == 1) {
    //                         // Tambahkan keterangan (Sudah Dilaksanakan) di dalam kurung dan disable option
    //                         $('#tna_id').append('<option value="' + value.tna_id +
    //                             '" disabled>' + value.nama +
    //                             ' <span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span></option>'
    //                         );
    //                     } else {
    //                         // TNA yang belum dilaksanakan, bisa dipilih
    //                         $('#tna_id').append('<option value="' + value.tna_id +
    //                             '">' + value.nama + '</option>');
    //                     }
    //                 });
    //             }

    //             // Re-inisialisasi select2 setelah dropdown TNA di-update
    //             $('#tna_id').select2({
    //                 placeholder: "Pilih TNA Pelatihan...", // Placeholder untuk TNA Pelatihan
    //                 allowClear: true,
    //                 language: {
    //                     noResults: function() {
    //                         return "Tidak ada TNA ditemukan"; // Pesan custom saat tidak ada hasil
    //                     }
    //                 },
    //                 templateResult: function(state) {
    //                     var $state = $('<span>' + state.text + '</span>');

    //                     // Jika TNA sudah dilaksanakan, ubah tampilannya untuk bagian dalam kurung
    //                     if ($(state.element).prop('disabled')) {
    //                         var text = $state.html();
    //                         // Mencari teks yang ada dalam kurung dan memberi gaya merah dan miring
    //                         $state.html(text.replace(/\(Sudah Dilaksanakan\)/g,
    //                             '<span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span>'
    //                         ));
    //                     }
    //                     return $state;
    //                 }
    //             });

    //             // Pastikan nilai yang dipilih sebelumnya tetap ada setelah error
    //             var selectedTna = '{{ old('tna_id') }}'; // Ambil nilai yang dipilih sebelumnya
    //             if (selectedTna) {
    //                 $('#tna_id').val(selectedTna).trigger('change');
    //             }
    //         },
    //         error: function() {
    //             console.error('Gagal memuat data TNA');
    //         }
    //     });
    // }

    $(document).ready(function() {

        // Inisialisasi Select2 untuk dropdown
        // Ambil nilai NIP yang sudah terisi saat halaman pertama kali dimuat
        // var nip = $('#nip').val(); 

        // // Jika NIP sudah terisi, langsung filter TNA berdasarkan NIP tersebut
        // if (nip) {
        //     filterTNA(nip); // Panggil fungsi filterTNA
        // }

        

        // Pastikan TNA yang sudah ada di database ditampilkan di dropdown saat halaman pertama kali dimuat
    // var tnaId = $('#tna_id').val();  // Ambil nilai TNA yang sudah terisi sebelumnya (dari database)
    // if (tnaId) {
    //     // TNA sudah ada, langsung set nilai TNA
    //     $('#tna_id').val(tnaId).trigger('change');
    // }

        $('#nip').select2({
            placeholder: "Pilih Pegawai", // Placeholder untuk NIP Pegawai
            allowClear: true // Tombol clear (hapus)
        });

        $('#skala_id').select2({
            placeholder: "Pilih Skala Pelatihan...", // Placeholder untuk Skala Pelatihan
            allowClear: true
        });

        $('#bentuk_id').select2({
            placeholder: "Pilih Bentuk Pelatihan...", // Placeholder untuk Bentuk Pelatihan
            allowClear: true
        });

        $('#kategori_id').select2({
            placeholder: "Pilih Kategori Pelatihan...", // Placeholder untuk Kategori Pelatihan
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
                    // url: '{{ url('get-kategoris-by-bentuk') }}/' + bentuk_id,
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
                    // url: '{{ url('gget-jenis-by-kategori') }}/' + kategori_id,
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

    // Inisialisasi Select2 untuk dropdown
    $('#nip').select2({
        placeholder: "Pilih Pegawai", // Placeholder untuk NIP Pegawai
        allowClear: true // Tombol clear (hapus)
    });

    // Event listener untuk perubahan pada dropdown NIP
    $('#nip').change(function() {
        var nip = $(this).val(); // Ambil nilai NIP yang dipilih

        // Jika NIP sudah terisi, filter TNA berdasarkan NIP tersebut
        if (nip) {
            updateTnaDropdown(nip); // Panggil fungsi untuk memperbarui dropdown TNA berdasarkan NIP
        } else {
            // Jika NIP kosong, tampilkan pesan default di dropdown TNA
            $('#tna_id').empty();
            $('#tna_id').append('<option value="">Pilih TNA</option>');
        }
    });

    // Event listener untuk klik pada dropdown TNA
    $('#tna_id').click(function() {
        var nip = $('#nip').val(); // Ambil kembali nilai NIP jika terjadi perubahan
        if (nip) {
            updateTnaDropdown(nip); // Panggil fungsi untuk memperbarui dropdown TNA berdasarkan NIP
        } else {
            // Jika NIP kosong, tampilkan pesan default di dropdown TNA
            $('#tna_id').empty();
            $('#tna_id').append('<option value="">Pilih TNA</option>');
        }
    });

    // Fungsi untuk memperbarui dropdown TNA berdasarkan NIP
    function updateTnaDropdown(nip) {
        if (nip) {
            $.ajax({
                url: '{{ url('pelatihan-tna/ambil-tna-berdasarkan-nip') }}/' + nip, // Ganti dengan route yang sesuai
                type: 'GET',
                data: { nip: nip },
                success: function(data) {
                    // Kosongkan dropdown TNA sebelumnya
                    $('#tna_id').empty();
                    $('#tna_id').append('<option value="">Pilih TNA</option>'); // Tambahkan opsi default

                    // Isi dropdown TNA dengan data yang diterima
                    $.each(data, function(key, value) {
                        // Cek apakah status TNA adalah 1 (Sudah Dilaksanakan)
                        if (value.status_tna == 1) {
                            // Tambahkan keterangan (Sudah Dilaksanakan) di dalam kurung dan disable option
                            $('#tna_id').append('<option value="' + value.tna_id + '" disabled>' + value.nama + ' <span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span></option>');
                        } else {
                            // TNA yang belum dilaksanakan, bisa dipilih
                            $('#tna_id').append('<option value="' + value.tna_id + '">' + value.nama + '</option>');
                        }
                    });

                    // Re-inisialisasi select2 setelah dropdown TNA di-update
                    $('#tna_id').select2({
                        placeholder: "Pilih TNA Pelatihan...", // Placeholder untuk TNA Pelatihan
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return "Tidak ada TNA ditemukan"; // Ganti pesan default jika tidak ada hasil
                            }
                        },
                        templateResult: function(state) {
                            var $state = $('<span>' + state.text + '</span>');
                            if ($(state.element).prop('disabled')) {
                                var text = $state.html();
                                $state.html(text.replace(/\(Sudah Dilaksanakan\)/g, 
                                    '<span style="color:red; font-style:italic;">(Sudah Dilaksanakan)</span>'));
                            }
                            return $state;
                        }
                    });

                    // Pastikan nilai yang dipilih sebelumnya tetap ada setelah error
                    var selectedTna = '{{ old('tna_id', $pelatihan->tna_id) }}'; // Ambil nilai yang dipilih sebelumnya
                    if (selectedTna) {
                        $('#tna_id').val(selectedTna).trigger('change'); // Pilih nilai TNA yang sudah dipilih sebelumnya
                    }
                },
                error: function() {
                    console.error('Gagal memuat data TNA');
                }
            });
        } else {
            // Reset dropdown TNA jika nip kosong
            $('#tna_id').empty();
            $('#tna_id').append('<option value="">Pilih TNA</option>');
            $('#tna_id').select2({
                placeholder: "Pilih TNA Pelatihan...",
                allowClear: true
            });
        }
    }

    // Cek apakah NIP sudah terisi saat halaman pertama kali dimuat
    var nip = $('#nip').val();
    if (nip) {
        updateTnaDropdown(nip); // Panggil fungsi untuk memperbarui dropdown TNA berdasarkan NIP yang ada
    }
    });
</script>

<!-- Mengatur format durasi dalam H:i -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Membatasi input hanya dalam format HH.MM
        const durasiInput = document.getElementById('durasi');

        durasiInput.addEventListener('input', function(e) {
            // Mengganti titik dua dengan titik
            let value = e.target.value;
            if (value.includes(':')) {
                value = value.replace(/:/g, '.');
            }
            // Memastikan panjang input 5 karakter (maksimal HH.MM)
            if (value.length <= 5) {
                e.target.value = value;
            } else {
                e.target.value = value.slice(0, 5);
            }
        });
    });
</script>
@endpush