@extends('layouts.dasar')
{{-- Menggunakan nama layout yang Anda berikan --}}

@section('title', 'MySDM-TNA')

{{-- TIDAK ADA @section('styles') KARENA SEMUA CSS SUDAH DI-LOAD DI LAYOUT --}}

@section('content')
<h2>Daftar TNA Pegawai</h2>
<div class="card shadow mb-1">
    <div class="card-header py-3">
        {{-- TOMBOL AKSI ATAS --}}
        <div class="d-flex justify-content-between mb-1">
            <div>
                {{-- Tombol Tambah (Create) --}}
                <a href="{{ route('pelatihan.tna.create') }}" class="btn btn-sm btn-outline-primary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data TNA Baru">
                    <i class="fas fa-plus"></i>
                </a>

                <!-- Ekspor ke Excel -->
                <a href="{{ route('pelatihan.tna.ekspor') }}" class="btn btn-sm btn-outline-success mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Ekspor ke Excel">
                    <i class="fas fa-file-excel"></i>
                </a>

                <!-- Backup ke Excel -->
                <a href="{{ route('pelatihan.tna.backupTna') }}" class="btn btn-sm btn-outline-success mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Backup ke Excel">
                    <i class="fas fa-download"></i> <!-- Ikon unduh -->
                </a>

                {{-- Tombol Impor --}}
                <a href="#" id="import-btn" class="btn btn-sm btn-outline-info mx-1" data-bs-toggle="modal"
                    data-bs-target="#importTnaModal" data-bs-placement="top" title="Impor Data TNA">
                    <i class="fas fa-arrow-alt-circle-down"></i> <!-- Ikon impor -->
                </a>

                <!-- Link untuk download Template CSV -->
                <a href="{{ route('pelatihan.tna.templateTnaCSV') }}" class="btn btn-sm btn-outline-secondary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Download Template CSV">
                    <i class="fas fa-file-csv"></i>
                </a>

                <!-- Link untuk download Template Excel -->
                <a href="{{ route('pelatihan.tna.templateTnaExcel') }}" class="btn btn-sm btn-outline-secondary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Download Template Excel">
                    <i class="fas fa-file-excel"></i>
                </a>

                {{-- Tombol Hapus Terpilih (Bulk Delete) - ID: hapus-terpilih --}}
                <button id="hapus-terpilih" class="btn btn-sm btn-outline-danger mx-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Hapus Data Terpilih" style="display: none;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">



        {{-- DATATABLE TNA --}}
        <div class="table-responsive">
            {{-- ID tabel diganti menjadi pelatihan-table agar sinkron dengan JS di dasar.blade.php --}}
            <table class="table table-bordered" id="pelatihan-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 20px;"><input type="checkbox" id="master-checkbox"></th>
                        <th>Nama Pegawai / NIP</th>
                        <th>Kebutuhan Pelatihan</th>
                        <th>Skala TNA</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data dimuat oleh DataTables AJAX --}}
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

