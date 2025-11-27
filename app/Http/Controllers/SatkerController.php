<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satker;
use App\Models\Wilayah_Negara;
use App\Models\Wilayah_Provinsi;
use App\Models\Wilayah_Kabupaten;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SatkerController extends BaseController
{
    // Method untuk index
    public function index()
    {
        $satkers = Satker::all();
        return view('tempat_tugas.satker.index', compact('satkers'));
    }

    public function datatable(Request $request)
    {
        // Mengambil data satker beserta relasinya
        $satkers = Satker::with(['instansi', 'negara', 'provinsi', 'kabupaten'])
            ->select([
                'satker_id',
                'nama',
                'keterangan',
                'instansi_id',
                'negara_id',
                'provinsi_id',
                'kabupaten_id'
            ])
            ->orderBy('created_at', 'desc');

        return datatables()->of($satkers)
            ->addIndexColumn()
            ->addColumn('kode', function ($satker) {
                return $satker->satker_id; // Menampilkan satker_id sebagai kode
            })
            ->addColumn('instansi', function ($satker) {
                return $satker->instansi ? $satker->instansi->nama : 'Tidak Ditemukan';
            })
            ->addColumn('negara', function ($satker) {
                return $satker->negara ? $satker->negara->nama : 'Tidak Ditemukan';
            })
            ->addColumn('provinsi', function ($satker) {
                return $satker->provinsi ? $satker->provinsi->nama : 'Tidak Ditemukan';
            })
            ->addColumn('kabupaten', function ($satker) {
                return $satker->kabupaten ? $satker->kabupaten->nama : 'Tidak Ditemukan';
            })
            ->addColumn('action', function ($satker) {
                // URL dasar untuk setiap tombol aksi
                $baseUrl = route('tempat_tugas.satker.index'); // Menggunakan route helper untuk URL dasar
                $detailUrl = $baseUrl . '/' . $satker->satker_id;
                $editUrl = $baseUrl . '/' . $satker->satker_id . '/edit';
                $deleteUrl = $baseUrl . '/' . $satker->satker_id; // Tambahkan URL untuk Hapus jika diperlukan

                // Tombol Detail
                $detailBtn = '<a href="' . $detailUrl . '" class="btn btn-sm btn-outline-info mx-1" data-bs-toggle="tooltip" title="Detail"><i class="fas fa-eye"></i></a>';

                // Tombol Edit
                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning mx-1" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>';

                // Tombol Delete dengan konfirmasi
                $deleteBtn = '<button class="btn btn-sm btn-outline-danger btn-delete mx-1" data-id="' . $satker->satker_id . '" data-bs-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt"></i></button>';

                // Gabungkan tombol-tombol aksi
                return '<div class="d-flex justify-content-start align-items-center">' . $detailBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->make(true);
    }



    // Method untuk show
    public function show($satker_id)
    {
        $satker = Satker::with(['negara', 'provinsi', 'kabupaten'])->findOrFail($satker_id);
        return view('tempat_tugas.satker.show', compact('satker'));
    }

    // Method untuk create
    public function create()
    {
        $negara = Wilayah_Negara::all();
        return view('tempat_tugas.satker.create', compact('negara'));
    }

    // Method untuk menyimpan data (untuk create atau update)
    public function StoreOrUpdate(Request $request, $satker_id = null)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'instansi_id' => 'required|exists:instansis,instansi_id',
            'negara_id' => 'required|exists:wilayah_negaras,negara_id',
            'provinsi_id' => 'required|exists:wilayah_provinsis,provinsi_id',
            'kabupaten_id' => 'nullable|exists:wilayah_kabupatens,kabupaten_id', // Kab. bisa null
            'nama' => 'required|string|max:70', // Tidak lagi membutuhkan validasi unique saat update
            'keterangan' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mendapatkan data dari request
        $instansi_id = $request->instansi_id;
        $negara_id = $request->negara_id;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $nama = $request->nama;
        $keterangan = $request->keterangan;

        // Tentukan nilai satker_id menggunakan rumus yang Anda berikan
        $new_satker_id = '';

        if ($kabupaten_id) {
            $new_satker_id = $instansi_id . $kabupaten_id;
        } else {
            $new_satker_id = $instansi_id . $provinsi_id;
        }

        // Jika melakukan update dan satker_id berubah
        if ($satker_id) {
            $satker = Satker::findOrFail($satker_id);  // Ambil data berdasarkan satker_id

            // Periksa jika satker_id baru berbeda dan sudah ada
            if ($satker->satker_id != $new_satker_id) {
                $existingSatker = Satker::where('satker_id', $new_satker_id)->first();
                if ($existingSatker) {
                    return redirect()->back()->with('error', 'Satker ID sudah ada, silakan coba yang lain.')
                        ->withInput();
                }
                $satker->satker_id = $new_satker_id; // Update satker_id jika berbeda
            }
        } else {
            // Periksa apakah satker_id baru sudah ada (untuk create)
            $existingSatker = Satker::where('satker_id', $new_satker_id)->first();
            if ($existingSatker) {
                return redirect()->back()->with('error', 'Satker ID sudah ada, silakan coba yang lain.')
                    ->withInput();
            }
            $satker = new Satker();  // Jika create, buat instance baru
            $satker->satker_id = $new_satker_id;
        }

        // Mengisi data satker
        $satker->instansi_id = $instansi_id;
        $satker->negara_id = $negara_id;
        $satker->provinsi_id = $provinsi_id;
        $satker->kabupaten_id = $kabupaten_id;
        $satker->nama = $nama;
        $satker->keterangan = $keterangan;

        // Pastikan `updated_at` hanya diisi pada saat update
        if ($satker_id) {
            $satker->updated_at = now();  // Diupdate saat update
        } else {
            $satker->updated_at = null;  // Tidak perlu diisi saat create
        }

        // Simpan data satker
        $satker->save();

        // Redirect dengan pesan sukses
        return redirect()->route('tempat_tugas.satker.index')->with('success', 'Satker berhasil disimpan!');
    }

    // Method untuk store (create)
    public function store(Request $request)
    {
        return $this->StoreOrUpdate($request); // Panggil fungsi simpan untuk handle create
    }

    // Method untuk update
    public function update(Request $request, $satker_id)
    {
        return $this->StoreOrUpdate($request, $satker_id); // Panggil fungsi simpan untuk handle update
    }

    // Method untuk edit
    public function edit($satker_id)
    {
        $satker = Satker::findOrFail($satker_id);
        $negara = Wilayah_Negara::all(); // Ambil data negara untuk dropdown
        return view('tempat_tugas.satker.edit', compact('satker', 'negara'));
    }

    // Method untuk destroy (hapus)
    public function destroy($satker_id)
    {
        // Cek apakah satker dengan ID ini ada
        $satker = Satker::find($satker_id);

        if (!$satker) {
            return response()->json(['message' => 'Satker tidak ditemukan!'], 404);
        }

        // Hapus data satker
        $satker->delete();

        // Return response dengan pesan sukses
        return response()->json(['message' => 'Satker berhasil dihapus.']);
    }


    public function hapusTerpilih(Request $request)
    {
        $ids = $request->input('ids');
        $satkers = Satker::whereIn('satker_id', $ids)->delete();  // Hapus berdasarkan ID yang dipilih

        return response()->json(['message' => 'Data Satker terpilih berhasil dihapus.']);
    }

    // Fungsi untuk ambil data satker berdasarkan satker_id
    public function ambilSatker($satker_id)
    {
        // Mencari data satker beserta relasinya (negara, provinsi, kabupaten)
        $satker = Satker::with(['instansi', 'negara', 'provinsi', 'kabupaten'])
            ->where('satker_id', $satker_id)
            ->first();

        // Cek jika data satker ditemukan
        if (!$satker) {
            return response()->json(['error' => 'Satker tidak ditemukan'], 404);
        }

        // Kembalikan data dalam format JSON
        return response()->json([
            'satker_id' => $satker->satker_id,
            'instansi_id' => $satker->instansi_id,
            'instansi' => $satker->instansi ? $satker->instansi->nama : 'Tidak Ditemukan',
            'negara_id' => $satker->negara_id,
            'negara' => $satker->negara ? $satker->negara->nama : 'Tidak Ditemukan',
            'provinsi_id' => $satker->provinsi_id,
            'provinsi' => $satker->provinsi ? $satker->provinsi->nama : 'Tidak Ditemukan',
            'kabupaten_id' => $satker->kabupaten_id,
            'kabupaten' => $satker->kabupaten ? $satker->kabupaten->nama : 'Tidak Ditemukan',
            'nama' => $satker->nama,
            'keterangan' => $satker->keterangan
        ]);
    }
}
