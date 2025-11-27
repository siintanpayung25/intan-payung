<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- FontAwesome CSS (untuk Icon) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

    <!-- FIX: DataTables Responsive CSS ditambahkan di sini -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.css" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- File CSS untuk Layout, Navbar, dan Sidebar -->
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/halaman_utama.css') }}" rel="stylesheet"> <!-- Menyertakan halaman_utama.css -->
    <link href="{{ asset('css/form_select2.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <!-- Navbar -->
        @include('layouts.navbar')

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content" id="mainContent">
                <!-- Menampilkan Pesan -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @elseif(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
                @elseif(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
                @endif

                <!-- Konten Utama -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi / Pesan -->
    <div class="modal fade" id="modalMessage" tabindex="-1" aria-labelledby="modalMessageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMessageLabel">Pesan Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessageBody">
                    <!-- Pesan akan ditampilkan di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="modalDeleteButton">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery (required for DataTables and SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

    <!-- FIX: DataTables Responsive JS ditambahkan di sini -->
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.min.js"></script>

    <!-- FontAwesome JS -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- JavaScript untuk Sidebar Toggle -->
    <script src="{{ asset('js/sidebar.js') }}"></script>

    <!-- Menambahkan script tambahan jika ada -->
    @stack('scripts')

    <!-- Script untuk menampilkan pesan di modal -->
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Hapus dari Aksi
            $(document).on('click', '.btn-delete', function() {
                var pelatihanId = $(this).data('id'); // Ambil ID pelatihan dari tombol
                $('#modalMessageBody').text('Apakah Anda yakin ingin menghapus data ini?'); // Isi pesan modal
                $('#modalDeleteButton').data('id', pelatihanId).data('action', 'single'); // Set data-id dan action
                $('#modalDeleteButton').removeClass('btn-warning').addClass('btn-danger').text('Hapus');
                $('#modalMessage .btn-secondary').text('Tutup');

                var modal = new bootstrap.Modal(document.getElementById('modalMessage')); // Inisialisasi modal
                modal.show(); // Tampilkan modal
            });

            // Ketika tombol "Hapus" diklik di dalam modal
            $('#modalDeleteButton').on('click', function() {
                var actionType = $(this).data('action');
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalMessage'));

                if (actionType === 'single') {
                    var pelatihanId = $(this).data('id'); // Ambil ID pelatihan
                    // Nonaktifkan tombol saat loading
                    $('#modalDeleteButton').prop('disabled', true);
                    $('#modalMessageBody').html('<div class="text-center"><i class="fas fa-sync fa-spin"></i> Sedang menghapus...</div>');


                    $.ajax({
                        // Menggunakan rute generik yang Anda sediakan
                        url: "{{ route('pelatihan.destroy', ':id') }}".replace(':id', pelatihanId), 
                        type: "POST", // Diubah ke POST untuk mengirim method DELETE
                        data: {
                            '_method': 'DELETE', // Method Spoofing
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#modalMessageBody').html('<span class="text-success">Data berhasil dihapus.</span>');
                            // Reload DataTable
                            $('#pelatihan-table').DataTable().ajax.reload();
                            setTimeout(function() {
                                modalInstance.hide(); 
                            }, 1500);
                        },
                        error: function() {
                            $('#modalMessageBody').html('<span class="text-danger">Terjadi kesalahan saat menghapus data.</span>');
                            setTimeout(function() {
                                modalInstance.hide();
                            }, 2000);
                        },
                        complete: function() {
                            $('#modalDeleteButton').prop('disabled', false); // Aktifkan kembali
                        }
                    });
                }
                // Logika Hapus Massal harus dihandle di index.blade.php karena butuh 'ids' array
            });

            // Event untuk Hapus Terpilih
            $('#hapus-terpilih').on('click', function() {
                var selectedIds = $('.row-checkbox:checked').map(function() { // Ganti .select-item ke .row-checkbox
                    return $(this).val(); // Menggunakan .val() yang sudah disiapkan di row-checkbox
                }).get();

                if (selectedIds.length > 0) {
                    $('#modalMessageLabel').text('Konfirmasi Hapus Massal TNA');
                    $('#modalMessageBody').html('Apakah Anda yakin ingin menghapus <b>' + selectedIds.length + '</b> data TNA yang terpilih?');
                    
                    // Set action ke 'bulk'
                    $('#modalDeleteButton').data('action', 'bulk').data('ids', selectedIds);
                    $('#modalDeleteButton').removeClass('btn-warning').addClass('btn-danger').text('Hapus');
                    $('#modalMessage .btn-secondary').text('Batal');

                    var modal = new bootstrap.Modal(document.getElementById('modalMessage'));
                    modal.show();
                    
                    // Logic ajax bulk delete dipindahkan ke index.blade.php agar bisa diakses oleh DataTables draw event
                } else {
                    // Mengganti alert() dengan SweetAlert (atau modal lain) untuk UX yang lebih baik
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih data terlebih dahulu!',
                        confirmButtonText: 'Tutup'
                    });
                }
            });
            
            // Listener untuk mereset modal setelah ditutup
            document.getElementById('modalMessage').addEventListener('hidden.bs.modal', function() {
                 $('#modalDeleteButton').removeData('action').removeData('id').removeData('ids');
                 $('#modalMessage .btn-secondary').show();
            });

        });
    </script>
    @endpush


</body>

</html>