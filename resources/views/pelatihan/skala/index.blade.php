@extends('layouts.dasar')

@section('title', 'Daftar Pelatihan Skala')

@section('content')
<div class="container">
    <h1>Data Pelatihan Skala</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('pelatihan-skala.create') }}" class="btn btn-primary">Tambah Pelatihan Skala</a>
    </div>

    <table id="pelatihan-skala-table" class="table table-striped">
        <thead>
            <tr>
                <th>Skala ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan dimuat melalui DataTables -->
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#pelatihan-skala-table').DataTable({
            processing: true, // Untuk menampilkan "Processing..."
            serverSide: true, // Untuk server-side processing
            ajax: {
                url: '{{ route("pelatihan-skala.index") }}', // Mengarah ke route yang benar
                type: 'GET', // Menggunakan GET
                dataSrc: 'data' // Data yang diterima dari server akan berada di dalam key 'data'
            },
            columns: [{
                    data: 'skala_id'
                }, // Menampilkan skala_id
                {
                    data: 'nama'
                }, // Menampilkan nama
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                } // Menampilkan aksi (edit/hapus)
            ]
        });
    });
</script>
@endsection