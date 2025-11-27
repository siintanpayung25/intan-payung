@extends('layouts.dasar')

@section('title', 'Tambah Pelatihan Bentuk')

@section('content')
<div class="container">
    <h1>Tambah Pelatihan Bentuk</h1>

    <form action="{{ route('pelatihan-bentuk.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="bentuk_id" class="form-label">Skala ID</label>
            <input type="text" class="form-control" id="bentuk_id" name="bentuk_id" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection