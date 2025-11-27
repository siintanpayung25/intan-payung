@extends('layouts.dasar')

@section('title', 'MySDM-Pelatihan')

@section('content')
<h2>Daftar Pelatihan</h2>
<div class="card shadow mb-1">
    <div class="card-header py-3">
        {{-- TOMBOL AKSI ATAS --}}
        <div class="d-flex justify-content-between mb-1">
            <div>
                <!-- Tombol Tambah -->
                <a href="{{ route('pelatihan.create') }}" class="btn btn-sm btn-outline-primary mx-1"
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
                    data-bs-target="#importPelatihanModal" data-bs-placement="top" title="Impor Data TNA">
                    <i class="fas fa-arrow-alt-circle-down"></i> <!-- Ikon impor -->
                </a>

                <!-- Link untuk download Template CSV -->
                <a href="{{ route('pelatihan.templatePelatihanCSV') }}" class="btn btn-sm btn-outline-secondary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Download Template CSV">
                    <i class="fas fa-file-csv"></i>
                </a>

                <!-- Link untuk download Template Excel -->
                <a href="{{ route('pelatihan.templatePelatihanExcel') }}" class="btn btn-sm btn-outline-secondary mx-1"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Download Template Excel">
                    <i class="fas fa-file-excel"></i>
                </a>

                <!-- Tombol Hapus Terpilih -->
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
            <table class="table table-bordered" id="pelatihan-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th> <!-- Checkbox untuk select all -->
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Nama Pelatihan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Durasi</th>
                        <th>Kategori Pelatihan</th>
                        <th>Jenis Pelatihan</th>
                        <th>TNA</th>
                        <th>Instansi</th>
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
<div class="modal fade" id="importPelatihanModal" tabindex="-1" aria-labelledby="importPelatihanModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPelatihanModalLabel">Impor Data Pelatihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatihan.impor') }}" method="POST" enctype="multipart/form-data">
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
    $(document).ready(function() {
        var table = $('#pelatihan-table').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: "{{ route('pelatihan.index') }}",
            columns: [
                {
                    data: 'checkbox', 
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="select-item" data-pelatihan_id="${row.pelatihan_id}">`;
                    }
                },
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'pegawai.nama'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'tgl_mulai'
                },
                {
                    data: 'tgl_selesai'
                },
                {
                    data: 'durasi'
                },
                {
                    data: 'kategori.nama'
                },
                {
                    data: 'jenis.nama'
                },
                {
                    data: 'tna.nama'
                },
                {
                    data: 'instansi.nama',
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Checkbox Select All
        $('#selectAll').on('click', function() {
            var isChecked = this.checked;
            $('#pelatihan-table .select-item').each(function() {
                $(this).prop('checked', isChecked);
            });
            toggleDeleteButton();
        });

        // Checkbox Individual (Row)
        $('#pelatihan-table').on('change', '.select-item', function() {
            toggleDeleteButton();
        });

        // Toggle Delete Button
        function toggleDeleteButton() {
            var selectedCount = $('.select-item:checked').length;
            if (selectedCount > 0) {
                $('#hapus-terpilih').show();
            } else {
                $('#hapus-terpilih').hide();
            }
        }

        // Hapus Terpilih (Delete Selected)
        $('#hapus-terpilih').on('click', function() {
            var selectedIds = $('.select-item:checked').map(function() {
                return $(this).data('pelatihan_id');
            }).get();

            if (selectedIds.length > 0) {
                // Send the selected IDs to the server to delete them
                $.ajax({
                    url: "{{ route('pelatihan.hapus_terpilih') }}", 
                    type: "DELETE",
                    data: {
                        ids: selectedIds, 
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#pelatihan-table').DataTable().ajax.reload();
                        $('.select-item').prop('checked', false);
                        $('#hapus-terpilih').hide();
                        alert('Data berhasil dihapus.');
                    },
                    error: function(error) {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });

        // Hapus (Delete) per Baris
        $('#pelatihan-table').on('click', '.hapus-btn', function() {
            var pelatihanId = $(this).data('pelatihan_id');
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '/pelatihan/' + pelatihanId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#pelatihan-table').DataTable().ajax.reload();
                        alert('Data berhasil dihapus.');
                    },
                    error: function(error) {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });
    });
</script>
@endpush