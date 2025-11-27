@extends('layouts.dasar')

@section('title', 'Edit Pelatihan Skala')

@section('content')
<div class="container">
    <h1>Edit Pelatihan Skala</h1>

    <form action="{{ route('pelatihan-skala.update', $pelatihanSkala->skala_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="skala_id" class="form-label">Skala ID</label>
            <input type="text" name="skala_id" id="skala_id" class="form-control" value="{{ $pelatihanSkala->skala_id }}" disabled>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Pelatihan Skala</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $pelatihanSkala->nama }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection