@extends('layouts.dasar')

@section('title', 'Edit Pelatihan Bentuk')

@section('content')
<div class="container">
    <h1>Edit Pelatihan Bentuk</h1>

    <form action="{{ route('pelatihan-bentuk.update', $pelatihanBentuk->bentuk_id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Pastikan metode POST diubah menjadi PUT untuk update -->
        <div class="mb-3">
            <label for="bentuk_id" class="form-label">Bentuk ID</label>
            <input type="text" class="form-control" id="bentuk_id" name="bentuk_id" value="{{ $pelatihanBentuk->bentuk_id }}" disabled>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $pelatihanBentuk->nama }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection