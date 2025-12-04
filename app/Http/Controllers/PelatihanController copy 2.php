<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pelatihan_Skala;
use App\Models\Pelatihan_Bentuk;
use App\Models\Pelatihan_Kategori;
use App\Models\Pelatihan_Jenis;
use App\Models\Pelatihan_Tna;
use App\Models\Pelatihan_Jam_Pelajaran;
use App\Models\Instansi;
use App\Models\Pelatihan;
use App\Rules\ValidDurasi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PelatihanController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Memulai query dengan relasi yang diperlukan
            $pelatihan = Pelatihan::with('pegawai', 'kategori', 'jenis', 'instansi', 'tna')
                ->select('pelatihan_id', 'nama', 'tgl_mulai', 'tgl_selesai', 'durasi', 'kategori_id', 'jenis_id', 'instansi_id', 'tna_id', 'nip');

            // Cek apakah session 'NamalevelUser' ada dan jika 'nip' tersedia di session
            if (session('NamalevelUser') === 'Pegawai' && session('nip')) {
                $nip = session('nip');
                $pelatihan->where('nip', $nip);
            }

            // Pencarian untuk semua kolom
            if ($request->get('search')['value']) {
                $search = $request->get('search')['value'];
                $pelatihan->where(function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('tgl_mulai', 'like', '%' . $search . '%')
                        ->orWhere('tgl_selesai', 'like', '%' . $search . '%')
                        ->orWhere('durasi', 'like', '%' . $search . '%')
                        ->orWhere('kategori_id', 'like', '%' . $search . '%')
                        ->orWhere('jenis_id', 'like', '%' . $search . '%')
                        ->orWhere('instansi_id', 'like', '%' . $search . '%')
                        ->orWhere('tna_id', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%')
                        ->orWhereHas('pegawai', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('kategori', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('jenis', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('tna', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('instansi', function ($query) use ($search) {
                            $query->where('nama', 'like', '%' . $search . '%');
                        });
                });
            }

            // Mendapatkan nama kolom dan arah sorting dari DataTables dan Tangani order yang tidak valid
            $sortColumnIndex = $request->get('order')[0]['column'] ?? 0; // Default ke kolom pertama jika tidak ada
            $sortDirection = $request->get('order')[0]['dir'] ?? 'asc'; // Default ke 'asc' jika arah sorting tidak ada
            // Kolom yang dapat di-sort, sesuaikan dengan nama kolom DataTables
            $sortableColumns = [
                'checkbox',
                'DT_RowIndex',
                'nip',       // Pegawai
                'nama',               // Nama pelatihan
                'tgl_mulai',          // Tanggal Mulai
                'tgl_selesai',        // Tanggal Selesai
                'durasi',             // Durasi
                'kategori_id',      // Kategori
                'jenis_id',         // Jenis
                'tna_id',         // Training Need Analysis (TNA)
                'instansi_id',      // Instansi
                'aksi'                // Kolom aksi
            ];

            // Menyesuaikan indeks yang dipilih dari DataTables menjadi nama kolom
            $sortColumnName = $sortableColumns[$sortColumnIndex];

            // Melakukan sorting berdasarkan kolom yang dipilih
            if ($sortColumnName !== 'checkbox' && $sortColumnName !== 'DT_RowIndex' && $sortColumnName !== 'aksi') {
                $pelatihan->orderBy($sortColumnName, $sortDirection);
            }

            // Menghitung total records
            $totalRecords = Pelatihan::count();

            // Menghitung jumlah data setelah pencarian (untuk recordsFiltered)
            $totalFiltered = $pelatihan->count();

            // Mengambil data dengan pagination
            $pelatihan = $pelatihan->skip($request->get('start'))
                ->take($request->get('length'))
                ->get();

            // Menambahkan nomor urut untuk DT_RowIndex dan kolom checkbox
            $pelatihan = $pelatihan->map(function ($item, $index) {
                $item->DT_RowIndex = $index + 1;  // Menambahkan nomor urut
                $item->checkbox = '<input type="checkbox" class="select-item" data-pelatihan_id="' . $item->pelatihan_id . '">';
                $item->aksi = '<div class="btn-group" role="group" aria-label="Aksi">
        <a href="#" class="btn btn-outline-info btn-sm btn-detail mx-1" data-id="' . $item->pelatihan_id . '" data-bs-toggle="tooltip" title="Detail">
            <i class="fas fa-eye"></i>
        </a>
        <a href="' . route('pelatihan.edit', $item->pelatihan_id) . '" class="btn btn-outline-warning btn-sm mx-1" data-bs-toggle="tooltip" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-outline-danger btn-sm mx-1 btn-delete" data-id="' . $item->pelatihan_id . '" data-bs-toggle="tooltip" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </div>';
                return $item;
            });
            // Mengembalikan response untuk DataTables
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $pelatihan
            ]);
        }

        // Jika bukan AJAX, kembalikan view normal
        return view('pelatihan.pelatihan.index');
    }




    public function create()
    {
        // Ambil semua data yang diperlukan untuk dropdown
        $pegawais = Pegawai::all();  // Ambil semua pegawai
        $skalas = Pelatihan_Skala::all();  // Ambil semua skala pelatihan
        $bentuks = Pelatihan_Bentuk::all();  // Ambil semua bentuk pelatihan
        $jeniss = Pelatihan_Jenis::all();  // Ambil semua jenis pelatihan
        $tnas = Pelatihan_Tna::all();  // Ambil semua tna pelatihan
        $instansis = Instansi::all();  // Ambil semua instansi

        // Ambil semua kategori pelatihan, yang nantinya akan difilter oleh Bentuk
        $kategoris = Pelatihan_Kategori::all();

        // Pastikan semua data dikirimkan ke view
        return view('pelatihan.pelatihan.create', compact('pegawais', 'skalas', 'bentuks', 'jeniss', 'instansis', 'tnas', 'kategoris'));
    }

    // fungsi storeOrUpdate
    public function storeOrUpdate(Request $request, $id = null)
    {

        // Validasi dasar input user
        $request->validate([
            'nip' => 'required',
            'skala_id' => 'required',
            'bentuk_id' => 'required',
            'kategori_id' => 'required',
            'jenis_id' => 'required',
            'tna_id' => 'nullable|string|max:27',
            'instansi_id' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'tgl_mulai' => 'required|date|before_or_equal:tgl_selesai',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'durasi' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'], // desimal biasa
            'link_bukti_dukung' => 'required|url|max:255',
            'nomor_sertifikat' => 'nullable|unique:pelatihans,nomor_sertifikat,' . $id . ',pelatihan_id|string|max:50',
        ]);

        //  Validasi untuk memastikan tgl_mulai tidak melebihi tanggal hari ini
        $today = Carbon::now()->toDateString(); // Mendapatkan tanggal hari ini dalam format Y-m-d
        if ($request->tgl_mulai > $today) {
            return back()->withErrors(['tgl_mulai' => 'Tanggal mulai tidak boleh melebihi tanggal hari ini.'])->withInput();
        }

        //  Validasi untuk memastikan tgl_mulai tidak melebihi tanggal hari ini
        $today = Carbon::now()->toDateString(); // Mendapatkan tanggal hari ini dalam format Y-m-d
        if ($request->tgl_selesai > $today) {
            return back()->withErrors(['tgl_selesai' => 'Tanggal selesai tidak boleh melebihi tanggal hari ini.'])->withInput();
        }

        // Cek apakah rangking lebih besar dari jumlah_peserta
        if ($request->rangking > $request->jumlah_peserta) {
            return back()
                ->withErrors(['rangking' => 'Rangking tidak boleh lebih besar dari jumlah peserta.'])
                ->withInput();
        }

        // Cek apakah ada pelatihan dengan kategori_id 101 dan jenis_id yang sama untuk nip yang sama
        $pelatihanSamaKategoriJenis = Pelatihan::where('nip', $request->nip)  // Pastikan nip yang sama
            ->where('kategori_id', '101')  // Cek kategori_id = 101
            ->where('jenis_id', $request->jenis_id)  // Cek jenis pelatihan yang sama
            ->where('pelatihan_id', '!=', $id)  // Mengecualikan ID pelatihan yang sedang diedit
            ->first();  // Ambil pelatihan pertama yang ditemukan

        if ($pelatihanSamaKategoriJenis) {
            // Jika ada pelatihan dengan kategori 101 dan jenis pelatihan yang sama
            $existingDate = Carbon::parse($pelatihanSamaKategoriJenis->tgl_mulai)->format('d-m-Y');  // Format tanggal menjadi dd-mm-yyyy

            // Ambil nama jenis pelatihan berdasarkan jenis_id
            $jenisNama = Pelatihan_Jenis::where('jenis_id', $request->jenis_id)->value('nama');

            // Ambil nama pegawai berdasarkan nip
            $pegawaiNama = Pegawai::where('nip', $request->nip)->value('nama');

            // Munculkan pesan error dengan nama jenis pelatihan, nama pegawai, dan tanggal yang ditemukan
            return back()->withErrors(['jenis_id' => "Jenis pelatihan '{$jenisNama}' sudah pernah diikuti oleh '{$pegawaiNama}' pada tanggal '{$existingDate}'."])->withInput();
        }

        // Cek jika kategori_id = 102 dan jenis_id bukan '07' untuk nip yang sama
        if ($request->kategori_id == '102' && $request->jenis_id != '07') {
            // Cari pelatihan yang sudah ada dengan kondisi kategori_id = 102 dan jenis_id yang sama
            $pelatihanSamaKategoriJenis = Pelatihan::where('nip', $request->nip)  // Pastikan nip yang sama
                ->where('kategori_id', '102')  // Cek kategori_id = 102
                ->where('jenis_id', $request->jenis_id)  // Cek jenis pelatihan yang sama
                ->first();  // Ambil pelatihan pertama yang ditemukan

            if ($pelatihanSamaKategoriJenis) {
                // Jika ada pelatihan yang sudah ada dengan kategori_id 102 dan jenis_id yang sama
                $existingDate = Carbon::parse($pelatihanSamaKategoriJenis->tgl_mulai)->format('d-m-Y');  // Format tanggal menjadi dd-mm-yyyy

                // Ambil nama jenis pelatihan berdasarkan jenis_id
                $jenisNama = Pelatihan_Jenis::where('jenis_id', $request->jenis_id)->value('nama');

                // Ambil nama pegawai berdasarkan nip
                $pegawaiNama = Pegawai::where('nip', $request->nip)->value('nama');

                // Ambil nama kategori berdasarkan kategori_id
                $kategoriNama = Pelatihan_Kategori::where('kategori_id', '102')->value('nama');

                // Munculkan pesan error jika pelatihan sudah ada
                return back()->withErrors([
                    'jenis_id' => "Jenis pelatihan '{$jenisNama}' sudah pernah diikuti oleh '{$pegawaiNama}' pada tanggal '{$existingDate}'." .
                        " Untuk jenis pelatihan '{$jenisNama}' dengan kategori '{$kategoriNama}' hanya boleh diinput sekali."
                ])->withInput();
            }
        }

        // Ambil durasi maksimal dari tabel master pelatihan_jam_pelajaran
        $jamPelajaran = Pelatihan_Jam_Pelajaran::first();

        if (!$jamPelajaran) {
            return back()
                ->withErrors(['durasi' => 'Data durasi maksimal belum tersedia.'])
                ->withInput();
        }

        // Ambil durasi maksimal per hari dalam desimal (misalnya: 3.5)
        $maxDurasiPerHari = floatval($jamPelajaran->asynchronous) + floatval($jamPelajaran->synchronous);

        // Hitung jumlah hari pelatihan
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1;

        // Hitung durasi maksimal berdasarkan jumlah hari
        $durasiMaxDesimal = $maxDurasiPerHari * $jumlahHari;

        // Ambil input durasi sebagai desimal
        $durasiInput = floatval($request->durasi);

        // Validasi durasi agar tidak kurang dari 0 dan tidak lebih dari 999
        if ($durasiInput <= 0 || $durasiInput > 999) {
            return back()
                ->withErrors(['durasi' => 'Durasi harus lebih dari 0 dan kurang dari atau sama dengan 999.'])
                ->withInput();
        }

        // Hitung total durasi pelatihan yang sudah ada di tanggal yang sama
        $pelatihanSamaTanggalDurasi = Pelatihan::where('nip', $request->nip)
            ->where('tgl_mulai', $request->tgl_mulai);

        if ($id) {
            $currentPelatihan = Pelatihan::find($id);
            if ($currentPelatihan) {
                $pelatihanSamaTanggalDurasi->where('pelatihan_id', '!=', $currentPelatihan->pelatihan_id);
            }
        }

        $pelatihanSamaTanggalDurasi = $pelatihanSamaTanggalDurasi->get();

        $totalDurasiDesimal = 0.0;

        // Menjumlahkan durasi pelatihan yang sudah ada
        foreach ($pelatihanSamaTanggalDurasi as $pel) {
            $durasiDesimal = floatval($pel->durasi); // diasumsikan disimpan sebagai angka desimal
            $totalDurasiDesimal += $durasiDesimal;
        }

        // Tambahkan durasi inputan ke total durasi
        $totalDurasiDesimal += $durasiInput;

        // Validasi apakah total melebihi batas maksimum
        if ($totalDurasiDesimal > $durasiMaxDesimal) {
            $pelatihanSamaTanggalQuery = Pelatihan::where('nip', $request->nip)
                ->where('tgl_mulai', $request->tgl_mulai);

            if ($id) {
                $pelatihanSamaTanggalQuery->where('pelatihan_id', '!=', $id);
            }

            $pelatihanSamaTanggalList = $pelatihanSamaTanggalQuery->pluck('nama')->toArray();

            $totalPelatihan = count($pelatihanSamaTanggalList) + 1; // termasuk yang sedang input

            $pegawaiNama = Pegawai::where('nip', $request->nip)->value('nama');
            $pelatihanList = implode(', ', $pelatihanSamaTanggalList);

            $errors = [
                'durasi' => "Jumlah seluruh durasi mulai tanggal tersebut sudah {$totalDurasiDesimal}, melebihi batas maksimal {$durasiMaxDesimal}."
            ];

            if ($totalPelatihan > 1) {
                $errors['tgl_mulai'] = "Pada tanggal ini, pelatihan untuk $pegawaiNama sudah ada: $pelatihanList.";
            }

            return back()
                ->withErrors($errors)
                ->withInput();
        }

        // Simpan durasi langsung dalam bentuk desimal (jika kolom `durasi` bertipe `float` atau `decimal`)
        $durasiFormatted = $durasiInput;

        // Buat pelatihan_id dasar dari data yang ada
        $nip = $request->nip;
        $skala = $request->skala_id;
        $bentuk = $request->bentuk_id;
        $kategori = $request->kategori_id;
        $jenis = $request->jenis_id;

        $tglMulai = $request->tgl_mulai;

        $baseKey = "{$nip}-{$skala}{$bentuk}{$kategori}{$jenis}-{$tglMulai}-";

        // Cari pelatihan terakhir berdasarkan pola pelatihan_id yang sama
        $lastPelatihan = Pelatihan::where('pelatihan_id', 'like', $baseKey . '%')
            ->orderBy('pelatihan_id', 'desc')
            ->first();

        if ($id) {
            // Update mode
            $pelatihan = Pelatihan::findOrFail($id);

            // untuk update TNA di tabel pelatihan_tna
            // Ambil tna_id awal dari pelatihan yang ada di database
            $tna_awal = $pelatihan->tna_id;

            // Ambil tna_id baru dari input form
            $tna_akhir = $request->tna_id;

            // dd($tna_awal, $tna_akhir);

            // Cek jika tna_akhir kosong, set tna_id yang disimpan dengan tna_awal
            if (empty($tna_akhir)) {
                $tna_akhir = $tna_awal; // Set tna_akhir menjadi tna_awal
            }

            // Cek jika tna_id berubah
            if ($tna_awal !== $tna_akhir) {
                // 1. Jika tna_id berubah, ubah status TNA yang lama menjadi 0
                if ($tna_awal) {
                    $previousTna = Pelatihan_Tna::find($tna_awal);
                    if ($previousTna) {
                        // Update status TNA yang lama menjadi 0 dan hanya memperbarui updated_at
                        $previousTna->update([
                            'status_tna' => 0,
                        ]);
                        $previousTna->touch();  // Memperbarui `updated_at` tanpa mengubah kolom lainnya
                    }
                }

                // 2. Cek apakah status TNA yang baru sudah 1
                $newTna = Pelatihan_Tna::find($tna_akhir);
                if ($newTna) {
                    // Jika status TNA yang baru sudah 1, tampilkan pesan error
                    // if ($newTna->status_tna == 1) {
                    //     return back()->withErrors(['tna_id' => 'TNA ini sudah dilaksanakan. Silakan pilih TNA yang belum dilaksanakan.'])->withInput();
                    // }

                    // Update status TNA yang baru menjadi 1 dan hanya memperbarui updated_at
                    $newTna->update([
                        'status_tna' => 1,
                    ]);
                    $newTna->touch();  // Memperbarui `updated_at` tanpa mengubah kolom lainnya
                }
            } else {
                // Jika tna_id tidak berubah, hanya ubah status TNA yang baru menjadi 1
                $newTna = Pelatihan_Tna::find($tna_akhir);
                if ($newTna) {
                    // Jika status TNA yang baru sudah 1, tampilkan pesan error
                    // if ($newTna->status_tna == 1) {
                    //     return back()->withErrors(['tna_id' => 'TNA ini sudah dilaksanakan. Silakan pilih TNA yang belum dilaksanakan.'])->withInput();
                    // }

                    // Update status TNA yang baru menjadi 1 dan hanya memperbarui updated_at
                    $newTna->update([
                        'status_tna' => 1,
                    ]);
                    $newTna->touch();  // Memperbarui `updated_at` tanpa mengubah kolom lainnya
                }
            }

            $newBaseKey = "{$request->nip}-{$request->skala_id}{$request->bentuk_id}{$request->kategori_id}{$request->jenis_id}-{$request->tgl_mulai}-";

            if ($newBaseKey !== substr($pelatihan->pelatihan_id, 0, strlen($newBaseKey))) {
                // Generate kode pelatihan baru untuk baseKey baru
                $lastPelatihanForNewKey = Pelatihan::where('pelatihan_id', 'like', $newBaseKey . '%')
                    ->orderBy('pelatihan_id', 'desc')
                    ->first();

                if ($lastPelatihanForNewKey) {
                    $lastCode = intval(substr($lastPelatihanForNewKey->pelatihan_id, -2));
                    $kodePelatihan = str_pad($lastCode + 1, 2, '0', STR_PAD_LEFT);
                } else {
                    $kodePelatihan = '01';
                }

                $pelatihan_id = $newBaseKey . $kodePelatihan;
            } else {
                $pelatihan_id = $pelatihan->pelatihan_id;
            }

            // Cek duplikat pelatihan_id selain dirinya sendiri
            $exists = Pelatihan::where('pelatihan_id', $pelatihan_id)
                ->where('pelatihan_id', '!=', $id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['pelatihan_id' => "Pelatihan dengan ID \"$pelatihan_id\" sudah ada untuk {$pelatihan->pegawai->nama}."])
                    ->withInput();
            }

            // Update data
            $pelatihan->update([
                'pelatihan_id' => $pelatihan_id,
                'nip' => $request->nip,
                'kategori_id' => $request->kategori_id,
                'skala_id' => $request->skala_id,
                'bentuk_id' => $request->bentuk_id,
                'jenis_id' => $request->jenis_id,
                'tna_id' => $tna_akhir,  // Pastikan tna_id yang disimpan adalah tna_akhir (yang bisa kosong diganti tna_awal)
                'kode_pelatihan' => substr($pelatihan_id, -2),
                'nama' => $request->nama,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'durasi' => $durasiFormatted,
                'instansi_id' => $request->instansi_id,
                'jumlah_peserta' => $request->jumlah_peserta,
                'rangking' => $request->rangking,
                'link_bukti_dukung' => $request->link_bukti_dukung,
                'nomor_sertifikat' => $request->nomor_sertifikat,
            ]);
        } else {

            // Create mode: Cek status TNA yang baru
            $newTna = Pelatihan_Tna::find($request->tna_id);
            if ($newTna) {
                // Jika status TNA yang baru sudah 1, tampilkan pesan error
                // if ($newTna->status_tna == 1) {
                //     return back()->withErrors(['tna_id' => 'TNA ini sudah dilaksanakan. Silakan pilih TNA yang belum dilaksanakan.'])->withInput();
                // }

                // Ubah status TNA yang baru menjadi 1 dan set updated_at hanya jika belum ada
                $newTna->update([
                    'status_tna' => 1,
                ]);
                $newTna->touch();  // Memperbarui `updated_at` tanpa mengubah kolom lainnya
            }
            // Create mode
            if ($lastPelatihan) {
                $lastCode = intval(substr($lastPelatihan->pelatihan_id, -2));
                $kodePelatihan = str_pad($lastCode + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $kodePelatihan = '01';
            }

            $pelatihan_id = $baseKey . $kodePelatihan;

            // Cek duplikat pelatihan_id
            $exists = Pelatihan::where('pelatihan_id', $pelatihan_id)->exists();

            if ($exists) {
                return back()
                    ->withErrors(['pelatihan_id' => "Pelatihan dengan ID \"$pelatihan_id\" sudah ada."])
                    ->withInput();
            }

            // Simpan data baru
            Pelatihan::create([
                'pelatihan_id' => $pelatihan_id,
                'nip' => $request->nip,
                'kategori_id' => $request->kategori_id,
                'skala_id' => $request->skala_id,
                'bentuk_id' => $request->bentuk_id,
                'jenis_id' => $request->jenis_id,
                'tna_id' => $request->tna_id,
                'kode_pelatihan' => $kodePelatihan,
                'nama' => $request->nama,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'durasi' => $durasiFormatted,
                'instansi_id' => $request->instansi_id,
                'jumlah_peserta' => $request->jumlah_peserta,
                'rangking' => $request->rangking,
                'link_bukti_dukung' => $request->link_bukti_dukung,
                'nomor_sertifikat' => $request->nomor_sertifikat,
            ]);
        }

        return redirect()->route('pelatihan.index')->with('success', 'Data pelatihan berhasil disimpan.');
    }
    // Batas bawah storeOrUpdate



    public function store(Request $request)
    {
        // Panggil storeOrUpdate untuk proses penyimpanan data
        return $this->storeOrUpdate($request); // Tidak perlu parameter ID, karena ini create
    }

    public function edit($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $kategoris = Pelatihan_Kategori::all();
        $pegawais = Pegawai::all();
        $skalas = Pelatihan_Skala::all();
        $bentuks = Pelatihan_Bentuk::all();
        $jeniss = Pelatihan_Jenis::all();
        $tnas = Pelatihan_Tna::all();
        $instansis = Instansi::all();

        // Mengubah durasi jika formatnya HH:MM:SS menjadi HH.MM
        $durasi = $pelatihan->durasi;
        if ($durasi) {
            // Pastikan formatnya sesuai dengan HH.MM
            // Jika durasi formatnya HH:MM:SS, kita akan mengubahnya menjadi HH.MM
            $durasi = preg_replace('/(\d{2}):(\d{2}):(\d{2})/', '$1.$2', $durasi);
        }

        return view('pelatihan.pelatihan.edit', compact('pelatihan', 'kategoris', 'pegawais', 'skalas', 'bentuks', 'jeniss', 'instansis', 'tnas', 'durasi'));
    }


    // Method untuk update data pelatihan
    public function update(Request $request, $id)
    {
        // Panggil storeOrUpdate untuk proses pembaruan data
        return $this->storeOrUpdate($request, $id); // Kirimkan ID untuk update
    }

    public function destroy($id)
    {
        // Mengambil pelatihan berdasarkan ID
        $pelatihan = Pelatihan::findOrFail($id);

        // Cek apakah pelatihan memiliki tna_id
        if ($pelatihan->tna_id) {
            // Update status TNA di pelatihan_tna menjadi 0
            $tna = Pelatihan_Tna::find($pelatihan->tna_id);
            if ($tna) {
                $tna->update([
                    'status_tna' => 0  // Mengubah status menjadi 0
                ]);
            }
        }

        // Menghapus pelatihan
        $pelatihan->delete();

        // Mengembalikan response JSON sukses
        return response()->json(['message' => 'Pelatihan berhasil dihapus']);
    }

    // Method di Controller untuk menangani penghapusan data terpilih
    public function hapusTerpilih(Request $request)
    {
        // Cek apakah ada data yang dikirimkan
        if (!$request->has('ids') || empty($request->ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih'], 400);
        }

        // Ambil daftar ID yang dipilih
        $ids = $request->ids;

        // Loop untuk setiap ID yang dipilih
        foreach ($ids as $id) {
            // Mengambil pelatihan berdasarkan ID
            $pelatihan = Pelatihan::find($id);

            if ($pelatihan && $pelatihan->tna_id) {
                // Update status TNA di pelatihan_tna menjadi 0
                $tna = Pelatihan_Tna::find($pelatihan->tna_id);
                if ($tna) {
                    $tna->update([
                        'status_tna' => 0  // Mengubah status menjadi 0
                    ]);
                }
            }

            // Hapus pelatihan
            $pelatihan->delete();
        }

        return response()->json(['message' => 'Data terpilih berhasil dihapus']);
    }


    public function show($id)
    {
        // Menyaring data berdasarkan ID yang diberikan
        $pelatihan = Pelatihan::with('pegawai', 'skala', 'bentuk', 'kategori', 'jenis', 'tna', 'instansi')->findOrFail($id);

        // Mengembalikan data dalam format JSON
        return response()->json($pelatihan);
    }
}
