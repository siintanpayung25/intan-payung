@extends('layouts.dasar')

@section('title', 'Tambah Pelatihan Skala')

@section('content')
<div class="container">
    <h1>Tambah Pelatihan Skala</h1>

    <form action="{{ route('pelatihan-skala.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="skala_id" class="form-label">Skala ID</label>
            <input type="text" name="skala_id" id="skala_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Pelatihan Skala</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection