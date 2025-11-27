<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    /**
     * Menampilkan daftar instansi.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua instansi dari database
        $instansis = Instansi::all();

        return response()->json($instansis, 200);
    }

    /**
     * Menyimpan instansi baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'instansi_id' => 'required|string|size:3|unique:instansis',
            'nama' => 'required|string|max:100',
            'negara_id' => 'required|string|size:3|exists:wilayah_negaras,negara_id',
        ]);

        // Membuat instansi baru
        $instansi = Instansi::create([
            'instansi_id' => $request->instansi_id,
            'nama' => $request->nama,
            'negara_id' => $request->negara_id,
        ]);

        return response()->json($instansi, 201);
    }

    /**
     * Menampilkan detail instansi berdasarkan ID.
     *
     * @param  string  $instansi_id
     * @return \Illuminate\Http\Response
     */
    public function show($instansi_id)
    {
        // Cek apakah instansi dengan instansi_id ada
        $instansi = Instansi::find($instansi_id);

        if (!$instansi) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }

        return response()->json($instansi, 200);
    }

    /**
     * Mengupdate instansi berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $instansi_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $instansi_id)
    {
        // Validasi input
        $request->validate([
            'instansi_id' => 'required|string|size:3|unique:instansis,instansi_id,' . $instansi_id,
            'nama' => 'required|string|max:100',
            'negara_id' => 'required|string|size:3|exists:wilayah_negaras,negara_id',
        ]);

        // Cari instansi berdasarkan instansi_id
        $instansi = Instansi::find($instansi_id);

        if (!$instansi) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }

        // Update data instansi
        $instansi->update([
            'instansi_id' => $request->instansi_id,
            'nama' => $request->nama,
            'negara_id' => $request->negara_id,
        ]);

        return response()->json($instansi, 200);
    }

    /**
     * Menghapus instansi berdasarkan ID.
     *
     * @param  string  $instansi_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($instansi_id)
    {
        // Cari instansi berdasarkan instansi_id
        $instansi = Instansi::find($instansi_id);

        if (!$instansi) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }

        // Hapus instansi
        $instansi->delete();

        return response()->json(['message' => 'Instansi deleted successfully'], 200);
    }

    // Fungsi untuk mengambil data instansi untuk dropdown
    public function ambilInstansi()
    {
        try {
            // Ambil semua instansi dari tabel instansi
            $instansis = Instansi::all();

            // Cek apakah ada data instansi
            if ($instansis->isEmpty()) {
                return response()->json(['message' => 'Data Instansi tidak ditemukan.'], 404);
            }

            // Format data instansi untuk dropdown (bisa disesuaikan jika ada kolom lain yang ingin ditampilkan)
            $data = $instansis->map(function ($instansi) {
                return [
                    'instansi_id' => $instansi->instansi_id,  // Menggunakan instansi_id sebagai ID
                    'nama' => $instansi->nama,       // Menggunakan nama instansi
                ];
            });

            return response()->json($data, 200);  // Kembalikan response JSON
        } catch (\Exception $e) {
            // Menangani error dan memberikan pesan yang lebih jelas
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
