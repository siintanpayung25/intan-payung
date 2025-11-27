<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>

    <!-- Menambahkan CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Menambahkan Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            position: relative;
            max-width: 100%;
        }

        .background-image {
            width: 100%;
            height: auto;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .text-right {
            position: absolute;
            top: 65%;
            /* Geser lebih ke bawah */
            right: 5%;
            /* Geser ke kanan */
            transform: translateY(-50%);
            color: white;
            text-align: right;
        }

        .custom-title {
            font-size: 3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .custom-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            color: #f8f9fa;
            margin-top: 20px;
        }

        .btn-container {
            margin-top: 30px;
        }

        .btn-success {
            padding: 15px 30px;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Gambar dengan teks overlay -->
        <img src="{{ asset('gambar/utama/Halaman utama background biru.jpg') }}" alt="Selamat Datang"
            class="background-image">

        <!-- Teks Overlay -->
        <div class="text-right">
            {{-- <h1 class="custom-title">Si Intan Payung</h1>
            <p class="custom-subtitle">Sistem informasi Kepegawaian BPS Provinsi Riau</p> --}}

            <!-- Tombol Login yang akan memunculkan modal -->
            <div class="btn-container" style="position: absolute; top: 75%; right: 10%;">
                <button type="button" class="btn btn-lg btn-success" data-bs-toggle="modal" data-bs-target="#loginModal"
                    id="openModalBtn">
                    Login
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}" autofocus>

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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <!-- Tombol Login -->
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
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