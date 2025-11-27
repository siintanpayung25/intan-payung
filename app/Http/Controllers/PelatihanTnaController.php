<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pegawai; // Model Pegawai sudah di-uncomment
use App\Models\Pelatihan_Sifat_Tna; // Model Sifat TNA sudah di-uncomment
use App\Models\Pelatihan;
use App\Models\Pelatihan_Tna;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\Pelatihan\TnaEkspor;
use App\Exports\Pelatihan\TnaBackup;
use App\Imports\Pelatihan\TnaImpor;
use App\Exports\Pelatihan\TnaTemplateEksporCSV;
use App\Exports\Pelatihan\TnaTemplateEksporExcel;

class PelatihanTnaController extends BaseController
{
    /**
     * Tampilkan halaman daftar TNA.
     */
    public function index()
    {
        return view('pelatihan.tna.index');
    }

    /**
     * Mengambil data TNA untuk DataTables Serverside, termasuk logika Filter dan Sorting.
     */
    public function datatable(Request $request)
    {
        // Ambil level user dari session yang sudah dibagikan di BaseController
        $levelUser = session('NamalevelUser');
        $nipUser = session('nip');

        // Query Dasar: Ambil data dan lakukan JOIN yang diperlukan untuk sorting dan filtering relasi
        $data = Pelatihan_Tna::with(['pegawai', 'sifatTna'])
            ->leftJoin('pegawais', 'pelatihan_tna.nip', '=', 'pegawais.nip')
            ->leftJoin('pelatihan_sifat_tna', 'pelatihan_tna.sifat_tna_id', '=', 'pelatihan_sifat_tna.sifat_tna_id')
            ->select('pelatihan_tna.tna_id', 'pelatihan_tna.*');

        // Jika yang login adalah Pegawai, filter data berdasarkan nip
        if ($levelUser === 'Pegawai') {
            $data->where('pelatihan_tna.nip', $nipUser);
        }

        return DataTables::of($data)
            ->addIndexColumn()

            // =========================================================
            //  FUNGSI PENCARIAN (SERVER-SIDE FILTERING)
            // =========================================================
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !empty($request->input('search')['value'])) {
                    $searchValue = $request->input('search')['value'];

                    $query->where(function ($q) use ($searchValue) {
                        // Pencarian di kolom TNA utama (Nama Pelatihan, Tahun)
                        $q->where('pelatihan_tna.nama', 'like', "%{$searchValue}%")
                            ->orWhere('pelatihan_tna.tahun', 'like', "%{$searchValue}%");

                        // Pencarian di relasi Pegawai (Nama atau NIP)
                        $q->orWhereHas('pegawai', function ($qPegawai) use ($searchValue) {
                            $qPegawai->where('nama', 'like', "%{$searchValue}%")
                                ->orWhere('nip', 'like', "%{$searchValue}%");
                        });

                        // Pencarian di relasi Sifat TNA
                        $q->orWhereHas('sifatTna', function ($qSifat) use ($searchValue) {
                            $qSifat->where('nama', 'like', "%{$searchValue}%");
                        });
                    });
                }
            })

            // =========================================================
            //  DEFINISI KOLOM KUSTOM
            // =========================================================
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="id[]" class="row-checkbox" value="' . $row->tna_id . '">';
            })
            ->addColumn('pegawai_nama', function ($row) {
                // Pengecekan null eksplisit untuk menghindari Fatal Error jika relasi hilang (FIX)
                return $row->pegawai ? ($row->pegawai->nama . ' / ' . $row->pegawai->nip) : 'Data Pegawai Hilang';
            })
            ->addColumn('sifat_tna_nama', function ($row) {
                // Pengecekan null eksplisit
                return $row->sifatTna ? $row->sifatTna->nama : 'N/A';
            })
            ->addColumn('status', function ($row) {
                // Konversi status_tna (0/1) ke badge berwarna
                $statusValue = $row->status_tna;
                $label = 'Draft';
                $class = 'secondary';

                if ($statusValue == 1) {
                    $label = 'Sudah Dilaksanakan';
                    $class = 'success'; // Hijau
                } elseif ($statusValue == 0) {
                    $label = 'Belum Dilaksanakan';
                    $class = 'warning'; // Kuning/Orange
                }

                return '<span class="badge bg-' . $class . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($row) {
                // Tombol Aksi (Detail, Edit, Delete)
                $baseUrl = route('pelatihan.tna.index'); // Menggunakan route helper untuk dasar URL
                $detailUrl = $baseUrl . '/' . $row->tna_id;
                $editUrl = $baseUrl . '/' . $row->tna_id . '/edit';

                $detailBtn = '<a href="' . $detailUrl . '" class="btn btn-sm btn-outline-info mx-1" data-bs-toggle="tooltip" title="Detail"><i class="fas fa-eye"></i></a>';
                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning mx-1" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>';
                $deleteBtn = '<button class="btn btn-sm btn-outline-danger btn-delete mx-1" data-id="' . $row->tna_id . '" data-bs-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt"></i></button>';

                return '<div class="d-flex justify-content-start align-items-center">' . $detailBtn . $editBtn . $deleteBtn . '</div>';
            })

            // =========================================================
            //  LOGIKA SORTING TAMBAHAN (Hanya untuk kolom Kustom/Relasi)
            // =========================================================
            // Kolom 'nama' dan 'tahun' otomatis disortir.

            // 1. Status (Kolom kustom, diurutkan berdasarkan nilai angka status_tna)
            ->orderColumn('status', 'pelatihan_tna.status_tna $1')
            // 2. Nama Pegawai (Kolom relasi, diurutkan berdasarkan field 'nama' di tabel 'pegawais')
            ->orderColumn('pegawai_nama', 'pegawais.nama $1')
            // 3. Skala TNA (Kolom relasi, diurutkan berdasarkan field 'nama' di tabel 'pelatihan_sifat_tna')
            ->orderColumn('sifat_tna_nama', 'pelatihan_sifat_tna.nama $1')

            ->rawColumns(['checkbox', 'action', 'status'])
            ->make(true);
    }

    /**
     * Tampilkan formulir untuk membuat TNA baru (CREATE).
     */
    public function create()
    {
        // Mendapatkan data relasi yang diperlukan untuk dropdown pada form.
        $pegawais = Pegawai::orderBy('nama', 'asc')->get(['nip', 'nama']);
        $sifatTnas = Pelatihan_Sifat_Tna::all();

        return view('pelatihan.tna.create', compact('pegawais', 'sifatTnas'));
    }

    /**
     * Simpan TNA baru ke database (STORE).
     */
    public function store(Request $request) // [PERUBAHAN] Menggunakan Request $request dan validasi inline
    {
        // 1. Validasi data (Inline, ditambahkan rule 'exists' untuk keamanan)
        $validatedData = $request->validate([
            'nip' => 'required|string|max:20|exists:pegawais,nip',
            'nama' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            // Sifat TNA (ditambahkan max:2 untuk konsistensi dan exists)
            'sifat_tna_id' => 'required|string|max:2|exists:pelatihan_sifat_tna,sifat_tna_id',
            'deskripsi' => 'nullable|string',
            'status_tna' => 'required|in:0,1',
        ]);

        $nip = $validatedData['nip'];

        // 2. [KRITIS] Hitung kode_tna (Nomor Urut 2 Digit per NIP)
        // Hitung jumlah TNA yang sudah ada untuk NIP ini
        $existingTnaCount = Pelatihan_Tna::where('nip', $nip)->count();
        $newCodeNumber = $existingTnaCount + 1; // Nomor urut baru

        // Format kode_tna menjadi 2 digit (misal: 3 menjadi "03")
        $kodeTna = str_pad($newCodeNumber, 2, '0', STR_PAD_LEFT);

        // 3. Generate Primary Key tna_id: {nip}{sifat_tna_id}{kode_tna}-{tahun}
        $tnaId = $nip .
            $validatedData['sifat_tna_id'] .
            $kodeTna .
            '-' .
            $validatedData['tahun'];

        // 4. (PENTING) Cek Uniqueness untuk TNA ID gabungan
        if (Pelatihan_Tna::where('tna_id', $tnaId)->exists()) {
            // Seharusnya jarang terjadi karena kodeTna dihitung secara urut, 
            // tetapi ini penting untuk mencegah tabrakan data.
            return back()
                ->withInput()
                ->withErrors([
                    'tahun' => 'Gagal membuat ID TNA. ID yang dihasilkan (' . $tnaId . ') sudah terdaftar. Mohon cek urutan penomoran.'
                ])
                ->with('error', 'Gagal menyimpan. ID TNA yang dihasilkan sudah terdaftar.');
        }

        // 5. [PERBAIKAN] Tambahkan tna_id dan kode_tna ke data yang akan disimpan
        $validatedData['tna_id'] = $tnaId;
        $validatedData['kode_tna'] = $kodeTna; // <--- INI PERBAIKANNYA
        // Jika kolom 'nip' dan 'sifat_tna_id' tidak ada di tabel, hapus baris berikut.
        // Jika ada, biarkan saja karena sudah termasuk dalam $validatedData.

        // 6. Simpan data ke database
        Pelatihan_Tna::create($validatedData);

        return redirect()->route('pelatihan.tna.index')->with('success', 'Data TNA berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail TNA (SHOW).
     */
    public function show(string $id)
    {
        $pelatihan_tna = Pelatihan_Tna::where('tna_id', $id)->with(['pegawai', 'sifatTna'])->firstOrFail();
        // TODO: Lengkapi logika untuk menampilkan detail TNA
        return view('pelatihan.tna.show', compact('pelatihan_tna'));
    }

    /**
     * Tampilkan formulir untuk mengedit TNA (EDIT).
     */
    public function edit(string $tna_id)
    {
        // 1. Cari data TNA yang ingin diedit berdasarkan Primary Key (tna_id)
        $pelatihan_tna = Pelatihan_Tna::where('tna_id', $tna_id)->first();

        if (!$pelatihan_tna) {
            return redirect()->route('pelatihan.tna.index')->with('error', 'Data TNA tidak ditemukan.');
        }

        // 2. Ambil data pendukung untuk dropdown (Pegawai dan Sifat TNA)
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        $sifatTnas = Pelatihan_Sifat_Tna::orderBy('sifat_tna_id', 'asc')->get();

        // Variabel otentikasi (NamalevelUser, nip) TELAH DIHAPUS dari sini
        // karena di Blade file (Canvas) sudah dihilangkan dan fungsinya diabaikan.

        // 3. Kirim data yang murni dibutuhkan ke view 'edit'
        return view('pelatihan.tna.edit', compact(
            'pelatihan_tna', // Data TNA yang diedit (NIP, Sifat TNA lama)
            'pegawais',     // Semua opsi Pegawai
            'sifatTnas'     // Semua opsi Sifat TNA
        ));
    }

    /**
     * Perbarui TNA di database (UPDATE).
     */
    public function update(Request $request, string $tna_id)
    {
        // 1. Cari data TNA yang akan diupdate
        $tna = Pelatihan_Tna::findOrFail($tna_id);


        // 2. Validasi data yang boleh diubah.
        // Kita harus memvalidasi SEMUA field yang dikirimkan oleh form, 
        // termasuk yang menjadi bagian dari Primary Key (kecuali NIP) jika diizinkan berubah.
        $validatedData = $request->validate([
            // Validasi data yang TIDAK seharusnya diubah, tapi dikirim oleh form (wajib divalidasi)
            'nip' => 'required|string|max:20|exists:pegawais,nip',
            'sifat_tna_id' => 'required|string|max:2|exists:pelatihan_sifat_tna,sifat_tna_id',
            'kode_tna' => 'required|string|max:2',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status_tna' => 'in:0,1',
        ]);


        // 3. Simpan perubahan. Kita HANYA mengupdate field yang benar-benar boleh diubah.
        // Asumsi: NIP (pemilik TNA) tidak boleh diubah setelah dibuat.
        // Tahun dan Sifat TNA diizinkan untuk diubah, karena ini adalah data perencanaan.


        $dataToUpdate = [
            'nip' => $validatedData['nip'],
            'sifat_tna_id' => $validatedData['sifat_tna_id'],
            'kode_tna' => $validatedData['kode_tna'],
            'nama' => $validatedData['nama'],
            'deskripsi' => $validatedData['deskripsi'],
            'tahun' => $validatedData['tahun'],
            'status_tna' => $validatedData['status_tna'],
        ];

        // 4. Lakukan update
        $tna->update($dataToUpdate);

        return redirect()->route('pelatihan.tna.index')->with('success', 'Data TNA berhasil diperbarui.');
    }

    /**
     * Hapus TNA dari database (DESTROY).
     */
    public function destroy($id)
    {
        $tna = Pelatihan_Tna::find($id); // Gunakan find() daripada findOrFail() untuk penanganan custom

        if (!$tna) {
            return response()->json(['message' => 'Data TNA tidak ditemukan.'], 404);
        }

        try {
            $tna->delete();
            return response()->json(['message' => 'Data TNA berhasil dihapus.']);
        } catch (\Exception $e) {
            // Tangani error database jika terjadi
            return response()->json(['message' => 'Gagal menghapus data TNA: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hapus TNA yang terpilih secara massal (BULK DELETE).
     */
    public function hapusTerpilih(Request $request)
    {
        $ids = $request->ids;

        // Cek PENTING: Lakukan pengecekan null eksplisit ($ids === null) 
        // untuk memastikan count() tidak pernah dipanggil pada null (mengatasi fatal error).
        // Cek juga apakah $ids adalah array dan tidak kosong.
        if ($ids === null || !is_array($ids) || count($ids) === 0) {
            return response()->json(['message' => 'Pilih setidaknya satu data untuk dihapus.'], 400);
        }

        // Jalankan penghapusan
        $count = Pelatihan_Tna::whereIn('tna_id', $ids)->delete();

        if ($count > 0) {
            return response()->json(['message' => "{$count} data TNA berhasil dihapus."], 200);
        }

        return response()->json(['message' => 'Gagal menghapus data. Tidak ada data yang cocok ditemukan.'], 404);
    }

    /**
     * Mendapatkan data TNA yang sesuai dengan nip yang dipilih.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ambilTnaBerdasarkanNip($nip)
    {
        // $nip = $request->nip;

        // Tampilkan nip yang diterima untuk debugging
        // dd($nip);  // Debug nip yang diterima

        // Ambil TNA yang memiliki 18 digit pertama tna_id yang cocok dengan nip
        $tnas = Pelatihan_Tna::whereRaw("LEFT(tna_id, 18) = ?", [$nip])->get();

        // Kembalikan data TNA sebagai JSON
        return response()->json($tnas);
    }

    // Fungsi untuk mengambil status TNA pegawai
    public function statusTna($nip)
    {
        // Ambil data pelatihan berdasarkan NIP
        $pelatihan = Pelatihan::where('nip', $nip)->firstOrFail();

        // Ambil semua TNA
        $tnas = Pelatihan_Tna::all();

        // Kirim data ke view
        return view('pelatihan.form', compact('pelatihan', 'tnas'));
    }

    /**
     * Mengimplementasikan fungsi ekspor data TNA.
     * Menggunakan TnaEkspor.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function ekspor(Request $request)
    {
        try {
            // 1. Ambil dan format data yang dibutuhkan untuk ekspor
            $dataToExport = pelatihan_Tna::select(
                'pelatihan_tna.nip',
                'pegawais.nama as nama_pegawai',
                'pelatihan_tna.nama as kebutuhan_pelatihan',
                'pelatihan_sifat_tna.nama as sifat_tna',
                'pelatihan_tna.tahun',
                'pelatihan_tna.deskripsi',
                // Menggunakan DB::raw tanpa backslash
                DB::raw('CASE WHEN pelatihan_tna.status_tna = 1 THEN "Sudah dilaksanakan" ELSE "Belum dilaksanakan" END as status_text')
            )
                ->join('pegawais', 'pegawais.nip', '=', 'pelatihan_tna.nip')
                ->join('pelatihan_sifat_tna', 'pelatihan_sifat_tna.sifat_tna_id', '=', 'pelatihan_tna.sifat_tna_id')
                ->get();

            // 2. Kirim data ke kelas export dan instruksikan unduh file Excel
            return Excel::download(new TnaEkspor($dataToExport), 'tna_pegawai_' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error("Gagal mengekspor TNA: " . $e->getMessage());
            // Jika gagal, redirect kembali dengan pesan error
            return redirect()->route('pelatihan.tna.index')
                ->with('error', 'Gagal melakukan ekspor data. Detail: ' . $e->getMessage());
        }
    }

    /**
     * Mengimplementasikan fungsi backup data TNA.
     * Menggunakan TnaBackup.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function backupTna(Request $request)
    {
        try {
            // 1. Ambil dan format data yang dibutuhkan untuk backup
            $dataToBackup = pelatihan_Tna::select(
                'tna_id',
                'nip',
                'sifat_tna_id',
                'kode_tna',
                'nama',
                'deskripsi',
                'tahun',
                'status_tna',
                'created_at',
                'updated_at',
            )->get();

            // 2. Kirim data ke kelas backup dan instruksikan unduh file Excel
            return Excel::download(new TnaBackup($dataToBackup), 'backup_tna_pegawai_' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error("Gagal backup TNA: " . $e->getMessage());
            // Jika gagal, redirect kembali dengan pesan error
            return redirect()->route('pelatihan.tna.index')
                ->with('error', 'Gagal melakukan backup data. Detail: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi untuk mengimpor file Excel atau CSV
     */
    public function impor(Request $request)
    {
        try {
            // Validasi file yang diupload
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',  // Maksimal 10MB
            ]);

            // Proses impor data
            Excel::import(new TnaImpor, $request->file('file'));

            // Jika berhasil, beri pesan sukses
            $this->flashSuccess('Data berhasil diimpor.');

            // Redirect kembali ke halaman sebelumnya
            return back();
        } catch (\Exception $e) {
            // Jika ada error, beri pesan kesalahan ke session
            $this->flashError('Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());

            // Redirect kembali ke halaman sebelumnya
            return back();
        }
    }

    public function templateTnaCSV()
    {
        // Menggunakan TnaTemplateExportCSV untuk mendownload template CSV
        return Excel::download(new TnaTemplateEksporCSV, 'template_tna.csv');
    }

    public function templateTnaExcel()
    {
        return Excel::download(new TnaTemplateEksporExcel, 'template_tna.xlsx');
    }
}
