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

                <!-- Tombol Hapus Terpilih (awalnya disembunyikan) -->
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
                        <th>No</th> <!-- Nomor Urut -->
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

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pelatihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <!-- Isi modal akan dimasukkan di sini -->
                <p>Loading... Menunggu data...</p>
            </div>
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
            columns: [{
        data: 'checkbox', // Kolom ini berisi data untuk checkbox
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
                    data: 'instansi',
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        

        // Checkbox Select All (Header)
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
        return $(this).data('pelatihan_id'); // Mengambil 'pelatihan_id' yang dipilih
    }).get();

    if (selectedIds.length > 0) {
        // Update jumlah yang dipilih di pesan modal konfirmasi
        $('#modalMessageBody').html(`Apakah Anda yakin ingin menghapus ${selectedIds.length} data terpilih?`);

        // Tampilkan modal konfirmasi
        var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
        modal.show();

        // Tombol "Tutup" di modal konfirmasi
        $('#modalMessage .btn-secondary').on('click', function() {
            modal.hide(); // Menutup modal konfirmasi

            // Hapus tanda checklist di setiap baris dan di checkbox "Select All" di header
            $('.select-item').prop('checked', false);
            $('#selectAll').prop('checked', false); // Menghilangkan tanda checklist di checkbox Select All
            $('#hapus-terpilih').hide(); // Menyembunyikan tombol "Hapus Terpilih" lagi
        });

        // Tombol "Hapus" di modal konfirmasi (menghapus data yang dipilih)
        $('#modalDeleteButton').on('click', function() {
            $.ajax({
                url: "{{ route('pelatihan.hapus_terpilih') }}", // Endpoint untuk menghapus data terpilih
                type: "DELETE", // Menggunakan metode DELETE untuk penghapusan
                data: {
                    ids: selectedIds, // Kirim ID yang dipilih
                    _token: '{{ csrf_token() }}' // Token CSRF
                },
                success: function(response) {
                    // Menutup modal konfirmasi
                    modal.hide();

                    // Pesan sukses setelah penghapusan
                    $('#modalMessageBody').text('Data terpilih berhasil dihapus');
                    var successModal = new bootstrap.Modal(document.getElementById('modalMessage'));
                    successModal.show();

                    setTimeout(function() {
                        successModal.hide(); // Menyembunyikan modal sukses setelah 2 detik
                    }, 2000);

                    // Reload DataTable untuk memperbarui tampilan
                    $('#pelatihan-table').DataTable().ajax.reload();

                    // Reset checkbox dan sembunyikan tombol "Hapus Terpilih"
                    $('.select-item').prop('checked', false);
                    $('#selectAll').prop('checked', false); // Reset Select All checkbox
                    $('#hapus-terpilih').hide(); // Menyembunyikan tombol Hapus Terpilih
                },
                error: function() {
                    // Menampilkan pesan kesalahan jika penghapusan gagal
                    $('#modalMessageBody').text('Terjadi kesalahan saat menghapus data.');
                    var errorModal = new bootstrap.Modal(document.getElementById('modalMessage'));
                    errorModal.show();

                    setTimeout(function() {
                        errorModal.hide(); // Sembunyikan modal error setelah 2 detik
                    }, 2000);
                }
            });
        });
    } else {
        // Jika tidak ada data yang dipilih
        alert('Pilih data terlebih dahulu!');
    }
});

        // Tombol hapus di kolom aksi
        $(document).on('click', '.btn-delete', function() {
            var pelatihanId = $(this).data('id'); // Ambil ID pelatihan dari tombol
            var row = $(this).closest('tr'); // Menemukan baris tempat tombol ini diklik
            var namaPegawai = row.find('td:eq(2)').text(); // Nama Pegawai ada di kolom ke-3
            var namaPelatihan = row.find('td:eq(3)').text(); // Nama Pelatihan ada di kolom ke-4

            // Fungsi untuk format tanggal dd-mm-yyyy
            function formatDate(date) {
                var d = new Date(date);
                var day = ("0" + d.getDate()).slice(-2); // Mendapatkan hari dan memastikan dua digit
                var month = ("0" + (d.getMonth() + 1)).slice(-2); // Mendapatkan bulan dan memastikan dua digit
                var year = d.getFullYear(); // Mendapatkan tahun
                return day + '-' + month + '-' + year;
            }

            var tglMulai = formatDate(row.find('td:eq(4)').text()); // Format Tanggal Mulai
            var tglSelesai = formatDate(row.find('td:eq(5)').text()); // Format Tanggal Selesai

            // Menyusun pesan untuk modal konfirmasi
            var message = `
        <strong>Nama Pegawai:</strong> ${namaPegawai} <br>
        <strong>Nama Pelatihan:</strong> ${namaPelatihan} <br>
        <strong>Tanggal Mulai:</strong> ${tglMulai} <br>
        <strong>Tanggal Selesai:</strong> ${tglSelesai} <br>
        Apakah Anda yakin ingin menghapus data ini?
    `;

            // Isi pesan modal konfirmasi
            $('#modalMessageBody').html(message); // Gunakan .html() agar bisa menampilkan HTML (tag <br>)

            $('#modalDeleteButton').data('id', pelatihanId); // Set data-id pada tombol hapus di modal
            var modal = new bootstrap.Modal(document.getElementById('modalMessage')); // Inisialisasi modal
            modal.show(); // Tampilkan modal

            // Ketika tombol "Hapus" diklik di dalam modal
            $('#modalDeleteButton').one('click', function() {
                // Lakukan penghapusan data
                $.ajax({
                    url: "{{ route('pelatihan.destroy', '') }}/" + pelatihanId, // Gunakan ID pelatihan untuk hapus
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Menampilkan pesan sukses di dalam modal
                        $('#modalMessageBody').text('Data berhasil dihapus');
                        modal.hide(); // Menutup modal konfirmasi setelah penghapusan selesai

                        // Tampilkan modal sukses
                        var successModal = new bootstrap.Modal(document.getElementById('modalMessage'));
                        successModal.show();

                        setTimeout(function() {
                            successModal.hide(); // Sembunyikan modal sukses setelah 2 detik
                        }, 2000);

                        // Reload DataTable dan reset checkbox
                        $('#pelatihan-table').DataTable().ajax.reload();
                    },
                    error: function() {
                        $('#modalMessageBody').text('Terjadi kesalahan saat menghapus data.');
                        var errorModal = new bootstrap.Modal(document.getElementById('modalMessage'));
                        errorModal.show();

                        setTimeout(function() {
                            errorModal.hide();
                        }, 2000);
                    }
                });
            });

            // Ketika tombol "Tutup" diklik, hanya tutup modal
            $('#modalMessage .btn-secondary').one('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
                modal.hide(); // Menutup modal konfirmasi tanpa melakukan penghapusan
            });
        });




        // Event listener untuk tombol Detail
