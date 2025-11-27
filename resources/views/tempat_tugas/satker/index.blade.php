@extends('layouts.dasar')

@section('title', 'Daftar Satker')

@section('content')
<h2>Daftar Satker</h2>
<div class="card shadow mb-1">
    <div class="card-header py-3">
        {{-- Tombol Aksi Atas --}}
        <div class="d-flex justify-content-between mb-1">
            <div>
                {{-- Tombol Tambah Satker --}}
                <a href="{{ route('tempat_tugas.satker.create') }}" class="btn btn-sm btn-outline-primary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data Satker Baru">
                    <i class="fas fa-plus"></i>
                </a>

                {{-- Ekspor ke Excel --}}
                <a href="{{ route('tempat_tugas.satker.ekspor') }}" class="btn btn-sm btn-outline-success mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Ekspor ke Excel">
                    <i class="fas fa-file-excel"></i>
                </a>

                {{-- Backup ke Excel --}}
                <a href="{{ route('tempat_tugas.satker.backup') }}" class="btn btn-sm btn-outline-success mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Backup ke Excel">
                    <i class="fas fa-download"></i> <!-- Ikon unduh -->
                </a>

                {{-- Tombol Hapus Terpilih (Bulk Delete) --}}
                <button id="hapus-terpilih" class="btn btn-sm btn-outline-danger mx-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Hapus Data Terpilih" style="display: none;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Tabel Satker --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="satker-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 20px;"><input type="checkbox" id="master-checkbox"></th>
                        <th>Nama Satker</th>
                        {{-- <th>Kode Satker</th> --}}
                        {{-- <th>Keterangan</th> --}}
                        {{-- <th>Instansi</th> <!-- Kolom Instansi ditambahkan --> --}}
                        {{-- <th>Negara</th> --}}
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalMessage" tabindex="-1" aria-labelledby="modalMessageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMessageLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="modalDeleteButton">Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let table;  // deklarasikan variabel table sekali saja
    let bulkDeleteIds = [];

    function toggleBulkDeleteButton() {
        var checkedCount = $('.row-checkbox:checked:not(:disabled)').length;
        if (checkedCount > 0) {
            $('#hapus-terpilih').show();
        } else {
            $('#hapus-terpilih').hide();
            if ($('#master-checkbox').prop('checked')) {
                $('#master-checkbox').prop('checked', false);
            }
        }
    }

    function resetCheckboxes() {
        $('#master-checkbox').prop('checked', false);
        $('#satker-table tbody .row-checkbox:not(:disabled)').prop('checked', false);
        toggleBulkDeleteButton();
    }

    $(document).ready(function() {
        // Inisialisasi DataTable hanya di sini, tanpa deklarasi ulang table
        table = $('#satker-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('tempat_tugas.satker.datatable') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    data: 'satker_id', // Menggunakan 'satker_id' sebagai data checkbox
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="row-checkbox select-item" name="ids[]" value="${data}">`;
                    }
                },
                { data: 'nama', name: 'nama' },  // Kolom nama Satker
                { data: 'provinsi', name: 'provinsi' },  // Nama provinsi (relasi)
                { data: 'kabupaten', name: 'kabupaten' },  // Nama kabupaten (relasi)
                { data: 'action', name: 'action', orderable: false, searchable: false }  // Aksi untuk setiap baris
            ]
        });

        // Checkbox Master
        $('#master-checkbox').on('click', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteButton();
        });

        // Mengatur checkbox master berdasarkan baris yang dipilih
        $('#satker-table tbody').on('change', '.row-checkbox', function() {
            var allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
            $('#master-checkbox').prop('checked', allChecked);
            toggleBulkDeleteButton();
        });

        // Bulk delete action
        $('#hapus-terpilih').off('click').on('click', function() {
            bulkDeleteIds = $('.row-checkbox:checked:not(:disabled)').map(function() {
                return $(this).val();
            }).get();

            if (bulkDeleteIds.length > 0) {
                $('#modalMessageLabel').text('Konfirmasi Hapus Massal Satker');
                $('#modalMessageBody').html('Apakah Anda yakin ingin menghapus <b>' + bulkDeleteIds.length + '</b> data Satker yang terpilih?');

                $('#modalDeleteButton').show().removeClass('btn-warning').addClass('btn-danger').text('Hapus').data('action', 'bulk');

                var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
                modal.show();
            }
        });

        // Confirm delete for bulk actions
        $('#modalDeleteButton').off('click').on('click', function() {
            var actionType = $(this).data('action');
            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalMessage'));

            $(this).hide();
            $('#modalMessage .btn-secondary').hide();
            $('#modalMessageBody').html('<div class="text-center"><i class="fas fa-sync fa-spin"></i> Sedang memproses...</div>');

            if (actionType === 'bulk') {
                $.ajax({
                    url: "{{ route('tempat_tugas.satker.hapus_terpilih') }}",  // Pastikan rute ini benar
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        'ids': bulkDeleteIds
                    },
                    success: function(response) {
                        $('#modalMessageBody').html('<span class="text-success">' + response.message + '</span>');
                        table.ajax.reload(); // Reload datatable
                        setTimeout(function() {
                            modalInstance.hide(); // Tutup modal setelah 1.5 detik
                        }, 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        $('#modalMessageBody').html('<span class="text-danger">' + errorMsg + '</span>');
                        setTimeout(function() {
                            modalInstance.hide();
                        }, 2000); // Menunggu 2 detik jika error
                    },
                    complete: function() {
                        $('#modalDeleteButton').show();
                        $('#modalMessage .btn-secondary').show();
                    }
                });
            }
        });

// Handle tombol hapus untuk setiap baris
$('#satker-table').on('click', '.btn-delete', function() {
    var satkerId = $(this).data('id');  // Ambil ID dari data yang akan dihapus

    // Mengatur event untuk tombol konfirmasi hapus
    $('#modalDeleteButton').off('click').on('click', function() {
        $.ajax({
            url: "{{ url('tempat_tugas-satker') }}/" + satkerId,  // Endpoint untuk hapus satker
            type: 'DELETE',
            data: {
                '_token': '{{ csrf_token() }}',
            },
            success: function(response) {
                alert(response.message);  // Tampilkan pesan sukses
                $('#modalMessage').modal('hide');  // Tutup modal konfirmasi
                table.ajax.reload();  // Refresh data di tabel
            },
            error: function(xhr) {
                alert('Terjadi kesalahan, coba lagi!');  // Tampilkan pesan error
                $('#modalMessage').modal('hide');  // Menutup modal jika terjadi error
            }
        });
    });

    // Menampilkan modal konfirmasi hapus
    $('#modalMessage').modal('show');
});

        // Reset checkboxes when modal is closed
        document.getElementById('modalMessage').addEventListener('hidden.bs.modal', function() {
            resetCheckboxes();
        });
    });
</script>


@endpush