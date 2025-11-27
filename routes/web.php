<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelamatDatangController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\SidebarController;
use App\Http\Controllers\PelatihanTnaController;
use App\Http\Controllers\PelatihanSkalaController;
use App\Http\Controllers\PelatihanBentukController;
use App\Http\Controllers\PelatihanKategoriController;
use App\Http\Controllers\PelatihanJenisController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PelatihanRekapCapainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\WilayahNegaraController;
use App\Http\Controllers\WilayahProvinsiController;
use App\Http\Controllers\WilayahKabupatenController;
use App\Http\Controllers\WilayahKecamatanController;
use App\Http\Controllers\WilayahDesaController;
use App\Http\Controllers\WilayahStatusAdmKabupatenController;
use App\Http\Controllers\InstansiController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route untuk login dan logout
Route::get('/', [SelamatDatangController::class, 'index'])->name('login');
Route::post('/login', [SelamatDatangController::class, 'loginProses'])->name('loginProses');
Route::post('/logout', [SelamatDatangController::class, 'logout'])->name('logout');

//  Menggunakan grup middleware untuk route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Route Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =============================
    // TRAINING NEEDS ANALYSIS (TNA)
    // =============================
    Route::group([
        'prefix' => 'pelatihan-tna',
        'as' => 'pelatihan.tna.',
    ], function () {
        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [PelatihanTnaController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom (HARUS DITEMPATKAN SEBELUM RUTE ID)
        Route::delete('/hapus-terpilih', [PelatihanTnaController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [PelatihanTnaController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [PelatihanTnaController::class, 'impor'])->name('impor');
        Route::get('/backup-tna', [PelatihanTnaController::class, 'backupTna'])->name('backupTna');
        Route::get('/template-tna-csv', [PelatihanTnaController::class, 'templateTnaCSV'])->name('templateTnaCSV');
        Route::get('/template-tna-excel', [PelatihanTnaController::class, 'templateTnaExcel'])->name('templateTnaExcel');

        // Rute Khusus untuk Mengambil TNA Berdasarkan NIP
        // Catatan: Karena ada ID pada path, pastikan rute ini DITARUH SEBELUM rute show/edit/update
        Route::get('/ambil-tna-berdasarkan-nip/{nip}', [PelatihanTnaController::class, 'ambilTnaBerdasarkanNip'])->name('ambilTnaBerdasarkanNip');


        // 3. RUTE STANDAR CRUD (Menggantikan Route::resource)
        // *Menggunakan {tna_id} secara eksplisit agar sesuai dengan Controller*

        // INDEX dan STORE (Tidak ada parameter ID)
        Route::get('/', [PelatihanTnaController::class, 'index'])->name('index');
        Route::post('/', [PelatihanTnaController::class, 'store'])->name('store');
        Route::get('/create', [PelatihanTnaController::class, 'create'])->name('create');

        // SHOW, EDIT, UPDATE, DESTROY (Menggunakan parameter {tna_id})
        // Ini adalah rute yang menyebabkan 404 sebelumnya!
        Route::get('/{tna_id}', [PelatihanTnaController::class, 'show'])->name('show');
        Route::get('/{tna_id}/edit', [PelatihanTnaController::class, 'edit'])->name('edit'); // <-- Rute EDIT
        Route::put('/{tna_id}', [PelatihanTnaController::class, 'update'])->name('update');
        Route::delete('/{tna_id}', [PelatihanTnaController::class, 'destroy'])->name('destroy');
    });

    // =========
    // Skala pelatihan
    // =========
    Route::resource('pelatihan-skala', PelatihanSkalaController::class);  // Untuk CRUD PelatihanSkala
    Route::post('pelatihan-skala/hapus-terpilih', [PelatihanSkalaController::class, 'hapusTerpilih'])->name('pelatihan-skala.hapus_terpilih');

    // =========
    // Bentuk pelatihan
    // =========
    Route::resource('/pelatihan-bentuk', PelatihanBentukController::class);
    Route::post('/pelatihan-bentuk/hapus-terpilih', [PelatihanBentukController::class, 'hapusTerpilih'])->name('pelatihan-bentuk.hapus_terpilih');
    Route::get('/get-kategoris-by-bentuk/{bentuk_id}', [PelatihanBentukController::class, 'getKategorisByBentuk']);
    Route::get('/get-jenis-by-kategori/{kategori_id}', [PelatihanController::class, 'getJenisByKategori']);


    // =========
    // Kategori pelatihan
    // =========
    // Route::apiResource(name: 'pelatihan-kategori', controller: PelatihanKategoriController::class)->only(['index']);
    Route::resource('/pelatihan-kategori', PelatihanKategoriController::class);
    Route::post('/pelatihan-kategori/hapus-terpilih', [PelatihanKategoriController::class, 'hapusTerpilih'])->name('pelatihan-kategori.hapus_terpilih');

    // =========
    // Jenis pelatihan
    // =========
    // Route::resource('/pelatihan-jenis', PelatihanJenisController::class);
    // Route::post('/pelatihan-jenis/hapus-terpilih', [PelatihanJenisController::class, 'hapusTerpilih'])->name('pelatihan-jenis.hapus_terpilih');
    Route::get('/get-jenis-by-kategori/{kategori_id}', [PelatihanJenisController::class, 'getJenisByKategori']);

    // =========
    // Pelatihan
    // =========
    Route::group([
        'prefix' => 'pelatihan',
        'as' => 'pelatihan.',
    ], function () {
        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)

        // 2. Aksi Kustom (HARUS DITEMPATKAN SEBELUM RUTE ID)
        Route::delete('/hapus-terpilih', [PelatihanController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [PelatihanController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [PelatihanController::class, 'impor'])->name('impor');
        Route::get('/backup-pelatihan]', [PelatihanController::class, 'backupPelatihan'])->name('backupPelatihan');
        Route::get('/template-pelatihan-csv', [PelatihanController::class, 'templatePelatihanCSV'])->name('templatePelatihanCSV');
        Route::get('/template-pelatihan-excel', [PelatihanController::class, 'templatePelatihanExcel'])->name('templatePelatihanExcel');

        // 3. RUTE STANDAR CRUD (Menggantikan Route::resource)
        // *Menggunakan {tna_id} secara eksplisit agar sesuai dengan Controller*

        // INDEX dan STORE (Tidak ada parameter ID)
        Route::get('/', [PelatihanController::class, 'index'])->name('index');
        Route::post('/', [PelatihanController::class, 'store'])->name('store');
        Route::get('/create', [PelatihanController::class, 'create'])->name('create');

        // SHOW, EDIT, UPDATE, DESTROY (Menggunakan parameter {tna_id})
        // Ini adalah rute yang menyebabkan 404 sebelumnya!
        Route::get('/{pelatihan_id}', [PelatihanController::class, 'show'])->name('show');
        Route::get('/{pelatihan_id}/edit', [PelatihanController::class, 'edit'])->name('edit'); // <-- Rute EDIT
        Route::put('/{pelatihan_id}', [PelatihanController::class, 'update'])->name('update');
        Route::delete('/{pelatihan_id}', [PelatihanController::class, 'destroy'])->name('destroy');


        // Route::resource('/pelatihan', PelatihanController::class); // CRUD: index, create, store, show, edit, update, destroy
        // Route::post('/pelatihan/store', [PelatihanController::class, 'storeOrUpdate'])->name('pelatihan.store');
        // Route::put('/pelatihan/{id}', [PelatihanController::class, 'storeOrUpdate'])->name('pelatihan.update');
        // Route::post('pelatihan/hapus-terpilih', [PelatihanController::class, 'hapusTerpilih'])->name('pelatihan.hapus_terpilih');
        // Route::get('pelatihan/ekspor', [PelatihanController::class, 'ekspor'])->name('pelatihan.ekspor');
        // Route::post('pelatihan/impor', [PelatihanController::class, 'impor'])->name('pelatihan.impor');
    });

    // =========
    // Rekap Capaian Pelatihan
    // =========
    Route::resource('/pelatihan-rekap-capaian', PelatihanRekapCapainController::class);
    Route::get('/pelatihan/rekap-durasi/data', [PelatihanRekapCapainController::class, 'getDataForDatatable'])->name('pelatihan.rekap_durasi.data');
    Route::post('/pelatihan/rekap-durasi/sinkronisasi', [PelatihanRekapCapainController::class, 'pemicuRekap'])->name('pelatihan.rekap_durasi.sinkronisasi');
    Route::get('/pelatihan/rekap-durasi/export/{format}', [PelatihanRekapCapainController::class, 'exportData'])->name('pelatihan.rekap_durasi.export');

    // ======
    // SATKER
    // ======
    Route::group([
        'prefix' => 'tempat_tugas-satker', // Menggunakan prefix yang lebih jelas, bisa diubah sesuai kebutuhan
        'as' => 'tempat_tugas.satker.', // Menambahkan alias untuk grup ini
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [SatkerController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom (HARUS DITEMPATKAN SEBELUM RUTE ID)
        Route::delete('/hapus-terpilih', [SatkerController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [SatkerController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [SatkerController::class, 'impor'])->name('impor');
        Route::get('/backup', [SatkerController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [SatkerController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [SatkerController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Satker berdasarkan ID (Jika perlu)
        Route::get('/ambil-satker/{satker_id}', [SatkerController::class, 'ambilSatker'])->name('ambilSatker'); // Contoh route dengan ID

        // 3. Rute CRUD Standar (Menggantikan Route::resource)
        Route::get('/', [SatkerController::class, 'index'])->name('index'); // Daftar Satker
        Route::post('/', [SatkerController::class, 'store'])->name('store'); // Simpan Satker
        Route::get('/create', [SatkerController::class, 'create'])->name('create'); // Form Tambah Satker

        // Show, Edit, Update, Destroy (Menggunakan {satker_id})
        Route::get('/{satker_id}', [SatkerController::class, 'show'])->name('show'); // Menampilkan Detail Satker
        Route::get('/{satker_id}/edit', [SatkerController::class, 'edit'])->name('edit'); // Form Edit Satker
        Route::put('/{satker_id}', [SatkerController::class, 'update'])->name('update'); // Update Satker
        Route::delete('/{satker_id}', [SatkerController::class, 'destroy'])->name('destroy'); // Hapus Satker
    });

    // =============================
    // INSTANSI
    // =============================
    Route::group([
        'prefix' => 'instansi',
        'as' => 'instansi.',
    ], function () {
        // 1. Endpoint untuk mengambil data instansi untuk dropdown (AJAX)
        Route::get('/ambil-instansi', [InstansiController::class, 'ambilInstansi'])->name('ambilInstansi');

        // 2. Endpoint untuk dropdown instansi (untuk AJAX)
        Route::get('/dropdown', [InstansiController::class, 'getInstansiDropdown'])->name('dropdown');

        // 3. Rute Khusus untuk Mengambil Instansi Berdasarkan ID
        Route::get('/{instansi_id}', [InstansiController::class, 'show'])->name('show');

        // 4. RUTE STANDAR CRUD (Menggantikan Route::resource)
        Route::get('/', [InstansiController::class, 'index'])->name('index');
        Route::post('/', [InstansiController::class, 'store'])->name('store');
        Route::get('/create', [InstansiController::class, 'create'])->name('create');
        Route::get('/{instansi_id}/edit', [InstansiController::class, 'edit'])->name('edit');
        Route::put('/{instansi_id}', [InstansiController::class, 'update'])->name('update');
        Route::delete('/{instansi_id}', [InstansiController::class, 'destroy'])->name('destroy');
    });

    // ======
    // Negara
    // ======
    Route::group([
        'prefix' => 'wilayah-negara', // Prefix untuk Negara
        'as' => 'wilayah.negara.', // Alias untuk grup Negara
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [WilayahNegaraController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom
        Route::delete('/hapus-terpilih', [WilayahNegaraController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [WilayahNegaraController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [WilayahNegaraController::class, 'impor'])->name('impor');
        Route::get('/backup', [WilayahNegaraController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [WilayahNegaraController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [WilayahNegaraController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Negara berdasarkan ID (Jika perlu)
        Route::get('/ambil-negara/{negara_id}', [WilayahNegaraController::class, 'ambilNegara'])->name('ambilNegara');

        // 3. Rute CRUD Standar
        Route::get('/', [WilayahNegaraController::class, 'index'])->name('index'); // Daftar Negara
        Route::post('/', [WilayahNegaraController::class, 'store'])->name('store'); // Simpan Negara
        Route::get('/create', [WilayahNegaraController::class, 'create'])->name('create'); // Form Tambah Negara

        // Show, Edit, Update, Destroy
        Route::get('/{negara_id}', [WilayahNegaraController::class, 'show'])->name('show'); // Menampilkan Detail Negara
        Route::get('/{negara_id}/edit', [WilayahNegaraController::class, 'edit'])->name('edit'); // Form Edit Negara
        Route::put('/{negara_id}', [WilayahNegaraController::class, 'update'])->name('update'); // Update Negara
        Route::delete('/{negara_id}', [WilayahNegaraController::class, 'destroy'])->name('destroy'); // Hapus Negara

        // Rute ambil negara
        Route::get('/ambil-negara', [WilayahNegaraController::class, 'ambilNegara'])->name('ambilNegara');
    });

    // ======
    // Provinsi
    // ======
    Route::group([
        'prefix' => 'wilayah-provinsi', // Prefix untuk Provinsi
        'as' => 'wilayah.provinsi.', // Alias untuk grup Provinsi
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [WilayahProvinsiController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom
        Route::delete('/hapus-terpilih', [WilayahProvinsiController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [WilayahProvinsiController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [WilayahProvinsiController::class, 'impor'])->name('impor');
        Route::get('/backup', [WilayahProvinsiController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [WilayahProvinsiController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [WilayahProvinsiController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Provinsi berdasarkan ID
        Route::get('/ambil-provinsi/{provinsi_id}', [WilayahProvinsiController::class, 'ambilProvinsi'])->name('ambilProvinsi');

        // 3. Rute CRUD Standar
        Route::get('/', [WilayahProvinsiController::class, 'index'])->name('index'); // Daftar Provinsi
        Route::post('/', [WilayahProvinsiController::class, 'store'])->name('store'); // Simpan Provinsi
        Route::get('/create', [WilayahProvinsiController::class, 'create'])->name('create'); // Form Tambah Provinsi

        // Show, Edit, Update, Destroy
        Route::get('/{provinsi_id}', [WilayahProvinsiController::class, 'show'])->name('show'); // Menampilkan Detail Provinsi
        Route::get('/{provinsi_id}/edit', [WilayahProvinsiController::class, 'edit'])->name('edit'); // Form Edit Provinsi
        Route::put('/{provinsi_id}', [WilayahProvinsiController::class, 'update'])->name('update'); // Update Provinsi
        Route::delete('/{provinsi_id}', [WilayahProvinsiController::class, 'destroy'])->name('destroy'); // Hapus Provinsi

        // Rute ambil provinsi berdasarkan negara yang dipilih
        Route::get('/ambil-provinsi/{negara_id}', [WilayahProvinsiController::class, 'ambilProvinsi'])->name('ambilProvinsi');
    });

    // ======
    // Kabupaten
    // ======
    Route::group([
        'prefix' => 'wilayah-kabupaten', // Prefix untuk Kabupaten
        'as' => 'wilayah.kabupaten.', // Alias untuk grup Kabupaten
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [WilayahKabupatenController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom
        Route::delete('/hapus-terpilih', [WilayahKabupatenController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [WilayahKabupatenController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [WilayahKabupatenController::class, 'impor'])->name('impor');
        Route::get('/backup', [WilayahKabupatenController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [WilayahKabupatenController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [WilayahKabupatenController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Kabupaten berdasarkan ID
        Route::get('/ambil-kabupaten/{kabupaten_id}', [WilayahKabupatenController::class, 'ambilKabupaten'])->name('ambilKabupaten');

        // 3. Rute CRUD Standar
        Route::get('/', [WilayahKabupatenController::class, 'index'])->name('index'); // Daftar Kabupaten
        Route::post('/', [WilayahKabupatenController::class, 'store'])->name('store'); // Simpan Kabupaten
        Route::get('/create', [WilayahKabupatenController::class, 'create'])->name('create'); // Form Tambah Kabupaten

        // Show, Edit, Update, Destroy
        Route::get('/{kabupaten_id}', [WilayahKabupatenController::class, 'show'])->name('show'); // Menampilkan Detail Kabupaten
        Route::get('/{kabupaten_id}/edit', [WilayahKabupatenController::class, 'edit'])->name('edit'); // Form Edit Kabupaten
        Route::put('/{kabupaten_id}', [WilayahKabupatenController::class, 'update'])->name('update'); // Update Kabupaten
        Route::delete('/{kabupaten_id}', [WilayahKabupatenController::class, 'destroy'])->name('destroy'); // Hapus Kabupaten

        // Mengambil status administrasi kabupaten
        Route::get('/ambil-status-administrasi/{status_adminkab_id}', [WilayahKabupatenController::class, 'ambilStatusAdministrasiKabupaten'])
            ->name('ambilStatusAdministrasi'); // Berikan nama rute yang jelas

        // Rute untuk ambil kabupaten berdasarkan negara dan provinsi
        Route::get('/ambil-kabupaten/{negara_id}/{provinsi_id}', [WilayahKabupatenController::class, 'ambilKabupaten'])->name('ambilKabupaten');

        // Rute untuk ambil kabupaten berdasarkan negara dan provinsi dengan status administrasi nya (Kabupaten atau Kota_)
        Route::get('/ambil-kabupaten-dan-administrasi/{negara_id}/{provinsi_id}', [WilayahKabupatenController::class, 'ambilKabupatenDanAdministrasi']);
    });


    // ======
    // Kecamatan
    // ======
    Route::group([
        'prefix' => 'wilayah-kecamatan', // Prefix untuk Kecamatan
        'as' => 'wilayah.kecamatan.', // Alias untuk grup Kecamatan
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [WilayahKecamatanController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom
        Route::delete('/hapus-terpilih', [WilayahKecamatanController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [WilayahKecamatanController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [WilayahKecamatanController::class, 'impor'])->name('impor');
        Route::get('/backup', [WilayahKecamatanController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [WilayahKecamatanController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [WilayahKecamatanController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Kecamatan berdasarkan ID
        Route::get('/ambil-kecamatan/{kecamatan_id}', [WilayahKecamatanController::class, 'ambilKecamatan'])->name('ambilKecamatan');

        // 3. Rute CRUD Standar
        Route::get('/', [WilayahKecamatanController::class, 'index'])->name('index'); // Daftar Kecamatan
        Route::post('/', [WilayahKecamatanController::class, 'store'])->name('store'); // Simpan Kecamatan
        Route::get('/create', [WilayahKecamatanController::class, 'create'])->name('create'); // Form Tambah Kecamatan

        // Show, Edit, Update, Destroy
        Route::get('/{kecamatan_id}', [WilayahKecamatanController::class, 'show'])->name('show'); // Menampilkan Detail Kecamatan
        Route::get('/{kecamatan_id}/edit', [WilayahKecamatanController::class, 'edit'])->name('edit'); // Form Edit Kecamatan
        Route::put('/{kecamatan_id}', [WilayahKecamatanController::class, 'update'])->name('update'); // Update Kecamatan
        Route::delete('/{kecamatan_id}', [WilayahKecamatanController::class, 'destroy'])->name('destroy'); // Hapus Kecamatan
    });

    // ======
    // Desa
    // ======
    Route::group([
        'prefix' => 'wilayah-desa', // Prefix untuk Desa
        'as' => 'wilayah.desa.', // Alias untuk grup Desa
    ], function () {

        // 1. Endpoint DataTables Serverside (Penting untuk AJAX)
        Route::get('/datatable', [WilayahDesaController::class, 'datatable'])->name('datatable');

        // 2. Aksi Kustom
        Route::delete('/hapus-terpilih', [WilayahDesaController::class, 'hapusTerpilih'])->name('hapus_terpilih');
        Route::get('/ekspor', [WilayahDesaController::class, 'ekspor'])->name('ekspor');
        Route::post('/impor', [WilayahDesaController::class, 'impor'])->name('impor');
        Route::get('/backup', [WilayahDesaController::class, 'backup'])->name('backup'); // Backup data
        Route::get('/template-csv', [WilayahDesaController::class, 'templateCSV'])->name('templateCSV'); // Template CSV
        Route::get('/template-excel', [WilayahDesaController::class, 'templateExcel'])->name('templateExcel'); // Template Excel

        // Rute khusus untuk mengambil Desa berdasarkan ID
        Route::get('/ambil-desa/{desa_id}', [WilayahDesaController::class, 'ambilDesa'])->name('ambilDesa');

        // 3. Rute CRUD Standar
        Route::get('/', [WilayahDesaController::class, 'index'])->name('index'); // Daftar Desa
        Route::post('/', [WilayahDesaController::class, 'store'])->name('store'); // Simpan Desa
        Route::get('/create', [WilayahDesaController::class, 'create'])->name('create'); // Form Tambah Desa

        // Show, Edit, Update, Destroy
        Route::get('/{desa_id}', [WilayahDesaController::class, 'show'])->name('show'); // Menampilkan Detail Desa
        Route::get('/{desa_id}/edit', [WilayahDesaController::class, 'edit'])->name('edit'); // Form Edit Desa
        Route::put('/{desa_id}', [WilayahDesaController::class, 'update'])->name('update'); // Update Desa
        Route::delete('/{desa_id}', [WilayahDesaController::class, 'destroy'])->name('destroy'); // Hapus Desa
    });

    Route::group([
        'prefix' => 'wilayah-status-adm-kabupaten',
        'as' => 'wilayah.statusAdmKabupaten.',
    ], function () {
        // Rute untuk mengambil nama status administrasi berdasarkan status_adminkab_id
        Route::get('/ambil-status-kabupaten/{status_adminkab_id}', [WilayahStatusAdmKabupatenController::class, 'ambilStatusKabupaten'])->name('ambilStatusKabupaten');

        // 2. Rute Kustom lainnya (opsional, tergantung kebutuhan Anda)
        // Misalnya, Ekspor data atau Backup jika ada fitur serupa
        // Route::get('/ekspor', [WilayahStatusAdmKabupatenController::class, 'ekspor'])->name('ekspor');

        // 3. RUTE STANDAR CRUD (Menggantikan Route::resource)
        Route::get('/', [WilayahStatusAdmKabupatenController::class, 'index'])->name('index');
        Route::post('/', [WilayahStatusAdmKabupatenController::class, 'store'])->name('store');
        Route::get('/create', [WilayahStatusAdmKabupatenController::class, 'create'])->name('create');

        // Rute dengan parameter ID (untuk show, edit, update, delete)
        Route::get('/{status_adminkab_id}', [WilayahStatusAdmKabupatenController::class, 'show'])->name('show');
        Route::get('/{status_adminkab_id}/edit', [WilayahStatusAdmKabupatenController::class, 'edit'])->name('edit');
        Route::put('/{status_adminkab_id}', [WilayahStatusAdmKabupatenController::class, 'update'])->name('update');
        Route::delete('/{status_adminkab_id}', [WilayahStatusAdmKabupatenController::class, 'destroy'])->name('destroy');
    });
});
