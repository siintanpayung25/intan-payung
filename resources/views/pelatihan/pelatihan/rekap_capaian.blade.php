@extends('layouts.dasar')
{{-- Menggunakan layout dasar sesuai permintaan --}}
@section('title', 'Rekap Capaian Durasi Pelatihan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rekap Capaian Pelatihan Pegawai</h1>

    <!-- Card Utama untuk Filter dan Aksi Data -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter dan Aksi Data</h6>
        </div>
        <div class="card-body">

            {{-- FORM FILTER DENGAN METHOD GET UNTUK MENDUKUNG CASCADING DROPDOWN SERVER-SIDE --}}
            {{-- Action mengarah kembali ke index agar Controller memuat ulang daftar Satker yang difilter --}}
            <form id="filter-form" method="GET" action="{{ route('pelatihan-rekap-capaian.index') }}">
                <div class="row mb-4">

                    {{-- Dropdown Filter: Tahun --}}
                    <div class="col-md-3">
                        <label for="filter_tahun">Tahun Rekap</label>
                        {{-- Menggunakan variabel $years dan $selectedYear dari Controller --}}
                        <select id="filter_tahun" name="tahun_rekap" class="form-control">
                            @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $year==$selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                            @endforeach
                            {{-- Catatan: Logika untuk menambahkan tahun saat ini sudah diurus oleh Controller Anda --}}
                        </select>
                    </div>

                    {{-- Dropdown Filter: Provinsi --}}
                    <div class="col-md-3">
                        <label for="filter_provinsi">Provinsi</label>
                        <select id="filter_provinsi" name="provinsi_id" class="form-control select2">
                            <option value="">-- Pilih Semua Provinsi --</option>
                            @foreach ($provinsis as $provinsi)
                            <option value="{{ $provinsi->provinsi_id }}" {{ $provinsi->provinsi_id ==
                                $selectedProvinsiId ? 'selected' : '' }}>
                                {{ $provinsi->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Filter: Satker (Diisi oleh Controller berdasarkan Provinsi) --}}
                    <div class="col-md-3">
                        <label for="filter_satker">Satker</label>
                        <select id="filter_satker" name="satker_id" class="form-control" {{ $selectedProvinsiId ? ''
                            : 'disabled' }}>

                            <option value="">-- Pilih Semua Satker --</option>

                            {{-- Loop data $satkers yang sudah difilter oleh Controller --}}
                            @foreach ($satkers as $satker)
                            <option value="{{ $satker->satker_id }}" {{ $satker->satker_id ==
                                $selectedSatkerId ? 'selected' : '' }}>
                                {{ $satker->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Filter: UNOR --}}
                    <div class="col-md-3">
                        <label for="filter_unor">Unit Organisasi (UNOR)</label>
                        <select id="filter_unor" name="unor_id" class="form-control">
                            <option value="">-- Semua UNOR --</option>
                            @foreach ($unors as $unor)
                            <option value="{{ $unor->unor_id }}" {{ $unor->unor_id == $selectedUnorId ? 'selected' : ''
                                }}>
                                {{ $unor->singkatan }} - {{ $unor->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {{-- AREA ACTION BUTTONS --}}
            <div class="d-flex justify-content-start gap-2">
                <button id="btn_tampilkan" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
                <button id="btn_rekap" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Sinkronisasi Data
                    Rekap</button>
                <button id="btn_export_excel" class="btn btn-success"><i class="fas fa-file-excel"></i> Ekspor
                    Excel</button>
            </div>
        </div>
    </div>

    {{-- AREA DATATABLE --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hasil Rekap Capaian Pelatihan</h6>
        </div>
        <div class="card-body">

            <!-- FITUR CARI DATATABLE (BARU DITAMBAHKAN) -->
            <div class="row mb-3">
                <div class="col-md-5 ml-auto">
                    {{-- FIX SPASI: Menggunakan d-flex untuk memisahkan input-group dari tombol clear --}}
                    <div class="d-flex align-items-center">
                        <div class="input-group">
                            {{-- FIX JARAK: Menambahkan me-2 pada input agar terpisah dari tombol Cari --}}
                            <input type="text" id="global_search" class="form-control me-2"
                                placeholder="Cari Nama Pegawai atau NIP..." autofocus>
                        </div>

                        {{-- FIX PENYEJAJARAN: Memastikan tombol berada di baris yang sama --}}
                        <button class="btn btn-primary mb-2" type="button" id="btn_trigger_search" title="Cari Data">
                            <i class="fas fa-search"></i>
                        </button>

                        {{-- FIX SPASI: Tombol Clear memiliki margin kiri (ms-2) --}}
                        <button class="btn btn-outline-secondary mb-2 ms-2" type="button" id="btn_clear_search"
                            title="Hapus Pencarian">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- AKHIR FITUR CARI DATATABLE -->

            <div class="table-responsive">
                <table class="table table-bordered" id="rekapDurasiTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama Pegawai</th>
                            <th>UNOR</th>
                            <th>Satker</th>
                            <th>Durasi TNA (Jam)</th>
                            <th>Durasi Non-TNA (Jam)</th>
                            <th>TOTAL Capaian (Jam)</th>
                            <th>Target Jam</th>
                            <th>% Capaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data Datatable akan diisi via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Memuat JQuery, SweetAlert2, dan DataTables (FIX: error ReferenceError dan Datatable tidak berfungsi) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

@endsection

@push('scripts')
<script>
    // Asumsi: SweetAlert2 (Swal) dan jQuery tersedia.
    
    $(document).ready(function() {
        
        // 1. CASCADING DROPDOWN LOGIC (Server-side submit)
        $('#filter_provinsi').change(function() {
            // Kosongkan Satker dan submit form untuk memuat ulang halaman
            $('#filter_satker').val(''); 
            $('#filter-form').submit();
        });
        
        // 2. DATATABLE INITIALIZATION
        var rekapTable = $('#rekapDurasiTable').DataTable({
            processing: true,
            serverSide: true,
            // Pastikan route ini sudah didefinisikan untuk endpoint Datatable
            ajax: {
                url: '{{ route("pelatihan.rekap_durasi.data") }}', 
                data: function (d) {
                    // Filter Dropdown
                    d.tahun = $('#filter_tahun').val();
                    d.provinsi_id = $('#filter_provinsi').val();
                    d.satker_id = $('#filter_satker').val();
                    d.unor_id = $('#filter_unor').val();
                    
                    // PENTING: Mengirim nilai pencarian ke server dengan parameter unik (untuk backend yang mengharapkan custom parameter)
                    d.global_search_value = $('#global_search').val(); 
                }
            },
            columns: [
                { data: 'nip', name: 'nip' },
                { data: 'nama_pegawai', name: 'pegawai.nama' }, 
                { data: 'nama_unor', name: 'unor.nama' }, 
                { data: 'nama_satker', name: 'satker.nama' }, 
                { data: 'durasi_tna', name: 'durasi_tna', class: 'text-right' },
                { data: 'durasi_non_tna', name: 'durasi_non_tna', class: 'text-right' },
                { data: 'durasi_total', name: 'durasi_total', class: 'text-right' },
                { data: 'target_jam_setahun', name: 'target_jam_setahun', class: 'text-right' },
                { 
                    data: 'persentase_capaian', 
                    name: 'persentase_capaian', 
                    class: 'text-right',
                    // Menambahkan renderer untuk format persentase
                    render: function(data, type, row) {
                        // Memastikan data adalah angka yang valid sebelum menambahkan '%'
                        if (data === null || data === undefined) return '';
                        return type === 'display' || type === 'filter' ? data + ' %' : data;
                    } 
                }
            ],
            order: [[0, 'asc']], 
            // 'rtip' memastikan Datatable tidak menampilkan fitur pencarian bawaan
            dom: 'rtip', 
            paging: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });

        // 3. EVENT HANDLERS
        
        // FIX PENCARIAN: Fungsi yang lebih robust untuk memuat ulang tabel
        const reloadTable = function(e) {
            if(e) e.preventDefault();

            // Set nilai pencarian ke Datatables API (mengirim parameter 'search[value]' standar)
            // Ini membantu jika backend Anda mengandalkan parameter standar Datatables
            rekapTable.search($('#global_search').val());
            
            // Muat ulang tabel (Datatables akan mengirim semua parameter, termasuk custom filters dan search value)
            rekapTable.ajax.reload();
        };

        // Tombol Tampilkan Data secara eksplisit: memicu reload Datatable
        $('#btn_tampilkan').on('click', reloadTable);
        
        // Reload Datatable secara otomatis ketika filter non-Provinsi diubah
        $('#filter_satker, #filter_unor, #filter_tahun').change(reloadTable);
        
        // 4. SEARCH INPUT HANDLERS
        
        // Tombol Cari Data yang baru ditambahkan
        $('#btn_trigger_search').on('click', reloadTable);
        
        // Tombol Clear Search: Mengosongkan input dan memuat ulang data
        $('#btn_clear_search').on('click', function() {
            $('#global_search').val('');
            reloadTable();
        });
        
        // Menambahkan Enter Key Listener agar user bisa langsung reload
        $('#global_search').on('keyup', function (e) {
            if (e.key === 'Enter') {
                reloadTable();
            }
        });


        // Logika Tombol Sinkronisasi (Implementasi AJAX)
        $('#btn_rekap').on('click', function(e) {
            e.preventDefault();

            // Kumpulkan data filter
            const dataFilter = {
                tahun: $('#filter_tahun').val(),
                provinsi_id: $('#filter_provinsi').val(),
                satker_id: $('#filter_satker').val(),
                unor_id: $('#filter_unor').val(),
            };

            // Tampilkan pesan konfirmasi (menggunakan SweetAlert2 jika tersedia)
            const confirmSync = Swal ? Swal.fire({
                title: 'Konfirmasi Sinkronisasi',
                text: "Anda yakin ingin menjalankan sinkronisasi data rekap durasi untuk filter yang dipilih? Proses ini mungkin memakan waktu.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }) : new Promise(resolve => resolve({ isConfirmed: confirm("Anda yakin ingin menjalankan sinkronisasi data rekap durasi?") }));
            
            confirmSync.then((result) => {
                if (result.isConfirmed) {
                    
                    // Tampilkan Loading Indicator
                    if (Swal) {
                        Swal.fire({
                            title: 'Memproses Sinkronisasi...',
                            html: 'Mohon tunggu, proses sinkronisasi sedang berjalan.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }

                    // Kirim permintaan AJAX
                    $.ajax({
                        // Menggunakan route yang sudah dikoreksi
                        url: '{{ route("pelatihan.rekap_durasi.sinkronisasi") }}', 
                        type: 'POST', // Biasanya POST untuk aksi yang memodifikasi data
                        data: {
                            ...dataFilter,
                            _token: '{{ csrf_token() }}' // Penting untuk POST Laravel
                        },
                        success: function(response) {
                            // Tampilkan pesan sukses dan reload Datatable
                            if (Swal) {
                                Swal.fire('Berhasil!', response.message || 'Data rekap durasi berhasil disinkronkan.', 'success');
                            } else {
                                alert(response.message || 'Data rekap durasi berhasil disinkronkan.');
                            }
                            rekapTable.ajax.reload();
                        },
                        error: function(xhr) {
                            // Tampilkan pesan error
                            const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat sinkronisasi data.';
                            if (Swal) {
                                Swal.fire('Gagal!', message, 'error');
                            } else {
                                alert(message);
                            }
                            console.error("Error Sinkronisasi:", xhr.responseText);
                        }
                    });
                }
            });
        });
        
        // ==========================================================
        // BARIS INI DIFUNGSIKAN: Logika Tombol Ekspor Excel
        // ==========================================================
        $('#btn_export_excel').on('click', function(e) {
            e.preventDefault();

            // 1. Kumpulkan semua nilai filter
            const tahun = $('#filter_tahun').val();
            const provinsiId = $('#filter_provinsi').val();
            const satkerId = $('#filter_satker').val();
            const unorId = $('#filter_unor').val();

            // 2. Tentukan URL dasar untuk ekspor (Asumsi route ini sudah didefinisikan di web.php)
            // Ganti "pelatihan-rekap-capaian.export" jika route Anda berbeda.
            const baseUrl = '{{ route("pelatihan.rekap_durasi.export", ["format" => "xlsx"]) }}';

            // 3. Buat objek parameter
            const params = {
                tahun_rekap: tahun,
                provinsi_id: provinsiId,
                satker_id: satkerId,
                unor_id: unorId,
                // Tambahkan parameter lain jika dibutuhkan oleh Controller (misalnya: 'format')
            };

            // 4. Konversi objek parameter menjadi string query (contoh: ?key=value&...)
            // $.param() dari jQuery sangat berguna untuk ini.
            const queryString = $.param(params);

            // 5. Gabungkan URL dasar dan string query
            const exportUrl = `${baseUrl}?${queryString}`;

            // 6. Picu unduhan dengan mengarahkan browser ke URL GET request
            window.location.href = exportUrl;
            
            // Opsional: Tampilkan notifikasi SweetAlert2 bahwa unduhan dimulai
            if (Swal) {
                Swal.fire({
                    title: 'Memulai Ekspor...',
                    text: 'File Excel sedang dipersiapkan dan akan diunduh sebentar lagi.',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                });
            } else {
                console.log("Memulai proses ekspor Excel ke:", exportUrl);
            }
        });
        // ==========================================================
    });
</script>
@endpush