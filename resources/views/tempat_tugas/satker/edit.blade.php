@extends('layouts.dasar')

@section('title', 'Edit Satker')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Menggunakan struktur asli: col-lg-10 mx-auto --}}
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3">
                    <h5 class="mb-0">Edit Data Satker</h5>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('tempat_tugas.satker.update', $satker->satker_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Satker ID (hidden) -->
                        <input type="hidden" id="satker_id" name="satker_id" value="{{ $satker->satker_id }}">

                        <!-- Instansi -->
                        <div class="row">
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

                            <!-- Negara -->
                            <div class="col-md-6 mb-3">
                                <label for="negara_id" class="form-label fw-bold">Negara <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="negara_id" name="negara_id" required>
                                    <option value="" disabled selected>--- Pilih Negara ---</option>
                                    @isset($negara)
                                    @foreach ($negara as $negaraItem)
                                    <option value="{{ $negaraItem->negara_id }}" {{ $satker->negara_id ==
                                        $negaraItem->negara_id ? 'selected' : '' }}>
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

                        <!-- Provinsi -->
                        <div class="row">
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

                            <!-- Kabupaten -->
                            <div class="col-md-6 mb-3">
                                <label for="kabupaten_id" class="form-label fw-bold">Kabupaten <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="kabupaten_id" name="kabupaten_id" required>
                                    <option value="" disabled selected>--- Pilih Kabupaten ---</option>
                                    {{-- Opsi kabupaten akan di-load menggunakan AJAX --}}
                                </select>
                                @error('kabupaten_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Satker -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Satker <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama', $satker->nama) }}" placeholder="Nama Satker" required
                                    readonly>
                                @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ old('keterangan', $satker->keterangan) }}"
                                    placeholder="Keterangan tentang Satker">
                                @error('keterangan')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tempat_tugas.satker.index') }}" class="btn btn-secondary me-2">Batal</a>
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
                // Set selected value for Instansi
                $('#instansi_id').val("{{ $satker->instansi_id }}").trigger('change');
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat mengambil data instansi.');
            }
        });

        // Ambil Provinsi berdasarkan Negara yang sudah dipilih
        function loadProvinsi() {
            var negara_id = $('#negara_id').val();

            if (negara_id) {
                $.ajax({
                    url: '/wilayah-provinsi/ambil-provinsi/' + negara_id, // Memanggil ambilProvinsi di controller
                    method: 'GET',
                    success: function(data) {
                        $('#provinsi_id').empty().append('<option value="" disabled selected>--- Pilih Provinsi ---</option>');
                        data.forEach(function(provinsi) {
                            $('#provinsi_id').append(new Option(provinsi.nama, provinsi.provinsi_id));
                        });
                        // Set selected value for Provinsi
                        $('#provinsi_id').val("{{ $satker->provinsi_id }}").trigger('change');
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat mengambil data provinsi.');
                    }
                });
            } else {
                $('#provinsi_id').empty().append('<option value="" disabled selected>--- Pilih Provinsi ---</option>');
                $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
            }
        }

        // Ambil Kabupaten berdasarkan Provinsi yang sudah dipilih
        function loadKabupaten() {
            var provinsi_id = $('#provinsi_id').val();
            var negara_id = $('#negara_id').val();

            if (provinsi_id && negara_id) {
                $.ajax({
                    url: '/wilayah-kabupaten/ambil-kabupaten-dan-administrasi/' + negara_id + '/' + provinsi_id,
                    method: 'GET',
                    success: function(data) {
                        $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
                        data.forEach(function(kabupaten) {
                            $('#kabupaten_id').append(new Option(kabupaten.nama, kabupaten.kabupaten_id));
                        });
                        // Set selected value for Kabupaten
                        $('#kabupaten_id').val("{{ $satker->kabupaten_id }}").trigger('change');
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat mengambil data kabupaten.');
                    }
                });
            } else {
                $('#kabupaten_id').empty().append('<option value="" disabled selected>--- Pilih Kabupaten ---</option>');
            }
        }

        // Load Provinsi dan Kabupaten berdasarkan data yang sudah ada di satker
        loadProvinsi();
        loadKabupaten();

        // Ketika negara dipilih
        $('#negara_id').change(function() {
            loadProvinsi();  // Load provinsi berdasarkan negara
        });

        // Ketika provinsi dipilih
        $('#provinsi_id').change(function() {
            loadKabupaten();  // Load kabupaten berdasarkan provinsi
        });

        // Update nama satker saat instansi, provinsi, atau kabupaten dipilih
        $('#instansi_id, #provinsi_id, #kabupaten_id').change(function() {
            var instansi_nama = $('#instansi_id option:selected').text() || '';
            var provinsi_nama = $('#provinsi_id option:selected').text() || '';
            var kabupaten_nama = $('#kabupaten_id option:selected').text() || '';

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

        // Ketika form disubmit, pastikan validasi nama satker
        $('#submit_button').click(function(e) {
            e.preventDefault(); // Mencegah pengiriman form

            var instansi_id = $('#instansi_id').val();
            if (!instansi_id) {
                alert('Instansi belum dipilih!');
                return; // Hentikan submit jika instansi tidak dipilih
            }

            var nama = $('#nama').val();
            if (!nama) {
                alert('Nama Satker wajib diisi!');
                return; // Hentikan submit jika nama kosong
            }

            // Jika semua valid, kirim form
            $(this).closest('form').submit(); // Melanjutkan submit form
        });
    });
</script>
@endpush