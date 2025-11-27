$(document).ready(function () {
    // Inisialisasi tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Inisialisasi DataTables dengan responsivitas
    $('#pelatihan-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: "{{ route('pelatihan.index') }}", // Sesuaikan URL dengan data yang akan diambil
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'pegawai.nama' },
            { data: 'nama' },
            { data: 'tgl_mulai' },
            { data: 'tgl_selesai' },
            { data: 'durasi' },
            { data: 'kategori.nama' },
            { data: 'jenis.nama' },
            { data: 'instansi.nama' },
            { data: 'aksi', orderable: false, searchable: false }
        ],
        responsive: true,  // Aktifkan responsivitas
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        }
    });

    // Fungsi untuk memastikan bahwa semua element Select2 diinisialisasi
    $('.select2').select2();

    // Menambahkan fitur untuk sidebar toggle
    $('#sidebarToggle').on('click', function () {
        $('.sidebar').toggleClass('active');
    });
});