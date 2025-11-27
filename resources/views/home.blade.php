@extends('layouts.dasar')

@section('title', 'MySDM-Home')

@section('content')
<h1>Selamat datang, {{ Auth::user()->pegawai->nama }}!</h1> <!-- Menampilkan nama pegawai -->

<!-- Menampilkan Data Pegawai -->
@if (Auth::user()->pegawai)
<h2>Data Pegawai</h2>
<ul>
    <li><strong>Username:</strong> {{ Auth::user()->username }}</li>
    <li><strong>NIP:</strong> {{ Auth::user()->pegawai->nip }}</li>
    <li><strong>Nama:</strong> {{ Auth::user()->pegawai->nama }}</li>
    <li><strong>Jabatan:</strong> {{ Auth::user()->pegawai->jabatan->nama }}</li>
    <li><strong>Status Pegawai:</strong> {{ Auth::user()->pegawai->statusPegawai->nama }}</li>
    <li><strong>Golongan:</strong> {{ Auth::user()->pegawai->golongan->pangkat . ' (' . Auth::user()->pegawai->golongan->gol_ruang . ')' }}</li>
    <li><strong>Level user:</strong> {{ Auth::user()->pegawai->LevelUser->nama }}</li>
    <!-- Tambahkan kolom lain yang ingin ditampilkan -->
</ul>
@else
<p>Data pegawai tidak ditemukan.</p>
@endif
@endsection

@section('scripts')
<!-- Optional custom scripts -->
<script>
    // Custom scripts if needed
</script>
@endsection