@extends('layouts.dasar')

@section('title', 'Buat Satker Baru')

@section('content')
{{-- Library Select2 CSS dan JS diasumsikan sudah dimuat di layouts.dasar. --}}

<div class="container-fluid py-4">
    <div class="row">
        {{-- Menggunakan struktur asli: col-lg-10 mx-auto --}}
        <div class="col-lg-10 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3">
                    <h5 class="mb-0">Tambah Data Satker</h5>
                </div>
                {{-- Menggunakan card body asli: p-3 --}}
                <div class="card-body p-3">

                    {{-- Formulir untuk menambahkan Satker --}}
                    <form action="{{ route('tempat_tugas.satker.store') }}" method="POST">
                        @csrf

                        <!-- Satker ID (hidden) -->
                        <input type="hidden" id="satker_id" name="satker_id"
                            value="{{ old('satker_id', $satker_id ?? '') }}">

                        {{-- Container row untuk instansi --}}
                        <div class="row">
                            {{-- Instansi --}}
                            <div class="col-md-6 mb-3">
                                <label for="instansi_id" class="form-label fw-bold">Instansi <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="instansi_id" name="instansi_id" required>
                                    <option value="" disabled selected>--- Pilih Instansi ---</option>
                                    {{-- Data Instansi akan di-load menggunakan AJAX --}}
                                </select>
                                @error('instansi_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Negara --}}
                            <div class="col-md-6 mb-3">
                                <label for="negara_id" class="form-label fw-bold">Negara <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="negara_id" name="negara_id" required>
                                    <option value="" disabled selected>--- Pilih Negara ---</option>

                                    {{-- Data Negara --}}
                                    @isset($negara)
                                    @foreach ($negara as $negaraItem)
                                    <option value="{{ $negaraItem->negara_id }}" {{ old('negara_id')==$negaraItem->
                                        negara_id ? 'selected' : '' }}>
                                        {{ $negaraItem->nama }}
                                    </option>
                                    @endforeach
                                    @endisset
                                </select>
                                @error('negara_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Container row untuk wilayah --}}
                        <div class="row">
                            {{-- Provinsi --}}
                            <div class="col-md-6 mb-3">
                                <label for="provinsi_id" class="form-label fw-bold">Provinsi <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="provinsi_id" name="provinsi_id" required>
                                    <option value="" disabled selected>--- Pilih Provinsi ---</option>
                                    {{-- Opsi provinsi akan di-load menggunakan AJAX --}}
                                </select>
                                @error('provinsi_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kabupaten --}}
                            <div class="col-md-6 mb-3">
                                <label for="kabupaten_id" class="form-label fw-bold">Kabupaten <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="kabupaten_id" name="kabupaten_id" required>
                                    <option value="" disabled selected>--- Pilih Kabupaten ---</option>
                                    {{-- Data Kabupaten --}}
                                </select>
                                @error('kabupaten_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Container row untuk nama satker dan keterangan --}}
                        <div class="row">
                            {{-- Nama Satker --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Satker <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                                    placeholder="Nama Satker" required readonly> <!-- Tambahkan disabled di sini -->
                                @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Keterangan Satker --}}
                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ old('keterangan') }}" placeholder="Keterangan tentang Satker">
                                @error('keterangan')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit button dan cancel --}}
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tempat_tugas.satker.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="button" id="submit_button" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Satker
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
        // Inisialisasi Select2 untuk dropdown negara, provinsi, kabupaten, dan instansi
        $('#instansi_id').select2({
            placeholder: "Pilih Instansi",
            allowClear: true,
        });

        $('#negara_id').select2({
            placeholder: "Pilih Negara",
            allowClear: true,
        });

        $('#provinsi_id').select2({
            placeholder: "Pilih Provinsi",
            allowClear: true,
        });

        $('#kabupaten_id').select2({
            placeholder: "Pilih Kabupaten",
            allowClear: true,
        });

        // Ambil instansi untuk dropdown
        $.ajax({
            url: '{{ route('instansi.ambilInstansi') }}',  // Panggil rute untuk ambil data instansi
            method: 'GET',
            success: function(data) {
                $('#instansi_id').empty().append('<option value="" disabled selected>--- Pilih Instansi ---</option>');
                data.forEach(function(instansi) {
                    $('#instansi_id').append(new Option(instansi.nama, instansi.instansi_id));
                });
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat mengambil data instansi.');
            }
        });

        // Ketika instansi dipilih
        $('#instansi_id').change(function() {
            var instansi_nama = $('#instansi_id option:selected').text();  // Ambil nama instansi yang dipilih
            var provinsi_nama = $('#provinsi_id option:selected').text();  // Ambil nama provinsi yang dipilih
            var kabupaten_nama = $('#kabupaten_id option:selected').text();  // Ambil nama kabupaten yang dipilih

            // Cek jika nama provinsi atau kabupaten adalah placeholder, set null jika iya
            if (provinsi_nama === '--- Pilih Provinsi ---') {
                provinsi_nama = null;
            }

            if (kabupaten_nama === '--- Pilih Kabupaten ---') {
                kabupaten_nama = null;
            }

            // Cek kondisi: Jika kabupaten ada, pilih kabupaten. Jika hanya provinsi ada, pilih provinsi.
            if (kabupaten_nama) {
                // Jika kabupaten dipilih, tampilkan nama kabupaten
                $('#nama').val(instansi_nama + ' Kabupaten ' + kabupaten_nama);
            } else if (provinsi_nama) {
                // Jika hanya provinsi yang dipilih, tampilkan nama provinsi
                $('#nama').val(instansi_nama + ' Provinsi ' + provinsi_nama);
            } else {
                // Jika hanya instansi yang dipilih, tampilkan nama instansi
                $('#nama').val(instansi_nama);
            }
        });

        // Ketika negara dipilih
        $('#negara_id').change(function() {
            var negara_id = $(this).val();

            if (negara_id) {
                $.ajax({
                    url: '/wilayah-provinsi/ambil-provinsi/' + negara_id, // Memanggil ambilProvinsi di controller
                    method: 'GET',
                    success: function(data) {
                        $('#provinsi_id').empty().append('<option value="" disabled selected>--- Pilih Provinsi ---</option>');
                        data.forEach(function(provinsi) {
                            $('#provinsi_id').append(new Option(provinsi.nama, provinsi.provinsi_id));
                        });
                        $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat mengambil data provinsi.');
                    }
                });
            } else {
                $('#provinsi_id').empty().append('<option value="" disabled selected>--- Pilih Provinsi ---</option>');
                $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
                $('#nama').val(''); // Reset nama satker
            }
        });

        // Ketika provinsi dipilih
        $('#provinsi_id').change(function() {
            var provinsi_id = $(this).val();

            if (provinsi_id) {
                // Mengambil nama provinsi yang dipilih langsung dari dropdown
                var provinsi_nama = $('#provinsi_id option:selected').text();
                
                // Cek apakah instansi dipilih atau tidak
                var instansi_nama = $('#instansi_id option:selected').val() ? $('#instansi_id option:selected').text() : '';  // Ambil nama instansi, jika tidak ada pilihannya, kosongkan

                // Ganti "BPS" dengan nama instansi jika ada
                $('#nama').val(instansi_nama + ' Provinsi ' + provinsi_nama);

                // Clear kabupaten_id dan reset nama jika provinsi dipilih
                $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');

                // Ambil kabupaten terkait menggunakan AJAX jika provinsi dipilih
                $.ajax({
                    url: '/wilayah-kabupaten/ambil-kabupaten-dan-administrasi/' + $('#negara_id').val() + '/' + provinsi_id, // Memanggil ambilKabupaten di controller
                    method: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            data.forEach(function(kabupaten) {
                                $('#kabupaten_id').append(new Option(kabupaten.nama, kabupaten.kabupaten_id));
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat mengambil data kabupaten.');
                    }
                });
            } else {
                $('#nama').val(''); // Reset nama satker jika provinsi tidak dipilih
                $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
            }
        });

        // Ketika kabupaten dipilih
        $('#kabupaten_id').change(function() {
            var kabupaten_id = $(this).val();

            if (kabupaten_id) {
                $.ajax({
                    url: '/wilayah-kabupaten/ambil-kabupaten-dan-administrasi/' + $('#negara_id').val() + '/' + $('#provinsi_id').val(),
                    method: 'GET',
                    success: function(data) {
                        var kabupaten = data.find(kab => kab.kabupaten_id == kabupaten_id);

                        if (kabupaten) {
                            var status_adminkab_id = kabupaten.status_adminkab_id;

                            if (status_adminkab_id) {
                                $.ajax({
                                    url: '/wilayah-status-adm-kabupaten/ambil-status-kabupaten/' + status_adminkab_id,
                                    method: 'GET',
                                    success: function(statusData) {
                                        var status_adminkab_nama = statusData.nama || '';
                                        var kabupaten_nama = kabupaten.nama || '';

                                        // Ambil nama instansi
                                        var instansi_nama = $('#instansi_id option:selected').text() || '';

                                        // Mengisi nama
                                        $('#nama').val(instansi_nama + ' ' + (status_adminkab_nama ? status_adminkab_nama + ' ' : '') + kabupaten_nama);

                                        // Pastikan pengisian nama selesai sebelum validasi
                                        $('#submit_button').prop('disabled', false); // Enable tombol submit setelah semua selesai
                                    },
                                    error: function(xhr, status, error) {
                                        alert('Terjadi kesalahan saat mengambil data status administrasi.');
                                    }
                                });
                            } else {
                                alert('Status Admin Kab ID tidak ditemukan.');
                            }
                        } else {
                            alert('Kabupaten tidak ditemukan.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat mengambil data kabupaten.');
                    }
                });
            } else {
                $('#nama').val('');
            }
        });
        // Menambahkan alert sebelum submit
        $('#submit_button').click(function(e) {
            e.preventDefault(); // Mencegah pengiriman form

            // Cek apakah instansi_id sudah dipilih
            var instansi_id = $('#instansi_id').val();
            if (!instansi_id) {
                alert('Instansi belum dipilih!');
                return; // Hentikan submit jika instansi tidak dipilih
            }

             // Alert untuk memeriksa nilai instansi_id yang dipilih
            var instansi_nama = $('#instansi_id option:selected').text();  // Mengambil nama instansi
            // alert('Nama Instansi: ' + instansi_nama);  // Menampilkan alert dengan nama instansi

            // Cek apakah nama sudah diisi
            var nama = $('#nama').val();
            if (!nama) {
                alert('Nama Satker wajib diisi!');
                return; // Hentikan submit jika nama kosong
            }

            // Menampilkan alert untuk Nama Satker
            // alert('Nama Satker: ' + nama);  // Menampilkan nama yang sudah diisi di #nama

            // Jika semua valid, kirim form
            $(this).closest('form').submit(); // Melanjutkan submit form
        });
    });
</script>


@endpush