$(document).on('click', '.btn-detail', function() {
    var pelatihanId = $(this).data('id');  // Ambil ID pelatihan dari data-id tombol

    console.log('Pelatihan ID:', pelatihanId);  // Debugging: Pastikan ID yang diambil sesuai

    // Menampilkan pesan sementara di modal
    $('#modalDetailBody').html('<p>Menunggu data untuk pelatihan dengan ID: ' + pelatihanId + '...</p>');

    var modal = new bootstrap.Modal(document.getElementById('modalDetail'), {
        backdrop: 'static',
        keyboard: false
    });

    modal.show();

    // Pastikan URL yang dipanggil sesuai dengan rute kamu
    $.ajax({
        url: '/pelatihan/' + pelatihanId,  // Pastikan rutenya sesuai dengan {pelatihan_id}
        method: 'GET',
        success: function(response) {
            if (response) {
                var detailHTML = `
                    <table class="table">
                        <tr><th>Nama</th><td>${response.nama}</td></tr>
                        <tr><th>Instansi</th><td>${response.instansi ? response.instansi.nama : '-'}</td></tr>
                        <!-- Isi dengan field lainnya -->
                    </table>
                `;
                $('#modalDetailBody').html(detailHTML);
            } else {
                $('#modalDetailBody').html('<p>Data detail pelatihan tidak ditemukan.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error);
            $('#modalDetailBody').html('<p>Gagal memuat detail pelatihan. Silakan coba lagi.</p>');
        }
    });
});



        // Event untuk tombol "Tutup" di modal
        $('#modalMessage .btn-secondary').on('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
            modal.hide(); // Menutup modal konfirmasi
        });
    });
</script>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pelatihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <!-- Konten Detail Akan Dimasukkan di Sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endpush