<!-- Modal Impor -->
<div class="modal fade" id="importTnaModal" tabindex="-1" aria-labelledby="importTnaModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importTnaModalLabel">Impor Data TNA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatihan.tna.impor') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel/CSV</label>
                        <input class="form-control" type="file" id="file" name="file" required>
                        <div class="form-text">Maksimal ukuran file 10MB. Format yang didukung: XLSX, XLS, CSV.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let table;
    let bulkDeleteIds = [];

    // Fungsi untuk menampilkan/menyembunyikan tombol Bulk Delete
    function toggleBulkDeleteButton() {
        // HANYA hitung checkbox yang TERPILIH dan TIDAK dinonaktifkan
        var checkedCount = $('.row-checkbox:checked:not(:disabled)').length;
        if (checkedCount > 0) {
            $('#hapus-terpilih').show();
        } else {
            $('#hapus-terpilih').hide();
            // Jika tombol bulk delete tersembunyi, pastikan master-checkbox juga tidak terceklis
            if ($('#master-checkbox').prop('checked')) { // FIX: Menggunakan ID master-checkbox
                $('#master-checkbox').prop('checked', false); // FIX: Menggunakan ID master-checkbox
            }
        }
    }

    // Fungsi untuk mereset semua checkbox (digunakan setelah modal ditutup atau pembatalan)
    function resetCheckboxes() {
    // Reset checkbox di kolom judul dengan ID master-checkbox
    $('#master-checkbox').prop('checked', false); // Reset checkbox di judul kolom
    
    // Reset checkbox pada setiap baris, kecuali yang dinonaktifkan
    $('#pelatihan-table tbody .row-checkbox:not(:disabled)').prop('checked', false);
    
    // Memastikan tombol bulk delete dinonaktifkan jika tidak ada checkbox yang dipilih
    toggleBulkDeleteButton();
}

    $(document).ready(function() {
        // --- 1. Inisialisasi DataTable Serverside ---
        var table = $('#pelatihan-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            // Panggil rute datatable yang benar
            ajax: "{{ route('pelatihan.tna.datatable') }}", 
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    // Menggunakan tna_id sebagai data, dan merender checkbox berdasarkan status_tna
                    data: 'tna_id', // Pastikan server mengirimkan tna_id
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        // Cek status_tna. Anggap status '1' adalah terkunci.
                        const isLocked = String(row.status_tna) === '1';
                        const disabledAttr = isLocked ? 'disabled' : '';
                        const tooltip = isLocked ? 'data-bs-toggle="tooltip" data-bs-placement="top" title="Dikunci karena sudah dilaksanakan"' : '';
                        
                        // Checkbox value adalah ID TNA
                        return `<input type="checkbox" class="row-checkbox select-item" name="ids[]" value="${data}" ${disabledAttr} ${tooltip}>`;
                    }
                },
                { data: 'pegawai_nama', name: 'pegawai_nama' }, 
                { data: 'nama', name: 'nama' }, 
                { data: 'sifat_tna_nama', name: 'sifatTna.nama' }, 
                { data: 'tahun', name: 'tahun' },
                { data: 'status', name: 'status', orderable: true },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],           
        });

        // --- 2. Logika Checkbox untuk Master dan Bulk Delete ---
        
        // Cek/Uncek semua
        $('#master-checkbox').on('click', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        // Cek individual
        $('#pelatihan-table tbody').on('change', '.row-checkbox', function() {
            var allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
            $('#master-checkbox').prop('checked', allChecked);
            toggleBulkDeleteButton();
        });

        // =========================================================
        // PENGELOLAAN CHECKBOX DAN TOOLTIPS PADA DataTables DRAW
        // =========================================================
        table.on('draw.dt', function() {
            // Sinkronisasi status 'master-checkbox' (Jika semua item yang TIDAK disabled terpilih)
            const totalCheckboxes = $('.row-checkbox:not(:disabled)').length;
            const checkedCheckboxes = $('.row-checkbox:checked:not(:disabled)').length;
            if (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes) {
                $('#master-checkbox').prop('checked', true); // FIX: Menggunakan ID master-checkbox
            } else {
                $('#master-checkbox').prop('checked', false); // FIX: Menggunakan ID master-checkbox
            }
            
            // Pastikan tombol bulk delete disembunyikan jika tidak ada yang dipilih
            toggleBulkDeleteButton();
        });

        // Handle Klik Checkbox 'Select All' (Blok ini diubah untuk menggunakan master-checkbox)
        $('#master-checkbox').off('click').on('click', function() { // FIX: Menggunakan ID master-checkbox
            var isChecked = $(this).prop('checked');
            // HANYA centang/uncentang checkbox yang TIDAK disabled
            $('#pelatihan-table tbody .row-checkbox:not(:disabled)').prop('checked', isChecked);
            toggleBulkDeleteButton();
        });

        // Handle Klik Checkbox Baris
        $('#pelatihan-table').off('change', '.row-checkbox').on('change', '.row-checkbox', function() {
            // Abaikan perubahan jika checkbox disabled
            if ($(this).prop('disabled')) return;
            
            // Cek apakah semua checkbox yang TIDAK disabled sudah terpilih
            const totalCheckboxes = $('.row-checkbox:not(:disabled)').length;
            const checkedCheckboxes = $('.row-checkbox:checked:not(:disabled)').length;
            
            if (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes) {
                 $('#master-checkbox').prop('checked', true); // FIX: Menggunakan ID master-checkbox
            } else {
                 $('#master-checkbox').prop('checked', false); // FIX: Menggunakan ID master-checkbox
            }

            toggleBulkDeleteButton();
        });

        // =========================================================
        // LOGIKA HAPUS TUNGGAL & MASSAL (Menggunakan Modal Global)
        // =========================================================

        // 1. Tampilkan modal untuk Hapus Tunggal (menampilkan detail data)
        $(document).off('click', '.btn-delete');
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();

            const $tr = $(this).closest('tr');
            // DataTables API digunakan untuk mengambil data lengkap dari baris yang diklik
            const rowData = table.row($tr).data();

            // Peningkatan keandalan: konversi ke string dan cek eksplisit
            const statusTNA = rowData && rowData.status_tna !== undefined ? String(rowData.status_tna) : null;
            const isLocked = statusTNA === '1';

            if (isLocked) {
                // Tampilkan pesan peringatan BUKAN pesan konfirmasi hapus
                $('#modalMessageLabel').text('Aksi Dibatalkan: Data TNA Terkunci');
                $('#modalMessageBody').html(`
                    <p class="text-danger">
                        <i class="fas fa-lock me-2"></i> 
                        Data TNA ini tidak dapat dihapus karena ${rowData.status || 'Terkunci'}.
                    </p>
                    <p class="small">Hubungi administrator jika diperlukan.</p>
                `);
                // Sembunyikan tombol delete
                $('#modalDeleteButton').hide();
                // Pastikan tombol tutup ada dan tampil
                $('#modalMessage .btn-secondary').show().text('Tutup');

                var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
                modal._config.backdrop = true; // Boleh ditutup
                modal._config.keyboard = true; // Boleh ditutup
                modal.show();
                return; // Hentikan proses penghapusan
            }

            if (!rowData || !rowData.tna_id) {
                // Fallback jika data tidak lengkap
                const pelatihanId = $(this).data('id');
                $('#modalMessageBody').html(`
                    <p>Apakah Anda yakin ingin menghapus data TNA ID: <b>${pelatihanId}</b> ini?</p>
                    <p class="text-danger small mt-2">Detail data tidak dapat dimuat. Tindakan ini tidak dapat dibatalkan.</p>
                `);
                $('#modalMessageLabel').text('Konfirmasi Penghapusan TNA');
                $('#modalDeleteButton').show().removeClass('btn-warning').addClass('btn-danger').text('Hapus').data('id', pelatihanId).data('action', 'single');
            } else {
                // Tampilkan detail data yang diminta
                const pelatihanId = rowData.tna_id;
                const namaPegawai = rowData.pegawai_nama || 'N/A';
                const namaPelatihan = rowData.nama || 'N/A';
                const sifatTNA = rowData.sifat_tna_nama || 'N/A';
                const statusTNA = rowData.status || 'N/A';

                $('#modalMessageLabel').text('Konfirmasi Penghapusan TNA');

                // Membangun pesan konfirmasi dengan detail data TNA
                $('#modalMessageBody').html(`
                    <p>Apakah Anda yakin ingin menghapus data TNA berikut?</p>
                    <ul class="list-unstyled mt-3 text-start px-4">
                        <li><strong>Pegawai:</strong> ${namaPegawai}</li>
                        <li><strong>Pelatihan:</strong> ${namaPelatihan}</li>
                        <li><strong>Sifat:</strong> ${sifatTNA}</li>
                        <li><strong>Status:</strong> ${statusTNA}</li>
                    </ul>
                    <p class="text-danger small mt-2">Karena akan dihapus permanen.</p>
                `);

                // Set action ke 'single' dan simpan ID
                $('#modalDeleteButton').show().removeClass('btn-warning').addClass('btn-danger').text('Hapus').data('id', pelatihanId).data('action', 'single');
            }

            // NOTE: Modal ini seharusnya ada (misalnya di layouts/dasar.blade.php)
            // Saya asumsikan elemen #modalMessage, #modalMessageLabel, #modalMessageBody, dan #modalDeleteButton sudah tersedia.
            var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
            // Kita atur modal agar TIDAK bisa ditutup dengan klik backdrop atau tombol ESC
            modal._config.backdrop = 'static';
            modal._config.keyboard = false;
            modal.show();
        });


        // 2. Tampilkan modal untuk Hapus Terpilih
        $('#hapus-terpilih').off('click').on('click', function() {
            // HANYA ambil ID dari checkbox yang TERPILIH dan TIDAK disabled
            bulkDeleteIds = $('.row-checkbox:checked:not(:disabled)').map(function() {
                return $(this).val();
            }).get();

            if (bulkDeleteIds.length > 0) {
                $('#modalMessageLabel').text('Konfirmasi Hapus Massal TNA');
                $('#modalMessageBody').html('Apakah Anda yakin ingin menghapus <b>' + bulkDeleteIds.length + '</b> data TNA yang terpilih?');

                // Set action ke 'bulk'
                $('#modalDeleteButton').show().removeClass('btn-warning').addClass('btn-danger').text('Hapus').data('action', 'bulk').data('id', null);

                var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
                // Kita atur modal agar TIDAK bisa ditutup dengan klik backdrop atau tombol ESC
                modal._config.backdrop = 'static';
                modal._config.keyboard = false;
                modal.show();
            }
        });

        // 3. Eksekusi Hapus Tunggal/Massal pada klik tombol Hapus di Modal
        $('#modalDeleteButton').off('click').on('click', function() {
            var actionType = $(this).data('action');
            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalMessage'));

            // Sembunyikan tombol delete sementara untuk mencegah double click
            $(this).hide();
            // Sembunyikan tombol batal juga
            $('#modalMessage .btn-secondary').hide();
            $('#modalMessageBody').html('<div class="text-center"><i class="fas fa-sync fa-spin"></i> Sedang memproses...</div>');

            if (actionType === 'bulk') {
                // LOGIKA HAPUS MASSAL
                $.ajax({
                    url: "{{ route('pelatihan.tna.hapus_terpilih') }}",
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        'ids': bulkDeleteIds
                    },
                    success: function(response) {
                        $('#modalMessageBody').html('<span class="text-success">' + response.message + '</span>');
                        table.ajax.reload();
                        // Reset checkboxes dilakukan di listener hidden.bs.modal
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menghapus data massal.';
                        $('#modalMessageBody').html('<span class="text-danger">' + errorMsg + '</span>');
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 2000);
                    },
                    complete: function() {
                        // Munculkan kembali tombol batal (meskipun akan segera disembunyikan oleh listener hidden)
                        $('#modalDeleteButton').show();
                        $('#modalMessage .btn-secondary').show();
                    }
                });
            } else if (actionType === 'single') {
                // LOGIKA HAPUS TUNGGAL
                var pelatihanId = $(this).data('id');

                $.ajax({
                    url: "{{ route('pelatihan.tna.index') }}/" + pelatihanId, // Mengarah ke rute DESTROY
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modalMessageBody').html('<span class="text-success">Data TNA ID ' + pelatihanId + ' berhasil dihapus.</span>');
                        table.ajax.reload();
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menghapus data tunggal.';
                        $('#modalMessageBody').html('<span class="text-danger">' + errorMsg + '</span>');
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 2000);
                    },
                    complete: function() {
                        $('#modalDeleteButton').show();
                        $('#modalMessage .btn-secondary').show();
                    }
                });
            }
        });

        // Listener untuk mereset modal dan checkbox setelah ditutup
        document.getElementById('modalMessage').addEventListener('hidden.bs.modal', function() {
            // 1. Reset Modal State
            $('#modalMessageLabel').text('Pesan Konfirmasi');
            $('#modalMessageBody').text('Apakah Anda yakin?');
            $('#modalDeleteButton').removeData('action').removeData('id').show();

            // 2. Reset konfigurasi modal ke default (agar bisa ditutup kembali normal)
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalMessage'));
            if (modal) {
                modal._config.backdrop = true;
                modal._config.keyboard = true;
            }

            // 3. Hapus centang pada semua checkbox
            resetCheckboxes();
        });
    });
</script>
@endpush