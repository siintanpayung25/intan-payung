<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>

    <!-- Menambahkan CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Menambahkan gambar -->
    <img src="{{ asset('gambar/utama/Halaman utama background biru.jpg') }}" alt="Selamat Datang"
        class="img-fluid shadow-lg rounded">
    {{-- <img src="{{ asset('gambar/utama/Halaman utama background putih.jpg') }}" alt="Selamat Datang"
        class="img-fluid shadow-lg rounded"> --}}

    <!-- Form Login yang langsung ditampilkan di kanan sedikit ke bawah -->
    <div class="position-absolute" style="top: 65%; right: 3%; transform: translateY(-50%); width: 30%;">
        <div class="card">
            <div class="card-header text-center">
                <h5 class="card-title mb-0">Login</h5>
            </div>
            <div class="card-body">
                <!-- Menampilkan Error Umum jika ada -->
                @if ($errors->has('login_error'))
                <div class="alert alert-danger">
                    {{ $errors->first('login_error') }}
                </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('loginProses') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username') }}" autofocus>

                        <!-- Menampilkan pesan error khusus untuk Username -->
                        @error('username')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password">

                        <!-- Menampilkan pesan error khusus untuk Password -->
                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer d-flex justify-content-end">
                        <!-- Tombol Batal untuk menutup modal -->
                        <button type="button" class="btn btn-secondary mt-2 me-2" data-bs-dismiss="modal">Batal</button>
                        <!-- Tombol Login -->
                        <button type="submit" class="btn btn-primary mt-2">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Menambahkan JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Pastikan modal tetap terbuka jika ada error
        @if ($errors->any() || session('login_error'))
            var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
            myModal.show();
        @endif
    </script>
</body>

</html>