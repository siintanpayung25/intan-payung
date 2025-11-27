@extends('layouts.dasar')

@section('title', 'Daftar Pelatihan Bentuk')

@section('content')
<div class="container">
    <h1>Data Pelatihan Bentuk</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <!-- Tombol Tambah Pelatihan Bentuk -->
        <a href="{{ route('pelatihan-bentuk.create') }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Pelatihan Bentuk">
            <i class="fas fa-plus-circle"></i> <!-- Icon tambah -->
        </a>

        <!-- Tombol Hapus Terpilih -->
        <button class="btn btn-outline-danger" id="hapus-terpilih" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Terpilih">
            <i class="fas fa-trash-alt"></i> <!-- Icon hapus terpilih -->
        </button>
    </div>

    <form id="hapus-form" action="{{ route('pelatihan-bentuk.hapus_terpilih') }}" method="POST">
        @csrf
        <table class="table table-striped" id="pelatihan-bentuk-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih Semua"></th>
                    <th>Skala ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelatihanBentuk as $bentuk)
                <tr>
                    <td><input type="checkbox" class="select-item" name="ids[]" value="{{ $bentuk->bentuk_id }}"></td>
                    <td>{{ $bentuk->bentuk_id }}</td>
                    <td>{{ $bentuk->nama }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <a href="{{ route('pelatihan-bentuk.edit', $bentuk->bentuk_id) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="fas fa-edit"></i> <!-- Icon edit -->
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('pelatihan-bentuk.destroy', $bentuk->bentuk_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                <i class="fas fa-trash"></i> <!-- Icon hapus -->
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

@endsection

@section('scripts')
<!-- Script untuk Tooltip Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
    // Inisialisasi Tooltip
    $(document).ready(function() {
        // Tooltip Bootstrap untuk tombol
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Ketika checkbox "Pilih Semua" di header diklik
        $('#select-all').on('click', function() {
            var isChecked = this.checked; // Ambil status checkbox "Pilih Semua"
            // Set status checkbox di semua baris data
            $('input[name="ids[]"]').prop('checked', isChecked);
        });

        // Hapus terpilih
        $('#hapus-terpilih').on('click', function() {
            var ids = [];
            // Ambil semua checkbox yang dicentang
            $('input[name="ids[]"]:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                alert('Pilih data yang ingin dihapus');
                return;
            }

            // Konfirmasi hapus
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $('#hapus-form').submit(); // Submit form hapus terpilih
            }
        });

        // Ketika checkbox di baris data diubah
        $('input[name="ids[]"]').on('click', function() {
            var total = $('input[name="ids[]"]').length; // Total checkbox
            var checked = $('input[name="ids[]"]:checked').length; // Total checkbox yang tercentang

            // Jika semua checkbox tercentang, set checkbox header tercentang
            if (total === checked) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
    });
</script>
@endsection