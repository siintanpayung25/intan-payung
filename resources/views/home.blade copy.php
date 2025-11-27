<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Selamat datang, {{ Auth::user()->pegawai->nama }}!</h1> <!-- Menampilkan username user -->

    <!-- Menampilkan Data Pegawai -->
    @if (Auth::user()->pegawai)
        <h2>Data Pegawai</h2>
        <ul>
            <li><strong>NIP:</strong> {{ Auth::user()->pegawai->nip }}</li>
            <li><strong>Nama:</strong> {{ Auth::user()->pegawai->nama }}</li>
            <li><strong>Jabatan:</strong> {{ Auth::user()->pegawai->jabatan->nama }}</li>
            <li><strong>Status Pegawai:</strong> {{ Auth::user()->pegawai->statusPegawai->nama }}</li>
            <li><strong>Golongan:</strong> {{ Auth::user()->pegawai->golongan->pangkat . ' (' . Auth::user()->pegawai->golongan->gol_ruang . ')' }}</li>
            <!-- Tambahkan kolom lain yang ingin ditampilkan -->
        </ul>
    @else
        <p>Data pegawai tidak ditemukan.</p>
    @endif

    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
