@extends('layouts.dasar')

@section('title', 'Kategori Pelatihan')

@section('content')
<table id="tabel_bentuk_pelatihan">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nama</th>
        </tr>
    </thead>
</table>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabel_bentuk_pelatihan').DataTable({
            serverSide: true,
            processing: true,
            ajax: "{{ route('pelatihan-kategori.index') }}", // Menggunakan rute yang benar
            columns: [{
                    data: 'kategori_id'
                },
                {
                    data: 'nama'
                }
            ]
        });
    });
</script>
@endpush